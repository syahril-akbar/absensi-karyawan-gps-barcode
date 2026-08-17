<?php

namespace App\Livewire\Admin;

use App\Support\Settings;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class PwaSettingsComponent extends Component
{
    use InteractsWithBanner;

    public $enabled = true;
    public $name = '';
    public $short_name = '';
    public $description = '';
    public $theme_color = '';
    public $background_color = '';
    public $display = '';
    public $orientation = '';

    public function mount()
    {
        $this->enabled = Settings::getBool('pwa.enabled');
        $this->name = Settings::get('pwa.name');
        $this->short_name = Settings::get('pwa.short_name');
        $this->description = Settings::get('pwa.description');
        $this->theme_color = Settings::get('pwa.theme_color');
        $this->background_color = Settings::get('pwa.background_color');
        $this->display = Settings::get('pwa.display');
        $this->orientation = Settings::get('pwa.orientation');
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:500'],
            'theme_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'display' => ['required', 'in:standalone,fullscreen,minimal-ui,browser'],
            'orientation' => ['required', 'in:any,portrait,landscape,portrait-primary,landscape-primary'],
        ]);

        Settings::setMany([
            'pwa.enabled' => $this->enabled ? '1' : '0',
            'pwa.name' => $this->name,
            'pwa.short_name' => $this->short_name,
            'pwa.description' => $this->description,
            'pwa.theme_color' => $this->theme_color,
            'pwa.background_color' => $this->background_color,
            'pwa.display' => $this->display,
            'pwa.orientation' => $this->orientation,
        ]);

        $this->banner(__('Updated successfully.'));
    }

    public function render()
    {
        return view('livewire.admin.pwa-settings', [
            'manifestUrl' => route('pwa.manifest'),
        ]);
    }
}