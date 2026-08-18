<?php
/**
 * Recovery seeder: restore karyawan & absensi dari CSV export.
 *
 * Input: /tmp/absensi_export.csv
 * - Buat 11 karyawan baru (Syahril sudah ada)
 * - Map old user_id ULID → new ULID
 * - Insert 59 attendance records
 * - Default: password "password", group "user", division random
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendanceRecoverySeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('absensi_export.csv');
        $rows = array_map('str_getcsv', file($csvPath));
        $header = array_map('strtolower', array_map('trim', array_shift($rows)));
        $header = array_map(function ($h) {
            return str_replace(' ', '_', $h);
        }, $header);

        // Normalize header keys
        $colIndex = array_flip($header);

        // Map kolom CSV → field DB
        $keyMap = [
            'date'           => 'date',
            'name'           => 'name',
            'nip'            => 'nip',
            'time_in'        => 'time_in',
            'time_out'       => 'time_out',
            'barcode_id'     => 'barcode_id',
            'coordinates'    => 'coordinates',
            'status'         => 'status',
            'shift'          => 'shift',
            'created_at'     => 'created_at',
            'updated_at'     => 'updated_at',
            'user_id'        => 'old_user_id',
            'shift_id'       => 'old_shift_id',
        ];

        // Build lookup: old_user_id → new_user_id
        $oldToNew = [];
        $createdUsers = 0;
        $skippedUsers = 0;

        // Division IDs for assignment
        $divisionIds = DB::table('divisions')->pluck('id')->toArray();
        $educationIds = DB::table('educations')->pluck('id')->toArray();
        $jobTitleIds = DB::table('job_titles')->pluck('id')->toArray();

        // Default password for all karyawan
        $defaultPassword = Hash::make('password');

        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            $oldUserId = $data['user_id'] ?? null;
            $name = trim($data['name'] ?? '');
            $nip = trim($data['nip'] ?? '');

            if (!$oldUserId || !$name || isset($oldToNew[$oldUserId])) {
                continue;
            }

            // Check if user with same name already exists (Syahril)
            $existing = DB::table('users')->where('name', $name)->first();
            if ($existing) {
                $oldToNew[$oldUserId] = $existing->id;
                $skippedUsers++;
                continue;
            }

            // Create new user
            $newId = Str::ulid()->toString();
            $slugName = strtolower(preg_replace('/[^a-z0-9]+/i', '', $name));
            $email = $slugName . '@karyawan.absensi.local';
            $emailBase = $email;
            $counter = 1;
            while (DB::table('users')->where('email', $email)->exists()) {
                $email = $slugName . $counter . '@karyawan.absensi.local';
                $counter++;
            }

            $gender = in_array($name, ['NURUL AMALIA', 'Nabiilah Mardiyah Azis', 'Fadhila Reskyta Pratiwi', 'Miftahul Jannah', 'Jihan Aziizah Iqbal', 'Hijrah Amalia M', 'Andi fauziah', 'Mitchelle Virga Sumardian', 'Reski Anugrah Sari'])
                ? 'female' : 'male';

            DB::table('users')->insert([
                'id'                => $newId,
                'nip'               => $nip,
                'name'              => $name,
                'email'             => $email,
                'phone'             => '',
                'gender'            => $gender,
                'group'             => 'user',
                'password'          => $defaultPassword,
                'raw_password'      => 'password',
                'email_verified_at' => now(),
                'division_id'       => $divisionIds[array_rand($divisionIds)] ?? null,
                'education_id'      => $educationIds[array_rand($educationIds)] ?? null,
                'job_title_id'      => $jobTitleIds[array_rand($jobTitleIds)] ?? null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            $oldToNew[$oldUserId] = $newId;
            $createdUsers++;
        }

        $this->command?->info("Users created: {$createdUsers}, skipped (existing): {$skippedUsers}");

        // Insert attendance records
        $inserted = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            $oldUserId = $data['user_id'] ?? null;

            if (!$oldUserId || !isset($oldToNew[$oldUserId])) {
                $skipped++;
                continue;
            }

            $coords = explode(',', $data['coordinates'] ?? '-0,0');
            $lat = (float) trim($coords[0] ?? '0');
            $lng = (float) trim($coords[1] ?? '0');

            $statusMap = [
                'Hadir'     => 'present',
                'Terlambat' => 'late',
                'Izin'      => 'excused',
                'Sakit'     => 'sick',
                'Alpa'      => 'absent',
                'Tidak Lengkap' => 'incomplete',
            ];
            $rawStatus = trim($data['status'] ?? 'present');
            $dbStatus = $statusMap[$rawStatus] ?? $rawStatus;

            // Map shift name → DB shift_id
            $shiftName = trim($data['shift'] ?? '');
            $shiftId = 7; // default Senin-Kamis
            if (stripos($shiftName, 'jumat') !== false) {
                $shiftId = 8;
            }

            DB::table('attendances')->insert([
                'user_id'    => $oldToNew[$oldUserId],
                'barcode_id' => 1, // Balai Diklat
                'date'       => $data['date'] ?? null,
                'time_in'    => $data['time_in'] ?? null,
                'time_out'   => $data['time_out'] ?: null,
                'shift_id'   => $shiftId,
                'latitude'   => $lat,
                'longitude'  => $lng,
                'status'     => $dbStatus,
                'note'       => null,
                'attachment' => null,
                'created_at' => $data['created_at'] ?? now(),
                'updated_at' => $data['updated_at'] ?? now(),
            ]);

            $inserted++;
        }

        $this->command?->info("Attendances inserted: {$inserted}, skipped: {$skipped}");
        $this->command?->info("Recovery complete. Total users: " . DB::table('users')->count());
    }
}
