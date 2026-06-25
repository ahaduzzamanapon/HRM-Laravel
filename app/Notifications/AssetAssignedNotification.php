<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\AssetAssignment;

class AssetAssignedNotification extends Notification
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
                    ->subject('New Asset Assigned: ' . $this->assignment->asset->name)
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('You have been assigned a new asset: ' . $this->assignment->asset->name . ' (' . $this->assignment->asset->asset_code . ').')
                    ->line('Assigned Date: ' . \Carbon\Carbon::parse($this->assignment->assigned_date)->format('M d, Y'))
                    ->line('Expected Return Date: ' . ($this->assignment->expected_return_date ? \Carbon\Carbon::parse($this->assignment->expected_return_date)->format('M d, Y') : 'N/A'))
                    ->line('Condition: ' . ($this->assignment->condition_on_assignment ?? 'N/A'))
                    ->action('View Assignments', url('/admin/inventory/asset-assignments'))
                    ->line('Please take care of the asset while it is in your possession.');
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
            'message' => 'You were assigned asset: ' . $this->assignment->asset->name,
        ];
    }
}
