<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'author',
        'publisher',
        'access_level',
        'is_physical',
        'file_path',
        'publication_date',
    ];

    public function collectionUpdates()
    {
        return $this->hasMany(CollectionUpdate::class, 'book_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'book_id');
    }
}
