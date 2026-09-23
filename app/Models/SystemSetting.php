<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting by key with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::remember("system_setting_{$key}", 3600, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Set a setting value and invalidate cache.
     */
    public static function set(string $key, mixed $value): static
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
        );

        Cache::forget("system_setting_{$key}");
        Cache::forget("system_maintenance_data");

        return $setting;
    }

    /**
     * Check if maintenance mode is enabled.
     */
    public static function isMaintenanceMode(): bool
    {
        $val = static::get('maintenance_mode', '0');
        return $val === '1' || $val === 'true' || $val === true;
    }

    /**
     * Get all maintenance-related configuration in one call.
     */
    public static function getMaintenanceData(): array
    {
        try {
            return Cache::remember('system_maintenance_data', 3600, function () {
                $secret = static::where('key', 'maintenance_secret')->value('value');
                if (!$secret) {
                    $secret = Str::random(24);
                    static::set('maintenance_secret', $secret);
                }

                return [
                    'active'     => static::isMaintenanceMode(),
                    'title'      => static::get('maintenance_title', 'SajiHub Sedang Dalam Pemeliharaan'),
                    'message'    => static::get('maintenance_message', 'Kami sedang melakukan peningkatan performa dan pemeliharaan server SajiHub. Sistem akan segera kembali normal.'),
                    'end_time'   => static::get('maintenance_end_time', null),
                    'secret'     => $secret,
                    'updated_at' => static::get('maintenance_updated_at', null),
                    'updated_by' => static::get('maintenance_updated_by', null),
                ];
            });
        } catch (\Throwable $e) {
            return [
                'active'     => false,
                'title'      => 'SajiHub Sedang Dalam Pemeliharaan',
                'message'    => 'Kami sedang melakukan peningkatan sistem.',
                'end_time'   => null,
                'secret'     => 'sajihub_secret',
                'updated_at' => null,
                'updated_by' => null,
            ];
        }
    }
}
