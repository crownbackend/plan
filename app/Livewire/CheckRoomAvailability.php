<?php

namespace App\Livewire;

use App\Models\MeetingRoom;
use App\Models\RoomReservation;
use Livewire\Component;
use Carbon\Carbon;

class CheckRoomAvailability extends Component
{
    public $room_id;
    public $date;
    public $reservations = [];

    public function updatedRoomId()
    {
        $this->loadReservations();
    }

    public function updatedDate()
    {
        $this->loadReservations();
    }

    public function loadReservations()
    {
        if (!$this->room_id || !$this->date) {
            $this->reservations = [];
            return;
        }

        $this->reservations = RoomReservation::where('room_id', $this->room_id)
            ->whereDate('start_time', $this->date)
            ->orderBy('start_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.check-room-availability', [
            'rooms' => MeetingRoom::all(),
        ]);
    }
}

