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
    }, 4000);
};

// ==============================================================================
// LIVE NOTIFICATION POLLING
// ==============================================================================
function pollNotifications() {
    const badge = document.getElementById('notification-badge');
    if (!badge) return;

    fetch('/resident/notifications/poll')
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

// Start polling every 30 seconds if authenticated
if (document.querySelector('meta[name="user-authenticated"]')) {
    setInterval(pollNotifications, 30000);
}
