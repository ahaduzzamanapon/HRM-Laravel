<?php

namespace App\Http\Controllers;

use App\Models\NewMovement;
use App\Models\NewMovementTravel;
use App\Models\NewMovementMeeting;
use App\Models\NewMovementExpense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function haversineKm($lat1, $lng1, $lat2, $lng2)
    {
        $theta = $lng1 - $lng2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2))
                + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = max(-1, min(1, $dist));
        $dist = acos($dist);
        $km   = rad2deg($dist) * 60 * 1.1515 * 1.609344;
        return is_nan($km) ? 0 : round($km, 2);
    }

    private function getTaStats($employeeId = null, $startDate = null, $endDate = null)
    {
        $base = function () use ($employeeId, $startDate, $endDate) {
            $q = NewMovementExpense::join('new_movement_movements', 'new_movement_movements.id', '=', 'new_movement_travel_expenses.movement_id')
                ->join('users', 'users.id', '=', 'new_movement_movements.employee_id');

            if ($employeeId) {
                $q->where('new_movement_movements.employee_id', $employeeId);
            } elseif (!isSuperAdmin()) {
                applyBranchScope($q, 'users.branch_id');
            }

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

    // ================================================================
    //  ADMIN VIEWS
    // ================================================================

    // GET /new-movement  →  Admin Dashboard
    public function index()
    {
        $q = NewMovement::with(['user.branch'])->latest();
        if (!isSuperAdmin()) {
            $q->whereHas('user', function ($u) {
                applyBranchScope($u, 'branch_id');
            });
        }
        $movements = $q->get();
        foreach ($movements as $m) {
            if ($m->status === 'active') {
                $travel  = NewMovementTravel::where('movement_id', $m->id)->where('status', 'running')->latest()->first();
                $meeting = NewMovementMeeting::where('movement_id', $m->id)->whereNull('end_time')->first();
                if ($travel) {
                    $m->current_status_text = 'Traveling';
                    $m->current_location    = $travel->from_location . ' ➔ ' . ($travel->to_location ?? 'Destination');
                    $m->status_icon         = 'fa-car';
                    $m->current_lat         = $travel->start_lat;
                    $m->current_lng         = $travel->start_lng;
                } elseif ($meeting) {
                    $m->current_status_text = 'In Meeting';
                    $m->current_location    = 'With ' . $meeting->client_name . ' at ' . $meeting->location;
                    $m->status_icon         = 'fa-users';
                    $m->current_lat         = $meeting->latitude;
                    $m->current_lng         = $meeting->longitude;
                } else {
                    $m->current_status_text = 'Decision';
                    $m->current_location    = 'In Transit';
                    $m->status_icon         = 'fa-clock-o';
                }
            } else {
                $m->current_status_text = 'Completed';
                $m->current_location    = $m->start_location;
                $m->status_icon         = 'fa-check-circle';
                $m->current_lat         = 0;
                $m->current_lng         = 0;
            }
        }
        $stats = $this->getTaStats();
        return view('new_movement.dashboard', compact('movements', 'stats'));
    }

    // GET /new-movement/ta-list
    public function taList(Request $request)
    {
        $q = NewMovement::with(['user.branch', 'updater.branch'])->where('ta_status', '!=', 'not_applied');
        if (!isSuperAdmin()) {
            $q->whereHas('user', function ($u) {
                applyBranchScope($u, 'branch_id');
            });
        }

        if ($request->ta_status) $q->where('ta_status', $request->ta_status);
        if ($request->employee_id) $q->where('employee_id', $request->employee_id);
        if ($request->start_date && $request->end_date) {
            $q->whereDate('created_at', '>=', $request->start_date)
              ->whereDate('created_at', '<=', $request->end_date);
        }
        $movements = $q->latest()->get();

        $empQuery = User::orderBy('name');
        if (!isSuperAdmin()) {
            applyBranchScope($empQuery, 'branch_id');
        }
        $employees = $empQuery->get();

        return view('new_movement.ta_list', compact('movements', 'employees'));
    }

    // GET /new-movement/ta-summary
    public function taSummary(Request $request)
    {
        $startDate  = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate    = $request->end_date   ?? now()->toDateString();
        $taStatus   = $request->ta_status  ?? 'approved';
        $employeeId = $request->employee_id;

        $q = NewMovement::selectRaw('employee_id, COUNT(id) as total_movements, SUM(ta_amount) as applied_amount, SUM(ta_app_amt) as approved_amount')
            ->with(['user.branch'])
            ->where('ta_status', '!=', 'not_applied')
            ->where('ta_status', $taStatus)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if (!isSuperAdmin()) {
            $q->whereHas('user', function ($u) {
                applyBranchScope($u, 'branch_id');
            });
        }
        if ($employeeId) $q->where('employee_id', $employeeId);

        $summary = $q->groupBy('employee_id')->get();

        $empQuery = User::orderBy('name');
        if (!isSuperAdmin()) {
            applyBranchScope($empQuery, 'branch_id');
        }
        $employees = $empQuery->get();

        return view('new_movement.ta_summary', compact('summary', 'employees', 'startDate', 'endDate', 'taStatus'));
    }

    // GET /new-movement/ta-summary-details
    public function taSummaryDetails(Request $request)
    {
        $employeeId = $request->employee_id;
        if (!$employeeId) abort(400, 'Employee ID required');
        $employee  = User::with('branch')->findOrFail($employeeId);

        if (!isSuperAdmin()) {
            checkBranchAccess($employee->branch_id);
        }

        $q = NewMovement::where('employee_id', $employeeId)->where('ta_status', $request->ta_status ?? 'approved');
        if ($request->start_date && $request->end_date) {
            $q->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
        }
        $movements = $q->latest()->get();
        return view('new_movement.ta_summary_details', compact('employee', 'movements'));
    }

    // POST /new-movement/ta-summary-approve
    public function taSummaryApprove(Request $request)
    {
        $q = NewMovement::where('ta_status', 'approved');
        if (!isSuperAdmin()) {
            $q->whereHas('user', function ($u) {
                applyBranchScope($u, 'branch_id');
            });
        }

        if ($request->employee_id) $q->where('employee_id', $request->employee_id);
        if ($request->start_date && $request->end_date) {
            $q->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
        }
        $q->update(['ta_status' => $request->ta_status ?? 'handed_over']);
        return response()->json(['success' => true, 'message' => 'TA status updated']);
    }

    // GET /new-movement/details/{id}
    public function details($id)
    {
        $movement = NewMovement::with(['user.branch', 'updater.branch', 'travels', 'meetings', 'expenses.travel'])->findOrFail($id);

        if (!can('movements') && auth()->user()->group_id != 1) {
            if ($movement->employee_id != auth()->id()) {
                abort(403, 'Unauthorized. You can only view your own movements.');
            }
        } elseif (!isSuperAdmin()) {
            checkBranchAccess($movement->user->branch_id);
        }

        return view('new_movement.details', compact('movement'));
    }

    // POST /new-movement/admin-approve-ta/{id}
    public function adminApproveTa(Request $request, $id)
    {
        $movement = NewMovement::with(['user', 'expenses'])->findOrFail($id);

        if (!can('movements') && auth()->user()->group_id != 1) {
            return back()->with('error', 'Unauthorized action.');
        }

        if (!isSuperAdmin()) {
            checkBranchAccess($movement->user->branch_id);
        }

        $action = $request->input('action', 'approve'); // 'approve' or 'reject'

        \DB::beginTransaction();
        try {
            if ($action === 'reject') {
                $movement->update([
                    'ta_status'  => 'rejected',
                    'ta_app_amt' => 0,
                    'admin_note' => $request->input('admin_note'),
                    'updated_by' => auth()->id(),
                ]);

                foreach ($movement->expenses as $exp) {
                    $exp->update(['approve_amount' => 0]);
                }

                \DB::commit();
                return back()->with('success', 'TA Application rejected successfully.');
            }

            // Approve action
            $expensesData = $request->input('expenses', []);
            $totalApproved = 0;

            foreach ($movement->expenses as $exp) {
                $approvedAmt = isset($expensesData[$exp->id]) ? max(0, (float)$expensesData[$exp->id]) : $exp->amount;
                $exp->update(['approve_amount' => $approvedAmt]);
                $totalApproved += $approvedAmt;
            }

            if ($request->filled('total_approved_amount')) {
                $totalApproved = max(0, (float)$request->input('total_approved_amount'));
            }

            $movement->update([
                'ta_status'  => 'approved',
                'ta_app_amt' => $totalApproved,
                'admin_note' => $request->input('admin_note'),
                'updated_by' => auth()->id(),
            ]);

            \DB::commit();
            return back()->with('success', 'TA Application approved successfully. Total approved amount: ৳' . number_format($totalApproved, 2));
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ================================================================
    //  EMPLOYEE VIEWS
    // ================================================================

    // GET /new-movement/my-dashboard
    public function empDashboard()
    {
        $userId        = Auth::id();
        $activeMovement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();

        if ($activeMovement) {
            $activeTravel = NewMovementTravel::where('movement_id', $activeMovement->id)->where('status', 'running')->latest()->first();
            if ($activeTravel) return redirect()->route('new-movement.traveling');

            $activeMeeting = NewMovementMeeting::where('movement_id', $activeMovement->id)->whereNull('end_time')->first();
            if ($activeMeeting) return redirect()->route('new-movement.meeting-running');

            $pendingFeedback = NewMovementMeeting::where('movement_id', $activeMovement->id)->whereNotNull('end_time')->whereNull('feedback')->latest()->first();
            if ($pendingFeedback) return redirect()->route('new-movement.feedback-form');

            return redirect()->route('new-movement.decision');
        }

        $movements = NewMovement::where('employee_id', $userId)->latest()->get();
        $stats     = $this->getTaStats($userId);
        return view('new_movement.emp_dashboard', compact('movements', 'stats'));
    }

    // GET /new-movement/start  + POST /new-movement/start
    public function showStart()
    {
        return view('new_movement.start_form');
    }

    public function processStart(Request $request)
    {
        $request->validate(['start_location' => 'required|string']);
        $userId = Auth::id();

        $photoPath = null;
        if ($request->hasFile('start_photo')) {
            $file      = $request->file('start_photo');
            $filename  = 'start_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movement_photos'), $filename);
            $photoPath = 'uploads/movement_photos/' . $filename;
        }

        $movement = NewMovement::create([
            'employee_id'     => $userId,
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

        return redirect()->route('new-movement.traveling');
    }

    // GET /new-movement/traveling
    public function traveling()
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        $travel = NewMovementTravel::where('movement_id', $movement->id)->where('status', 'running')->latest()->first();
        if (!$travel) return redirect()->route('new-movement.decision');

        return view('new_movement.traveling', compact('movement', 'travel'));
    }

    // POST /new-movement/reached-destination
    public function reachedDestination(Request $request)
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

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

        if ($request->is_office_return == 1 || $request->is_home_return == 1) {
            $movement->update(['end_time' => now(), 'status' => 'completed']);
            return redirect()->route('new-movement.my-dashboard')->with('success', 'Movement completed!');
        }

        return redirect()->route('new-movement.start-meeting');
    }

    // GET /new-movement/start-meeting
    public function showStartMeeting()
    {
        return view('new_movement.meeting_form');
    }

    // POST /new-movement/start-meeting
    public function processStartMeeting(Request $request)
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        NewMovementMeeting::create([
            'movement_id'      => $movement->id,
            'entity_type'      => $request->entity_type,
            'client_name'      => $request->client_name,
            'contact_person'   => $request->contact_person,
            'contact_email'    => $request->contact_email,
            'contact_phone'    => $request->contact_phone,
            'contact_job_title'=> $request->contact_job_title,
            'meeting_type'     => $request->meeting_type,
            'location'         => $request->location,
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'remarks'          => $request->remarks,
            'start_time'       => now(),
        ]);

        return redirect()->route('new-movement.meeting-running');
    }

    // GET /new-movement/meeting-running
    public function meetingRunning()
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        $meeting = NewMovementMeeting::where('movement_id', $movement->id)->whereNull('end_time')->first();
        if (!$meeting) return redirect()->route('new-movement.decision');

        return view('new_movement.meeting_running', compact('meeting'));
    }

    // POST /new-movement/end-meeting
    public function endMeeting()
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        $meeting = NewMovementMeeting::where('movement_id', $movement->id)->whereNull('end_time')->first();
        if ($meeting) {
            $meeting->update(['end_time' => now()]);
            return redirect()->route('new-movement.feedback-form');
        }
        return redirect()->route('new-movement.decision');
    }

    // GET /new-movement/feedback-form
    public function feedbackForm()
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        $meeting = NewMovementMeeting::where('movement_id', $movement->id)->latest()->first();
        return view('new_movement.feedback_form', compact('meeting'));
    }

    // POST /new-movement/submit-feedback
    public function submitFeedback(Request $request)
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        $meeting = NewMovementMeeting::where('movement_id', $movement->id)->latest()->first();
        if ($meeting) {
            $meeting->update(['feedback' => $request->feedback]);
        }
        return redirect()->route('new-movement.decision');
    }

    // GET /new-movement/log-visit
    public function showLogVisit()
    {
        return view('new_movement.log_visit_form');
    }

    // POST /new-movement/log-visit
    public function processLogVisit(Request $request)
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        NewMovementMeeting::create([
            'movement_id'      => $movement->id,
            'entity_type'      => $request->entity_type,
            'client_name'      => $request->client_name,
            'contact_person'   => $request->contact_person,
            'contact_email'    => $request->contact_email,
            'contact_phone'    => $request->contact_phone,
            'contact_job_title'=> $request->contact_job_title,
            'meeting_type'     => 'visit_only',
            'location'         => $request->location,
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'remarks'          => $request->remarks,
            'start_time'       => now(),
            'end_time'         => now(),
            'feedback'         => 'Client Visit (Busy/No Meeting)',
        ]);

        return redirect()->route('new-movement.decision');
    }

    // GET /new-movement/decision
    public function decision()
    {
        return view('new_movement.decision');
    }

    // POST /new-movement/handle-decision
    public function handleDecision(Request $request)
    {
        $userId   = Auth::id();
        $movement = NewMovement::where('employee_id', $userId)->where('status', 'active')->latest()->first();
        if (!$movement) return redirect()->route('new-movement.my-dashboard');

        $choice = $request->choice;

        if ($choice === 'meeting') {
            return redirect()->route('new-movement.start-meeting');
        }

        $travelData = [
            'movement_id'   => $movement->id,
            'from_location' => $request->current_location,
            'start_lat'     => $request->latitude,
            'start_lng'     => $request->longitude,
            'start_time'    => now(),
            'status'        => 'running',
        ];

        if ($choice === 'office') {
            $travelData['to_location']    = 'Office';
            $travelData['is_office_return'] = 1;
        } elseif ($choice === 'home') {
            $travelData['to_location'] = 'Home';
        } else {
            // Next client visit
        }

        NewMovementTravel::create($travelData);
        return redirect()->route('new-movement.traveling');
    }

    // POST /new-movement/apply-ta/{id}
    public function applyTa(Request $request, $id)
    {
        $request->validate(['expenses' => 'required|array|min:1']);
        $userId = Auth::id();
        $movement = NewMovement::where('id', $id)->where('employee_id', $userId)->firstOrFail();

        \DB::beginTransaction();
        try {
            NewMovementExpense::where('movement_id', $id)->delete();
            $total = 0;
            foreach ($request->expenses as $exp) {
                $amount = (float)($exp['amount'] ?? 0);
                $total += $amount;
                NewMovementExpense::create([
                    'movement_id'    => $id,
                    'travel_id'      => $exp['travel_id'] ?? null,
                    'transport_type' => $exp['type'] ?? null,
                    'amount'         => $amount,
                    'approve_amount' => $amount,
                    'note'           => $exp['note'] ?? null,
                    'created_by'     => $userId,
                ]);
            }
            $movement->update(['ta_status' => 'pending', 'ta_amount' => $total, 'ta_app_amt' => $total, 'updated_by' => $userId]);
            \DB::commit();
            return redirect()->route('new-movement.my-dashboard')->with('success', 'TA applied successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
