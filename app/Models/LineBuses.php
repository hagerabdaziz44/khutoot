<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineBuses extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function buses()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
}
