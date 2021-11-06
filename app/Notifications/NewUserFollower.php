<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserFollower extends Notification
{
    use Queueable;

    private $followerUserId;
    private $url;
    private $followedBack;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($followerUserId, $url, $followedBack)
    {
        $this->followerUserId = $followerUserId;
        $this->url = $url;
        $this->followedBack = $followedBack;
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
            "followerUserId" => $this->followerUserId,
            "url" => $this->url,
            "followedBack" => $this->followedBack,
            "user" => $notifiable
        ];
    }
}
