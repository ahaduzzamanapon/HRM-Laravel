<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Loan;

class LoanAmountModifiedNotification extends Notification
{
    use Queueable;

    public $loan;
    public $oldAmount;
    public $newAmount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Loan $loan, $oldAmount, $newAmount)
    {
        $this->loan = $loan;
        $this->oldAmount = $oldAmount;
        $this->newAmount = $newAmount;
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
        $appNo = $this->loan->application_no ?? ('LN-' . $this->loan->id);

        return (new MailMessage)
                    ->subject('Loan Application Amount Updated (' . $appNo . ')')
                    ->greeting('Hello ' . ($notifiable->name ?? 'Employee') . ',')
                    ->line('Your requested loan amount for application (' . $appNo . ') has been updated by an administrator.')
                    ->line('Previous Amount: ৳ ' . number_format($this->oldAmount, 2))
                    ->line('New Requested Amount: ৳ ' . number_format($this->newAmount, 2))
                    ->line('Installment Term: ' . $this->loan->installments . ' Months')
                    ->line('Monthly EMI: ৳ ' . number_format($this->loan->monthly_installment, 2))
                    ->action('View Loan Details', url('/employee-loans/' . $this->loan->id))
                    ->line('If you have any questions, please contact HR / Finance department.');
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
        
        return [
            'type' => 'loan_application',
            'loan_id' => $this->loan->id,
            'user_id' => $this->loan->employee_id,
            'applicant_name' => $applicantName,
            'status' => 'Amount Modified',
            'title' => 'Loan Amount Modified',
            'application_no' => $appNo,
            'old_amount' => $this->oldAmount,
            'new_amount' => $this->newAmount,
            'message' => "Your requested loan amount for {$appNo} was modified from ৳" . number_format($this->oldAmount, 2) . " to ৳" . number_format($this->newAmount, 2),
            'url' => route('employeeLoans.show', $this->loan->id),
        ];
    }
}
