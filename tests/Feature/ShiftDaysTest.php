<?php

use App\Livewire\Admin\MasterData\ShiftComponent;
use App\Models\Shift;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->admin = User::factory()->create(['group' => 'admin']);
});

test('admin can edit shift days and it is persisted as integers', function () {
    $shift = Shift::create([
        'name' => 'Shift Jumat',
        'start_time' => '08:00:00',
        'end_time' => '16:00:00',
        'days' => [],
    ]);

    actingAs($this->admin);

    Livewire::test(ShiftComponent::class)
        ->call('edit', $shift->id)
        ->set('form.days', ['5'])
        ->call('update')
        ->assertHasNoErrors();

    $this->assertSame([5], Shift::find($shift->id)->days);
});

test('creating a shift with days stores valid days', function () {
    actingAs($this->admin);

    Livewire::test(ShiftComponent::class)
        ->call('showCreating')
        ->set('form.name', 'Shift Pagi')
        ->set('form.start_time', '07:00')
        ->set('form.days', ['1', '2', '3', '4'])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('shifts', ['name' => 'Shift Pagi']);
    $shift = Shift::where('name', 'Shift Pagi')->first();
    $this->assertSame([1, 2, 3, 4], $shift->days);
});

test('toggleDay adds and removes a day from the form', function () {
    actingAs($this->admin);

    Livewire::test(ShiftComponent::class)
        ->call('showCreating')
        ->call('toggleDay', 5)
        ->assertSet('form.days', [5])
        ->call('toggleDay', 5)
        ->assertSet('form.days', [])
        ->call('toggleDay', 1)
        ->call('toggleDay', 5)
        ->assertSet('form.days', [1, 5]);
});

test('editing a shift renders the saved days as active pills', function () {
    $shift = Shift::create([
        'name' => 'Shift Jumat',
        'start_time' => '08:00:00',
        'end_time' => '16:00:00',
        'days' => [5],
    ]);

    actingAs($this->admin);

    $component = Livewire::test(ShiftComponent::class)
        ->call('edit', $shift->id);

    $component->assertSeeHtml('class="inline-flex cursor-pointer items-center rounded-full border px-3 py-1.5 text-sm font-medium transition border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300"');
});