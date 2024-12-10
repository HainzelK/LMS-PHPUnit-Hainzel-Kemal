<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the index method returns a list of books.
     *
     * @return void
     */
    public function test_index_returns_list_of_books()
    {
        // Create 3 books
        $books = Book::factory()->count(3)->create();

        // Perform GET request to the index page
        $response = $this->get(route('students.index'));

        // Assert that the response is OK and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('students.index');
        $response->assertViewHas('books');
        $response->assertViewHas('books', $books);
    }

    /**
     * Test the borrowing of a book for a valid number of days (1-4).
     *
     * @return void
     */
    public function test_borrow_book_for_valid_days()
    {
        // Create a student and a book
        $student = Student::factory()->create();
        $book = Book::factory()->create();

        // Simulate student login
        Auth::login($student->user);

        // Prepare valid data (borrow for 3 days)
        $data = [
            'book_id' => $book->id,
            'days' => 3,
        ];

        // Perform POST request to borrow the book
        $response = $this->post(route('students.borrow'), $data);

        // Assert the response redirects to the index page
        $response->assertRedirect(route('students.index'));

        // Assert that the borrow record is created in the database
        $this->assertDatabaseHas('borrows', [
            'student_id' => $student->id,
            'book_id' => $book->id,
            'borrowed_at' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
        ]);

        // Assert that a success message is returned
        $response->assertSessionHas('success', 'Book borrowed successfully for 3 days.');
    }

    /**
     * Test borrowing a book with an invalid number of days (e.g., more than 4).
     *
     * @return void
     */
    public function test_borrow_book_with_invalid_days()
    {
        // Create a student and a book
        $student = Student::factory()->create();
        $book = Book::factory()->create();

        // Simulate student login
        Auth::login($student->user);

        // Prepare invalid data (borrow for 5 days)
        $data = [
            'book_id' => $book->id,
            'days' => 5, // Invalid number of days
        ];

        // Perform POST request to borrow the book
        $response = $this->post(route('students.borrow'), $data);

        // Assert that the response is a redirect back due to validation errors
        $response->assertSessionHasErrors('days');
    }

    /**
     * Test borrowing a book with missing book_id.
     *
     * @return void
     */
    public function test_borrow_book_missing_book_id()
    {
        // Create a student
        $student = Student::factory()->create();

        // Simulate student login
        Auth::login($student->user);

        // Prepare data with missing book_id
        $data = [
            'days' => 3,
        ];

        // Perform POST request to borrow the book
        $response = $this->post(route('students.borrow'), $data);

        // Assert that the response is a redirect back due to validation errors
        $response->assertSessionHasErrors('book_id');
    }

    /**
     * Test borrowing a book with missing days.
     *
     * @return void
     */
    public function test_borrow_book_missing_days()
    {
        // Create a student and a book
        $student = Student::factory()->create();
        $book = Book::factory()->create();

        // Simulate student login
        Auth::login($student->user);

        // Prepare data with missing days
        $data = [
            'book_id' => $book->id,
        ];

        // Perform POST request to borrow the book
        $response = $this->post(route('students.borrow'), $data);

        // Assert that the response is a redirect back due to validation errors
        $response->assertSessionHasErrors('days');
    }

    /**
     * Test borrowing a book when not logged in (unauthorized).
     *
     * @return void
     */
    public function test_borrow_book_not_logged_in()
    {
        // Create a book
        $book = Book::factory()->create();

        // Prepare valid data
        $data = [
            'book_id' => $book->id,
            'days' => 3,
        ];

        // Perform POST request to borrow the book without being logged in
        $response = $this->post(route('students.borrow'), $data);

        // Assert that the response redirects to the login page
        $response->assertRedirect(route('login'));
    }
}
