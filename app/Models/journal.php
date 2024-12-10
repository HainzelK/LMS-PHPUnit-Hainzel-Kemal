<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_date',
        'volume',
        'issue',
        'abstract',
        'restricted_access',
    ];

    public function collectionUpdates()
    {
        return $this->hasMany(CollectionUpdate::class, 'journal_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'journal_id');
    }
}
