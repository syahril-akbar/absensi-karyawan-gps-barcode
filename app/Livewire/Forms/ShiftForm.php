<?php

namespace App\Livewire\Forms;

use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ShiftForm extends Form
{
    public ?Shift $shift = null;

    public $name = '';
    public $start_time = null;
    public $end_time = null;
    public array $days = [];

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shifts')->ignore($this->shift)
            ],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'days' => ['array'],
            'days.*' => ['integer', 'min:1', 'max:7'],
        ];
    }

    public function setShift(Shift $shift)
    {
        $this->shift = $shift;
        $this->name = $shift->name;
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
        $this->days = array_map('intval', $shift->days ?? []);
        return $this;
    }

    protected function normalizeDays()
    {
        $this->days = array_values(array_map('intval', array_filter((array) $this->days, 'is_numeric')));
    }

    public function store()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $this->normalizeDays();
        $this->validate();
        Shift::create($this->all());
        $this->reset();
    }

    public function update()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $this->normalizeDays();
        $this->validate();
        $this->shift->update($this->all());
        $this->reset();
    }

    public function delete()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $this->shift->delete();
        $this->reset();
    }
}
