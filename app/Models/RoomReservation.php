<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReservation extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'user_id', 'title', 'description', 'start_time', 'end_time'];

    public function room()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'reservation_attendees', 'reservation_id', 'user_id');
    }
}
