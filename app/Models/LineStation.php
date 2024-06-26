<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineStation extends Model
{
  
    use HasFactory;
    protected $guarded = [];
    public function line()
    {
        return $this->belongsTo(Line::class);
    }
}
