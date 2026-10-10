<div>
  <form wire:submit="save" class="grid grid-cols-1 gap-6">

    {{-- Kartu Logo & Identitas Kop --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <h3 class="text-base font-bold text-gray-900 dark:text-white">Logo Instansi</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Logo ini akan ditampilkan pada kop surat laporan PDF absensi dan dokumen resmi.
      </p>

      <div class="mt-4 flex flex-col gap-6 sm:flex-row sm:items-center">
        {{-- Preview Logo --}}
        <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 p-2 dark:border-gray-700 dark:bg-gray-900">
          @if ($logo)
            <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo" class="max-h-full max-w-full object-contain">
          @elseif ($current_logo)
            <img src="{{ asset('storage/' . $current_logo) }}" alt="Logo Saat Ini" class="max-h-full max-w-full object-contain">
          @else
            <img src="{{ asset('Images/logo-mks-tr2.png') }}" alt="Logo Default" class="max-h-full max-w-full object-contain">
          @endif
        </div>

        <div class="flex-1 space-y-2">
          <input type="file" wire:model="logo" accept="image/*"
            class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-950/60 dark:file:text-indigo-300">
          <p class="text-xs text-gray-400 dark:text-gray-500">Format: PNG, JPG, WEBP. Maksimal 2MB. Disarankan PNG berlatar transparan.</p>
          @error('logo')
            <x-input-error for="logo" class="mt-1" />
          @enderror

          @if ($current_logo)
            <div class="pt-1">
              <button type="button" wire:click="deleteLogo"
                class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline dark:text-rose-400">
                Gunakan Logo Default Sistem
              </button>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Kartu Hirarki Instansi --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <h3 class="text-base font-bold text-gray-900 dark:text-white">Hierarki & Alamat Instansi</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Informasi yang dicetak pada baris header kop surat resmi.
      </p>

      <div class="mt-5 space-y-4">
        <div>
          <x-label for="kementerian" value="Kementerian / Lembaga Induk" />
          <x-input id="kementerian" type="text" class="mt-1 block w-full uppercase" wire:model="kementerian"
            placeholder="KEMENTERIAN PEKERJAAN UMUM" required />
          @error('kementerian')
            <x-input-error for="kementerian" class="mt-1" />
          @enderror
        </div>

        <div>
          <x-label for="eselon1" value="Badan / Direktorat Jenderal (Eselon I)" />
          <x-input id="eselon1" type="text" class="mt-1 block w-full uppercase" wire:model="eselon1"
            placeholder="BADAN PENGEMBANGAN SUMBER DAYA MANUSIA" required />
          @error('eselon1')
            <x-input-error for="eselon1" class="mt-1" />
          @enderror
        </div>

        <div>
          <x-label for="nama" value="Nama Balai / Satuan Kerja (Eselon II/III)" />
          <x-input id="nama" type="text" class="mt-1 block w-full uppercase" wire:model="nama"
            placeholder="BALAI PENGEMBANGAN KOMPETENSI PU WILAYAH VIII MAKASSAR" required />
          @error('nama')
            <x-input-error for="nama" class="mt-1" />
          @enderror
        </div>

        <div>
          <x-label for="alamat" value="Alamat Lengkap & Kontak Kantor" />
          <textarea id="alamat" rows="2"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
            wire:model="alamat"
            placeholder="Jl. Nuri No.19, Mariso, Kec. Mariso, Kota Makassar, Sulawesi Selatan 90121"></textarea>
          @error('alamat')
            <x-input-error for="alamat" class="mt-1" />
          @enderror
        </div>
      </div>
    </div>

    {{-- Kartu Penandatangan Laporan --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <h3 class="text-base font-bold text-gray-900 dark:text-white">Pengesahan / Penandatangan Laporan</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Informasi yang dicetak pada bagian tanda tangan footer laporan rekap kehadiran.
      </p>

      <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <x-label for="kota" value="Kota Dokumen" />
          <x-input id="kota" type="text" class="mt-1 block w-full" wire:model="kota"
            placeholder="Makassar" required />
          @error('kota')
            <x-input-error for="kota" class="mt-1" />
          @enderror
        </div>

        <div>
          <x-label for="penandatangan_jabatan" value="Jabatan Penandatangan" />
          <x-input id="penandatangan_jabatan" type="text" class="mt-1 block w-full" wire:model="penandatangan_jabatan"
            placeholder="Penanggung Jawab / Pembimbing Magang" />
          @error('penandatangan_jabatan')
            <x-input-error for="penandatangan_jabatan" class="mt-1" />
          @enderror
        </div>

        <div>
          <x-label for="penandatangan_nama" value="Nama Lengkap Penandatangan" />
          <x-input id="penandatangan_nama" type="text" class="mt-1 block w-full" wire:model="penandatangan_nama"
            placeholder="Contoh: Ir. Fulan, M.T." />
          @error('penandatangan_nama')
            <x-input-error for="penandatangan_nama" class="mt-1" />
          @enderror
        </div>

        <div>
          <x-label for="penandatangan_nip" value="NIP (Kosongkan jika bukan PNS)" />
          <x-input id="penandatangan_nip" type="text" class="mt-1 block w-full" wire:model="penandatangan_nip"
            placeholder="19850101 201001 1 001" />
          @error('penandatangan_nip')
            <x-input-error for="penandatangan_nip" class="mt-1" />
          @enderror
        </div>
      </div>
    </div>

    <div>
      <x-button type="submit" class="w-full justify-center sm:w-auto">
        Simpan Informasi Kantor
      </x-button>
    </div>

  </form>
</div>
