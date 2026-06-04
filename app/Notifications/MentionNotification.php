<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MentionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $mentioner,
        public Comment $comment
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'mentioner_name' => $this->mentioner->name,
            'comment_body'   => $this->comment->body,
            'post_id'        => $this->comment->post_id,
        ];
    }
}