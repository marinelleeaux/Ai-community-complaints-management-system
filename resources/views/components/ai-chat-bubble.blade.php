<!-- Floating Interactive Ask AI Assistant Bubble Widget -->
<div id="ai-floating-widget" class="fixed bottom-6 right-6 z-50 font-sans" x-data="{ open: false }">
    
    <!-- Floating Chat Drawer / Popover Window -->
    <div id="ai-chat-drawer" 
         class="hidden flex-col w-[380px] max-w-[calc(100vw-2rem)] h-[520px] bg-white/95 dark:bg-[#0F1B2E]/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/90 dark:border-slate-800/90 overflow-hidden mb-4 transition-all duration-300 transform origin-bottom-right animate-in fade-in slide-in-from-bottom-4">
        
        <!-- Header -->
        <div class="px-5 py-4 bg-gradient-to-r from-[#12345B] via-[#0B2340] to-emerald-900 text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <div class="relative w-9 h-9 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 text-emerald-400">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-[#12345B] animate-pulse"></span>
                </div>
                <div>
                    <h3 class="font-black text-sm text-white tracking-wide flex items-center gap-1.5">
                        <span>Barangay AI Assistant</span>
                    </h3>
                    <p class="text-[10px] text-emerald-300 font-medium">Bisaya • Tagalog • English</p>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <a href="{{ route('resident.chatbot') }}" title="Open Fullscreen Chat" class="p-1.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                </a>
                <button type="button" onclick="toggleAiDrawer()" title="Close Widget" class="p-1.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Conversation Area -->
        <div id="ai-drawer-messages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs">
            <!-- Greeting Bubble -->
            <div class="flex gap-2.5 items-start">
                <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-blue-600 to-emerald-500 text-white flex items-center justify-center font-bold text-[10px] shrink-0 shadow-sm mt-0.5">
                    AI
                </div>
                <div class="p-3.5 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800/90 text-slate-800 dark:text-slate-100 border border-slate-200/80 dark:border-slate-700/80 max-w-[85%] shadow-xs leading-relaxed">
                    <p class="font-semibold text-slate-900 dark:text-white mb-1">Maayong adlaw! 👋</p>
                    Ako ang inyong opisyal nga <strong>North Poblacion AI Assistant</strong>. Pwede ka mangutana bahin sa imong reklamo, iskedyul sa basura, o mga serbisyo sa barangay sa <em>Bisaya, Tagalog, o English</em>.
                </div>
            </div>

            <!-- Quick Suggestion Pills -->
            <div id="ai-quick-pills" class="flex flex-wrap gap-1.5 pt-1 pl-9">
                <button type="button" onclick="sendQuickPrompt('Unsa nay update sa akong mga reklamo?')" 
                        class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/80 hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                    📋 Status sa akong reklamo
                </button>
                <button type="button" onclick="sendQuickPrompt('Unsa ang iskedyul sa pagkolekta sa basura sa Purok?')" 
                        class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/80 hover:bg-emerald-100 dark:hover:bg-emerald-900 transition-colors">
                    🚛 Iskedyul sa Basura
                </button>
                <button type="button" onclick="sendQuickPrompt('Unsaon pagkuha og Barangay Clearance?')" 
                        class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/80 hover:bg-purple-100 dark:hover:bg-purple-900 transition-colors">
                    📑 Barangay Clearance
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 border-t border-slate-200/80 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60">
            <form id="ai-drawer-form" onsubmit="handleAiDrawerSubmit(event)" class="flex items-center gap-2">
                <input type="text" 
                       id="ai-drawer-input" 
                       placeholder="Pangutana sa AI (Bisaya, Tagalog, English)..." 
                       autocomplete="off"
                       class="flex-1 px-3.5 py-2.5 rounded-2xl bg-slate-100/90 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                <button type="submit" 
                        id="ai-drawer-send-btn"
                        class="w-9 h-9 rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-600/30 hover:opacity-95 active:scale-95 transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Floating Trigger Bubble Button -->
    <button type="button" 
            id="ai-bubble-btn"
            onclick="toggleAiDrawer()" 
            aria-label="Open Barangay AI Assistant"
            class="relative group flex items-center gap-2.5 px-4 py-3 rounded-full bg-gradient-to-r from-[#12345B] via-[#0B2340] to-emerald-800 hover:from-blue-700 hover:to-emerald-700 text-white font-bold text-xs shadow-xl shadow-blue-950/40 border border-white/20 transition-all duration-300 transform hover:scale-105 active:scale-95 cursor-pointer">
        
        <!-- Pulse Ring Animation -->
        <span class="absolute -inset-1 rounded-full bg-gradient-to-r from-emerald-500 to-blue-500 opacity-40 blur-xs group-hover:opacity-80 transition duration-500 animate-pulse"></span>
        
        <!-- Icon -->
        <div class="relative w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-emerald-300 group-hover:rotate-12 transition-transform">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
            </svg>
        </div>

        <span class="relative tracking-wide font-extrabold text-[12px] pr-1">Ask AI</span>

        <!-- Sparkle Star Badge -->
        <span class="relative -ml-1 text-emerald-300 animate-bounce">✨</span>
    </button>
