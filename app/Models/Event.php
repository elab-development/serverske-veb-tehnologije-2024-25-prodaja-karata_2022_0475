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
        'price',
        'category',
        'image'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Helper funkcija: vraća punu URL putanju do slike
    public function getImageUrlAttribute()
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }
}
