<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Librarian extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collectionUpdates()
    {
        return $this->hasMany(CollectionUpdate::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
