<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\DiscardNotificationRequest;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function list(): JsonResponse
    {   
        $userId = Auth::id();
        $notifications = $this->notificationService->list($userId);
        return response()->json(['notifications' => $notifications], 200);
    }

    public function discard(DiscardNotificationRequest $request): JsonResponse
    {   
        $id = $request->validated()['id'];
        $response = $this->notificationService->discard($id);
        return response()->json($response, 200);
    }
}