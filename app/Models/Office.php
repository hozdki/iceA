<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'name',
        'city',
        'address',
        'capacity',
        'daily_rate',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
