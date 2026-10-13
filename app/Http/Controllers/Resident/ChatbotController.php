<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\AI\BarangayAssistantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(protected BarangayAssistantService $assistantService)
    {
    }

    public function index()
    {
        $user = Auth::user();

        // Get or create active chat session
        $session = ChatSession::firstOrCreate(
            ['resident_id' => $user->id],
            [
                'id' => (string) Str::uuid(),
                'title' => 'Barangay Assistant Chat',
            ]
        );

        $messages = $session->messages()->get();

        return view('resident.chatbot.index', compact('session', 'messages', 'user'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'uuid'],
        ]);

        $user = Auth::user();

        $session = ChatSession::firstOrCreate(
            ['resident_id' => $user->id],
            [
                'id' => (string) Str::uuid(),
                'title' => 'Barangay Assistant Chat',
            ]
        );

        $reply = $this->assistantService->respond($user, $session, $request->message);

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'session_id' => $session->id,
        ]);
    }

    public function clearHistory()
    {
        $user = Auth::user();
        $session = ChatSession::where('resident_id', $user->id)->first();
        if ($session) {
            $session->messages()->delete();
        }

        return redirect()->route('resident.chatbot')->with('success', 'Conversation history cleared.');
    }
}
