<?php

namespace App\Services\Notification;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class NotificationService
{
    public function create(string $userId, string $content): Notification
    {
        try {
            return Notification::create([
                'assigned_user' => $userId,
                'content' => $content
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao criar notificação: ' . $e->getMessage());
            throw new Exception('unable_to_create_notification');
        }
    }

    public function list(string $userId): array
    {
        return Notification::where('assigned_user', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function discard(int $notificationId): bool
    {
        try {
            return Notification::where('id', $notificationId)->delete();
        } catch (Exception $e) {
            Log::error('Erro ao descartar notificação: ' . $e->getMessage());
            throw new Exception('unable_to_discard_notification');
        }
    }
}
