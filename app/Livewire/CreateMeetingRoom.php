<?php

namespace App\Livewire;

use App\Models\MeetingRoom;
use Livewire\Component;

class CreateMeetingRoom extends Component
{
    public $name;
    public $capacity;
    public $location;

    protected $rules = [
        'name' => 'required|string|max:255',
        'capacity' => 'nullable|integer|min:1',
        'location' => 'nullable|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        MeetingRoom::create([
            'name' => $this->name,
            'capacity' => $this->capacity,
            'location' => $this->location,
        ]);

        session()->flash('success', 'Salle créée avec succès.');
        $this->reset(['name', 'capacity', 'location']);
    }

    public function render()
    {
        return view('livewire.create-meeting-room');
    }
}

