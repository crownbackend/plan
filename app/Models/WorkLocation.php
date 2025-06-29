<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLocation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'location_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
