@php
  use Illuminate\Support\Carbon;
  use App\Support\Settings;
  use Illuminate\Support\Facades\Storage;

  $selectedDate = Carbon::parse($date ?? ($week ?? $month))->settings(['formatFunction' => 'translatedFormat']);
  $showUserDetail = !$month || $week || $date; // is week or day filter
  $isPerDayFilter = isset($date);

  // Logo instansi: prioritas upload kustom, fallback logo bawaan
  $customLogo = Settings::get('office.logo');
  if ($customLogo && Storage::disk('public')->exists($customLogo)) {
      $logoPath = Storage::disk('public')->path($customLogo);
  } else {
      $logoPath = public_path('Images/logo-mks-tr2.png');
  }
  $hasLogo = file_exists($logoPath);

  // Informasi kantor & kop dinamis
  $kementerian = Settings::get('office.kementerian', 'KEMENTERIAN PEKERJAAN UMUM');
  $eselon1 = Settings::get('office.eselon1', 'BADAN PENGEMBANGAN SUMBER DAYA MANUSIA');
  $namaBalai = Settings::get('office.nama', 'BALAI PENGEMBANGAN KOMPETENSI PU WILAYAH VIII MAKASSAR');
  $alamatKantor = Settings::get('office.alamat', 'Jl. Nuri No.19, Mariso, Kec. Mariso, Kota Makassar, Sulawesi Selatan 90121');
  $kotaDokumen = Settings::get('office.kota', 'Makassar');
  $penandatanganJabatan = Settings::get('office.penandatangan_jabatan', 'Penanggung Jawab / Pembimbing Magang');
  $penandatanganNama = Settings::get('office.penandatangan_nama', '');
  $penandatanganNip = Settings::get('office.penandatangan_nip', '');

  // Acuan lebar kolom nama: menyesuaikan dengan nama terpanjang
  $longestNameLen = $employees->map(fn($e) => mb_strlen($e->name))->max() ?? 15;
  $nameColWidth = max(85, min(165, (int)($longestNameLen * 5.8) + 16)) . 'pt';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Absensi | {{ $date ?? ($week ?? $month) }}</title>
  <style>
    @page {
      margin: 10mm 10mm 12mm 10mm;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      padding: 0;
      color: #0f172a;
      font-size: 10px;
    }

    /* Kop Surat */
    .kop-table {
      width: 100%;
      border-collapse: collapse;
      border-bottom: 2px solid #0f172a;
      margin-bottom: 10px;
      padding-bottom: 6px;
    }

    .kop-table td {
      border: none;
      padding: 0;
    }

    .kop-logo {
      width: 70px;
      text-align: left;
      vertical-align: middle;
    }

    .kop-text {
      text-align: center;
      vertical-align: middle;
    }

    .kop-instansi-1 {
      font-size: 11px;
      font-weight: bold;
      color: #1e293b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .kop-instansi-2 {
      font-size: 10px;
      font-weight: bold;
      color: #334155;
      text-transform: uppercase;
      margin-top: 1px;
    }

    .kop-instansi-3 {
      font-size: 12px;
      font-weight: bold;
      color: #0f172a;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-top: 1px;
    }

    .kop-alamat {
      font-size: 8px;
      color: #64748b;
      margin-top: 2px;
    }

    /* Judul Laporan */
    .report-header {
      text-align: center;
      margin-bottom: 10px;
    }

    .report-title {
      font-size: 13px;
      font-weight: bold;
      color: #0f172a;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin: 0;
    }

    .report-subtitle {
      font-size: 10px;
      color: #475569;
      margin-top: 2px;
      font-weight: bold;
    }

    /* Meta Info */
    .meta-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
      font-size: 9px;
      color: #334155;
    }

    .meta-table td {
      border: none;
      padding: 1px 0;
    }

    /* Tabel Utama */
    .attendance-table {
      width: 100%;
      border-collapse: collapse;
      font-size: @if($month) 9px @else 10px @endif;
    }

    .attendance-table th,
    .attendance-table td {
      border: 1px solid #cbd5e1;
      padding: @if($month) 4px 2px @else 5px 4px @endif;
    }

    .attendance-table th {
      background-color: #f1f5f9;
      color: #0f172a;
      font-weight: bold;
      text-align: center;
      vertical-align: middle;
    }

    .attendance-table th.th-weekend {
      background-color: #e2e8f0;
      color: #64748b;
    }

    .attendance-table th.th-rekap-group {
      background-color: #e0e7ff;
      color: #3730a3;
      border-left: 2px solid #6366f1;
    }

    .attendance-table th.th-rekap-item {
      background-color: #eef2ff;
      color: #3730a3;
    }

    .attendance-table th.th-rekap-start {
      border-left: 2px solid #6366f1;
    }

    .attendance-table td.cell-center {
      text-align: center;
      vertical-align: middle;
    }

    .attendance-table td.cell-rekap-start {
      border-left: 2px solid #6366f1;
    }

    .col-name {
      width: {{ $nameColWidth }};
      max-width: {{ $nameColWidth }};
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }

    /* Status Badges untuk tabel PDF */
    .st-H {
      color: #15803d;
      font-weight: bold;
    }

    .st-T {
      color: #b45309;
      font-weight: bold;
    }

    .st-I {
      color: #1d4ed8;
      font-weight: bold;
    }

    .st-S {
      color: #7e22ce;
      font-weight: bold;
    }

    .st-TT {
      color: #c2410c;
      font-weight: bold;
      font-size: 8px;
    }

    .st-A {
      color: #b91c1c;
      font-weight: bold;
    }

    .st-L {
      color: #94a3b8;
      background-color: #f1f5f9;
    }

    .st-blank {
      color: #94a3b8;
    }

    /* Footer: Legenda & Tanda Tangan */
    .footer-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .footer-table td {
      border: none;
      padding: 0;
      vertical-align: top;
    }

    .legend-box {
      border: 1px solid #cbd5e1;
      border-radius: 4px;
      font-size: 8px;
      color: #334155;
      width: 100%;
    }

    .legend-box th {
      background-color: #f8fafc;
      font-weight: bold;
      text-align: left;
      padding: 3px 6px;
      border-bottom: 1px solid #cbd5e1;
    }

    .legend-box td {
      padding: 4px 8px;
      border: none;
      vertical-align: top;
    }

    .legend-item {
      margin-bottom: 2px;
    }

    .legend-badge {
      display: inline-block;
      width: 22px;
      font-weight: bold;
    }

    .signature-box {
      width: 100%;
      text-align: center;
      font-size: 9.5px;
    }
  </style>
