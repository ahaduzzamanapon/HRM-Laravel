<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LeaveApplication;

class LeaveAppliedNotification extends Notification
{
    use Queueable;

    public $leaveApplication;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(LeaveApplication $leaveApplication)
    {
        $this->leaveApplication = $leaveApplication;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $empName = optional($this->leaveApplication->user)->name ?? 'An employee';
        $leaveTypeName = optional($this->leaveApplication->leaveType)->name ?? 'Leave';
        
        return (new MailMessage)
                    ->subject('New Leave Application: ' . $empName)
                    ->greeting('Hello Admin,')
                    ->line($empName . ' has submitted a new leave application.')
                    ->line('Leave Type: ' . $leaveTypeName)
                    ->line('Duration: ' . $this->leaveApplication->start_date . ' to ' . $this->leaveApplication->end_date)
                    ->line('Reason: ' . ($this->leaveApplication->reason ?? 'N/A'))
                    ->action('Review Application', url('/leaveApplications'))
                    ->line('Please review and take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $user = $this->leaveApplication->user;
        $applicantName = $user ? trim($user->name . ' ' . ($user->last_name ?? '')) : 'Employee';
        $leaveTypeName = optional($this->leaveApplication->leaveType)->name ?? 'Leave';
        $startDate = \Carbon\Carbon::parse($this->leaveApplication->start_date)->format('d M, Y');
        $endDate = \Carbon\Carbon::parse($this->leaveApplication->end_date)->format('d M, Y');
        $period = ($startDate === $endDate) ? $startDate : "{$startDate} - {$endDate}";

        return [
            'type' => 'leave_application',
            'leave_application_id' => $this->leaveApplication->id,
            'user_id' => $this->leaveApplication->user_id,
            'applicant_name' => $applicantName,
            'leave_type' => $leaveTypeName,
            'period' => $period,
            'title' => 'New Leave Application',
            'message' => "{$applicantName} applied for {$leaveTypeName} ({$period})",
            'url' => route('leaveApplications.show', $this->leaveApplication->id),
        ];
    }
}
