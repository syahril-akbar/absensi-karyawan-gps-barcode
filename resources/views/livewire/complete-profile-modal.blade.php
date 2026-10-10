<div>
  @if ($isOpen)
    <div
      class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4 overflow-hidden"
      style="position: fixed; inset: 0; z-index: 99999; background-color: rgba(15, 23, 42, 0.85); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); display: flex;">

      {{-- Modal Card --}}
      <div
        class="flex flex-col w-full h-[92vh] sm:h-auto sm:max-h-[85vh] sm:max-w-xl bg-white dark:bg-gray-800 rounded-t-3xl sm:rounded-3xl shadow-2xl border-t sm:border border-gray-100 dark:border-gray-700 overflow-hidden"
        style="max-height: 85vh; display: flex; flex-direction: column; animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);">

        {{-- Mobile Drag Handle Pill --}}
        <div class="sm:hidden flex justify-center pt-2.5 pb-1 bg-white dark:bg-gray-800 shrink-0">
          <div class="w-10 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        {{-- Header Modal (Fixed Top) --}}
        <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shrink-0" style="flex-shrink: 0;">
          <div class="flex items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30">
              <x-heroicon-o-user-circle class="h-6 w-6" />
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <h3 class="text-base font-bold text-gray-900 dark:text-white truncate">
                  Lengkapi Data Profil
                </h3>
                <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                  Wajib
                </span>
              </div>
              <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 truncate">
                Lengkapi biodata dan unit kerja sebelum melanjutkan absensi.
              </p>
            </div>
          </div>
        </div>

        {{-- Form & Scrollable Body --}}
        <form wire:submit="save" class="flex flex-col flex-1 overflow-hidden min-h-0" style="flex: 1 1 0%; min-height: 0; display: flex; flex-direction: column;">
          <div
            class="flex-1 overflow-y-auto px-5 py-5 sm:px-6 sm:py-6 space-y-6"
            style="flex: 1 1 0%; min-height: 0; overflow-y: auto; overscroll-behavior: contain; -webkit-overflow-scrolling: touch; scrollbar-width: thin;">

            {{-- Kelompok 1: Biodata & Kontak --}}
            <div class="space-y-4">
              <div class="flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-2 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">
                <x-heroicon-o-identification class="h-4 w-4" />
                <span>1. Biodata & Kontak</span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <x-label for="modal_phone" value="No. WhatsApp / HP *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_phone" type="tel" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="phone"
                    placeholder="Contoh: 08123456789" required />
                  @error('phone') <x-input-error for="phone" class="mt-1" /> @enderror
                </div>

                <div>
                  <x-label for="modal_gender" value="Jenis Kelamin *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-select id="modal_gender" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="gender" required>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                  </x-select>
                  @error('gender') <x-input-error for="gender" class="mt-1" /> @enderror
                </div>

                <div>
                  <x-label for="modal_birth_place" value="Tempat Lahir *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_birth_place" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="birth_place"
                    placeholder="Contoh: Makassar" required />
                  @error('birth_place') <x-input-error for="birth_place" class="mt-1" /> @enderror
                </div>

                <div>
                  <x-label for="modal_birth_date" value="Tanggal Lahir *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_birth_date" type="date" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="birth_date" required />
                  @error('birth_date') <x-input-error for="birth_date" class="mt-1" /> @enderror
                </div>
              </div>
            </div>

            {{-- Kelompok 2: Domisili --}}
            <div class="space-y-4 pt-1">
              <div class="flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-2 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">
                <x-heroicon-o-map-pin class="h-4 w-4" />
                <span>2. Alamat Domisili</span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                  <x-label for="modal_address" value="Alamat Tempat Tinggal *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_address" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="address"
                    placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan" required />
                  @error('address') <x-input-error for="address" class="mt-1" /> @enderror
                </div>

                <div class="sm:col-span-2">
                  <x-label for="modal_city" value="Kota / Kabupaten Domisili *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_city" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="city"
                    placeholder="Contoh: Kota Makassar" required />
                  @error('city') <x-input-error for="city" class="mt-1" /> @enderror
                </div>
              </div>
            </div>

            {{-- Kelompok 3: Penempatan & Pendidikan --}}
            <div class="space-y-4 pt-1">
              <div class="flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-2 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">
                <x-heroicon-o-briefcase class="h-4 w-4" />
                <span>3. Penempatan Kerja & Pendidikan</span>
              </div>

              <div class="space-y-4">
                <div>
                  <x-label for="modal_division_id" value="Divisi / Unit Kerja *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-select id="modal_division_id" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="division_id" required>
                    <option value="">-- Pilih Divisi / Unit Kerja --</option>
                    @foreach ($divisions as $div)
                      <option value="{{ $div->id }}">{{ $div->name }}</option>
                    @endforeach
                  </x-select>
                  @error('division_id') <x-input-error for="division_id" class="mt-1" /> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <x-label for="modal_job_title_id" value="Jabatan / Batch *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                    <x-select id="modal_job_title_id" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="job_title_id" required>
                      <option value="">-- Pilih Jabatan / Batch --</option>
                      @foreach ($jobTitles as $job)
                        <option value="{{ $job->id }}">{{ $job->name }}</option>
                      @endforeach
                    </x-select>
                    @error('job_title_id') <x-input-error for="job_title_id" class="mt-1" /> @enderror
                  </div>

                  <div>
                    <x-label for="modal_education_id" value="Pendidikan Terakhir *" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                    <x-select id="modal_education_id" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="education_id" required>
                      <option value="">-- Pilih Pendidikan --</option>
                      @foreach ($educations as $edu)
                        <option value="{{ $edu->id }}">{{ $edu->name }}</option>
                      @endforeach
                    </x-select>
                    @error('education_id') <x-input-error for="education_id" class="mt-1" /> @enderror
                  </div>
                </div>

                <div>
                  <x-label for="modal_nip" value="NIP (Opsional)" class="font-semibold text-xs text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_nip" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="nip"
                    placeholder="Kosongkan jika bukan PNS/PPPK" />
                  @error('nip') <x-input-error for="nip" class="mt-1" /> @enderror
                </div>
              </div>
            </div>

            {{-- Spacing ekstra di bawah scroll --}}
            <div class="h-6"></div>
          </div>

          {{-- Fixed Footer Bar --}}
          <div class="px-5 py-4 sm:px-6 sm:py-4 border-t border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800 shrink-0 shadow-[0_-4px_16px_rgba(0,0,0,0.05)]" style="flex-shrink: 0;">
            <button type="submit"
              wire:loading.attr="disabled"
              class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-600/20 active:scale-[0.98] disabled:opacity-50">
              <span wire:loading.remove class="inline-flex items-center gap-2">
                <x-heroicon-o-check class="h-5 w-5" />
                Simpan & Lanjutkan Absensi
              </span>
              <span wire:loading class="inline-flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Menyimpan Data...
              </span>
            </button>
          </div>
        </form>

      </div>
    </div>
  @endif
</div>
