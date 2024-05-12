<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function linestations()
    {
        return $this->hasMany(LineStation::class);
    }
  

    // Define an accessor method to calculate the difference in hours
    public function getTimeDifferenceAttribute()
    {
        $startTime = $this->start_time;
        $endTime = $this->end_time;

        // Convert time strings to Carbon instances
        $startDateTime = \Carbon\Carbon::createFromFormat('H:i:s', $startTime);
        $endDateTime = \Carbon\Carbon::createFromFormat('H:i:s', $endTime);

        // Calculate the difference in hours
        $difference = $startDateTime->diffInHours($endDateTime);

        return $difference;
    }

    protected $appends = ['time_difference']; // Specify the attribute to append to the JSON response


}
