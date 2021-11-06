<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewArticleUserComment extends Notification
{
    use Queueable;

    private $commenterUserId;
    private $url;
    private $commentId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($commenterUserId, $url, $commentId)
    {
        $this->commenterUserId = $commenterUserId;
        $this->url = $url;
        $this->commentId = $commentId;
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
            "commenterUserId" => $this->commenterUserId,
            "url" => $this->url,
            "commentId" => $this->commentId,
            "user" => $notifiable
        ];
    }
}
