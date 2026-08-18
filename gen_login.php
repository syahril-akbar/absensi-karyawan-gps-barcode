<?php
/** Generate login_karyawan.txt for all users */
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = \App\Models\User::where('group','user')->get();
$lines = [];
foreach ($users as $u) {
    $name = $u->name;
    $email = $u->email;
    $password = 'password'; // default password
    $jabatan = $u->jobTitle?->name ?? 'Staff';
    $pendidikan = $u->education?->name ?? 'S1';
    $lines[] = "Nama: $name\nEmail: $email\nPassword: $password\nJabatan: $jabatan\nPendidikan: $pendidikan\n---\n";
}

$content = implode("\n", $lines);
file_put_contents('login_karyawan.txt', $content);
echo "File login_karyawan.txt created with " . count($users) . " users.\n";