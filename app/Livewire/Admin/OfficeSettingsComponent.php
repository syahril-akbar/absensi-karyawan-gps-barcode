<?php

namespace App\Livewire\Admin;

use App\Support\Settings;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithFileUploads;

class OfficeSettingsComponent extends Component
{
    use InteractsWithBanner;
    use WithFileUploads;

    public string $kementerian = '';
    public string $eselon1 = '';
    public string $nama = '';
    public string $alamat = '';
    public string $kota = '';
    public string $penandatangan_jabatan = '';
    public string $penandatangan_nama = '';
    public string $penandatangan_nip = '';

    public $logo = null;
    public ?string $current_logo = null;

    public function mount(): void
    {
        $this->kementerian = Settings::get('office.kementerian', 'KEMENTERIAN PEKERJAAN UMUM');
        $this->eselon1 = Settings::get('office.eselon1', 'BADAN PENGEMBANGAN SUMBER DAYA MANUSIA');
        $this->nama = Settings::get('office.nama', 'BALAI PENGEMBANGAN KOMPETENSI PU WILAYAH VIII MAKASSAR');
        $this->alamat = Settings::get('office.alamat', 'Jl. Nuri No.19, Mariso, Kec. Mariso, Kota Makassar, Sulawesi Selatan 90121');
        $this->kota = Settings::get('office.kota', 'Makassar');
        $this->penandatangan_jabatan = Settings::get('office.penandatangan_jabatan', 'Penanggung Jawab / Pembimbing Magang');
        $this->penandatangan_nama = Settings::get('office.penandatangan_nama', '') ?? '';
        $this->penandatangan_nip = Settings::get('office.penandatangan_nip', '') ?? '';
        $this->current_logo = Settings::get('office.logo');
    }

    public function save(): void
    {
        $this->validate([
            'kementerian' => ['required', 'string', 'max:255'],
            'eselon1' => ['required', 'string', 'max:255'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:500'],
            'kota' => ['required', 'string', 'max:100'],
            'penandatangan_jabatan' => ['nullable', 'string', 'max:255'],
            'penandatangan_nama' => ['nullable', 'string', 'max:255'],
            'penandatangan_nip' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $updates = [
            'office.kementerian' => $this->kementerian,
            'office.eselon1' => $this->eselon1,
            'office.nama' => $this->nama,
            'office.alamat' => $this->alamat,
            'office.kota' => $this->kota,
            'office.penandatangan_jabatan' => $this->penandatangan_jabatan,
            'office.penandatangan_nama' => $this->penandatangan_nama,
            'office.penandatangan_nip' => $this->penandatangan_nip,
        ];

        if ($this->logo) {
            $path = $this->logo->storePublicly('office', 'public');
            $updates['office.logo'] = $path;
            $this->current_logo = $path;
            $this->logo = null;
        }

        Settings::setMany($updates);

        $this->banner('Informasi kantor dan kop laporan berhasil disimpan.');
    }

    public function deleteLogo(): void
    {
        if ($this->current_logo) {
            Storage::disk('public')->delete($this->current_logo);
            Settings::set('office.logo', '');
            $this->current_logo = null;
            $this->banner('Logo khusus dihapus. Kop surat kembali menggunakan logo default.');
        }
    }

    public function render()
    {
        return view('livewire.admin.office-settings');
    }
}
