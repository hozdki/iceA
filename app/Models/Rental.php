<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Rental extends Model
{
   protected $fillable = [
        'uid',
        'office_id',
        'start_date',   
        'end_date',
        'daily_rate',
        'base_fee',
    ];

    protected $appends = [
        'total_price',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
   // totalPrice = base_fee + dailyRate * napok száma
    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                $days = $this->start_date->diffInDays($this->end_date);
                return $this->base_fee + ($this->daily_rate * $days);
            },
        );
    }
    public function office()
    {

        return $this->belongsTo(Office::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
    
    /*{
        $startDate = \Carbon\Carbon::parse($this->start_date);
        $endDate = \Carbon\Carbon::parse($this->end_date);
        $days = $startDate->diffInDays($endDate);

        return ($days * $this->daily_rate) + $this->base_fee;
    }
    public function office()
    {

        return $this->belongsTo(Office::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }*/

