<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiChatService;
use Illuminate\Support\Facades\Validator;

class ChatbotController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiChatService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Send message to chatbot
     */
    public function sendMessage(Request $request)
    {
        // Log incoming request
        \Log::info('Chatbot request received', [
            'message' => $request->input('message'),
            'has_history' => !empty($request->input('history'))
        ]);

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array|max:20'
        ]);

        if ($validator->fails()) {
            \Log::error('Chatbot validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Pesan tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        try {
            $response = $this->geminiService->sendMessage($userMessage, $history);
            \Log::info('Chatbot response', ['success' => $response['success']]);
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Chatbot controller exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quick reply suggestions
     */
    public function getQuickReplies()
    {
        $quickReplies = $this->geminiService->getQuickReplies();

        return response()->json([
            'success' => true,
            'quick_replies' => $quickReplies
        ]);
    }
}
