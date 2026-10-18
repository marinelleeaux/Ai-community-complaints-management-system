<!-- Universal Confirmation Modal Box (Replaces native browser localhost popups) -->
<div id="universal-confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-950/65 backdrop-blur-xs transition-opacity" onclick="closeConfirmModal()"></div>

    <!-- Modal Box (Fully Dark Mode Compatible) -->
    <div class="relative w-full max-w-md rounded-3xl bg-white dark:bg-[#111C33] border border-slate-200/90 dark:border-slate-800 p-6 shadow-2xl z-10 animate-fade-in-up">
        <div class="flex items-start gap-4">
            <div id="confirm-modal-icon-wrap" class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 id="confirm-modal-title" class="text-base font-extrabold text-slate-900 dark:text-white leading-tight">
                    Confirm Action
                </h3>
                <p id="confirm-modal-message" class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                    Are you sure you want to proceed?
                </p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
            <button type="button" 
                    onclick="closeConfirmModal()" 
                    class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors">
                Cancel
            </button>
            <button type="button" 
                    id="confirm-modal-action-btn" 
                    class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-600/25 transition-all hover:scale-105 active:scale-95">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    let pendingConfirmCallback = null;

    function confirmAction(e, message, title = 'Confirm Action', btnText = 'Yes, Proceed', isDanger = true) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const form = e ? e.target.closest('form') : null;
        const link = e ? e.target.closest('a') : null;
        
        const titleEl = document.getElementById('confirm-modal-title');
        const msgEl = document.getElementById('confirm-modal-message');
        const actionBtn = document.getElementById('confirm-modal-action-btn');
        const iconWrap = document.getElementById('confirm-modal-icon-wrap');
        
        if (titleEl) titleEl.textContent = title;
        if (msgEl) msgEl.textContent = message;
        if (iconWrap) {
            if (isDanger) {
                iconWrap.className = 'w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 shadow-xs';
            } else {
                iconWrap.className = 'w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 shadow-xs';
            }
        }
        if (actionBtn) {
            actionBtn.textContent = btnText;
            if (isDanger) {
                actionBtn.className = 'px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-600/25 transition-all hover:scale-105 active:scale-95';
            } else {
                actionBtn.className = 'px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/25 transition-all hover:scale-105 active:scale-95';
            }
        }
        
        pendingConfirmCallback = () => {
            if (form) {
                form.submit();
            } else if (link && link.href) {
                window.location.href = link.href;
            }
        };
        
        const modal = document.getElementById('universal-confirm-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        return false;
    }

    function closeConfirmModal() {
        const modal = document.getElementById('universal-confirm-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        pendingConfirmCallback = null;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const actionBtn = document.getElementById('confirm-modal-action-btn');
        if (actionBtn) {
            actionBtn.onclick = function() {
                if (pendingConfirmCallback) {
                    const cb = pendingConfirmCallback;
                    closeConfirmModal();
                    cb();
                }
            };
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
