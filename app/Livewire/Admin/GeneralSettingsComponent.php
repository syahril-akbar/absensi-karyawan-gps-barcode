<?php

namespace App\Livewire\Admin;

use App\Support\Settings;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class GeneralSettingsComponent extends Component
{
    use InteractsWithBanner;

    public $registration_enabled = true;
    public $registration_message = '';

    public function mount()
    {
        $this->registration_enabled = Settings::getBool('registration.enabled');
        $this->registration_message = Settings::get('registration.message');
    }

    public function save()
    {
        $this->validate([
            'registration_message' => ['nullable', 'string', 'max:500'],
        ]);

        Settings::setMany([
            'registration.enabled' => $this->registration_enabled ? '1' : '0',
            'registration.message' => $this->registration_message,
        ]);

        $this->banner(__('Updated successfully.'));
    }

    public function render()
    {
        return view('livewire.admin.general-settings');
    }
}