<?php

namespace App\Livewire;

use App\Models\WorkLocation;
use Carbon\Carbon;
use Livewire\Component;

class AddToPlanning extends Component
{
    public $mode = 'recurrence';
    public $dates = [''];
    public $date_types = [];
    public $recurrence_days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
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

        return [
            'mode' => 'required|in:dates,recurrence',
            'recurrence_days' => 'array|min:1',
            'recurrence_start' => 'required|date',
            'recurrence_end' => 'required|date|after_or_equal:recurrence_start',
            'recurrence_types' => 'array',
        ];
    }

    public function mount()
    {
        $this->date_types = array_fill(0, count($this->dates), 'teletravail');
        $this->recurrence_start = Carbon::now()->toDateString();
    }

    public function addDateInput(): void
    {
        $this->dates[] = '';
        $this->date_types[] = 'teletravail';
    }

    public function removeDateInput($index): void
    {
        unset($this->dates[$index]);
        $this->dates = array_values($this->dates);

        unset($this->date_types[$index]);
        $this->date_types = array_values($this->date_types);
    }

    public function save(): void
    {
        $this->validate();

        if ($this->mode === 'dates') {
            $entries = $this->generateDateEntries();
            WorkLocation::insert($entries);
            session()->flash('success', 'Les dates ont été enregistrées avec succès.');
        } elseif ($this->mode === 'recurrence') {
            $entries = $this->generateRecurrenceEntries();
            WorkLocation::insert($entries);
            session()->flash('success', 'Les dates récurrentes ont été enregistrées avec succès.');
        }

    }


    public function generateDateEntries(): array
    {
        $entries = [];

        foreach ($this->dates as $index => $date) {
            $entries[] = [
                'user_id' => auth()->id(),
                'date' => Carbon::parse($date)->format('Y-m-d'),
                'location_type' => $this->date_types[$index] ?? 'teletravail',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $entries;
    }

    public function generateRecurrenceEntries(): array
    {
        $entries = [];

        $start = Carbon::parse($this->recurrence_start);
        $end = Carbon::parse($this->recurrence_end);

        while ($start->lte($end)) {
            $dayName = ucfirst($start->locale('fr')->dayName);

            if (in_array($dayName, $this->recurrence_days)) {
                $entries[] = [
                    'user_id' => auth()->id(), // ou $this->user_id si tu le passes en paramètre
                    'date' => $start->toDateString(),
                    'location_type' => $this->recurrence_types[$dayName] ?? 'teletravail',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $start->addDay();
        }

        return $entries;
    }

    public function render()
    {
        return view('livewire.add-to-planning');
    }
}
