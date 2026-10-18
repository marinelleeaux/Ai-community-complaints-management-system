<?php

namespace App\Services\Supabase;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    protected string $url;
    protected string $serviceRoleKey;
    protected string $anonKey;

    public function __construct()
    {
        $this->url = rtrim((string) config('supabase.url'), '/');
        $this->serviceRoleKey = (string) config('supabase.service_role_key');
        $this->anonKey = (string) config('supabase.anon_key');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->url)
            && ! empty($this->serviceRoleKey)
            && ! str_contains($this->url, 'your-project.supabase.co');
    }

    /**
     * Upload an UploadedFile or file contents to Supabase Storage, with fallback to local storage.
     */
    public function upload(string $bucket, UploadedFile $file, ?string $customFolder = null): array
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $folderPath = $customFolder ? trim($customFolder, '/') . '/' : '';
        $path = $folderPath . $filename;
        $mimeType = $file->getClientMimeType() ?: 'application/octet-stream';

        if (! $this->isConfigured()) {
            // Local storage fallback
            $diskPath = $file->storeAs("public/{$bucket}/{$folderPath}", $filename);
            $publicUrl = asset('storage/' . str_replace('public/', '', $diskPath));

            return [
                'success' => true,
                'path' => "{$bucket}/{$path}",
                'url' => $publicUrl,
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $mimeType,
            ];
        }

        // Always ensure a local copy is saved for instant local display & resilience
        try {
            $diskPath = $file->storeAs("public/{$bucket}/{$folderPath}", $filename);
            $localUrl = asset('storage/' . str_replace('public/', '', $diskPath));
        } catch (\Throwable $e) {
            Log::warning('Local storage copy failed: ' . $e->getMessage());
            $localUrl = null;
        }

        try {
            $fileContent = file_get_contents($file->getRealPath());
            $response = Http::withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => $mimeType,
            ])->withBody($fileContent, $mimeType)
                ->post("{$this->url}/storage/v1/object/{$bucket}/{$path}");

            if ($response->successful()) {
                $publicUrl = "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
                return [
                    'success' => true,
                    'path' => "{$bucket}/{$path}",
                    'url' => $localUrl ?: $publicUrl,
                    'supabase_url' => $publicUrl,
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $mimeType,
                ];
            }

            Log::error('Supabase storage upload error: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Supabase storage upload exception: ' . $e->getMessage());
        }

        return [
            'success' => true,
            'path' => "{$bucket}/{$path}",
            'url' => $localUrl ?: asset("storage/{$bucket}/{$path}"),
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $mimeType,
        ];

        return [
            'success' => true,
            'path' => "{$bucket}/{$path}",
            'url' => $publicUrl,
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $mimeType,
        ];
    }

    /**
     * Generate signed URL for private attachments.
     */
    public function getSignedUrl(string $bucket, string $path, int $expiresInSeconds = 3600): string
    {
        if (! $this->isConfigured()) {
            return asset('storage/' . $path);
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->url}/storage/v1/object/sign/{$bucket}/{$path}", [
                'expiresIn' => $expiresInSeconds,
            ]);

            if ($response->successful()) {
                $signedUrl = $response->json('signedURL');
                return "{$this->url}/storage/v1{$signedUrl}";
            }
        } catch (Exception $e) {
            Log::error('Supabase signed URL generation failed: ' . $e->getMessage());
        }

        return "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
    }
}
