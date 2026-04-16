<?php

namespace App\Services;

use App\Models\UserMessage;

class UserMessageService
{
    public static function send(
        int $userId,
        string $title,
        string $message,
        ?string $type = null,
        ?array $meta = null
    ): UserMessage {
        return UserMessage::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'meta' => $meta,
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}