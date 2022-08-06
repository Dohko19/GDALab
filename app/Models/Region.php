<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
