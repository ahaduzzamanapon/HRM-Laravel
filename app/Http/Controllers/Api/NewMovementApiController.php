<?php

namespace App\Http\Controllers\Api;

use App\Models\NewMovement;
use App\Models\NewMovementTravel;
use App\Models\NewMovementMeeting;
use App\Models\NewMovementExpense;
use Illuminate\Http\Request;

class NewMovementApiController extends BaseApiController
{
    // ── Helpers ──────────────────────────────────────────────────────────

    private function haversineKm($lat1, $lng1, $lat2, $lng2)
    {
        $theta = $lng1 - $lng2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2))
                + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = max(-1, min(1, $dist));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $km   = $dist * 60 * 1.1515 * 1.609344;
        return is_nan($km) ? 0 : round($km, 2);
    }

    // ── GET /api/v1/new-movements/dashboard ──────────────────────────────
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $activeMovement = NewMovement::where('employee_id', $user->id)->where('status', 'active')->latest()->first();

        $currentState = 'idle';
        $activeData   = null;

        if ($activeMovement) {
            $activeTravel = NewMovementTravel::where('movement_id', $activeMovement->id)->where('status', 'running')->latest()->first();
            if ($activeTravel) {
                $currentState = 'traveling';
                $activeData   = $activeTravel;
                $activeData->is_office_return = ($activeTravel->to_location === 'Office') ? 1 : 0;
                $activeData->is_home_return   = ($activeTravel->to_location === 'Home') ? 1 : 0;
            } else {
                $activeMeeting = NewMovementMeeting::where('movement_id', $activeMovement->id)->whereNull('end_time')->first();
                if ($activeMeeting) {
                    $currentState = 'meeting';
                    $activeData   = $activeMeeting;
                } else {
                    $pendingFeedback = NewMovementMeeting::where('movement_id', $activeMovement->id)->whereNotNull('end_time')->whereNull('feedback')->latest()->first();
                    if ($pendingFeedback) {
                        $currentState = 'feedback_pending';
                        $activeData   = $pendingFeedback;
                    } else {
                        $currentState = 'decision';
                    }
                }
            }
        }

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $historyQ = NewMovement::with(['travels', 'meetings', 'expenses'])->where('employee_id', $user->id);
        if ($startDate && $endDate) {
            $historyQ->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }
        $history = $historyQ->latest()->get();
        $stats   = $this->getTaStats($user->id, $startDate, $endDate);

        return $this->successResponse([
            'current_state'   => $currentState,
            'active_movement' => $activeMovement,
            'active_data'     => $activeData,
            'stats'           => $stats,
            'history'         => $history,
        ]);
    }

    // ── POST /api/v1/new-movements/start ─────────────────────────────────
    public function start(Request $request)
    {
        $request->validate(['start_location' => 'required|string']);
        $user = $request->user();

        $existingActive = NewMovement::where('employee_id', $user->id)->where('status', 'active')->first();
        if ($existingActive) {
            return $this->errorResponse('You already have an active movement', 422);
        }

        $photoPath = null;
        if ($request->start_photo) {
            $base64 = $request->start_photo;
            preg_match('/^data:image\/(.*);base64,/', $base64, $match);
            if (isset($match[1])) {
                $ext       = $match[1];
                $data      = base64_decode(preg_replace('/^data:image\/(.*);base64,/', '', str_replace(' ', '+', $base64)));
                $filename  = 'start_' . $user->id . '_' . time() . '.' . $ext;
                $dir       = public_path('uploads/movement_photos');
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                file_put_contents($dir . '/' . $filename, $data);
                $photoPath = 'uploads/movement_photos/' . $filename;
            }
        }

        $movement = NewMovement::create([
            'employee_id'     => $user->id,
            'start_location'  => $request->start_location,
            'start_latitude'  => $request->latitude,
            'start_longitude' => $request->longitude,
            'purpose'         => $request->purpose,
            'type'            => $request->type,
            'start_time'      => now(),
            'start_photo'     => $photoPath,
            'status'          => 'active',
        ]);

        NewMovementTravel::create([
            'movement_id'   => $movement->id,
            'from_location' => $request->start_location,
            'start_lat'     => $request->latitude,
            'start_lng'     => $request->longitude,
            'start_time'    => now(),
            'status'        => 'running',
        ]);

        return $this->successResponse(['movement_id' => $movement->id], 'Movement Started', 200);
    }

    // ── POST /api/v1/new-movements/reached-destination ───────────────────
    public function reachedDestination(Request $request)
    {
        $user     = $request->user();
        $movement = NewMovement::where('employee_id', $user->id)->where('status', 'active')->latest()->first();
        if (!$movement) return $this->errorResponse('No active movement', 404);

        $travel = NewMovementTravel::where('movement_id', $movement->id)->where('status', 'running')->latest()->first();
        if ($travel) {
            $distKm = 0;
            if ($travel->start_lat && $travel->start_lng && $request->latitude && $request->longitude) {
                $distKm = $this->haversineKm($travel->start_lat, $travel->start_lng, $request->latitude, $request->longitude);
            }
            $travel->update([
                'end_time'    => now(),
                'status'      => 'completed',
                'to_location' => $request->current_location,
                'end_lat'     => $request->latitude,
                'end_lng'     => $request->longitude,
                'distance_km' => $distKm,
            ]);
        }

        if ($request->is_office_return == 1 || $request->is_home_return == 1 || $request->to_location === 'Home') {
            $movement->update(['end_time' => now(), 'status' => 'completed']);
            return $this->successResponse(null, 'Movement Completed');
        }

        return $this->successResponse(null, 'Reached Destination');
    }

    // ── POST /api/v1/new-movements/start-meeting ─────────────────────────
    public function startMeeting(Request $request)
    {
        $user     = $request->user();
        $movement = NewMovement::where('employee_id', $user->id)->where('status', 'active')->latest()->first();
        if (!$movement) return $this->errorResponse('No active movement', 404);

        $meeting = NewMovementMeeting::create([
            'movement_id'     => $movement->id,
            'entity_type'     => $request->entity_type,
            'client_name'     => $request->client_name,
            'contact_person'  => $request->contact_person,
            'contact_email'   => $request->contact_email,
            'contact_phone'   => $request->contact_phone,
            'contact_job_title' => $request->contact_job_title,
            'meeting_type'    => $request->meeting_type,
            'location'        => $request->location,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
            'remarks'         => $request->remarks,
            'start_time'      => now(),
        ]);

        return $this->successResponse(['meeting_id' => $meeting->id], 'Meeting Started');
    }

    // ── POST /api/v1/new-movements/end-meeting ───────────────────────────
    public function endMeeting(Request $request)
    {
        $user = $request->user();

        if ($request->meeting_id) {
            $meeting = NewMovementMeeting::find($request->meeting_id);
            if (!$meeting) return $this->errorResponse('Meeting not found', 404);
        } else {
            $movement = NewMovement::where('employee_id', $user->id)->where('status', 'active')->latest()->first();
            if (!$movement) return $this->errorResponse('No active movement', 404);
            $meeting = NewMovementMeeting::where('movement_id', $movement->id)->whereNull('end_time')->first();
            if (!$meeting) return $this->errorResponse('No active meeting', 404);
        }

        $meeting->update(['end_time' => now()]);

        if ($request->feedback) {
            $meeting->update(['feedback' => $request->feedback]);
        }

        return $this->successResponse(['meeting_id' => $meeting->id, 'move_id' => $meeting->movement_id], 'Meeting Ended');
    }

    // ── POST /api/v1/new-movements/decision ──────────────────────────────
    public function decision(Request $request)
    {
        $user     = $request->user();
        $movement = NewMovement::where('employee_id', $user->id)->where('status', 'active')->latest()->first();
        if (!$movement) return $this->errorResponse('No active movement', 404);

        $choice = $request->choice;
        $travelData = [
            'movement_id'   => $movement->id,
            'from_location' => $request->current_location,
            'start_lat'     => $request->latitude,
            'start_lng'     => $request->longitude,
            'start_time'    => now(),
            'status'        => 'running',
        ];

        if ($choice === 'office')      $travelData['to_location'] = 'Office';
        elseif ($choice === 'home')    $travelData['to_location'] = 'Home';

        NewMovementTravel::create($travelData);
        return $this->successResponse(null, 'Next Travel Started');
    }

    // ── GET /api/v1/new-movements/details ────────────────────────────────
    public function details(Request $request)
    {
        $request->validate(['movement_id' => 'required|integer']);
        $movement = NewMovement::with(['user', 'travels', 'meetings', 'expenses'])->find($request->movement_id);
        if (!$movement) return $this->errorResponse('Movement not found', 404);

        return $this->successResponse($movement);
    }

    // ── POST /api/v1/new-movements/apply-ta ─────────────────────────────
    public function applyTa(Request $request)
    {
        $request->validate([
            'movement_id' => 'required|exists:new_movement_movements,id',
            'expenses'    => 'required|array|min:1',
        ]);

        $user       = $request->user();
        $movementId = $request->movement_id;
        $expenses   = $request->expenses;

        \DB::beginTransaction();
        try {
            NewMovementExpense::where('movement_id', $movementId)->delete();
            $total = 0;
            foreach ($expenses as $exp) {
                $amount = (float)($exp['amount'] ?? 0);
                $total += $amount;
                NewMovementExpense::create([
                    'movement_id'    => $movementId,
                    'travel_id'      => $exp['travel_id'] ?? null,
                    'transport_type' => $exp['type'] ?? null,
                    'amount'         => $amount,
                    'approve_amount' => $amount,
                    'note'           => $exp['note'] ?? null,
                    'created_by'     => $user->id,
                ]);
            }

            NewMovement::where('id', $movementId)->update([
                'ta_status'  => 'pending',
                'ta_amount'  => $total,
                'ta_app_amt' => $total,
                'updated_by' => $user->id,
                'updated_at' => now(),
            ]);

            \DB::commit();
            return $this->successResponse(['amount' => $total], 'TA Applied Successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // ── POST /api/v1/new-movements/submit-feedback ───────────────────────
    public function submitFeedback(Request $request)
    {
        $user = $request->user();

        if ($request->meeting_id) {
            $meeting = NewMovementMeeting::find($request->meeting_id);
        } else {
            $movementId = $request->movement_id;
            if (!$movementId) {
                $active = NewMovement::where('employee_id', $user->id)->where('status', 'active')->latest()->first();
                $movementId = $active ? $active->id : null;
            }
            $meeting = $movementId ? NewMovementMeeting::where('movement_id', $movementId)->latest()->first() : null;
        }

        if (!$meeting) return $this->errorResponse('No meeting found', 404);

        $updateData = ['feedback' => $request->feedback];
        foreach (['client_name', 'contact_person', 'contact_email', 'contact_phone', 'contact_job_title'] as $f) {
            if ($request->$f) $updateData[$f] = $request->$f;
        }
        $meeting->update($updateData);

        return $this->successResponse(null, 'Feedback Submitted');
    }

    // ── POST /api/v1/new-movements/log-visit ─────────────────────────────
    public function logVisit(Request $request)
    {
        $user     = $request->user();
        $movement = NewMovement::where('employee_id', $user->id)->where('status', 'active')->latest()->first();
        if (!$movement) return $this->errorResponse('No active movement', 404);

        NewMovementMeeting::create([
            'movement_id'     => $movement->id,
            'entity_type'     => $request->entity_type,
            'client_name'     => $request->client_name,
            'contact_person'  => $request->contact_person,
            'contact_email'   => $request->contact_email,
            'contact_phone'   => $request->contact_phone,
            'contact_job_title' => $request->contact_job_title,
            'meeting_type'    => 'visit_only',
            'location'        => $request->location,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
            'remarks'         => $request->remarks,
            'start_time'      => now(),
            'end_time'        => now(),
            'feedback'        => 'Client Visit (Busy/No Meeting)',
        ]);

        return $this->successResponse(null, 'Visit Logged');
    }

    // ── Private helper: TA stats ─────────────────────────────────────────
    private function getTaStats($employeeId = null, $startDate = null, $endDate = null)
    {
        $base = function () use ($employeeId, $startDate, $endDate) {
            $q = NewMovementExpense::join('new_movement_movements', 'new_movement_movements.id', '=', 'new_movement_travel_expenses.movement_id');
            if ($employeeId) $q->where('new_movement_movements.employee_id', $employeeId);
            if ($startDate && $endDate) {
                $q->whereDate('new_movement_movements.created_at', '>=', $startDate)
                  ->whereDate('new_movement_movements.created_at', '<=', $endDate);
            }
            return $q;
        };

        return [
            'total_ta'       => (float)($base()->sum('new_movement_travel_expenses.amount') ?? 0),
            'pending_ta'     => (float)($base()->whereIn('new_movement_movements.ta_status', ['pending', 'hr_approved'])->sum('new_movement_travel_expenses.amount') ?? 0),
            'approved_ta'    => (float)($base()->where('new_movement_movements.ta_status', 'approved')->sum('new_movement_travel_expenses.amount') ?? 0),
            'handed_over_ta' => (float)($base()->where('new_movement_movements.ta_status', 'handed_over')->sum('new_movement_travel_expenses.amount') ?? 0),
        ];
    }
}
