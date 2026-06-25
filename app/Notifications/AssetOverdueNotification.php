<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\AssetAssignment;

class AssetOverdueNotification extends Notification
{
    use Queueable;

    public $assignment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AssetAssignment $assignment)
    {
        $this->assignment = $assignment;
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
        return (new MailMessage)
                    ->subject('URGENT: Asset Return Overdue - ' . $this->assignment->asset->name)
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('This is an automated reminder that the following asset assigned to you is currently OVERDUE for return.')
                    ->line('Asset: ' . $this->assignment->asset->name . ' (' . $this->assignment->asset->asset_code . ')')
                    ->line('Expected Return Date: ' . \Carbon\Carbon::parse($this->assignment->expected_return_date)->format('M d, Y'))
                    ->line('Please return the asset to the administration or IT department as soon as possible.')
                    ->action('View Assignment Details', url('/admin/inventory/asset-assignments'))
                    ->line('If you have already returned it or need an extension, please contact the administrator.');
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
            'asset_id' => $this->assignment->asset_id,
            'assignment_id' => $this->assignment->id,
            'message' => 'OVERDUE: Please return ' . $this->assignment->asset->name,
        ];
    }
}
