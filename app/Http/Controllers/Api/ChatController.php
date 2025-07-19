<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Fixture;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

class ChatController extends Controller
{
    /**
     * Maçın sohbet mesajlarını getir
     */
    public function getMessages($fixtureId): JsonResponse
    {
        try {
            $messages = ChatMessage::byFixture($fixtureId)
                ->with(['user'])
                ->latest()
                ->limit(100)
                ->get()
                ->reverse()
                ->values();

            return response()->json([
                'success' => true,
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mesajlar alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Yeni mesaj gönder
     */
    public function sendMessage(Request $request, $fixtureId): JsonResponse
    {
        try {
            $request->validate([
                'message' => 'required|string|max:500',
                'user_name' => 'required|string|max:50',
                'user_avatar' => 'nullable|string|max:255'
            ]);

            // Maçın mevcut olduğunu kontrol et
            $fixture = Fixture::findOrFail($fixtureId);

            $chatMessage = ChatMessage::create([
                'fixture_id' => $fixtureId,
                'user_id' => Auth::id() ?? null,
                'message' => $request->message,
                'user_name' => $request->user_name,
                'user_avatar' => $request->user_avatar,
                'is_system' => false,
                'metadata' => $request->metadata ?? []
            ]);

            // Mesajı diğer kullanıcılara broadcast et
            broadcast(new \App\Events\MessageSent($chatMessage))->toOthers();

            return response()->json([
                'success' => true,
                'data' => $chatMessage,
                'message' => 'Mesaj başarıyla gönderildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mesaj gönderilirken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sistem mesajı gönder (gol, kart vb.)
     */
    public function sendSystemMessage(Request $request, $fixtureId): JsonResponse
    {
        try {
            $request->validate([
                'message' => 'required|string|max:500',
                'event_type' => 'required|string|in:goal,card,substitution,half_time,full_time'
            ]);

            $fixture = Fixture::findOrFail($fixtureId);

            $chatMessage = ChatMessage::create([
                'fixture_id' => $fixtureId,
                'user_id' => null,
                'message' => $request->message,
                'user_name' => 'Sistem',
                'user_avatar' => null,
                'is_system' => true,
                'metadata' => [
                    'event_type' => $request->event_type,
                    'timestamp' => now()->toISOString()
                ]
            ]);

            // Sistem mesajını broadcast et
            broadcast(new \App\Events\MessageSent($chatMessage));

            return response()->json([
                'success' => true,
                'data' => $chatMessage,
                'message' => 'Sistem mesajı gönderildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sistem mesajı gönderilirken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mesaj sil (admin/moderatör)
     */
    public function deleteMessage($messageId): JsonResponse
    {
        try {
            $message = ChatMessage::findOrFail($messageId);
            
            // Yetki kontrolü (basit - gerçek uygulamada role/permission kullanılmalı)
            if (!Auth::check() || !Auth::user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz yok'
                ], 403);
            }

            $message->delete();

            // Mesaj silindiğini broadcast et
            broadcast(new \App\Events\MessageDeleted($messageId, $message->fixture_id));

            return response()->json([
                'success' => true,
                'message' => 'Mesaj silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mesaj silinirken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aktif sohbet kullanıcılarını getir
     */
    public function getActiveUsers($fixtureId): JsonResponse
    {
        try {
            // Son 5 dakika içinde mesaj gönderen kullanıcılar
            $activeUsers = ChatMessage::byFixture($fixtureId)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->where('is_system', false)
                ->select('user_name', 'user_avatar')
                ->distinct()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $activeUsers,
                'count' => $activeUsers->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Aktif kullanıcılar alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sohbet istatistikleri
     */
    public function getChatStats($fixtureId): JsonResponse
    {
        try {
            $totalMessages = ChatMessage::byFixture($fixtureId)->count();
            $userMessages = ChatMessage::byFixture($fixtureId)->user()->count();
            $systemMessages = ChatMessage::byFixture($fixtureId)->system()->count();
            
            $lastHourMessages = ChatMessage::byFixture($fixtureId)
                ->where('created_at', '>=', now()->subHour())
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_messages' => $totalMessages,
                    'user_messages' => $userMessages,
                    'system_messages' => $systemMessages,
                    'last_hour_messages' => $lastHourMessages,
                    'messages_per_minute' => round($lastHourMessages / 60, 2)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sohbet istatistikleri alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