</head>

<body>
  <!-- Kop Surat -->
  <table class="kop-table">
    <tr>
      @if ($hasLogo)
        <td class="kop-logo">
          <img src="{{ $logoPath }}" style="width: 55px; height: auto;" />
        </td>
      @endif
      <td class="kop-text">
        <div class="kop-instansi-1">{{ $kementerian }}</div>
        <div class="kop-instansi-2">{{ $eselon1 }}</div>
        <div class="kop-instansi-3">{{ $namaBalai }}</div>
        <div class="kop-alamat">{{ $alamatKantor }}</div>
      </td>
      @if ($hasLogo)
        <td class="kop-logo" style="width: 55px;"></td>
      @endif
    </tr>
  </table>

  <!-- Judul Laporan -->
  <div class="report-header">
    <h2 class="report-title">Laporan Rekapitulasi Presensi Kehadiran</h2>
    <div class="report-subtitle">
      @if ($month)
        Periode: {{ $selectedDate->locale('id')->isoFormat('MMMM Y') }}
      @elseif ($week)
        Periode: {{ $start->locale('id')->isoFormat('D MMMM Y') }} s/d {{ $end->locale('id')->isoFormat('D MMMM Y') }}
      @elseif ($date)
        Tanggal: {{ $selectedDate->locale('id')->isoFormat('dddd, D MMMM Y') }}
      @endif
    </div>
  </div>

  <!-- Meta Info -->
  <table class="meta-table">
    <tr>
      <td style="width: 60%; vertical-align: top;">
        @if ($division)
          <div><strong>Divisi:</strong> {{ App\Models\Division::find($division)?->name ?? '-' }}</div>
        @endif
        @if ($jobTitle)
          <div><strong>Jabatan:</strong> {{ App\Models\JobTitle::find($jobTitle)?->name ?? '-' }}</div>
        @endif
      </td>
      <td style="width: 40%; text-align: right; vertical-align: bottom;">
        <em>Dicetak pada: {{ now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WITA</em>
      </td>
    </tr>
  </table>

  <!-- Tabel Absensi -->
  <table class="attendance-table">
    <thead>
      @if ($isPerDayFilter)
        <tr>
          <th style="width: 25px;">No.</th>
          <th class="col-name">Nama Pegawai</th>
          <th style="width: 80px;">NIP</th>
          <th>Divisi</th>
          <th>Jabatan</th>
          <th style="width: 50px;">Shift</th>
          <th style="width: 45px;">Masuk</th>
          <th style="width: 45px;">Pulang</th>
          <th style="width: 65px;">Status</th>
        </tr>
      @else
        <tr>
          <th rowspan="2" style="width: 25px;">No.</th>
          <th rowspan="2" class="col-name">{{ $showUserDetail ? 'Nama Pegawai' : 'Nama Pegawai / Tanggal' }}</th>
          @if ($showUserDetail)
            <th rowspan="2" style="width: 75px;">NIP</th>
            <th rowspan="2">Divisi</th>
            <th rowspan="2">Jabatan</th>
          @endif
          <th colspan="{{ count($dates) }}">Tanggal Pelaksanaan</th>
          <th colspan="6" class="th-rekap-group">Rekapitulasi</th>
        </tr>
        <tr>
          @foreach ($dates as $d)
            @php $isHol = \App\Helpers::isHoliday($d); @endphp
            <th class="{{ $isHol ? 'th-weekend' : '' }}" style="width: @if($month) 18px @else 28px @endif;">
              @if (!$month)
                {{ $d->format('d/m') }}
              @else
                {{ $d->format('d') }}
              @endif
            </th>
          @endforeach
          <th class="th-rekap-item th-rekap-start" style="width: 20px;">H</th>
          <th class="th-rekap-item" style="width: 20px;">T</th>
          <th class="th-rekap-item" style="width: 20px;">I</th>
          <th class="th-rekap-item" style="width: 20px;">S</th>
          <th class="th-rekap-item" style="width: 22px;">TT</th>
          <th class="th-rekap-item" style="width: 20px;">A</th>
        </tr>
      @endif
    </thead>
    <tbody>
      @forelse ($employees as $employee)
        @php
          $attendances = $employee->attendances;
          $attFirst = $employee->attendances->isEmpty() ? null : $employee->attendances->first();
        @endphp

        @if ($isPerDayFilter)
          <tr>
            <td class="cell-center">{{ $loop->iteration }}</td>
            <td class="col-name">{{ $employee->name }}</td>
            <td class="cell-center">{{ $employee->nip ?? '-' }}</td>
            <td>{{ $employee->division?->name ?? '-' }}</td>
            <td>{{ $employee->jobTitle?->name ?? '-' }}</td>
            <td class="cell-center">{{ $attFirst['shift'] ?? '-' }}</td>
            <td class="cell-center">
              {{ isset($attFirst['time_in']) && $attFirst['time_in'] ? Carbon::parse($attFirst['time_in'])->format('H:i') : '-' }}
            </td>
            <td class="cell-center">
              {{ isset($attFirst['time_out']) && $attFirst['time_out'] ? Carbon::parse($attFirst['time_out'])->format('H:i') : '-' }}
            </td>
            <td class="cell-center">
              @if ($attFirst && isset($attFirst['status']) && $attFirst['status'] !== '-')
                {{ __("status_{$attFirst['status']}") }}
              @else
                -
              @endif
            </td>
          </tr>
        @else
          @php
            $presentCount = 0;
            $lateCount = 0;
            $excusedCount = 0;
            $sickCount = 0;
            $absentCount = 0;
            $incompleteCount = 0;
          @endphp
          <tr>
            <td class="cell-center">{{ $loop->iteration }}</td>
            <td class="col-name">{{ $employee->name }}</td>
            @if ($showUserDetail)
              <td class="cell-center">{{ $employee->nip ?? '-' }}</td>
              <td>{{ $employee->division?->name ?? '-' }}</td>
              <td>{{ $employee->jobTitle?->name ?? '-' }}</td>
            @endif

            @foreach ($dates as $d)
              @php
                $isHoliday = \App\Helpers::isHoliday($d);
                $attItem = $attendances->firstWhere(fn($v, $k) => $v['date'] === $d->format('Y-m-d'));
                $status = $attItem['status'] ?? ($isHoliday ? 'L' : '-');

                if ($status === 'present') {
                    $presentCount++;
                } elseif ($status === 'late') {
                    $lateCount++;
                } elseif ($status === 'excused') {
                    $excusedCount++;
                } elseif ($status === 'sick') {
                    $sickCount++;
                } elseif ($status === 'incomplete') {
                    $incompleteCount++;
                } elseif ($status === 'absent') {
                    $absentCount++;
                }
              @endphp
              <td class="cell-center {{ $isHoliday ? 'st-L' : '' }}">
                @if ($status === 'present')
                  <span class="st-H">H</span>
                @elseif ($status === 'late')
                  <span class="st-T">T</span>
                @elseif ($status === 'excused')
                  <span class="st-I">I</span>
                @elseif ($status === 'sick')
                  <span class="st-S">S</span>
                @elseif ($status === 'incomplete')
                  <span class="st-TT">TT</span>
                @elseif ($status === 'absent')
                  <span class="st-A">A</span>
                @elseif ($isHoliday)
                  <span class="st-L">L</span>
                @else
                  <span class="st-blank">-</span>
                @endif
              </td>
            @endforeach

            <!-- Total Rekapitulasi -->
            <td class="cell-center cell-rekap-start st-H" style="font-weight: bold;">{{ $presentCount }}</td>
            <td class="cell-center st-T" style="font-weight: bold;">{{ $lateCount }}</td>
            <td class="cell-center st-I" style="font-weight: bold;">{{ $excusedCount }}</td>
            <td class="cell-center st-S" style="font-weight: bold;">{{ $sickCount }}</td>
            <td class="cell-center st-TT" style="font-weight: bold;">{{ $incompleteCount }}</td>
            <td class="cell-center st-A" style="font-weight: bold;">{{ $absentCount }}</td>
          </tr>
        @endif
      @empty
        <tr>
          <td colspan="{{ $isPerDayFilter ? 9 : count($dates) + ($showUserDetail ? 11 : 8) }}" class="cell-center" style="padding: 15px; color: #64748b;">
            Tidak ada data presensi yang ditemukan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <!-- Bagian Bawah: Legenda & Tanda Tangan -->
  <table class="footer-table">
    <tr>
      <td style="width: 58%; vertical-align: top;">
        @if (!$isPerDayFilter)
          <table class="legend-box">
            <tr>
              <th colspan="2">Keterangan Simbol & Status Kehadiran:</th>
            </tr>
            <tr>
              <td style="width: 50%;">
                <div class="legend-item"><span class="legend-badge st-H">H</span> : Hadir Tepat Waktu</div>
                <div class="legend-item"><span class="legend-badge st-T">T</span> : Terlambat Masuk</div>
                <div class="legend-item"><span class="legend-badge st-I">I</span> : Izin Resmi</div>
                <div class="legend-item"><span class="legend-badge st-S">S</span> : Izin Sakit</div>
              </td>
              <td style="width: 50%;">
                <div class="legend-item"><span class="legend-badge st-TT">TT</span> : Tanpa Tap Pulang <em>(Incomplete)</em></div>
                <div class="legend-item"><span class="legend-badge st-A">A</span> : Alpa / Tanpa Keterangan</div>
                <div class="legend-item"><span class="legend-badge st-L">L</span> : Libur Akhir Pekan / Nasional</div>
                <div class="legend-item"><span class="legend-badge st-blank">-</span> : Belum Terlaksana / Libur</div>
              </td>
            </tr>
          </table>
        @endif
      </td>
      <td style="width: 7%;"></td>
      <td style="width: 35%; vertical-align: top;">
        <div class="signature-box">
          <div>{{ $kotaDokumen }}, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</div>
          <div style="margin-top: 3px; font-weight: bold;">Mengetahui,</div>
          <div style="font-weight: bold;">{{ $penandatanganJabatan }}</div>
          @if ($penandatanganNama)
            <div style="margin-top: 48px; font-weight: bold; text-decoration: underline;">{{ $penandatanganNama }}</div>
            @if ($penandatanganNip)
              <div style="margin-top: 2px; font-size: 8.5px;">NIP. {{ $penandatanganNip }}</div>
            @endif
          @else
            <div style="margin-top: 48px; border-bottom: 1px solid #0f172a; width: 85%; margin-left: auto; margin-right: auto;"></div>
            <div style="margin-top: 3px; font-size: 8.5px;">NIP. .....................................................</div>
          @endif
        </div>
      </td>
    </tr>
  </table>
</body>

</html>
