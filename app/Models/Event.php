<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Dozvoljene kolone za masovno dodavanje
    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'price'
    ];

    // Relacija: jedan događaj može imati više tiketa/porudžbina
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
