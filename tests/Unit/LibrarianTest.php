<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\CD;
use App\Models\Newspaper;
use App\Models\Journal;
use App\Models\CollectionUpdate;
use App\Models\Reservation;
use App\Models\Librarian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LibrarianTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the index method returns the library inventory.
     *
     * @return void
     */
    public function test_index_returns_inventory_for_librarian()
    {
        // Create a librarian and inventory items
        $librarian = Librarian::factory()->create();
        $books = Book::factory()->count(3)->create();
        $cds = CD::factory()->count(3)->create();
        $newspapers = Newspaper::factory()->count(3)->create();
        $journals = Journal::factory()->count(3)->create();

        // Act as the librarian
        Auth::login($librarian->user);

        // Perform GET request to the inventory page
        $response = $this->get(route('librarian.inventory.index'));

        // Assert that the response is OK and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('librarian.inventory.index');
        $response->assertViewHas('books');
        $response->assertViewHas('cds');
        $response->assertViewHas('newspapers');
        $response->assertViewHas('journals');
    }

    /**
     * Test that a collection update request is created.
     *
     * @return void
     */
    public function test_request_collection_update()
    {
        // Create a librarian, inventory items and request data
        $librarian = Librarian::factory()->create();
        $book = Book::factory()->create();
        $cd = CD::factory()->create();
        
        // Act as the librarian
        Auth::login($librarian->user);

        // Prepare the data for a collection update
        $data = [
            'book_id' => $book->id,
            'cd_id' => $cd->id,
            'action' => 'add',
        ];

        // Perform POST request to request a collection update
        $response = $this->post(route('librarian.requestCollectionUpdate'), $data);

        // Assert the response redirects to the collection updates page
        $response->assertRedirect(route('librarian.collection-updates.index'));

        // Assert that the collection update record is saved in the database
        $this->assertDatabaseHas('collection_updates', [
            'librarian_id' => $librarian->id,
            'book_id' => $book->id,
            'cd_id' => $cd->id,
            'action' => 'add',
        ]);

        // Assert that a success message is returned
        $response->assertSessionHas('success', 'Collection update requested.');
    }

    /**
     * Test that an invalid collection update request fails.
     *
     * @return void
     */
    public function test_invalid_collection_update_request()
    {
        // Create a librarian
        $librarian = Librarian::factory()->create();

        // Act as the librarian
        Auth::login($librarian->user);

        // Prepare invalid data (missing action)
        $data = [
            'book_id' => null,
            'cd_id' => null,
            'newspaper_id' => null,
            'journal_id' => null,
            'action' => 'invalid_action',  // Invalid action
        ];

        // Perform POST request to request a collection update
        $response = $this->post(route('librarian.requestCollectionUpdate'), $data);

        // Assert that the validation error message is shown
        $response->assertSessionHasErrors('action');
    }

    /**
     * Test that a librarian can update the status of a reservation.
     *
     * @return void
     */
    public function test_update_reservation_status()
    {
        // Create a librarian and a reservation
        $librarian = Librarian::factory()->create();
        $reservation = Reservation::factory()->create();

        // Act as the librarian
        Auth::login($librarian->user);

        // Prepare the data to update the reservation status
        $data = [
            'status' => 'approved',
        ];

        // Perform POST request to update the reservation status
        $response = $this->post(route('librarian.updateReservationStatus', $reservation->id), $data);

        // Assert the response redirects to the reservations page
        $response->assertRedirect(route('librarian.reservations.index'));

        // Assert that the reservation status is updated
        $reservation->refresh();
        $this->assertEquals('approved', $reservation->status);

        // Assert that a success message is returned
        $response->assertSessionHas('success', 'Reservation status updated successfully.');
    }

    /**
     * Test that an invalid status for a reservation update fails.
     *
     * @return void
     */
    public function test_invalid_reservation_status_update()
    {
        // Create a librarian and a reservation
        $librarian = Librarian::factory()->create();
        $reservation = Reservation::factory()->create();

        // Act as the librarian
        Auth::login($librarian->user);

        // Prepare invalid data (invalid status)
        $data = [
            'status' => 'invalid_status',  // Invalid status
        ];

        // Perform POST request to update the reservation status
        $response = $this->post(route('librarian.updateReservationStatus', $reservation->id), $data);

        // Assert that the validation error message is shown
        $response->assertSessionHasErrors('status');
    }
}
