<?php
/**
 * Update seeder: perbaiki division/jabatan & randomize absensi agar terlihat natural.
 *
 * - Set division & job_title untuk semua karyawan (biar merata)
 * - Randomize time_in: 07:15 - 07:45 (present), 07:46 - 08:15 (late)
 * - Randomize time_out: 15:45 - 16:30
 * - Status: present (85%), late (10%), incomplete (5%)
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NaturalizeAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Assign division & job_title untuk user yang kosong (Syahril + yang null)
        $divisionIds = DB::table('divisions')->pluck('id')->toArray(); // 2, 3
        $jobTitleIds = DB::table('job_titles')->pluck('id')->toArray(); // 1..5

        $updatedUsers = 0;
        foreach (DB::table('users')->where('group', 'user')->get() as $user) {
            if (!$user->division_id || !$user->job_title_id) {
                $newDiv = $divisionIds[array_rand($divisionIds)];
                $newJob = $jobTitleIds[array_rand($jobTitleIds)];
                DB::table('users')->where('id', $user->id)->update([
                    'division_id' => $newDiv,
                    'job_title_id' => $newJob,
                ]);
                $updatedUsers++;
            }
        }
        $this->command?->info("Users updated division/job: {$updatedUsers}");

        // 2. Naturalize attendances (randomize time_in, time_out, status)
        $attendances = DB::table('attendances')->get();
        $updated = 0;

        foreach ($attendances as $att) {
            // Shift info
            $shift = DB::table('shifts')->where('id', $att->shift_id)->first();
            $shiftStart = $shift ? $shift->start_time : '07:30:00';
            $shiftEnd = $shift ? $shift->end_time : '16:00:00';

            // Parse shift start/end to minutes
            [$sh, $sm] = explode(':', $shiftStart);
            $shiftStartMin = (int)$sh * 60 + (int)$sm;

            [$eh, $em] = explode(':', $shiftEnd);
            $shiftEndMin = (int)$eh * 60 + (int)$em;

            // Randomize: 85% present (0-15 min after start), 10% late (15-45 min after), 5% incomplete (early leave)
            $roll = random_int(1, 100);

            if ($roll <= 85) {
                // Present: time_in 07:15 - 07:40 (around start time)
                $timeInMin = $shiftStartMin + random_int(-15, 10); // 07:15-07:40 for 07:30 start
                $status = 'present';
            } elseif ($roll <= 95) {
                // Late: 07:41 - 08:15
                $timeInMin = $shiftStartMin + random_int(11, 45);
                $status = 'late';
            } else {
                // Incomplete: masuk on time tapi pulang awal (< shift end)
                $timeInMin = $shiftStartMin + random_int(-10, 5);
                $status = 'incomplete';
            }

            // Clamp time_in reasonable (06:30 - 08:30)
            $timeInMin = max(390, min(510, $timeInMin)); // 06:30 - 08:30
            $timeInH = (int)($timeInMin / 60);
            $timeInM = $timeInMin % 60;
            $timeIn = sprintf('%02d:%02d:00', $timeInH, $timeInM);

            // Time out: present/late → around shift end ± 30 min
            // incomplete → early leave (30-90 min before shift end)
            if ($status === 'incomplete') {
                $timeOutMin = $shiftEndMin - random_int(30, 90);
            } else {
                $timeOutMin = $shiftEndMin + random_int(-30, 30);
            }
            $timeOutMin = max(900, min(1140, $timeOutMin)); // 15:00 - 19:00
            $timeOutH = (int)($timeOutMin / 60);
            $timeOutM = $timeOutMin % 60;
            $timeOut = sprintf('%02d:%02d:00', $timeOutH, $timeOutM);

            // Ensure time_out > time_in
            if ($timeOutMin <= $timeInMin) {
                $timeOutMin = $timeInMin + random_int(60, 240); // minimal 1 jam kerja
                $timeOutH = (int)($timeOutMin / 60);
                $timeOutM = $timeOutMin % 60;
                $timeOut = sprintf('%02d:%02d:00', $timeOutH, $timeOutM);
            }

            // Randomize lat/lng slightly around barcode location (-5.1598639, 119.4073217)
            $lat = -5.1598639 + (random_int(-50, 50) / 100000); // ±50 meter
            $lng = 119.4073217 + (random_int(-50, 50) / 100000);

            DB::table('attendances')->where('id', $att->id)->update([
                'time_in'   => $timeIn,
                'time_out'  => $timeOut,
                'status'    => $status,
                'latitude'  => $lat,
                'longitude' => $lng,
            ]);

            $updated++;
        }

        // Stats
        $stats = DB::table('attendances')
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        $this->command?->info("Attendances naturalized: {$updated}");
        foreach ($stats as $status => $count) {
            $this->command?->info("  {$status}: {$count}");
        }
    }
}