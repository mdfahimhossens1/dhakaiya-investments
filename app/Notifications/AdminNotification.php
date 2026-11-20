<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $type = 'info',
        public ?string $url = null,
        public ?int $broadcastId = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'        => $this->title,
            'message'      => $this->message,
            'type'         => $this->type,
            'url'          => $this->url,
            'broadcast_id' => $this->broadcastId,
        ];
    }
}