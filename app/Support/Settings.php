<?php

namespace App\Support;

use App\Models\Setting;

class Settings
{
    public const DEFAULTS = [
        'pwa.enabled' => '1',
        'pwa.name' => 'Absensi Karyawan',
        'pwa.short_name' => 'Absensi',
        'pwa.description' => 'Aplikasi absensi karyawan berbasis GPS & Barcode',
        'pwa.theme_color' => '#4f46e5',
        'pwa.background_color' => '#eef2ff',
        'pwa.display' => 'standalone',
        'pwa.orientation' => 'portrait-primary',
        'registration.enabled' => '1',
        'registration.message' => 'Pendaftaran akun sedang ditutup sementara. Silakan hubungi admin untuk informasi lebih lanjut.',
    ];

    private static ?array $cache = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();

        return self::$cache[$key] ?? $default ?? self::DEFAULTS[$key] ?? null;
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        return filter_var(self::get($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    public static function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        self::$cache[$key] = (string) $value;
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::set($key, $value);
        }
    }

    public static function all(): array
    {
        self::load();

        return self::$cache;
    }

    private static function load(): void
    {
        if (self::$cache !== null) {
            return;
        }

        self::$cache = [];
        foreach (Setting::all(['key', 'value']) as $setting) {
            self::$cache[$setting->key] = $setting->value;
        }
    }
}