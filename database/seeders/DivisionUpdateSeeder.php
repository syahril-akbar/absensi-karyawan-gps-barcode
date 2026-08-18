<?php
/**
 * Update division karyawan sesuai file s.id/iYoAk (divisi_karyawan.txt).
 *
 * Format file: "<nomor>. <Nama>, <gelar> - <Jabatan/Devisi>"
 * Mapping jabatan → division_id (dari tabel divisions).
 * Tidak mengubah job_title_id (sudah benar sebelumnya).
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('divisi_karyawan.txt');
        if (!file_exists($path)) {
            $this->command?->error("File divisi_karyawan.txt tidak ditemukan");
            return;
        }

        $divisions = DB::table('divisions')->get();

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        $updated = 0;
        $notFound = [];

        // Mapping jabatan (dari file) → nama division
        $mapping = [
            'arsiparis'            => 'Arsiparis',
            'programmer'           => 'Programmer',
            'web designer'         => 'Programmer',
            'networking'           => 'Programmer',
            'educational program'  => 'Educational Program',
            'drafter'              => 'Drafter',
            'revit'                => 'Drafter',
            'kehumasan'            => 'Kehumasan',
            'pemeliharaan'         => 'Pemeliharaan',
            'gedung'               => 'Pemeliharaan',
            'pipa'                 => 'Pemeliharaan',
        ];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            if (!preg_match('/^\d+\.\s*(.+?)\s*-\s*(.+)$/i', $line, $m)) {
                continue;
            }

            $fullName = trim($m[1]);
            $jobRaw = strtolower(trim($m[2]));

            // Nama: hapus gelar setelah koma
            $name = trim(explode(',', $fullName)[0]);
            $name = str_replace('⁠', '', $name); // zero-width char

            $user = DB::table('users')->where('group', 'user')->get()
                ->first(fn ($u) => strtolower(trim($u->name)) === strtolower($name));

            if (!$user) {
                $user = DB::table('users')->where('group', 'user')->get()
                    ->first(fn ($u) => stripos(trim($u->name), $name) !== false);
            }

            if (!$user) {
                $notFound[] = $name;
                continue;
            }

            // Cari division berdasarkan keyword jabatan
            $divisionName = null;
            foreach ($mapping as $keyword => $divName) {
                if (str_contains($jobRaw, $keyword)) {
                    $divisionName = $divName;
                    break;
                }
            }

            if (!$divisionName) {
                $divisionName = 'Divisi 2'; // fallback
            }

            $division = $divisions->first(fn ($d) => strtolower($d->name) === strtolower($divisionName));
            if (!$division) {
                $notFound[] = $name . " (div not found: {$divisionName})";
                continue;
            }

            DB::table('users')->where('id', $user->id)->update([
                'division_id' => $division->id,
            ]);
            $updated++;
            $this->command?->info("  {$name} → div={$division->id} ({$division->name})");
        }

        $this->command?->info("Updated: {$updated}");
        if ($notFound) {
            $this->command?->warn("Not found: " . implode(', ', $notFound));
        }
    }
}
