<x-layouts.resident>
    <x-slot:title>AI Barangay Assistant</x-slot:title>

    <div class="relative max-w-4xl mx-auto h-[calc(100vh-12rem)] flex flex-col bg-white/95 dark:bg-[#111C33]/95 backdrop-blur-xl rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl shadow-blue-900/10 dark:shadow-purple-950/30 overflow-hidden animate-fade-in-up">
        <!-- Ambient Background Glowing Blobs (Blue & Purple) -->
        <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-blue-500/15 dark:bg-blue-600/10 blur-3xl blob-morph blob-float pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full bg-purple-500/15 dark:bg-purple-600/10 blur-3xl blob-morph blob-float-delayed pointer-events-none"></div>

        <!-- Chat Header with Reflection Highlight -->
        <div class="relative z-10 px-6 py-4.5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between bg-gradient-to-r from-blue-50/70 via-indigo-50/40 to-purple-50/60 dark:from-slate-900/80 dark:via-blue-950/30 dark:to-purple-950/30 backdrop-blur-md">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg shadow-purple-500/25 animate-glow-pulse">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm md:text-base font-black text-[#12345B] dark:text-white leading-tight">
                            North Poblacion AI Barangay Assistant
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-950/80 dark:to-teal-950/80 text-emerald-700 dark:text-emerald-300 flex items-center gap-1 border border-emerald-200/50 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Online Friend
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                        Understands Cebuano / Bisaya, Tagalog, and English • Ready to help you anytime
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('resident.chatbot.clear') }}" onsubmit="return confirmAction(event, 'Are you sure you want to clear your chat history?', 'Clear Chat History', 'Yes, Clear');">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-white/80 hover:bg-white dark:bg-slate-800 dark:hover:bg-slate-700 text-[11px] font-bold text-slate-600 dark:text-slate-300 transition-all hover:scale-102 flex items-center gap-1 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Clear Chat</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Chat Messages Container -->
        <div id="chat-messages" class="relative z-10 flex-1 p-6 overflow-y-auto space-y-4">
            <!-- Welcome Greeting if empty -->
            @if($messages->isEmpty())
                <div class="p-6 rounded-3xl bg-gradient-to-br from-blue-50/80 via-indigo-50/50 to-purple-50/70 dark:from-blue-950/40 dark:via-indigo-950/30 dark:to-purple-950/40 border border-blue-200/60 dark:border-purple-900/60 max-w-xl mx-auto text-center space-y-3 animate-fade-in-up shadow-lg shadow-purple-950/5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center mx-auto shadow-md shadow-purple-500/25 text-xl font-bold animate-glow-pulse">
                        🏛
                    </div>
                    <h3 class="text-base font-black text-[#12345B] dark:text-white">
                        Maayong Adlaw, {{ $user->name }}! 👋
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Ako ang imong matinud-anong higala ug AI Assistant dinhi sa Barangay North Poblacion, Maramag. Makatabang ko kanimo sa pagsusi sa imong mga reklamo, pag-file og bag-ong reklamo, rekisitos sa clearance, iskedyul sa basura, o unsa pa diha.
                    </p>
                </div>
            @endif

            @foreach($messages as $msg)
                @if($msg->sender_type === 'user')
                    <!-- Resident Message Bubble (Blue to Purple Gradient) -->
                    <div class="flex justify-end animate-fade-in-up">
                        <div class="max-w-lg px-4.5 py-3 rounded-2xl rounded-tr-xs bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-xs md:text-sm leading-relaxed shadow-md shadow-purple-600/15">
                            {{ str_replace('*', '', $msg->message) }}
                        </div>
                    </div>
                @else
                    <!-- AI Assistant Message Bubble (Zero Asterisks) -->
                    <div class="flex items-start gap-3 animate-fade-in-up">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-1 shadow-sm shadow-indigo-500/20">
                            AI
                        </div>
                        <div class="max-w-lg px-4.5 py-3 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs md:text-sm leading-relaxed shadow-sm whitespace-pre-line border border-slate-200/60 dark:border-slate-700/60">
                            {!! nl2br(e(str_replace('*', '', $msg->message))) !!}
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Typing Indicator (Hidden by default) -->
            <div id="typing-indicator" class="hidden flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm animate-pulse">
                    AI
                </div>
                <div class="px-4 py-3 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center gap-1.5 shadow-xs border border-slate-200/50 dark:border-slate-700/50">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0.15s"></span>
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        </div>

        <!-- Suggested Prompt Chips -->
        <div class="px-6 py-2.5 border-t border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/50 flex items-center gap-2 overflow-x-auto text-xs no-scrollbar">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider shrink-0 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Suggested:
            </span>
            <button type="button" onclick="sendPrompt('Unsaon pagkuha og Barangay Clearance?')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-all hover:scale-105 flex items-center gap-1.5 shadow-xs">
                📄 Barangay Clearance
            </button>
            <button type="button" onclick="sendPrompt('Unsa ang update sa akong mga gisumite nga reklamo?')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-all hover:scale-105 flex items-center gap-1.5 shadow-xs">
                🔍 Status sa Akong Reklamo
            </button>
            <button type="button" onclick="sendPrompt('Iskedyul sa pagkolekta sa basura sa atong Purok')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-all hover:scale-105 flex items-center gap-1.5 shadow-xs">
                🚛 Waste Collection Schedule
            </button>
            <button type="button" onclick="sendPrompt('Unsa ang emergency hotlines sa Maramag?')" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 text-slate-700 dark:text-slate-300 shrink-0 transition-all hover:scale-105 flex items-center gap-1.5 shadow-xs">
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
                        class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-extrabold text-sm shadow-lg shadow-purple-600/20 transition-all flex items-center gap-2 shrink-0 hover:scale-105 active:scale-95">
                    <span>Send</span>
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
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

            // Append User Message to UI
            const userBubble = document.createElement('div');
            userBubble.className = 'flex justify-end animate-fade-in-up';
            userBubble.innerHTML = `<div class="max-w-lg px-4.5 py-3 rounded-2xl rounded-tr-xs bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-xs md:text-sm leading-relaxed shadow-md shadow-purple-600/15">${escapeHtml(message)}</div>`;
            messagesContainer.appendChild(userBubble);

            chatInput.value = '';
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
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                typingIndicator.classList.add('hidden');

                if (data.reply) {
                    const cleanReply = escapeHtml(data.reply.replace(/\*/g, '')).replace(/\n/g, '<br>');
                    const aiBubble = document.createElement('div');
                    aiBubble.className = 'flex items-start gap-3 animate-fade-in-up';
                    aiBubble.innerHTML = `
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-1 shadow-sm shadow-indigo-500/20">AI</div>
                        <div class="max-w-lg px-4.5 py-3 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs md:text-sm leading-relaxed shadow-sm whitespace-pre-line border border-slate-200/60 dark:border-slate-700/60">${cleanReply}</div>
                    `;
                    messagesContainer.appendChild(aiBubble);
                } else {
                    showErrorBubble('Nangayo kami og pasaylo, naay problema sa pagtubag. Palihug suwayi pag-usab.');
                }
            } catch (err) {
                typingIndicator.classList.add('hidden');
                showErrorBubble('Connection error. Palihug susiha ang imong koneksyon.');
            }

            scrollToBottom();
        }

        function showErrorBubble(errText) {
            const errBubble = document.createElement('div');
            errBubble.className = 'flex items-start gap-3 animate-fade-in-up';
            errBubble.innerHTML = `
                <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-1">!</div>
                <div class="max-w-lg px-4 py-3 rounded-2xl rounded-tl-xs bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-200 text-xs md:text-sm leading-relaxed border border-rose-200 dark:border-rose-900">${errText}</div>
            `;
            messagesContainer.appendChild(errBubble);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
    @endpush
</x-layouts.resident>
