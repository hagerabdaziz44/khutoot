<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function bus_seats()
    {
        return $this->belongsTo(BusSeat::class, 'bus_seat_id');
    }

    public function buses()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
    public function linestations()
    {
        return $this->belongsTo(LineStation::class, 'line_station_id');

    }
    public function line()
    {
        return $this->belongsTo(Line::class);
    }
}
