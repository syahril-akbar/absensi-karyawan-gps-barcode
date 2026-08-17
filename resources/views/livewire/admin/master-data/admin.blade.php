<div>
  {{-- Header + Aksi --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $users->total() }} akun admin terdaftar</p>
    @if (Auth::user()->isSuperadmin)
      <x-button wire:click="showCreating" class="w-full justify-center sm:w-auto">
        <x-heroicon-o-plus class="mr-2 h-4 w-4" />
        Tambah Admin
      </x-button>
    @endif
  </div>

  {{-- Tabel --}}
  <div class="mt-4 overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-gray-800">
    <div class="overflow-x-auto">
      <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-indigo-50 dark:bg-gray-900">
          <tr>
            <th scope="col"
              class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              No.
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              {{ __('Name') }}
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              {{ __('Email') }}
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              {{ __('Group') }}
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              {{ __('Phone Number') }}
            </th>
            <th scope="col" class="relative px-4 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
          @php
            $class = 'cursor-pointer group-hover:bg-gray-50 dark:group-hover:bg-gray-700';
          @endphp
          @foreach ($users as $user)
            @php
              $wireClick = "wire:click=show('$user->id')";
              $initial = strtoupper(mb_substr($user->name, 0, 1));
            @endphp
            <tr wire:key="{{ $user->id }}" class="group">
              <td class="{{ $class }} p-3 text-center text-sm font-medium text-gray-900 dark:text-white"
                {{ $wireClick }}>
                {{ $loop->iteration }}
              </td>
              <td class="{{ $class }} px-4 py-4 text-sm font-medium text-gray-900 dark:text-white"
                {{ $wireClick }}>
                <div class="flex items-center gap-3">
                  @if ($user->profile_photo_path)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                      class="h-9 w-9 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-gray-700">
                  @else
                    <div
                      class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600 ring-2 ring-indigo-100 dark:bg-indigo-900 dark:text-indigo-300 dark:ring-gray-700">
                      {{ $initial }}
                    </div>
                  @endif
                  <span class="truncate">{{ $user->name }}</span>
                </div>
              </td>
              <td class="{{ $class }} px-4 py-4 text-sm font-medium text-gray-900 dark:text-white"
                {{ $wireClick }}>
                {{ $user->email }}
              </td>
              <td class="{{ $class }} px-4 py-4 text-sm font-medium text-gray-900 dark:text-white"
                {{ $wireClick }}>
                @if ($user->isSuperadmin)
                  <span
                    class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900/60 dark:text-purple-200">Superadmin</span>
                @else
                  <span
                    class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-200">Admin</span>
                @endif
              </td>
              <td class="{{ $class }} px-4 py-4 text-sm font-medium text-gray-900 dark:text-white"
                {{ $wireClick }}>
                {{ $user->phone }}
              </td>
              <td class="px-4 py-4">
                <div class="flex justify-center gap-2">
                  @if (Auth::user()->isSuperadmin || Auth::user()->id == $user->id)
                    <x-button wire:click="edit('{{ $user->id }}')">
                      <x-heroicon-o-pencil-square class="mr-1.5 h-4 w-4" />
                      Edit
                    </x-button>
                    @if (Auth::user()->isSuperadmin && $user->isUser)
                      <x-danger-button wire:click="confirmDeletion('{{ $user->id }}', '{{ $user->name }}')">
                        <x-heroicon-o-trash class="h-4 w-4" />
                      </x-danger-button>
                    @endif
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if ($users->isEmpty())
      <div class="px-6 py-12 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
          <x-heroicon-o-shield-check class="h-7 w-7 text-gray-400 dark:text-gray-500" />
        </div>
        <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Tidak ada data</p>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Belum ada akun admin terdaftar.</p>
      </div>
    @endif
    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
      {{ $users->links() }}
    </div>
  </div>

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Admin
    </x-slot>

    <x-slot name="content">
      Apakah Anda yakin ingin menghapus <b>{{ $deleteName }}</b>?
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">
        {{ __('Cancel') }}
      </x-secondary-button>

      <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
        {{ __('Confirm') }}
      </x-danger-button>
    </x-slot>
  </x-confirmation-modal>

  {{-- Tambah --}}
  <x-dialog-modal wire:model="creating" maxWidth="2xl">
    <x-slot name="title">
      <span class="flex items-center gap-2">
        <x-heroicon-o-user-plus class="h-5 w-5 text-indigo-600" />
        Admin Baru
      </span>
    </x-slot>

    <form wire:submit="create">
      <x-slot name="content">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
          <div x-data="{ photoName: null, photoPreview: null }" class="flex flex-col items-center sm:flex-row sm:gap-6">
            <input type="file" id="photo" class="hidden" wire:model.live="form.photo" x-ref="photo"
              x-on:change="
                photoName = $refs.photo.files[0].name;
                const reader = new FileReader();
                reader.onload = (e) => {
                  photoPreview = e.target.result;
                };
                reader.readAsDataURL($refs.photo.files[0]);
              " />

            <div
              class="mt-2 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-indigo-100 ring-2 ring-indigo-200 dark:bg-indigo-900 dark:ring-indigo-800"
              x-show="!photoPreview">
              <x-heroicon-o-user class="h-10 w-10 text-indigo-400 dark:text-indigo-300" />
            </div>

            <div class="mt-2" x-show="photoPreview" style="display: none;">
              <span class="block h-24 w-24 rounded-full bg-cover bg-center bg-no-repeat ring-2 ring-indigo-200 dark:ring-indigo-800"
                x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
            </div>

            <div class="mt-3 flex flex-col items-center gap-2 sm:items-start">
              <x-secondary-button class="me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('Select A New Photo') }}
              </x-secondary-button>
              @if ($form->user?->profile_photo_path)
                <x-secondary-button type="button" wire:click="deleteProfilePhoto">
                  {{ __('Remove Photo') }}
                </x-secondary-button>
              @endif
              @error('form.photo')
                <x-input-error for="form.photo" message="{{ $message }}" class="mt-2" />
              @enderror
            </div>
          </div>
        @endif

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <x-label for="name">Nama Admin</x-label>
            <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.name"
              placeholder="Nama lengkap admin" />
            @error('form.name')
              <x-input-error for="form.name" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="email">{{ __('Email') }}</x-label>
            <x-input id="email" class="mt-1 block w-full rounded-xl" type="email" wire:model="form.email"
              placeholder="example@example.com" required />
            @error('form.email')
              <x-input-error for="form.email" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="nip">NIP</x-label>
            <x-input id="nip" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.nip"
              placeholder="12345678" required />
            @error('form.nip')
              <x-input-error for="form.nip" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="password">{{ __('Password') }}</x-label>
            <x-input id="password" class="mt-1 block w-full rounded-xl" type="password" wire:model="form.password"
              placeholder="New Password" required />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Default password: <b>admin</b></p>
            @error('form.password')
              <x-input-error for="form.password" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="form.group" value="{{ __('Group') }}" />
            <x-select id="form.group" class="mt-1 block w-full rounded-xl" wire:model="form.group" required>
              @foreach ($groups as $group)
                @if ($group != 'user')
                  <option value="{{ $group }}" {{ $group == $form->group ? 'selected' : '' }}>
                    {{ $group }}
                  </option>
                @endif
              @endforeach
            </x-select>
            @error('form.group')
              <x-input-error for="form.group" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="phone">{{ __('Phone') }}</x-label>
            <x-input id="phone" class="mt-1 block w-full rounded-xl" type="number" wire:model="form.phone"
              placeholder="+628123456789" />
            @error('form.phone')
              <x-input-error for="form.phone" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="city">{{ __('City') }}</x-label>
            <x-input id="city" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.city"
              placeholder="Domisili" />
            @error('form.city')
              <x-input-error for="form.city" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="address">{{ __('Address') }}</x-label>
            <x-input id="address" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.address"
              placeholder="Jl. Jend. Sudirman" />
            @error('form.address')
              <x-input-error for="form.address" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="form.division_id" value="{{ __('Division') }}" />
            <x-select id="form.division_id" class="mt-1 block w-full rounded-xl" wire:model="form.division_id">
              <option value="">{{ __('Select Division') }}</option>
              @foreach (App\Models\Division::all() as $division)
                <option value="{{ $division->id }}" {{ $division->id == $form->division_id ? 'selected' : '' }}>
                  {{ $division->name }}
                </option>
              @endforeach
            </x-select>
            @error('form.division_id')
              <x-input-error for="form.division_id" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="form.job_title_id" value="{{ __('Job Title') }}" />
            <x-select id="form.job_title_id" class="mt-1 block w-full rounded-xl" wire:model="form.job_title_id">
              <option value="">{{ __('Select Job Title') }}</option>
              @foreach (App\Models\JobTitle::all() as $jobTitle)
                <option value="{{ $jobTitle->id }}" {{ $jobTitle->id == $form->job_title_id ? 'selected' : '' }}>
                  {{ $jobTitle->name }}
                </option>
              @endforeach
            </x-select>
            @error('form.job_title_id')
              <x-input-error for="form.job_title_id" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('creating')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ml-2" wire:click="create" wire:loading.attr="disabled" wire:target="form.photo">
          {{ __('Confirm') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>

  {{-- Edit --}}
  <x-dialog-modal wire:model="editing" maxWidth="2xl">
    <x-slot name="title">
      <span class="flex items-center gap-2">
        <x-heroicon-o-pencil-square class="h-5 w-5 text-indigo-600" />
        Edit Admin
      </span>
    </x-slot>

    <form wire:submit.prevent="update" id="user-edit">
      <x-slot name="content">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
          <div x-data="{ photoName: null, photoPreview: null }" class="flex flex-col items-center sm:flex-row sm:gap-6">
            <input type="file" id="photo" class="hidden" wire:model.live="form.photo" x-ref="photo"
              x-on:change="
                photoName = $refs.photo.files[0].name;
                const reader = new FileReader();
                reader.onload = (e) => {
                  photoPreview = e.target.result;
                };
                reader.readAsDataURL($refs.photo.files[0]);
              " />

            <div class="mt-2" x-show="!photoPreview">
              <img src="{{ $form->user?->profile_photo_url }}" alt="{{ $form->user?->name }}"
                class="h-24 w-24 rounded-full object-cover ring-2 ring-indigo-200 dark:ring-indigo-800">
            </div>

            <div class="mt-2" x-show="photoPreview" style="display: none;">
              <span class="block h-24 w-24 rounded-full bg-cover bg-center bg-no-repeat ring-2 ring-indigo-200 dark:ring-indigo-800"
                x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
            </div>

            <div class="mt-3 flex flex-col items-center gap-2 sm:items-start">
              <x-secondary-button class="me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('Select A New Photo') }}
              </x-secondary-button>
              @if ($form->user?->profile_photo_path)
                <x-secondary-button type="button" wire:click="deleteProfilePhoto">
                  {{ __('Remove Photo') }}
                </x-secondary-button>
              @endif
              @error('form.photo')
                <x-input-error for="form.photo" message="{{ $message }}" class="mt-2" />
              @enderror
            </div>
          </div>
        @endif

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <x-label for="name">Nama Admin</x-label>
            <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.name"
              placeholder="Nama lengkap admin" />
            @error('form.name')
              <x-input-error for="form.name" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="email">{{ __('Email') }}</x-label>
            <x-input id="email" class="mt-1 block w-full rounded-xl" type="email" wire:model="form.email"
              placeholder="example@example.com" required />
            @error('form.email')
              <x-input-error for="form.email" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="nip">NIP</x-label>
            <x-input id="nip" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.nip"
              placeholder="12345678" required />
            @error('form.nip')
              <x-input-error for="form.nip" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="password">{{ __('Password') }}</x-label>
            <x-input id="password" class="mt-1 block w-full rounded-xl" type="password" wire:model="form.password"
              placeholder="New Password" />
            @error('form.password')
              <x-input-error for="form.password" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="phone">{{ __('Phone') }}</x-label>
            <x-input id="phone" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.phone"
              placeholder="+628123456789" />
            @error('form.phone')
              <x-input-error for="form.phone" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="city">{{ __('City') }}</x-label>
            <x-input id="city" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.city"
              placeholder="Domisili" />
            @error('form.city')
              <x-input-error for="form.city" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="address">{{ __('Address') }}</x-label>
            <x-input id="address" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.address"
              placeholder="Jl. Jend. Sudirman" />
            @error('form.address')
              <x-input-error for="form.address" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="form.division_id" value="{{ __('Division') }}" />
            <x-select id="form.division_id" class="mt-1 block w-full rounded-xl" wire:model="form.division_id">
              <option value="">{{ __('Select Division') }}</option>
              @foreach (App\Models\Division::all() as $division)
                <option value="{{ $division->id }}" {{ $division->id == $form->division_id ? 'selected' : '' }}>
                  {{ $division->name }}
                </option>
              @endforeach
            </x-select>
            @error('form.division_id')
              <x-input-error for="form.division_id" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>

          <div>
            <x-label for="form.job_title_id" value="{{ __('Job Title') }}" />
            <x-select id="form.job_title_id" class="mt-1 block w-full rounded-xl" wire:model="form.job_title_id">
              <option value="">{{ __('Select Job Title') }}</option>
              @foreach (App\Models\JobTitle::all() as $jobTitle)
                <option value="{{ $jobTitle->id }}" {{ $jobTitle->id == $form->job_title_id ? 'selected' : '' }}>
                  {{ $jobTitle->name }}
                </option>
              @endforeach
            </x-select>
            @error('form.job_title_id')
              <x-input-error for="form.job_title_id" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('editing')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ml-2" wire:click="update" wire:loading.attr="disabled" wire:target="form.photo">
          {{ __('Confirm') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>

  {{-- Detail --}}
  <x-modal wire:model="showDetail">
    @if ($form->user)
      @php
        $division = $form->user->division ? json_decode($form->user->division)->name : '-';
        $jobTitle = $form->user->jobTitle ? json_decode($form->user->jobTitle)->name : '-';
      @endphp
      <div class="px-6 py-4">
        <div class="my-4 flex items-center justify-center">
          @if ($form->user->profile_photo_path)
            <img class="h-24 w-24 rounded-full object-cover ring-4 ring-indigo-100 dark:ring-gray-700"
              src="{{ $form->user->profile_photo_url }}" alt="{{ $form->user->name }}"
              title="{{ $form->user->name }}" />
          @else
            <div
              class="flex h-24 w-24 items-center justify-center rounded-full bg-indigo-100 text-4xl font-bold text-indigo-600 ring-4 ring-indigo-100 dark:bg-indigo-900 dark:text-indigo-300 dark:ring-gray-700">
              {{ strtoupper(mb_substr($form->user->name, 0, 1)) }}
            </div>
          @endif
        </div>

        <div class="text-center text-lg font-bold text-gray-900 dark:text-white">
          {{ $form->user->name }}
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="nip" value="NIP" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $form->user->nip }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="group" value="{{ __('Group') }}" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ __($form->user->group) }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="email" value="{{ __('Email') }}" />
            <p class="mt-1 break-all text-sm font-medium text-gray-900 dark:text-white">{{ $form->user->email }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="phone" value="{{ __('Phone') }}" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $form->user->phone }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="birth_date" value="{{ __('Birth Date') }}" />
            @if ($form->user->birth_date)
              <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                {{ \Illuminate\Support\Carbon::parse($form->user->birth_date)->format('d M Y') }}
              </p>
            @else
              <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">-</p>
            @endif
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="birth_place" value="{{ __('Birth Place') }}" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $form->user->birth_place ?? '-' }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="address" value="{{ __('Address') }}" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
              {{ empty($form->user->address) ? '-' : $form->user->address }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="city" value="{{ __('City') }}" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
              {{ empty($form->user->city) ? '-' : $form->user->city }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="job_title_id" value="{{ __('Job Title') }}" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $jobTitle }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-3 dark:bg-gray-700">
            <x-label for="division_id" value="{{ __('Division') }}" />
            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $division }}</p>
          </div>
        </div>
      </div>
    @endif
  </x-modal>
</div>