<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusSeat extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function seats()
    {
        return $this->belongsTo(Seat::class ,'seat_id');
    }

    public function buses()
    {
        return $this->belongsTo(Bus::class ,'bus_id');
    }
}
