<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\LeaveApplication;

class LeaveStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $leaveApplication;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @param LeaveApplication $leaveApplication
     * @param string $status
     * @return void
     */
    public function __construct(LeaveApplication $leaveApplication, string $status)
    {
        $this->leaveApplication = $leaveApplication;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $leaveTypeName = optional($this->leaveApplication->leaveType)->name ?? 'Leave';
        $startDate = \Carbon\Carbon::parse($this->leaveApplication->start_date)->format('d M, Y');
        $endDate = \Carbon\Carbon::parse($this->leaveApplication->end_date)->format('d M, Y');
        $period = ($startDate === $endDate) ? $startDate : "{$startDate} - {$endDate}";

        $statusLabel = $this->status;

        return [
            'type' => 'leave_status_updated',
            'leave_application_id' => $this->leaveApplication->id,
            'user_id' => $this->leaveApplication->user_id,
            'applicant_name' => optional($this->leaveApplication->user)->name ?? 'Employee',
            'leave_type' => $leaveTypeName,
            'period' => $period,
            'status' => $this->status,
            'title' => "Leave Application {$statusLabel}",
            'message' => "Your {$leaveTypeName} application ({$period}) has been {$statusLabel}.",
            'url' => route('leaveApplications.show', $this->leaveApplication->id),
        ];
    }
}
