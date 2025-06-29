<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity', 'location'];

    public function reservations()
    {
        return $this->hasMany(RoomReservation::class, 'room_id');
    }
}
