<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAttendee extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'reservation_attendees';

    protected $fillable = ['reservation_id', 'user_id'];
}
