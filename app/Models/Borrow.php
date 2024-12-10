<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'student_id', 'lecturer_id', 'borrowed_at', 'due_date'];

    /**
     * Get the book that was borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the student who borrowed the book (for students).
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the lecturer who borrowed the book (for lecturers).
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
