<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark notification as read and redirect to the leave application details page.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function readAndRedirect($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            
            $data = $notification->data;
            $loanId = $data['loan_id'] ?? null;
            if ($loanId) {
                // Mark any other unread notifications for this loan as read for this user
                $user->unreadNotifications->each(function ($n) use ($loanId) {
                    $d = $n->data;
                    if (isset($d['loan_id']) && (int)$d['loan_id'] === (int)$loanId) {
                        $n->markAsRead();
                    }
                });
                return redirect(route('employeeLoans.show', $loanId));
            } elseif (isset($data['leave_application_id'])) {
                return redirect(route('leaveApplications.show', $data['leave_application_id']));
            } elseif (isset($data['url'])) {
                return redirect($data['url']);
            }
        } elseif (is_string($id) && str_starts_with($id, 'loan_pending_')) {
            $loanId = (int) str_replace('loan_pending_', '', $id);
            $user->unreadNotifications->each(function ($n) use ($loanId) {
                $d = $n->data;
                if (isset($d['loan_id']) && (int)$d['loan_id'] === $loanId) {
                    $n->markAsRead();
                }
            });
            return redirect(route('employeeLoans.show', $loanId));
        }

        return redirect('/');
    }

    /**
     * Mark all unread notifications as read for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return redirect()->back();
    }
}
