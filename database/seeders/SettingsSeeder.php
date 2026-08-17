<?php

namespace Database\Seeders;

use App\Support\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed default application settings (PWA).
     */
    public function run(): void
    {
        foreach (Settings::DEFAULTS as $key => $value) {
            Settings::set($key, $value);
        }
    }
}