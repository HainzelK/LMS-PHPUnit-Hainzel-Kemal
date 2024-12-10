<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CD extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'genre',
        'release_date',
        'copies',
    ];

    public function collectionUpdates()
    {
        return $this->hasMany(CollectionUpdate::class, 'cd_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'cd_id');
    }
}
