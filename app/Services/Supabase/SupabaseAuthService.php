<?php

namespace App\Services\Supabase;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseAuthService
{
    protected string $url;
    protected string $anonKey;
    protected string $serviceRoleKey;

    public function __construct()
    {
        $this->url = rtrim((string) config('supabase.url'), '/');
        $this->anonKey = (string) config('supabase.anon_key');
        $this->serviceRoleKey = (string) config('supabase.service_role_key');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->url)
            && ! empty($this->anonKey)
            && ! str_contains($this->url, 'your-project.supabase.co');
    }

    /**
     * Register a new resident via Supabase Auth without requiring email verification.
     */
    public function signUp(string $email, string $password, array $metadata = []): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => true,
                'is_mock' => true,
                'id' => null,
                'email' => $email,
            ];
        }

        try {
            // If service role key is available, use admin API to create user with email_confirm = true
            // directly satisfying the requirement: "DO NOT REQUIRE EMAIL VERIFICATION DURING REGISTRATION"
            if (! empty($this->serviceRoleKey)) {
                $response = Http::withHeaders([
                    'apikey' => $this->serviceRoleKey,
                    'Authorization' => "Bearer {$this->serviceRoleKey}",
                    'Content-Type' => 'application/json',
                ])->post("{$this->url}/auth/v1/admin/users", [
                    'email' => $email,
                    'password' => $password,
                    'email_confirm' => true,
                    'user_metadata' => $metadata,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'id' => $data['id'] ?? null,
                        'email' => $data['email'] ?? $email,
                    ];
                }
            }

            // Fallback to standard signup endpoint
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->url}/auth/v1/signup", [
                'email' => $email,
                'password' => $password,
                'data' => $metadata,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'id' => $data['user']['id'] ?? ($data['id'] ?? null),
                    'email' => $email,
                ];
            }

            $errorMessage = $response->json('msg') ?? $response->json('message') ?? 'Supabase registration failed.';
            Log::warning('Supabase signUp error: ' . $errorMessage);
            return [
                'success' => false,
                'error' => $errorMessage,
            ];
        } catch (Exception $e) {
            Log::error('Supabase signUp exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Authenticate via Supabase Auth.
     */
    public function signIn(string $email, string $password): array
    {
        if (! $this->isConfigured()) {
            // Local authentication fallback
            $user = User::where('email', $email)->first();
            if ($user && $user->password && Hash::check($password, $user->password)) {
                return [
                    'success' => true,
                    'is_local' => true,
                    'user_id' => $user->id,
                    'access_token' => 'local_token_' . md5($user->id . time()),
                ];
            }

            return [
                'success' => false,
                'error' => 'Invalid email or password.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->url}/auth/v1/token?grant_type=password", [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'access_token' => $data['access_token'] ?? null,
                    'refresh_token' => $data['refresh_token'] ?? null,
                    'user_id' => $data['user']['id'] ?? null,
                    'user' => $data['user'] ?? [],
                ];
            }

            // Fallback: If user exists in database with matching password, auto-sync to Supabase Auth
            $localUser = User::where('email', $email)->first();
            if ($localUser && $localUser->password && Hash::check($password, $localUser->password)) {
                try {
                    $this->signUp($email, $password, ['name' => $localUser->name]);
                } catch (\Throwable) {
                    // Ignore background sync errors
                }

                return [
                    'success' => true,
                    'is_local' => true,
                    'user_id' => $localUser->supabase_uid ?: $localUser->id,
                    'access_token' => 'sync_token_' . md5($localUser->id . time()),
                ];
            }

            $msg = $response->json('error_description') ?? $response->json('msg') ?? 'Invalid credentials.';
            return [
                'success' => false,
                'error' => $msg,
            ];
        } catch (Exception $e) {
            Log::error('Supabase signIn exception: ' . $e->getMessage());

            $localUser = User::where('email', $email)->first();
            if ($localUser && $localUser->password && Hash::check($password, $localUser->password)) {
                return [
                    'success' => true,
                    'is_local' => true,
                    'user_id' => $localUser->supabase_uid ?: $localUser->id,
                    'access_token' => 'sync_token_' . md5($localUser->id . time()),
                ];
            }

            return [
                'success' => false,
                'error' => 'Authentication service unavailable.',
            ];
        }
    }

    /**
     * Send password recovery email via Supabase.
     */
    public function sendPasswordReset(string $email, string $redirectTo): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => true,
                'message' => 'Password reset instructions would be dispatched.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->url}/auth/v1/recover", [
                'email' => $email,
                'options' => [
                    'email_redirect_to' => $redirectTo,
                ],
            ]);

            return [
                'success' => $response->successful(),
                'error' => $response->successful() ? null : ($response->json('msg') ?? 'Password recovery request failed.'),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a Supabase JWT Token server-side.
     */
    public function verifyToken(string $jwtToken): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => "Bearer {$jwtToken}",
            ])->get("{$this->url}/auth/v1/user");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Supabase verifyToken failed: ' . $e->getMessage());
        }

        return null;
    }
}
