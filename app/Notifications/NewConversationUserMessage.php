<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewConversationUserMessage extends Notification
{
    use Queueable;

    private $senderUserId;
    private $conversationId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($senderUserId, $conversationId)
    {
        $this->senderUserId = $senderUserId;
        $this->conversationId = $conversationId;
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

    public function toDatabase($notifiable)
    {
        return [
            "senderUserId" => $this->senderUserId,
            "conversationId" => $this->conversationId,
            "user" => $notifiable
        ];
    }
}
