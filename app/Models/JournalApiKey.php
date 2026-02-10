<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JournalApiKey extends Model
{
    protected $fillable = [
        'name',
        'key_hash',
        'key_prefix',
        'permissions',
        'allowed_ips',
        'last_used_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'allowed_ips' => 'array',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Generate a new API key. Returns the plain text key (show it once to the user).
     */
    public static function generateKey(): string
    {
        return 'ami_jk_' . Str::random(40);
    }

    /**
     * Create a new API key record. Returns [model, plainTextKey].
     */
    public static function createKey(string $name, array $permissions = [], ?array $allowedIps = null): array
    {
        $plainKey = self::generateKey();

        $model = self::create([
            'name' => $name,
            'key_hash' => hash('sha256', $plainKey),
            'key_prefix' => substr($plainKey, 0, 8),
            'permissions' => $permissions,
            'allowed_ips' => $allowedIps,
        ]);

        return [$model, $plainKey];
    }

    public static function findByKey(string $plainKey): ?self
    {
        $hash = hash('sha256', $plainKey);

        return self::where('key_hash', $hash)
            ->where('is_active', true)
            ->first();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasPermission(string $permission): bool
    {
        if (empty($this->permissions)) {
            return true; // No restrictions = all permissions
        }

        return in_array($permission, $this->permissions);
    }

    public function isIpAllowed(string $ip): bool
    {
        if (empty($this->allowed_ips)) {
            return true; // No restrictions = all IPs
        }

        return in_array($ip, $this->allowed_ips);
    }

    public function recordUsage(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}
