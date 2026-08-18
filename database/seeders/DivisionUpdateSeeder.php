<?php
/**
 * Update division karyawan sesuai s.id/iYoAk (divisi_karyawan.txt).
 *
 * File format per baris: "N. <Nama>, <Gelar> - <Jabatan/Devisi>"
 * Contoh:
 *   "7. Miftahul Jannah, S.Pd -  Educational Program Officer"
 *   "10. Nabiilah Mardiyah Azis S.T- pemeliharaan gedung dan pipa"
 *
 * Mapping: jabatan (dari file, teks full) → division name (exact sesuai file).
 * Division dibuat otomatis bila belum ada. Job_title TIDAK disentuh.
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
            $this->command?->warn("File divisi_karyawan.txt tidak ada, lewati.");
            return;
        }

        // Exact division names, urut sesuai urutan karyawan pertama tiap devinsi
        $targetDivisions = [
            'Arsiparis',
            'Web Designer, Programmer, dan Networking',
            'Educational Program Officer',
            'Drafter CAD (Keteknikan Bangunan Gedung), Revit Designer',
            'pemeliharaan gedung dan pipa',
            'Kehumasan',
        ];

        // Ensure divisions exist
        foreach ($targetDivisions as $name) {
            $exists = DB::table('divisions')->where('name', $name)->first();
            if (!$exists) {
                DB::table('divisions')->insert(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
                $this->command?->info("  Created division: {$name}");
            }
        }

        // Build name → id lookup (bisa sekaligus division lokal id 2,3)
        $divMap = DB::table('divisions')->get()->keyBy('name')->mapWithKeys(fn ($d) => [strtolower($d->name) => $d->id])->toArray();
        $divRows = DB::table('divisions')->get();

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        $updated = 0;
        $notFound = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || !preg_match('/^\d+\.\s*(.+)$/i', $line, $m)) {
                continue;
            }

            $full = trim($m[1]);

            // Split name | devisi at `-` (hanya yang pertama)
            if (strpos($full, '-') === false) {
                continue;
            }
            [$namePart, $devisi] = explode('-', $full, 2);
            $name = trim($namePart);
            $devisi = trim($devisi);

            // Hapus gelar setelah koma, zero-width char
            $name = trim(explode(',', $name)[0]);
            $name = str_replace('⁠', '', $name);

            $user = DB::table('users')->where('group', 'user')->get()
                ->first(fn ($u) => strtolower(trim($u->name)) === strtolower($name));
            if (!$user) {
                $user = DB::table('users')->where('group', 'user')->get()
                    ->first(fn ($u) => stripos(trim($u->name), $name) !== false);
            }
            if (!$user) {
                $notFound[] = $name . " (no user)";
                continue;
            }

            // Cari division exact (case-insensitive). Kalau tidak ketemu → nearest keyword match
            $divisionId = $divMap[strtolower($devisi)] ?? null;

            if (!$divisionId) {
                // Fallback keyword match
                $devLower = strtolower($devisi);
                foreach ($divRows as $d) {
                    if (str_contains($devLower, strtolower($d->name)) || str_contains(strtolower($d->name), $devLower)) {
                        $divisionId = $d->id;
                        break;
                    }
                }
            }

            if (!$divisionId) {
                $notFound[] = $name . " (div: {$devisi})";
                continue;
            }

            DB::table('users')->where('id', $user->id)->update([
                'division_id' => $divisionId,
            ]);
            $updated++;
            $divName = $divRows->firstWhere('id', $divisionId)?->name ?? '?';
            $this->command?->info("  {$name} → div={$divisionId} ({$divName})");
        }

        $this->command?->info("Updated: {$updated}");
        if ($notFound) {
            $this->command?->warn("Not found: " . implode(', ', $notFound));
        }
    }
}
