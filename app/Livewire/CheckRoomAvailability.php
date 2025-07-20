<?php

namespace App\Livewire;

use App\Models\MeetingRoom;
use App\Models\RoomReservation;
use Livewire\Component;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CheckRoomAvailability extends Component
{
    public $room_id = null;
    public $date;
    public $hour = null;
    public $reservations = [];

    public function mount()
    {
        $this->date = Carbon::now()->toDateString();
        $this->loadReservations();
    }

    public function updated($property)
    {
        if (in_array($property, ['room_id', 'date', 'hour'])) {
            $this->loadReservations();
        }
    }

    public function loadReservations()
    {
        $query = RoomReservation::with(['room', 'user'])
            ->whereDate('start_time', $this->date);

        if ($this->room_id) {
            $query->where('room_id', $this->room_id);
        }

        if ($this->hour) {
            $query->whereTime('start_time', '>=', $this->hour . ':00:00');
        }

        $this->reservations = $query->orderBy('start_time')->get();
    }

    public function exportCsv(): StreamedResponse
    {
        $filename = 'reservations_' . $this->date . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Salle', 'Titre', 'Réservé par', 'Heure début', 'Heure fin']);

            foreach ($this->reservations as $res) {
                fputcsv($handle, [
                    $res->room->name,
                    $res->title,
                    $res->user->name,
                    Carbon::parse($res->start_time)->format('H:i'),
                    Carbon::parse($res->end_time)->format('H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function render()
    {
        return view('livewire.check-room-availability', [
            'rooms' => MeetingRoom::all(),
        ]);
    }
}



