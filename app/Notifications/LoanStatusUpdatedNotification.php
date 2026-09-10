<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Loan;

class LoanStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $loan;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Loan $loan, string $status)
    {
        $this->loan = $loan;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appNo = $this->loan->application_no ?? ('LN-' . $this->loan->id);

        return (new MailMessage)
                    ->subject('Loan Application Status Updated: ' . $this->status . ' (' . $appNo . ')')
                    ->greeting('Hello ' . ($notifiable->name ?? 'Employee') . ',')
                    ->line('Your loan application (' . $appNo . ') status has been updated to: ' . $this->status)
                    ->line('Loan Amount: ৳ ' . number_format($this->loan->amount, 2))
                    ->line('Monthly EMI: ৳ ' . number_format($this->loan->monthly_installment, 2))
                    ->action('View Loan Details', url('/employee-loans/' . $this->loan->id))
                    ->line('Thank you for using our HRM system.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $appNo = $this->loan->application_no ?? ('LN-' . $this->loan->id);
        $user = $this->loan->employee;
        $applicantName = $user ? trim($user->name . ' ' . ($user->last_name ?? '')) : 'Employee';
        $amount = number_format($this->loan->amount, 2);

        return [
            'type' => 'loan_application',
            'loan_id' => $this->loan->id,
            'user_id' => $this->loan->employee_id,
            'applicant_name' => $applicantName,
            'status' => $this->status,
            'title' => 'Loan Status: ' . $this->status,
            'message' => "Your loan application ({$appNo}) of ৳{$amount} has been marked as {$this->status}.",
            'url' => route('employeeLoans.show', $this->loan->id),
        ];
    }
}
