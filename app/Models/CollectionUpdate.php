<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'librarian_id',
        'book_id',       // Foreign key for books
        'cd_id',         // Foreign key for CDs
        'newspaper_id',  // Foreign key for newspapers
        'journal_id',    // Foreign key for journals
        'action',
        'status',
    ];

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
