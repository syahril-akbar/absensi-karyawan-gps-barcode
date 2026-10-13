<div>
  @if ($isOpen)
    <div
      class="fixed inset-0 z-[100] flex items-end justify-center overflow-hidden bg-slate-900/80 p-0 backdrop-blur-sm sm:items-center sm:p-4">

      <style>
        @keyframes modalSlideUp {
          from {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
          }
          to {
            opacity: 1;
            transform: translateY(0) scale(1);
          }
        }
        .modal-card-enter {
          animation: modalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
      </style>

      {{-- Modal Card --}}
      <div
        class="modal-card-enter flex h-[92vh] w-full flex-col overflow-hidden rounded-t-3xl border-t border-gray-100 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:h-auto sm:max-h-[85vh] sm:max-w-xl sm:rounded-3xl sm:border">

        {{-- Mobile Drag Handle Pill --}}
        <div class="flex shrink-0 justify-center bg-white pb-1 pt-2.5 dark:bg-gray-800 sm:hidden">
          <div class="h-1 w-10 rounded-full bg-gray-300 dark:bg-gray-600"></div>
        </div>

        {{-- Header Modal (Fixed Top) --}}
        <div class="shrink-0 border-b border-gray-100 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-800 sm:px-6 sm:py-5">
          <div class="flex items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30">
              <x-heroicon-o-user-circle class="h-6 w-6" />
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-2">
                <h3 class="truncate text-base font-bold text-gray-900 dark:text-white">
                  Lengkapi Data Profil
                </h3>
                <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                  Wajib
                </span>
              </div>
              <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">
                Lengkapi biodata dan unit kerja sebelum melanjutkan absensi.
              </p>
            </div>
          </div>
        </div>

        {{-- Form & Scrollable Body --}}
        <form wire:submit="save" class="flex min-h-0 flex-1 flex-col overflow-hidden">
          <div
            class="flex-1 space-y-6 overflow-y-auto overscroll-contain px-5 py-5 sm:px-6 sm:py-6"
            style="scrollbar-width: thin;">

            {{-- Kelompok 1: Biodata & Kontak --}}
            <div class="space-y-4">
              <div class="flex items-center gap-2 border-b border-gray-100 pb-2 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:border-gray-700 dark:text-indigo-400">
                <x-heroicon-o-identification class="h-4 w-4" />
                <span>1. Biodata & Kontak</span>
              </div>

              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <x-label for="modal_phone" value="No. WhatsApp / HP *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_phone" type="tel" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="phone"
                    placeholder="Contoh: 08123456789" required />
                  @error('phone') <x-input-error for="phone" class="mt-1" /> @enderror
                </div>

                <div>
                  <x-label for="modal_gender" value="Jenis Kelamin *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-select id="modal_gender" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="gender" required>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                  </x-select>
                  @error('gender') <x-input-error for="gender" class="mt-1" /> @enderror
                </div>

                <div>
                  <x-label for="modal_birth_place" value="Tempat Lahir *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_birth_place" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="birth_place"
                    placeholder="Contoh: Makassar" required />
                  @error('birth_place') <x-input-error for="birth_place" class="mt-1" /> @enderror
                </div>

                <div>
                  <x-label for="modal_birth_date" value="Tanggal Lahir *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_birth_date" type="date" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="birth_date" required />
                  @error('birth_date') <x-input-error for="birth_date" class="mt-1" /> @enderror
                </div>
              </div>
            </div>

            {{-- Kelompok 2: Domisili --}}
            <div class="space-y-4 pt-1">
              <div class="flex items-center gap-2 border-b border-gray-100 pb-2 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:border-gray-700 dark:text-indigo-400">
                <x-heroicon-o-map-pin class="h-4 w-4" />
                <span>2. Alamat Domisili</span>
              </div>

              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                  <x-label for="modal_address" value="Alamat Tempat Tinggal *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_address" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="address"
                    placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan" required />
                  @error('address') <x-input-error for="address" class="mt-1" /> @enderror
                </div>

                <div class="sm:col-span-2">
                  <x-label for="modal_city" value="Kota / Kabupaten Domisili *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_city" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="city"
                    placeholder="Contoh: Kota Makassar" required />
                  @error('city') <x-input-error for="city" class="mt-1" /> @enderror
                </div>
              </div>
            </div>

            {{-- Kelompok 3: Penempatan & Pendidikan --}}
            <div class="space-y-4 pt-1">
              <div class="flex items-center gap-2 border-b border-gray-100 pb-2 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:border-gray-700 dark:text-indigo-400">
                <x-heroicon-o-briefcase class="h-4 w-4" />
                <span>3. Penempatan Kerja & Pendidikan</span>
              </div>

              <div class="space-y-4">
                <div>
                  <x-label for="modal_division_id" value="Divisi / Unit Kerja *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-select id="modal_division_id" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="division_id" required>
                    <option value="">-- Pilih Divisi / Unit Kerja --</option>
                    @foreach ($divisions as $div)
                      <option value="{{ $div->id }}">{{ $div->name }}</option>
                    @endforeach
                  </x-select>
                  @error('division_id') <x-input-error for="division_id" class="mt-1" /> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <x-label for="modal_job_title_id" value="Jabatan / Batch *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                    <x-select id="modal_job_title_id" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="job_title_id" required>
                      <option value="">-- Pilih Jabatan / Batch --</option>
                      @foreach ($jobTitles as $job)
                        <option value="{{ $job->id }}">{{ $job->name }}</option>
                      @endforeach
                    </x-select>
                    @error('job_title_id') <x-input-error for="job_title_id" class="mt-1" /> @enderror
                  </div>

                  <div>
                    <x-label for="modal_education_id" value="Pendidikan Terakhir *" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
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
                  <x-label for="modal_nip" value="NIP (Opsional)" class="text-xs font-semibold text-gray-700 dark:text-gray-300" />
                  <x-input id="modal_nip" type="text" class="mt-1.5 block w-full rounded-xl py-2.5 text-sm" wire:model="nip"
                    placeholder="Kosongkan jika bukan PNS/PPPK" />
                  @error('nip') <x-input-error for="nip" class="mt-1" /> @enderror
                </div>
              </div>
            </div>

            <div class="h-4"></div>
          </div>

          {{-- Fixed Footer Bar --}}
          <div class="shrink-0 border-t border-gray-100 bg-white px-5 py-4 shadow-[0_-4px_16px_rgba(0,0,0,0.05)] dark:border-gray-700 dark:bg-gray-800 sm:px-6 sm:py-4">
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