</div>

<script>
    function toggleAiDrawer() {
        const drawer = document.getElementById('ai-chat-drawer');
        const isHidden = drawer.classList.contains('hidden');
        if (isHidden) {
            drawer.classList.remove('hidden');
            drawer.classList.add('flex');
            setTimeout(() => document.getElementById('ai-drawer-input')?.focus(), 100);
        } else {
            drawer.classList.add('hidden');
            drawer.classList.remove('flex');
        }
    }

    function sendQuickPrompt(promptText) {
        const input = document.getElementById('ai-drawer-input');
        input.value = promptText;
        document.getElementById('ai-drawer-form').dispatchEvent(new Event('submit'));
    }

    async function handleAiDrawerSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('ai-drawer-input');
        const text = input.value.trim();
        if (!text) return;

        const container = document.getElementById('ai-drawer-messages');
        input.value = '';

        // Append Resident message bubble
        const userBubble = document.createElement('div');
        userBubble.className = 'flex justify-end';
        userBubble.innerHTML = `
            <div class="p-3.5 rounded-2xl rounded-tr-xs bg-blue-600 text-white max-w-[85%] shadow-sm text-xs leading-relaxed">
                ${escapeHtml(text)}
            </div>
        `;
        container.appendChild(userBubble);

        // Append Loading indicator
        const loadingId = 'loading-' + Date.now();
        const loadingBubble = document.createElement('div');
        loadingBubble.id = loadingId;
        loadingBubble.className = 'flex gap-2.5 items-start';
        loadingBubble.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-blue-600 to-emerald-500 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">AI</div>
            <div class="p-3 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800 text-slate-500 text-xs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-bounce"></span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-bounce [animation-delay:0.2s]"></span>
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-bounce [animation-delay:0.4s]"></span>
            </div>
        `;
        container.appendChild(loadingBubble);
        container.scrollTop = container.scrollHeight;

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/resident/chatbot/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            const data = await res.json();
            const loader = document.getElementById(loadingId);
            if (loader) loader.remove();

            const replyText = data.reply || "Pasayloa, duna'y temporary issue sa AI engine.";
            const assistantBubble = document.createElement('div');
            assistantBubble.className = 'flex gap-2.5 items-start';
            assistantBubble.innerHTML = `
                <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-blue-600 to-emerald-500 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">AI</div>
                <div class="p-3.5 rounded-2xl rounded-tl-xs bg-slate-100 dark:bg-slate-800/90 text-slate-800 dark:text-slate-100 border border-slate-200/80 dark:border-slate-700/80 max-w-[85%] shadow-xs leading-relaxed whitespace-pre-line">
                    ${formatAiResponse(replyText)}
                </div>
            `;
            container.appendChild(assistantBubble);
            container.scrollTop = container.scrollHeight;
        } catch (err) {
            const loader = document.getElementById(loadingId);
            if (loader) loader.remove();
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatAiResponse(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
    }
</script>
