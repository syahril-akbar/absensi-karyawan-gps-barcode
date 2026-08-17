<?php

namespace App\Livewire\Forms;

use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class HolidayForm extends Form
{
    public ?Holiday $holiday = null;

    public $date = null;
    public $name = '';

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                Rule::unique('holidays')->ignore($this->holiday)
            ],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function setHoliday(Holiday $holiday)
    {
        $this->holiday = $holiday;
        $this->date = $holiday->date->format('Y-m-d');
        $this->name = $holiday->name;
        return $this;
    }

    public function store()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $this->validate();
        Holiday::create($this->all());
        $this->reset();
    }

    public function update()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $this->validate();
        $this->holiday->update($this->all());
        $this->reset();
    }
}