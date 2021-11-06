<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewArticleProfileLike extends Notification
{
    use Queueable;

    private $likerUserId;
    private $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($likerUserId, $url)
    {
        $this->likerUserId = $likerUserId;
        $this->url = $url;
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
            "likerUserId" => $this->likerUserId,
            "url" => $this->url,
            "user" => $notifiable
        ];
    }
}
