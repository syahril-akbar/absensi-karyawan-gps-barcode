<?php

namespace App\Livewire\Admin\MasterData;

use App\Livewire\Forms\HolidayForm;
use App\Models\Holiday;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class HolidayComponent extends Component
{
    use InteractsWithBanner;
    use WithPagination;

    public HolidayForm $form;
    public $deleteName = null;
    public $creating = false;
    public $editing = false;
    public $confirmingDeletion = false;
    public $selectedId = null;

    public function showCreating()
    {
        $this->form->resetErrorBag();
        $this->form->reset();
        $this->creating = true;
    }

    public function create()
    {
        $this->form->store();
        $this->creating = false;
        $this->banner(__('Created successfully.'));
    }

    public function edit($id)
    {
        $this->form->resetErrorBag();
        $this->editing = true;
        /** @var Holiday $holiday */
        $holiday = Holiday::find($id);
        $this->form->setHoliday($holiday);
    }

    public function update()
    {
        $this->form->update();
        $this->editing = false;
        $this->banner(__('Updated successfully.'));
    }

    public function confirmDeletion($id, $name)
    {
        $this->deleteName = $name;
        $this->confirmingDeletion = true;
        $this->selectedId = $id;
    }

    public function delete()
    {
        Holiday::find($this->selectedId)->delete();
        $this->confirmingDeletion = false;
        $this->selectedId = null;
        $this->deleteName = null;
        $this->banner(__('Deleted successfully.'));
    }

    public function render()
    {
        $holidays = Holiday::orderBy('date')->paginate(10);
        return view('livewire.admin.master-data.holiday', ['holidays' => $holidays]);
    }
}