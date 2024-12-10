<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newspaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'publisher',
        'publication_date',
        'edition',
        'copies',
    ];

    public function collectionUpdates()
    {
        return $this->hasMany(CollectionUpdate::class, 'newspaper_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'newspaper_id');
    }
}
