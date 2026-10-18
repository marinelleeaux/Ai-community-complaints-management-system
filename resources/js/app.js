import './bootstrap';
import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';

// Expose Chart and Lucide globally
window.Chart = Chart;
window.lucide = { createIcons, icons };

// Initialize Lucide icons on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// ==============================================================================
// THEME MANAGER (Light, Dark, System Default)
// ==============================================================================
window.applyTheme = function (theme) {
    const root = document.documentElement;
    const isDark =
        theme === 'dark' ||
        (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (isDark) {
        root.classList.add('dark');
    } else {
        root.classList.remove('dark');
    }
    localStorage.setItem('npc_theme', theme);

    // Update active state in theme toggles
    document.querySelectorAll('[data-theme-btn]').forEach((btn) => {
        const btnTheme = btn.getAttribute('data-theme-btn');
        if (btnTheme === theme) {
            btn.classList.add('bg-blue-100', 'text-blue-700', 'dark:bg-blue-900/50', 'dark:text-blue-300');
            btn.classList.remove('text-gray-600', 'dark:text-gray-400');
        } else {
            btn.classList.remove('bg-blue-100', 'text-blue-700', 'dark:bg-blue-900/50', 'dark:text-blue-300');
            btn.classList.add('text-gray-600', 'dark:text-gray-400');
        }
    });

    // Notify backend if user is authenticated
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        fetch('/resident/settings/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ theme }),
        }).catch(() => {});
    }
};

window.setTheme = function (theme) {
    window.applyTheme(theme);
};

// System color scheme change listener
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    const saved = localStorage.getItem('npc_theme') || 'system';
    if (saved === 'system') {
        if (e.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});

// ==============================================================================
// TOAST NOTIFICATIONS
// ==============================================================================
window.showToast = function (message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    const colors = {
        success: 'bg-emerald-600 text-white',
        error: 'bg-rose-600 text-white',
        warning: 'bg-amber-600 text-white',
        info: 'bg-blue-600 text-white',
    }[type] || 'bg-slate-800 text-white';

    toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl text-sm font-medium transition-all duration-300 transform translate-y-2 opacity-0 ${colors}`;
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-white/80 hover:text-white">&times;</button>
    `;

    container.appendChild(toast);
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
};

// ==============================================================================
// LIVE NOTIFICATION POLLING (Optimized & Non-blocking)
// ==============================================================================
function pollNotifications() {
    // Skip polling if page is hidden/minimized to save server threads
    if (document.hidden) return;

    const badge = document.getElementById('notification-badge');
    if (!badge) return;

    // Route dynamically based on portal context
    const isAdmin = window.location.pathname.startsWith('/admin');
    const pollUrl = isAdmin ? '/admin/notifications/poll' : '/resident/notifications/poll';

    fetch(pollUrl, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then((res) => (res.ok ? res.json() : null))
        .then((data) => {
            if (data && typeof data.unread_count === 'number') {
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        })
        .catch(() => {});
}

// Start polling every 60 seconds if authenticated (non-intrusive)
if (document.querySelector('meta[name="user-authenticated"]')) {
    setInterval(pollNotifications, 60000);
}

// ==============================================================================
// INSTANT CLICK FEEDBACK & PROGRESS ACCELERATOR (Zero Server Contention)
// ==============================================================================
(function () {
    let progressBar = null;
    let progressTimer = null;

    function getProgressBar() {
        if (!progressBar) {
            progressBar = document.createElement('div');
            progressBar.id = 'system-top-loader';
            progressBar.className = 'fixed top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600 z-50 pointer-events-none transition-all duration-200 opacity-0';
            progressBar.style.width = '0%';
            document.body.appendChild(progressBar);
        }
        return progressBar;
    }

    function startTopLoader() {
        const bar = getProgressBar();
        clearInterval(progressTimer);
        bar.style.transition = 'width 0.2s ease, opacity 0.15s ease';
        bar.style.opacity = '1';
        bar.style.width = '30%';

        let width = 30;
        progressTimer = setInterval(() => {
            if (width < 88) {
                width += (88 - width) * 0.18;
                bar.style.width = width + '%';
            }
        }, 120);
    }

    function finishTopLoader() {
        if (!progressBar) return;
        clearInterval(progressTimer);
        progressBar.style.transition = 'width 0.15s ease, opacity 0.2s ease';
        progressBar.style.width = '100%';
        setTimeout(() => {
            progressBar.style.opacity = '0';
            setTimeout(() => {
                progressBar.style.width = '0%';
            }, 250);
        }, 120);
    }

    // Immediate click feedback & loader initiation on links and submits
    document.addEventListener('click', (e) => {
        // 1. Button active tactile response
        const btn = e.target.closest('button, a.btn, a[href]');
        if (btn && !btn.disabled) {
            btn.classList.add('scale-98');
            setTimeout(() => {
                if (btn) btn.classList.remove('scale-98');
            }, 200);
        }

        // 2. Navigation Top Loader for internal anchor clicks
        const a = e.target.closest('a[href]');
        if (a && a.href && !a.target && !e.ctrlKey && !e.metaKey && !a.href.startsWith('javascript:') && !a.href.includes('#')) {
            try {
                const parsed = new URL(a.href, window.location.origin);
                if (parsed.origin === window.location.origin && !a.hasAttribute('download')) {
                    startTopLoader();
                }
            } catch (_) {}
        }

        // 3. Form submission loader
        const submitBtn = e.target.closest('button[type="submit"]');
        if (submitBtn && submitBtn.form) {
            startTopLoader();
        }
    }, { passive: true });

    window.addEventListener('pageshow', finishTopLoader);
})();


