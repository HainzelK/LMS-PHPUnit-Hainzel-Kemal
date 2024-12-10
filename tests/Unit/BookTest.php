<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\Book;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class BookTest extends TestCase
{
    /** @test */
    public function it_can_list_books()
    {
        // Mock Book model
        $books = Book::factory()->count(5)->make();

        // Mock paginate call
        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('latest->paginate')
            ->once()
            ->andReturn($books);

        // Instantiate the controller
        $controller = new BookController();

        // Call the index method
        $response = $controller->index();

        // Assert the view and data
        $this->assertEquals('books.index', $response->name());
        $this->assertArrayHasKey('books', $response->getData());
        $this->assertCount(5, $response->getData()['books']);
    }

    /** @test */
    public function it_can_store_a_book()
    {
        Storage::fake('public');

        // Arrange: Mock the request
        $request = Request::create('/books', 'POST', [
            'title' => 'Test Book',
            'type' => 'Fiction',
            'author' => 'John Doe',
            'publisher' => 'Test Publisher',
            'access_level' => 'public',
            'is_physical' => false,
            'publication_date' => now()->toDateString(),
        ]);
        $request->files->set('file', UploadedFile::fake()->create('test.pdf', 100));

        // Mock Book model
        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('create')->once()->andReturn(true);

        // Instantiate the controller
        $controller = new BookController();

        // Act: Call the store method
        $response = $controller->store($request);

        // Assert: Check the response
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('books.index'), $response->headers->get('Location'));
        Storage::disk('public')->assertExists('books/test.pdf');
    }

    /** @test */
    public function it_can_show_a_book()
    {
        // Mock Book model
        $book = Book::factory()->make();

        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('findOrFail')
            ->once()
            ->with($book->id)
            ->andReturn($book);

        // Instantiate the controller
        $controller = new BookController();

        // Act: Call the show method
        $response = $controller->show($book->id);

        // Assert: Check the response
        $this->assertEquals('books.show', $response->name());
        $this->assertEquals($book, $response->getData()['book']);
    }

    /** @test */
    public function it_can_delete_a_book()
    {
        Storage::fake('public');

        // Mock Book model
        $book = Book::factory()->make(['file_path' => 'test.pdf']);

        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('findOrFail')
            ->once()
            ->with($book->id)
            ->andReturn($book);
        $bookMock->shouldReceive('delete')->once()->andReturn(true);

        // Mock Storage deletion
        Storage::shouldReceive('delete')
            ->once()
            ->with('public/books/test.pdf')
            ->andReturn(true);

        // Instantiate the controller
        $controller = new BookController();

        // Act: Call the destroy method
        $response = $controller->destroy($book->id);

        // Assert: Check the response
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('books.index'), $response->headers->get('Location'));
    }
}
