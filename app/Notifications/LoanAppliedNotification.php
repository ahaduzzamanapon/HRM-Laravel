<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Loan;

class LoanAppliedNotification extends Notification
{
    use Queueable;

    public $loan;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
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
        $empName = optional($this->loan->employee)->name ?? 'An employee';
        $loanTypeName = optional($this->loan->loanType)->name ?? 'Staff Loan';
        $appNo = $this->loan->application_no ?? ('LN-' . $this->loan->id);
        
        return (new MailMessage)
                    ->subject('New Loan Application: ' . $empName . ' (' . $appNo . ')')
                    ->greeting('Hello Admin,')
                    ->line($empName . ' has submitted a new loan application.')
                    ->line('Application No: ' . $appNo)
                    ->line('Loan Category: ' . $loanTypeName)
                    ->line('Requested Amount: ৳ ' . number_format($this->loan->amount, 2))
                    ->line('Installment Term: ' . $this->loan->installments . ' Months')
                    ->action('Review Loan Application', url('/employee-loans/' . $this->loan->id))
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
        $user = $this->loan->employee;
        $applicantName = $user ? trim($user->name . ' ' . ($user->last_name ?? '')) : 'Employee';
        $loanTypeName = optional($this->loan->leaveType)->name ?? optional($this->loan->loanType)->name ?? 'Staff Loan';
        $appNo = $this->loan->application_no ?? ('LN-' . $this->loan->id);
        $amount = number_format($this->loan->amount, 2);

        return [
            'type' => 'loan_application',
            'loan_id' => $this->loan->id,
            'user_id' => $this->loan->employee_id,
            'applicant_name' => $applicantName,
            'loan_type' => $loanTypeName,
            'amount' => $amount,
            'status' => $this->loan->status ?? 'Pending',
            'title' => 'New Loan Application',
            'message' => "{$applicantName} applied for {$loanTypeName} of ৳{$amount}",
            'url' => route('employeeLoans.show', $this->loan->id),
        ];
    }
}
