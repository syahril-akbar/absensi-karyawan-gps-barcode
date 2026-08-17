<x-app-layout>
  <div x-data="{ open: false, selectedId: null }" @detail-leave.window="selectedId = $event.detail; open = true">
    <div class="mx-auto w-full max-w-lg px-4 pb-6 pt-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
            <x-heroicon-o-document-text class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Izin</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Semua pengajuan izin kamu</p>
          </div>
        </div>
        <a href="{{ route('apply-leave') }}"
          class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-600 text-white shadow-md shadow-indigo-600/30 transition hover:bg-indigo-700">
          <x-heroicon-o-plus class="h-6 w-6" />
        </a>
      </div>

      <!-- Filter Status -->
      <div class="mt-6 flex gap-2 overflow-x-auto pb-1">
        @php
          $filters = [
              'all' => 'Semua',
              'pending' => 'Menunggu' . ($pendingCount > 0 ? " ($pendingCount)" : ''),
              'approved' => 'Disetujui',
              'rejected' => 'Ditolak',
          ];
        @endphp
        @foreach ($filters as $key => $label)
          <a href="{{ route('leave-history', ['filter' => $key]) }}"
            class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition {{ $currentFilter === $key ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-white text-gray-600 shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
            {{ $label }}
          </a>
        @endforeach
      </div>

      <!-- List -->
      <div class="mt-5 flex flex-col gap-3">
        @forelse ($leaveRequests as $req)
          <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-center gap-3">
                <div
                  class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $req->type === 'excused' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-purple-100 dark:bg-purple-900' }}">
                  @if ($req->type === 'excused')
                    <x-heroicon-o-paper-airplane class="h-5 w-5 text-blue-600 dark:text-blue-300" />
                  @else
                    <x-heroicon-o-heart class="h-5 w-5 text-purple-600 dark:text-purple-300" />
                  @endif
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-900 dark:text-white">
                    {{ $req->type === 'excused' ? 'Izin' : 'Sakit' }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $req->from_date->format('d/m/Y') }}
                    @if ($req->to_date != $req->from_date)
                      - {{ $req->to_date->format('d/m/Y') }}
                    @endif
                  </p>
                </div>
              </div>
              <span
                class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ match ($req->status) {
                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                    'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                    'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                    default => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                } }}">
                {{ match ($req->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => $req->status,
                } }}
              </span>
            </div>
            <p class="mt-3 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">{{ $req->note }}</p>
            <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
              <span class="text-xs text-gray-400 dark:text-gray-500">
                {{ $req->created_at->format('d/m/Y H:i') }}
              </span>
              <button @click="$dispatch('detail-leave', {{ $req->id }})"
                class="rounded-xl bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100 dark:bg-indigo-900/50 dark:text-indigo-300 dark:hover:bg-indigo-900">
                Detail
              </button>
            </div>
          </div>
        @empty
          <div class="flex flex-col items-center justify-center rounded-3xl bg-white px-6 py-14 text-center shadow-sm dark:bg-gray-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
              <x-heroicon-o-document-text class="h-7 w-7 text-gray-400 dark:text-gray-500" />
            </div>
            <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Belum ada pengajuan izin</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pengajuan yang kamu buat akan muncul di sini.</p>
          </div>
        @endforelse
      </div>

      <div class="mt-4">
        {{ $leaveRequests->links() }}
      </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak
      @keydown.escape.window="open = false">
      <div class="fixed inset-0 bg-gray-900/50" @click="open = false"></div>
      <div class="relative z-50 w-full max-w-md rounded-3xl bg-white p-6 shadow-xl dark:bg-gray-800">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Detail Pengajuan</h3>
          <button @click="open = false" class="rounded-full p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700">
            <x-heroicon-o-x-mark class="h-5 w-5" />
          </button>
        </div>

        @foreach ($leaveRequests as $req)
          <div x-show="selectedId === {{ $req->id }}">
            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
              <div class="flex items-center gap-2">
                <span class="font-medium text-gray-900 dark:text-white">Tipe:</span>
                <span
                  class="rounded-full px-3 py-1 text-xs font-semibold {{ $req->type === 'excused' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300' }}">
                  {{ $req->type === 'excused' ? 'Izin' : 'Sakit' }}
                </span>
              </div>
              <div>
                <span class="font-medium text-gray-900 dark:text-white">Tanggal:</span>
                {{ $req->from_date->format('d/m/Y') }}
                @if ($req->to_date != $req->from_date)
                  - {{ $req->to_date->format('d/m/Y') }}
                @endif
              </div>
              <div>
                <span class="font-medium text-gray-900 dark:text-white">Keterangan:</span>
                <p class="mt-1">{{ $req->note }}</p>
              </div>
              <div>
                <span class="font-medium text-gray-900 dark:text-white">Status:</span>
                @switch($req->status)
                  @case('pending')
                    <span class="text-amber-600 dark:text-amber-400">Menunggu</span>
                    @break
                  @case('approved')
                    <span class="text-emerald-600 dark:text-emerald-400">Disetujui</span>
                    @break
                  @case('rejected')
                    <span class="text-red-600 dark:text-red-400">Ditolak</span>
                    @if ($req->rejection_reason)
                      <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Alasan: {{ $req->rejection_reason }}</p>
                    @endif
                    @break
                @endswitch
              </div>
              @if ($req->attachment)
                <div>
                  <span class="font-medium text-gray-900 dark:text-white">Lampiran:</span>
                  @php
                    $ext = pathinfo($req->attachment, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                  @endphp
                  @if ($isImage)
                    <div class="mt-1">
                      <img src="{{ Storage::url($req->attachment) }}" alt="Lampiran"
                        class="max-h-48 rounded-xl object-contain">
                    </div>
                  @else
                    <a href="{{ Storage::url($req->attachment) }}" target="_blank"
                      class="ml-1 text-blue-500 hover:underline">Lihat</a>
                  @endif
                </div>
              @endif
              <div>
                <span class="font-medium text-gray-900 dark:text-white">Diajukan:</span>
                {{ $req->created_at->format('d/m/Y H:i') }}
              </div>
            </div>
          </div>
        @endforeach

        <div class="mt-6 flex justify-end">
          <button @click="open = false"
            class="rounded-xl bg-indigo-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>