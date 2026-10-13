<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ComplaintCategory;
use App\Models\Purok;
use App\Models\User;
use App\Services\AI\ComplaintAnalysisService;
use App\Services\Supabase\SupabaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct(
        protected ComplaintAnalysisService $aiService,
        protected SupabaseAuthService $authService
    ) {
    }

    public function index()
    {
        $admin = Auth::user();
        $puroks = Purok::withCount('complaints')->orderBy('name')->get();
        $categories = ComplaintCategory::withCount('complaints')->orderBy('name')->get();

        $aiStatus = [
            'is_configured' => $this->aiService->isConfigured(),
            'provider' => config('ai.provider'),
            'model' => config('ai.model'),
            'barangay' => config('ai.barangay.name'),
            'municipality' => config('ai.barangay.municipality'),
            'province' => config('ai.barangay.province'),
        ];

        $supabaseStatus = [
            'is_configured' => $this->authService->isConfigured(),
            'url' => config('supabase.url'),
            'has_anon_key' => ! empty(config('supabase.anon_key')),
            'has_service_key' => ! empty(config('supabase.service_role_key')),
        ];

        return view('admin.settings.index', compact('admin', 'puroks', 'categories', 'aiStatus', 'supabaseStatus'));
    }

    public function addPurok(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:puroks,name'],
        ]);

        $purok = Purok::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        AuditLog::log(Auth::id(), 'created_purok', 'puroks', (string) $purok->id, ['name' => $purok->name]);

        return back()->with('success', "Purok \"{$purok->name}\" added successfully.");
    }

    public function updatePurok(Request $request, int $id)
    {
        $purok = Purok::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:puroks,name,' . $purok->id],
        ]);

        $purok->update(['name' => $validated['name']]);
        AuditLog::log(Auth::id(), 'updated_purok', 'puroks', (string) $purok->id, ['name' => $purok->name]);

        return back()->with('success', "Purok updated to \"{$purok->name}\".");
    }

    public function togglePurok(int $id)
    {
        $purok = Purok::findOrFail($id);
        $purok->update(['is_active' => ! $purok->is_active]);

        AuditLog::log(Auth::id(), 'toggled_purok_status', 'puroks', (string) $purok->id, [
            'is_active' => $purok->is_active,
        ]);

        $statusStr = $purok->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Purok \"{$purok->name}\" {$statusStr}.");
    }

    public function addCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:complaint_categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category = ComplaintCategory::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        AuditLog::log(Auth::id(), 'created_category', 'complaint_categories', (string) $category->id, [
            'name' => $category->name,
        ]);

        return back()->with('success', "Category \"{$category->name}\" added successfully.");
    }

    public function updateCategory(Request $request, int $id)
    {
        $category = ComplaintCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:complaint_categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        AuditLog::log(Auth::id(), 'updated_category', 'complaint_categories', (string) $category->id);

        return back()->with('success', "Category \"{$category->name}\" updated.");
    }

    public function toggleCategory(int $id)
    {
        $category = ComplaintCategory::findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);

        AuditLog::log(Auth::id(), 'toggled_category_status', 'complaint_categories', (string) $category->id, [
            'is_active' => $category->is_active,
        ]);

        $statusStr = $category->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Category \"{$category->name}\" {$statusStr}.");
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $admin->update($validated);
        AuditLog::log($admin->id, 'updated_admin_profile', 'users', $admin->id);

        return back()->with('success', 'Admin profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::user();
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($admin->password && ! Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        AuditLog::log($admin->id, 'updated_admin_password', 'users', $admin->id);

        return back()->with('success', 'Administrator password updated successfully.');
    }
}
