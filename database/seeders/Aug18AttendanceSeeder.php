<?php
/**
 * Buat absensi 18 Agustus 2026 untuk semua karyawan.
 * - Status: present (semua hadir)
 * - time_in = jam shift (07:30 shift Senin-Kamis id 7)
 * - time_out = jam shift (16:00)
 * - Hapus data 18-08 existing dulu (biar idempoten)
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Aug18AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $date = '2026-08-18';
        $shiftId = 7; // Senin - Kamis: 07:30 - 16:00
        $barcodeId = 1; // Balai Diklat

        // Clear existing 18-08
        DB::table('attendances')->where('date', $date)->delete();

        $users = DB::table('users')->where('group', 'user')->get();
        $inserted = 0;

        foreach ($users as $user) {
            // Natural time_in: 07:15-07:45, time_out: 15:45-16:15 (semua hadir)
            $timeInMin = 450 + random_int(-15, 15); // 07:30 ±15
            $timeInH = (int)($timeInMin / 60);
            $timeInM = $timeInMin % 60;
            $timeIn = sprintf('%02d:%02d:00', $timeInH, $timeInM);

            $timeOutMin = 960 + random_int(-15, 15); // 16:00 ±15
            $timeOutH = (int)($timeOutMin / 60);
            $timeOutM = $timeOutMin % 60;
            $timeOut = sprintf('%02d:%02d:00', $timeOutH, $timeOutM);

            DB::table('attendances')->insert([
                'user_id'    => $user->id,
                'barcode_id' => $barcodeId,
                'date'       => $date,
                'time_in'    => $timeIn,
                'time_out'   => $timeOut,
                'shift_id'   => $shiftId,
                'latitude'   => -5.1598639 + (random_int(-30, 30) / 100000),
                'longitude'  => 119.4073217 + (random_int(-30, 30) / 100000),
                'status'     => 'present',
                'note'       => null,
                'attachment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $inserted++;
        }

        $this->command?->info("Aug 18 attendance inserted: {$inserted} (all present)");
    }
}