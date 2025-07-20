<?php

namespace App\Livewire;

use App\Models\MeetingRoom;
use App\Models\RoomReservation;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateRoomReservation extends Component
{
    public $room_id;
    public $title;
    public $description;
    public $start_time;
    public $end_time;
    public $attendees = [];

    public function rules()
    {
        return [
            'room_id' => 'required|exists:meeting_rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date|after:start_time',
            'attendees' => 'array',
            'attendees.*' => 'exists:users,id',
        ];
    }

    public function save()
    {
        $this->validate();

        $reservation = RoomReservation::create([
            'room_id' => $this->room_id,
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => Carbon::parse($this->start_time),
            'end_time' => Carbon::parse($this->end_time),
        ]);

        if (!empty($this->attendees)) {
            $reservation->attendees()->attach($this->attendees);
        }

        session()->flash('success', 'Réservation créée avec succès !');

        $this->reset(['room_id', 'title', 'description', 'start_time', 'end_time', 'attendees']);
    }

    public function render()
    {
        return view('livewire.create-room-reservation', [
            'rooms' => MeetingRoom::all(),
            'users' => User::where('id', '!=', auth()->id())->get(),
        ]);
    }
}

