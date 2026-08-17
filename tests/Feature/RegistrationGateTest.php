<?php

use App\Models\User;
use App\Support\Settings;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    Settings::set('registration.enabled', '1');
});

test('registration is blocked with an error when disabled', function () {
    Settings::set('registration.enabled', '0');

    $count = User::count();

    $response = $this->post('/register', [
        'name' => 'Karyawan Baru',
        'nip' => '12345',
        'email' => 'baru@example.com',
        'phone' => '081234567890',
        'gender' => 'male',
        'address' => 'Jl. Test No. 1',
        'city' => 'Makassar',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('name');
    expect(User::count())->toBe($count);
    expect(User::where('email', 'baru@example.com')->exists())->toBeFalse();
});

test('registration succeeds when enabled', function () {
    Settings::set('registration.enabled', '1');

    $this->post('/register', [
        'name' => 'Karyawan Baru',
        'nip' => '12346',
        'email' => 'baru2@example.com',
        'phone' => '081234567891',
        'gender' => 'male',
        'address' => 'Jl. Test No. 2',
        'city' => 'Makassar',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'baru2@example.com')->first();
    expect($user)->not->toBeNull();
    expect(Hash::check('password', $user->password))->toBeTrue();
});