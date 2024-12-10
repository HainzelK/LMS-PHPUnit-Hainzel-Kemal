<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Librarian;
use App\Models\Book;
use App\Models\CollectionUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_librarian()
    {
        // Arrange
        $userData = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'phone_number' => '1234567890',
        ];
        $librarianData = [
            'user_id' => 1, // Assume user ID is 1
            'name' => 'John Doe',
        ];

        // Create User first
        $user = User::create($userData);

        // Act
        $librarian = Librarian::create($librarianData);

        // Assert
        $this->assertInstanceOf(Librarian::class, $librarian);
        $this->assertEquals('John Doe', $librarian->name);
        $this->assertEquals($user->id, $librarian->user_id);
    }

    #[Test]
    public function it_can_delete_a_librarian()
    {
        // Arrange
        $userData = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'phone_number' => '1234567890',
        ];
        $librarianData = [
            'user_id' => 1, // Assume user ID is 1
            'name' => 'John Doe',
        ];

        $user = User::create($userData);
        $librarian = Librarian::create($librarianData);

        // Act
        $librarian->delete();

        // Assert
        $this->assertDatabaseMissing('librarians', ['id' => $librarian->id]);
    }

    #[Test]
    public function it_can_update_book_status()
    {
        // Arrange
        $bookData = [
            'title' => 'Sample Book',
            'author' => 'John Doe',
            'publisher' => 'Sample Publisher',
            'publication_year' => 2021,
            'type' => 'physical',
            'status' => 'unavailable', // Include status to avoid null
        ];

        $book = Book::create($bookData);

        // Act
        $book->update(['status' => 'available']);

        // Assert
        $this->assertEquals('available', $book->status);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'status' => 'available']);
    }

    #[Test]
    public function it_can_update_collection_status()
    {
        // Arrange
        $collectionData = [
            'status' => 'pending',
            'librarian_id' => 1, // Assume librarian ID is 1
            'action' => 'approved', // Ensure action is set to avoid null error
        ];

        $collectionUpdate = CollectionUpdate::create($collectionData);

        // Act
        $collectionUpdate->update(['status' => 'approved']);

        // Assert
        $this->assertEquals('approved', $collectionUpdate->status);
        $this->assertDatabaseHas('collection_updates', ['id' => $collectionUpdate->id, 'status' => 'approved']);
    }
}
