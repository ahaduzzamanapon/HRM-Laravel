<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DepartmentalCase;

class DisciplinaryCaseNotification extends Notification
{
    use Queueable;

    public $departmentalCase;
    public $actionType;

    /**
     * Create a new notification instance.
     *
     * @param DepartmentalCase $departmentalCase
     * @param string $actionType
     */
    public function __construct(DepartmentalCase $departmentalCase, $actionType = 'update')
    {
        $this->departmentalCase = $departmentalCase;
        $this->actionType = $actionType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $case = $this->departmentalCase;
        $caseNo = $case->case_no ?? ('DC-REF-' . $case->id);
        $penaltyName = $case->penalty ? $case->penalty->name : 'N/A';

        $mail = (new MailMessage)
            ->subject("Official Disciplinary Notice: {$caseNo}")
            ->greeting("Dear {$notifiable->name},")
            ->line("This is an official communication regarding a disciplinary case registered under reference number: **{$caseNo}**.");

        $mail->line("**Allegation Type:** " . ($case->allegation_type ?? 'N/A'));
        $mail->line("**Allegation Category:** " . ($case->allegation_category ?? 'N/A'));
        $mail->line("**Current Status:** " . ($case->status ?? 'Pending'));

        if ($case->incident_date) {
            $mail->line("**Incident Date:** " . \Carbon\Carbon::parse($case->incident_date)->format('d M, Y'));
        }

        if ($case->show_cause_date) {
            $mail->line("**Show Cause Notice Date:** " . \Carbon\Carbon::parse($case->show_cause_date)->format('d M, Y'));
        }

        if ($case->disciplinary_issue_details) {
            $mail->line("**Issue Details:** " . $case->disciplinary_issue_details);
        }

        if ($case->penalty_id) {
            $mail->line("**Penalty Imposed:** " . $penaltyName);
            if ($case->penalty_amount && $case->penalty_amount > 0) {
                $mail->line("**Penalty Amount:** " . number_format($case->penalty_amount, 2));
            }
        }

        if ($case->final_action_taken) {
            $mail->line("**Action / Decision Taken:** " . $case->final_action_taken);
        }

        $mail->line("Please contact Human Resources or your Departmental Committee for further instructions or clarifications.")
            ->salutation("Best regards,\nHR Department & Disciplinary Committee");

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'case_id' => $this->departmentalCase->id,
            'case_no' => $this->departmentalCase->case_no,
            'allegation_type' => $this->departmentalCase->allegation_type,
            'status' => $this->departmentalCase->status,
            'penalty' => $this->departmentalCase->penalty ? $this->departmentalCase->penalty->name : null,
            'action_type' => $this->actionType,
        ];
    }
}
