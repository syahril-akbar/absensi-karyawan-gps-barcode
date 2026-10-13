<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class CompleteProfileModalComponent extends Component
{
    use InteractsWithBanner;

    public bool $isOpen = false;

    public string $nip = '';

    public string $phone = '';

    public string $gender = '';

    public string $birth_date = '';

    public string $birth_place = '';

    public string $address = '';

    public string $city = '';

    public ?string $division_id = null;

    public ?string $job_title_id = null;

    public ?string $education_id = null;

    public function mount(): void
    {
        $user = Auth::user();

        if ($user && $user->is_user && ! $user->isProfileComplete()) {
            $this->isOpen = true;
            $this->nip = $user->nip ?? '';
            $this->phone = $user->phone ?? '';
            $this->gender = $user->gender ?? 'male';
            $this->birth_date = $user->birth_date ? $user->birth_date->format('Y-m-d') : '';
            $this->birth_place = $user->birth_place ?? '';
            $this->address = $user->address ?? '';
            $this->city = $user->city ?? '';
            $this->division_id = $user->division_id ? (string) $user->division_id : null;
            $this->job_title_id = $user->job_title_id ? (string) $user->job_title_id : null;
            $this->education_id = $user->education_id ? (string) $user->education_id : null;
        } else {
            $this->isOpen = false;
        }
    }

    public function rules(): array
    {
        return [
            'nip' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:25'],
            'gender' => ['required', 'in:male,female'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'division_id' => ['required', 'exists:divisions,id'],
            'job_title_id' => ['required', 'exists:job_titles,id'],
            'education_id' => ['required', 'exists:educations,id'],
        ];
    }

    protected $messages = [
        'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
        'gender.required' => 'Jenis kelamin wajib dipilih.',
        'birth_place.required' => 'Tempat lahir wajib diisi.',
        'birth_date.required' => 'Tanggal lahir wajib diisi.',
        'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
        'address.required' => 'Alamat domisili wajib diisi.',
        'city.required' => 'Kota domisili wajib diisi.',
        'division_id.required' => 'Divisi penempatan wajib dipilih.',
        'job_title_id.required' => 'Jabatan wajib dipilih.',
        'education_id.required' => 'Pendidikan terakhir wajib dipilih.',
    ];

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();

        $user->update([
            'nip' => $this->nip ?: null,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'city' => $this->city,
            'division_id' => $this->division_id,
            'job_title_id' => $this->job_title_id,
            'education_id' => $this->education_id,
        ]);

        $this->isOpen = false;
        $this->banner('Profil berhasil dilengkapi! Anda sekarang dapat melakukan absensi.');
        $this->dispatch('profile-completed');
    }

    public function render()
    {
        return view('livewire.complete-profile-modal', [
            'divisions' => Division::orderBy('name')->get(),
            'jobTitles' => JobTitle::orderBy('name')->get(),
            'educations' => Education::orderBy('id')->get(),
        ]);
    }
}
