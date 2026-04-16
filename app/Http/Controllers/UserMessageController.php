<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class UserMessageController extends Controller
{
    //  public function index(Request $request): View
    // {
    //     $messages = Auth::user()
    //         ->messages()
    //         ->latest()
    //         ->paginate(15);

    //     return view('customer.messages.index', compact('messages'));
    // }

    // public function dropdown(): JsonResponse
    // {
    //     $user = Auth::user();

    //     $messages = $user->messages()
    //         ->latest()
    //         ->take(8)
    //         ->get()
    //         ->map(function ($message) {
    //             return [
    //                 'id' => $message->id,
    //                 'title' => $message->title,
    //                 'message' => $message->message,
    //                 'type' => $message->type,
    //                 'is_read' => $message->is_read,
    //                 'created_at' => $message->created_at->diffForHumans(),
    //                 'url' => route('customer.messages.show', $message->id),
    //             ];
    //         });

    //     return response()->json([
    //         'status' => true,
    //         'unread_count' => $user->messages()->where('is_read', false)->count(),
    //         'messages' => $messages,
    //         'view_all_url' => route('customer.messages.index'),
    //     ]);
    // }

    // public function show(int $id): View
    // {
    //     $message = Auth::user()->messages()->findOrFail($id);

    //     if (!$message->is_read) {
    //         $message->update([
    //             'is_read' => true,
    //             'read_at' => now(),
    //         ]);
    //     }

    //     return view('customer.messages.show', compact('message'));
    // }

    // public function markAsRead(int $id): JsonResponse
    // {
    //     $message = Auth::user()->messages()->findOrFail($id);

    //     if (!$message->is_read) {
    //         $message->update([
    //             'is_read' => true,
    //             'read_at' => now(),
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Notification marked as read.',
    //         'unread_count' => Auth::user()->messages()->where('is_read', false)->count(),
    //     ]);
    // }

    // public function markAllAsRead(): JsonResponse
    // {
    //     Auth::user()->messages()
    //         ->where('is_read', false)
    //         ->update([
    //             'is_read' => true,
    //             'read_at' => now(),
    //         ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'All notifications marked as read.',
    //         'unread_count' => 0,
    //     ]);
    // }


    public function index(Request $request): View
    {
        $user = Auth::user();

        $messages = $user->messages()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCount = $user->messages()->count();
        $readCount = $user->messages()->where('is_read', true)->count();
        $unreadCount = $user->messages()->where('is_read', false)->count();
        $latestMessage = $user->messages()->latest()->first();

        return view('customer.messages.index', compact(
            'messages',
            'totalCount',
            'readCount',
            'unreadCount',
            'latestMessage'
        ));
    }

    public function dropdown(): JsonResponse
    {
        $user = Auth::user();

        $messages = $user->messages()
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'title' => $message->title,
                    'message' => $message->message,
                    'type' => $message->type,
                    'is_read' => (bool) $message->is_read,
                    'created_at' => optional($message->created_at)->diffForHumans(),
                    'url' => route('customer.messages.show', $message->id),
                ];
            });

        return response()->json([
            'status' => true,
            'unread_count' => $user->messages()->where('is_read', false)->count(),
            'messages' => $messages,
            'view_all_url' => route('customer.messages.index'),
        ]);
    }

    public function show(int $id): View
    {
        $user = Auth::user();

        $message = $user->messages()->findOrFail($id);

        if (! $message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('customer.messages.show', compact('message'));
    }

    public function markAsRead(int $id): JsonResponse
    {
        $user = Auth::user();

        $message = $user->messages()->findOrFail($id);

        if (! $message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read.',
            'unread_count' => $user->messages()->where('is_read', false)->count(),
        ]);
    }

    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();

        $user->messages()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read.',
            'unread_count' => 0,
        ]);
    }
}
