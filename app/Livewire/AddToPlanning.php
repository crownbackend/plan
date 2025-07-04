<?php

namespace App\Livewire;

use Livewire\Component;

class AddToPlanning extends Component
{
    public $mode = 'recurrence';
    public $dates = [''];
    public $date_types = [];
    public $recurrence_days = [];
    public $recurrence_start;
    public $recurrence_end;
    public $recurrence_types = [];

    public function rules()
    {
        if ($this->mode === 'dates') {
            return [
                'mode' => 'required|in:dates,recurrence',
                'dates' => 'array|min:1',
                'dates.*' => 'required|date',
                'date_types' => 'array',
                'date_types.*' => 'required|in:teletravail,sur_site',
            ];
        }

        // mode récurrence
        return [
            'mode' => 'required|in:dates,recurrence',
            'recurrence_days' => 'array|min:1',
            'recurrence_start' => 'required|date',
            'recurrence_end' => 'required|date|after_or_equal:recurrence_start',
            'recurrence_types' => 'array',
            // Tu peux ajouter une validation custom pour exiger un type pour chaque jour coché
        ];
    }

    public function mount()
    {
        $this->date_types = array_fill(0, count($this->dates), 'teletravail');
    }

    public function addDateInput()
    {
        $this->dates[] = '';
        $this->date_types[] = 'teletravail';
    }

    public function removeDateInput($index)
    {
        unset($this->dates[$index]);
        $this->dates = array_values($this->dates);

        unset($this->date_types[$index]);
        $this->date_types = array_values($this->date_types);
    }

    public function save()
    {
        $this->validate();

        dump([
            'dates' => $this->dates,
            'date_types' => $this->date_types,
            'recurrence_days' => $this->recurrence_days,
            'recurrence_types' => $this->recurrence_types,
        ]);

    }

    public function render()
    {
        return view('livewire.add-to-planning');
    }
}
