<?php

namespace App\Http\Controllers;

use App\Support\Settings;
use Illuminate\Http\JsonResponse;

class PwaController extends Controller
{
    public function manifest(): JsonResponse
    {
        $manifest = [
            'name' => Settings::get('pwa.name'),
            'short_name' => Settings::get('pwa.short_name'),
            'description' => Settings::get('pwa.description'),
            'lang' => 'id',
            'start_url' => '/',
            'scope' => '/',
            'display' => Settings::get('pwa.display', 'standalone'),
            'orientation' => Settings::get('pwa.orientation', 'portrait-primary'),
            'background_color' => Settings::get('pwa.background_color'),
            'theme_color' => Settings::get('pwa.theme_color'),
            'categories' => ['business', 'productivity'],
            'icons' => [
                ['src' => '/icons/icon-192x192.png', 'sizes' => '192x192', 'type' => 'image/png'],
                ['src' => '/icons/icon-512x512.png', 'sizes' => '512x512', 'type' => 'image/png'],
                ['src' => '/icons/icon-maskable-512x512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
