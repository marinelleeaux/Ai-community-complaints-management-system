<?php

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ResidentController as AdminResidentController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\StatusController;
use App\Http\Controllers\Resident\AnnouncementController as ResidentAnnouncementController;
use App\Http\Controllers\Resident\ChatbotController;
use App\Http\Controllers\Resident\ComplaintController as ResidentComplaintController;
use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use App\Http\Controllers\Resident\NotificationController as ResidentNotificationController;
use App\Http\Controllers\Resident\ProfileController as ResidentProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes: AI Community Complaint Management System
| Barangay North Poblacion, Maramag, Bukidnon
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin() || $user->isOfficial()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isApproved()) {
            return redirect()->route('resident.dashboard');
        }
        return redirect()->route('pending-approval');
    }
    return redirect()->route('login');
})->name('home');

// ==============================================================================
// 1. PUBLIC & AUTHENTICATION ROUTES
// ==============================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token?}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Account Status Notices
Route::get('/pending-approval', [StatusController::class, 'pendingApproval'])->name('pending-approval');
Route::get('/registration-rejected', [StatusController::class, 'registrationRejected'])->name('registration-rejected');
Route::get('/access-denied', [StatusController::class, 'accessDenied'])->name('access-denied');

// ==============================================================================
// 2. RESIDENT PORTAL ROUTES
// ==============================================================================
Route::prefix('resident')->name('resident.')->middleware(['auth', 'resident.approved'])->group(function () {
    Route::get('/dashboard', [ResidentDashboardController::class, 'index'])->name('dashboard');

    // Complaints
    Route::get('/complaints', [ResidentComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [ResidentComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ResidentComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{id}', [ResidentComplaintController::class, 'show'])->name('complaints.show');
    Route::get('/complaint-history', [ResidentComplaintController::class, 'history'])->name('complaint-history');

    // Announcements
    Route::get('/announcements', [ResidentAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{id}', [ResidentAnnouncementController::class, 'show'])->name('announcements.show');

    // AI Barangay Assistant Chatbot
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
    Route::post('/chatbot/clear', [ChatbotController::class, 'clearHistory'])->name('chatbot.clear');

    // Notifications
    Route::get('/notifications', [ResidentNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [ResidentNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [ResidentNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/poll', [ResidentNotificationController::class, 'poll'])->name('notifications.poll');

    // Profile & Settings
    Route::get('/profile', [ResidentProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ResidentProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [ResidentProfileController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [ResidentProfileController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/theme', [ResidentProfileController::class, 'updateTheme'])->name('settings.theme');
});

// ==============================================================================
// 3. ADMIN PORTAL ROUTES
// ==============================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Complaints Management
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{id}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::put('/complaints/{id}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    Route::put('/complaints/{id}/override-ai', [AdminComplaintController::class, 'overrideAi'])->name('complaints.override-ai');
    Route::put('/complaints/{id}/assign', [AdminComplaintController::class, 'assign'])->name('complaints.assign');
    Route::post('/complaints/{id}/note', [AdminComplaintController::class, 'addInternalNote'])->name('complaints.add-note');

    // Resident Management & Approvals
    Route::get('/residents', [AdminResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/pending', [AdminResidentController::class, 'pending'])->name('residents.pending');
    Route::get('/residents/{id}', [AdminResidentController::class, 'show'])->name('residents.show');
    Route::post('/residents/{id}/approve', [AdminResidentController::class, 'approve'])->name('residents.approve');
    Route::post('/residents/{id}/reject', [AdminResidentController::class, 'reject'])->name('residents.reject');
    Route::post('/residents/{id}/toggle-active', [AdminResidentController::class, 'toggleActive'])->name('residents.toggle-active');

    // Announcements Management
    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AdminAnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{id}/edit', [AdminAnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AdminAnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/{id}/toggle-publish', [AdminAnnouncementController::class, 'togglePublish'])->name('announcements.toggle-publish');

    // Reports & Analytics
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [AdminReportController::class, 'exportCsv'])->name('reports.export');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    // System Settings & Master Data
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/puroks', [AdminSettingsController::class, 'addPurok'])->name('settings.add-purok');
    Route::put('/settings/puroks/{id}', [AdminSettingsController::class, 'updatePurok'])->name('settings.update-purok');
    Route::post('/settings/puroks/{id}/toggle', [AdminSettingsController::class, 'togglePurok'])->name('settings.toggle-purok');
    Route::post('/settings/categories', [AdminSettingsController::class, 'addCategory'])->name('settings.add-category');
    Route::put('/settings/categories/{id}', [AdminSettingsController::class, 'updateCategory'])->name('settings.update-category');
    Route::post('/settings/categories/{id}/toggle', [AdminSettingsController::class, 'toggleCategory'])->name('settings.toggle-category');
    Route::put('/settings/profile', [AdminSettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [AdminSettingsController::class, 'updatePassword'])->name('settings.update-password');
});
