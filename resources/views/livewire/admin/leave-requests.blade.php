<div>
  {{-- Filter --}}
  <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h3 class="text-sm font-bold text-gray-900 dark:text-white">Filter Pengajuan</h3>
      <div class="flex items-center gap-2">
        <x-label for="filter" value="Status" />
        <x-select id="filter" wire:model.live="filter" class="rounded-xl">
          <option value="pending">Menunggu ({{ $pendingCount }})</option>
          <option value="approved">Disetujui</option>
          <option value="rejected">Ditolak</option>
          <option value="all">Semua</option>
        </x-select>
      </div>
    </div>
  </div>

  {{-- Tabel --}}
  <div class="mt-5 overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-gray-800">
    <div class="overflow-x-auto">
      <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-indigo-50 dark:bg-gray-900">
          <tr>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              Karyawan
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              Tipe
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              Tanggal
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              Keterangan
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              Status
            </th>
            <th scope="col" class="relative px-4 py-3">
              <span class="sr-only">Aksi</span>
            </th>
          </tr>
        </thead>
        @forelse ($requests as $req)
          <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            <tr wire:key="{{ $req->id }}" class="group">
              <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
                {{ $req->user->name }}
                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $req->user->nip }}</span>
              </td>
              <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                @if ($req->type === 'excused')
                  <span
                    class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/60 dark:text-blue-200">Izin</span>
                @else
                  <span
                    class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900/60 dark:text-purple-200">Sakit</span>
                @endif
              </td>
              <td class="px-4 py-4 text-sm text-gray-900 dark:text-white text-nowrap">
                {{ $req->from_date->format('d/m/Y') }}
                @if ($req->to_date != $req->from_date)
                  - {{ $req->to_date->format('d/m/Y') }}
                @endif
              </td>
              <td class="max-w-xs truncate px-4 py-4 text-sm text-gray-900 dark:text-white">
                {{ $req->note }}
              </td>
              <td class="px-4 py-4 text-sm text-nowrap">
                @switch($req->status)
                  @case('pending')
                    <span
                      class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/60 dark:text-amber-200">Menunggu</span>
                    @break
                  @case('approved')
                    <span
                      class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900/60 dark:text-green-200">Disetujui</span>
                    @break
                  @case('rejected')
                    <span
                      class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/60 dark:text-red-200">Ditolak</span>
                    @if ($req->rejection_reason)
                      <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">{{ $req->rejection_reason }}</span>
                    @endif
                    @break
                @endswitch
              </td>
              <td class="px-4 py-4 text-nowrap">
                <div class="flex items-center gap-1">
                  <x-button wire:click="viewDetail({{ $req->id }})" class="!px-3 !py-1">
                    <x-heroicon-o-eye class="mr-1 h-4 w-4" />
                    Detail
                  </x-button>
                  @if ($req->status === 'pending')
                    <x-button wire:click="confirmApprove({{ $req->id }})"
                      class="!px-3 !py-1 bg-green-600 hover:bg-green-500 active:bg-green-700 focus:ring-green-500">
                      <x-heroicon-o-check class="mr-1 h-4 w-4" />
                      Setujui
                    </x-button>
                    <x-danger-button wire:click="confirmReject({{ $req->id }})" class="!px-3 !py-1">
                      <x-heroicon-o-x-mark class="mr-1 h-4 w-4" />
                      Tolak
                    </x-danger-button>
                  @endif
                </div>
              </td>
            </tr>
            @if ($rejectingId === $req->id)
              <tr wire:key="reject-{{ $req->id }}">
                <td colspan="6" class="bg-gray-50 px-4 py-3 dark:bg-gray-900">
                  <div class="flex items-center gap-2">
                    <x-input type="text" placeholder="Alasan penolakan..." wire:model="rejectReason"
                      class="flex-1 rounded-xl" />
                    <x-danger-button wire:click="reject" class="shrink-0">
                      Konfirmasi
                    </x-danger-button>
                    <x-secondary-button wire:click="cancelReject" class="shrink-0">
                      Batal
                    </x-secondary-button>
                  </div>
                  @error('rejectReason')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                  @enderror
                </td>
              </tr>
            @endif
          </tbody>
        @empty
          <tbody>
            <tr>
              <td colspan="6" class="bg-white px-6 py-12 text-center dark:bg-gray-800">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                  <x-heroicon-o-clipboard-document-list class="h-7 w-7 text-gray-400 dark:text-gray-500" />
                </div>
                <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Tidak ada data</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Belum ada pengajuan dengan filter ini.</p>
              </td>
            </tr>
          </tbody>
        @endforelse
      </table>
    </div>
    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
      {{ $requests->links() }}
    </div>
  </div>

  {{-- Detail --}}
  <x-modal wire:model="showDetailModal">
    @if ($detailRequest)
      <div class="px-6 py-4">
        <h3 class="mb-5 text-xl font-bold text-gray-900 dark:text-white">Detail Pengajuan Izin</h3>

        <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Karyawan" />
            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $detailRequest->user->name }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Tipe" />
            <p class="mt-1 font-semibold text-gray-900 dark:text-white">
              {{ $detailRequest->type === 'excused' ? 'Izin' : 'Sakit' }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Tanggal" />
            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $detailRequest->from_date->format('d/m/Y') }}
              @if ($detailRequest->to_date != $detailRequest->from_date)
                - {{ $detailRequest->to_date->format('d/m/Y') }}
              @endif
            </p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Keterangan" />
            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $detailRequest->note }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Status" />
            @switch($detailRequest->status)
              @case('pending')
                <p class="mt-1 font-semibold text-amber-600 dark:text-amber-400">Menunggu</p>
                @break
              @case('approved')
                <p class="mt-1 font-semibold text-emerald-600 dark:text-emerald-400">Disetujui</p>
                @break
              @case('rejected')
                <p class="mt-1 font-semibold text-red-600 dark:text-red-400">Ditolak</p>
                @if ($detailRequest->rejection_reason)
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Alasan: {{ $detailRequest->rejection_reason }}</p>
                @endif
                @break
            @endswitch
          </div>
          @if ($detailRequest->attachment)
            <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
              <x-label value="Lampiran" />
              @php
                $ext = pathinfo($detailRequest->attachment, PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
              @endphp
              @if ($isImage)
                <img src="{{ Storage::url($detailRequest->attachment) }}" alt="Lampiran"
                  class="mt-2 max-h-48 rounded-xl object-contain">
              @else
                <a href="{{ Storage::url($detailRequest->attachment) }}" target="_blank"
                  class="mt-1 inline-flex items-center gap-1 text-blue-500 hover:underline">
                  <x-heroicon-o-paper-clip class="h-4 w-4" />
                  Lihat lampiran
                </a>
              @endif
            </div>
          @endif
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Diajukan" />
            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $detailRequest->created_at->format('d/m/Y H:i') }}</p>
          </div>
          @if ($detailRequest->reviewer)
            <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
              <x-label value="Diperiksa oleh" />
              <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                {{ $detailRequest->reviewer->name }} ({{ $detailRequest->reviewed_at?->format('d/m/Y H:i') }})</p>
            </div>
          @endif
        </div>
      </div>
    @endif
    <x-slot name="footer">
      <x-secondary-button wire:click="$set('showDetailModal', false)">Tutup</x-secondary-button>
    </x-slot>
  </x-modal>

  {{-- Konfirmasi Setujui --}}
  <x-confirmation-modal wire:model="confirmingApproval">
    <x-slot name="title">
      Konfirmasi Persetujuan
    </x-slot>
    <x-slot name="content">
      Setujui pengajuan izin dari <strong>{{ $approvingName }}</strong>?
    </x-slot>
    <x-slot name="footer">
      <x-secondary-button wire:click="$set('confirmingApproval', false)" wire:loading.attr="disabled">
        Batal
      </x-secondary-button>
      <x-button wire:click="executeApprove" class="ml-2 bg-green-600 hover:bg-green-500" wire:loading.attr="disabled">
        Setujui
      </x-button>
    </x-slot>
  </x-confirmation-modal>
</div>