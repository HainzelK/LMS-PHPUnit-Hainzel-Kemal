<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Lecturer;
use App\Models\Borrow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LecturerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the index method returns a view with books for lecturers.
     *
     * @return void
     */
    public function test_index_returns_books_for_lecturer()
    {
        // Create a lecturer and some books
        $lecturer = Lecturer::factory()->create();
        $books = Book::factory()->count(5)->create();

        // Act as the lecturer
        Auth::login($lecturer->user);

        // Perform GET request
        $response = $this->get(route('lecturers.index'));

        // Assert that the response is OK and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('lecturers.index');
        $response->assertViewHas('books');
    }

    /**
     * Test that a lecturer can borrow a book for 3 days.
     *
     * @return void
     */
    public function test_borrow_book_for_3_days()
    {
        // Create a lecturer and a book
        $lecturer = Lecturer::factory()->create();
        $book = Book::factory()->create();

        // Act as the lecturer
        Auth::login($lecturer->user);

        // Prepare the data to borrow the book
        $data = [
            'book_id' => $book->id,
        ];

        // Perform POST request to borrow the book
        $response = $this->post(route('lecturers.borrow'), $data);

        // Assert the response redirects to the index page
        $response->assertRedirect(route('lecturers.index'));

        // Assert that the borrow record is saved in the database
        $this->assertDatabaseHas('borrows', [
            'lecturer_id' => $lecturer->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(3)->toDateString(),
        ]);

        // Assert that a success message is returned
        $response->assertSessionHas('success', 'Book borrowed successfully for 3 days.');
    }

    /**
     * Test validation error when trying to borrow a non-existent book.
     *
     * @return void
     */
    public function test_borrow_invalid_book()
    {
        // Create a lecturer
        $lecturer = Lecturer::factory()->create();

        // Act as the lecturer
        Auth::login($lecturer->user);

        // Prepare invalid data (non-existent book ID)
        $data = [
            'book_id' => 999,  // Invalid book ID
        ];

        // Perform POST request to borrow the book
        $response = $this->post(route('lecturers.borrow'), $data);

        // Assert validation error response
        $response->assertSessionHasErrors('book_id');
    }
}
