<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'librarian_id',
        'book_id',       // Foreign key for books
        'cd_id',         // Foreign key for CDs
        'newspaper_id',  // Foreign key for newspapers
        'journal_id',    // Foreign key for journals
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function librarian()
    {
        return $this->belongsTo(Librarian::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function cd()
    {
        return $this->belongsTo(CD::class);
    }

    public function newspaper()
    {
        return $this->belongsTo(Newspaper::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
