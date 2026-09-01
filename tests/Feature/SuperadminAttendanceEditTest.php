<?php

use App\Livewire\Admin\AttendanceComponent;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->employee = User::factory()->create(['group' => 'user']);
    $this->shift = Shift::factory()->create([
        'start_time' => '07:30:00',
        'end_time' => '16:00:00',
    ]);
    $this->attendance = Attendance::create([
        'user_id' => $this->employee->id,
        'shift_id' => $this->shift->id,
        'date' => '2026-09-01',
        'time_in' => '07:35:00',
        'time_out' => null,
        'status' => 'late',
        'note' => null,
    ]);
});

test('superadmin can update an attendance from the admin component', function () {
    $superadmin = User::factory()->admin(superadmin: true)->create();

    Livewire::actingAs($superadmin)
        ->test(AttendanceComponent::class)
        ->call('editAttendance', $this->attendance->id)
        ->assertSet('editingAttendance', true)
        ->assertSet('attendanceTimeIn', '07:35')
        ->set('attendanceTimeOut', '16:04')
        ->set('attendanceStatus', 'late')
        ->set('attendanceNote', 'Diperbaiki oleh superadmin')
        ->call('updateAttendance')
        ->assertHasNoErrors()
        ->assertSet('editingAttendance', false);

    $this->attendance->refresh();

    expect($this->attendance->time_out->format('H:i:s'))->toBe('16:04:00')
        ->and($this->attendance->status)->toBe('late')
        ->and($this->attendance->note)->toBe('Diperbaiki oleh superadmin');
});

test('regular admin cannot open attendance editor', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(AttendanceComponent::class)
        ->call('editAttendance', $this->attendance->id)
        ->assertForbidden();
});

test('regular admin cannot submit attendance changes', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(AttendanceComponent::class)
        ->set('editingAttendanceId', $this->attendance->id)
        ->set('attendanceTimeIn', '07:35')
        ->set('attendanceTimeOut', '16:05')
        ->set('attendanceStatus', 'late')
        ->call('updateAttendance')
        ->assertForbidden();

    expect($this->attendance->fresh()->time_out)->toBeNull();
});

test('attendance editor validates time status and note', function () {
    $superadmin = User::factory()->admin(superadmin: true)->create();

    Livewire::actingAs($superadmin)
        ->test(AttendanceComponent::class)
        ->call('editAttendance', $this->attendance->id)
        ->set('attendanceTimeIn', '25:61:00')
        ->set('attendanceTimeOut', 'invalid')
        ->set('attendanceStatus', 'unknown')
        ->set('attendanceNote', str_repeat('x', 256))
        ->call('updateAttendance')
        ->assertHasErrors([
            'attendanceTimeIn',
            'attendanceTimeOut',
            'attendanceStatus',
            'attendanceNote',
        ]);
});
