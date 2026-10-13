<x-layouts.resident>
    <x-slot:title>AI Barangay Assistant</x-slot:title>

    <div class="max-w-4xl mx-auto h-[calc(100vh-12rem)] flex flex-col bg-white dark:bg-[#1E293B] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <!-- Chat Header -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-blue-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm md:text-base font-extrabold text-[#12345B] dark:text-white leading-tight">
                        North Poblacion AI Barangay Assistant
                    </h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Online • Answering in Cebuano/Bisaya, Tagalog, and English
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('resident.chatbot.clear') }}" onsubmit="return confirm('Clear chat history?');">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[11px] font-bold text-slate-600 dark:text-slate-300 transition-colors">
                        Clear History
                    </button>
                </form>
            </div>
        </div>

        <!-- Chat Messages Container -->
        <div id="chat-messages" class="flex-1 p-6 overflow-y-auto space-y-4">
            <!-- Welcome Greeting if empty -->
            @if($messages->isEmpty())
                <div class="p-6 rounded-3xl bg-blue-50/60 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/60 max-w-xl mx-auto text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center mx-auto shadow-sm">
                        👋
                    </div>
                    <h3 class="text-base font-black text-[#12345B] dark:text-white">
                        Maayong Adlaw, {{ $user->name }}!
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Ako ang opisyal nga AI Assistant sa Barangay North Poblacion, Maramag. Makatabang ko kanimo sa pagsusi sa imong reklamo, pamaagi sa mga clearance, iskedyul sa basura, o mga serbisyo sa barangay.
                    </p>
                </div>
            @endif

            @foreach($messages as $msg)
                @if($msg->sender_type === 'user')
                    <!-- Resident Message Bubble -->
                    <div class="flex justify-end">
                        <div class="max-w-lg px-4 py-3 rounded-2xl rounded-tr-xs bg-[#12345B] text-white text-xs md:text-sm leading-relaxed shadow-sm">
                            {{ $msg->message }}
                        </div>
                    </div>
                @else
                    <!-- AI Assistant Message Bubble -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-1 shadow-sm">
                            AI
                        </div>
                        <div class="max-w-lg px-4 py-3 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs md:text-sm leading-relaxed shadow-xs whitespace-pre-line">
                            {!! nl2br(e($msg->message)) !!}
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Typing Indicator (Hidden by default) -->
            <div id="typing-indicator" class="hidden flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm">
                    AI
                </div>
                <div class="px-4 py-3 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center gap-1.5 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.15s"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        </div>

        <!-- Suggested Prompt Chips -->
        <div class="px-6 py-2 border-t border-slate-100 dark:border-slate-800/60 bg-slate-50/40 dark:bg-slate-900/40 flex items-center gap-2 overflow-x-auto text-xs no-scrollbar">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider shrink-0">Suggestions:</span>
            <button type="button" onclick="sendPrompt('Unsaon pagkuha og Barangay Clearance?')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-colors">
                📄 Barangay Clearance
            </button>
            <button type="button" onclick="sendPrompt('Unsa ang update sa akong mga gisumite nga reklamo?')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-colors">
                🔍 Status sa Akong Reklamo
            </button>
            <button type="button" onclick="sendPrompt('Iskedyul sa pagkolekta sa basura sa atong Purok')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-colors">
                🚛 Waste Collection Schedule
            </button>
            <button type="button" onclick="sendPrompt('Unsa ang emergency hotlines sa Maramag?')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-colors">
                🚨 Emergency Numbers
            </button>
        </div>

        <!-- Input Bar -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-[#1E293B]">
            <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex items-center gap-3">
                <input id="chat-input" 
                       type="text" 
                       placeholder="Pangutana dinhi (Cebuano/Bisaya, Tagalog, English)..." 
                       autocomplete="off"
                       class="flex-1 px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                <button type="submit" 
                        id="chat-send-btn"
                        class="px-6 py-3.5 rounded-2xl bg-[#12345B] hover:bg-[#0B2340] text-white font-bold text-sm shadow-md transition-all flex items-center gap-2 shrink-0">
                    <span>Send</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const messagesContainer = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('typing-indicator');
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('chat-send-btn');

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        scrollToBottom();

        function sendPrompt(text) {
            chatInput.value = text;
            handleChatSubmit(new Event('submit'));
        }

        async function handleChatSubmit(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            // Render user bubble immediately
            const userBubble = document.createElement('div');
            userBubble.className = 'flex justify-end';
            userBubble.innerHTML = `
                <div class="max-w-lg px-4 py-3 rounded-2xl rounded-tr-xs bg-[#12345B] text-white text-xs md:text-sm leading-relaxed shadow-sm">
                    ${escapeHtml(message)}
                </div>
            `;
            messagesContainer.insertBefore(userBubble, typingIndicator);

            chatInput.value = '';
            chatInput.disabled = true;
            sendBtn.disabled = true;
            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            try {
                const response = await fetch("{{ route('resident.chatbot.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                typingIndicator.classList.add('hidden');

                if (data.success) {
                    const aiBubble = document.createElement('div');
                    aiBubble.className = 'flex items-start gap-3';
                    aiBubble.innerHTML = `
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-1 shadow-sm">
                            AI
                        </div>
                        <div class="max-w-lg px-4 py-3 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs md:text-sm leading-relaxed shadow-xs whitespace-pre-line">
                            ${data.reply}
                        </div>
                    `;
                    messagesContainer.insertBefore(aiBubble, typingIndicator);
                } else {
                    showToast('Could not reach assistant. Please try again.', 'error');
                }
            } catch (err) {
                typingIndicator.classList.add('hidden');
                showToast('Network error while sending message.', 'error');
            } finally {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
                scrollToBottom();
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
    @endpush
</x-layouts.resident>
