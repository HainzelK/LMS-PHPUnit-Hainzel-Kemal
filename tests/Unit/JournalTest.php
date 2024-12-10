<?php

namespace Tests\Unit;

use App\Models\Journal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if journals index page loads correctly.
     *
     * @return void
     */
    public function test_index_returns_success()
    {
        // Create some journals
        Journal::factory()->count(5)->create();

        // Perform the GET request
        $response = $this->get(route('journals.index'));

        // Assert the response status is OK
        $response->assertStatus(200);
        $response->assertViewIs('journals.index');
        $response->assertViewHas('journals');
    }

    /**
     * Test if create journal page loads correctly.
     *
     * @return void
     */
    public function test_create_returns_success()
    {
        // Perform the GET request
        $response = $this->get(route('journals.create'));

        // Assert the response status is OK
        $response->assertStatus(200);
        $response->assertViewIs('journals.create');
    }

    /**
     * Test storing a new journal.
     *
     * @return void
     */
    public function test_store_creates_journal()
    {
        $data = [
            'title'             => 'Test Journal',
            'author'            => 'Test Author',
            'publisher'         => 'Test Publisher',
            'publication_date'  => '2024-12-10',
            'volume'            => '1',
            'issue'             => '1',
            'abstract'          => 'Test abstract',
            'restricted_access' => true,
        ];

        // Perform the POST request to store the journal
        $response = $this->post(route('journals.store'), $data);

        // Assert the response redirects to the index page
        $response->assertRedirect(route('journals.index'));

        // Assert the journal is stored in the database
        $this->assertDatabaseHas('journals', $data);
    }

    /**
     * Test showing a specific journal.
     *
     * @return void
     */
    public function test_show_returns_success()
    {
        // Create a journal
        $journal = Journal::factory()->create();

        // Perform the GET request to show the journal
        $response = $this->get(route('journals.show', $journal->id));

        // Assert the response status is OK
        $response->assertStatus(200);
        $response->assertViewIs('journals.show');
        $response->assertViewHas('journal');
    }

    /**
     * Test editing a specific journal.
     *
     * @return void
     */
    public function test_edit_returns_success()
    {
        // Create a journal
        $journal = Journal::factory()->create();

        // Perform the GET request to edit the journal
        $response = $this->get(route('journals.edit', $journal->id));

        // Assert the response status is OK
        $response->assertStatus(200);
        $response->assertViewIs('journals.edit');
        $response->assertViewHas('journal');
    }

    /**
     * Test updating a specific journal.
     *
     * @return void
     */
    public function test_update_updates_journal()
    {
        // Create a journal
        $journal = Journal::factory()->create();

        // New data for the journal
        $data = [
            'title'             => 'Updated Journal',
            'author'            => 'Updated Author',
            'publisher'         => 'Updated Publisher',
            'publication_date'  => '2024-12-11',
            'volume'            => '2',
            'issue'             => '2',
            'abstract'          => 'Updated abstract',
            'restricted_access' => false,
        ];

        // Perform the PUT request to update the journal
        $response = $this->put(route('journals.update', $journal->id), $data);

        // Assert the response redirects to the index page
        $response->assertRedirect(route('journals.index'));

        // Assert the journal is updated in the database
        $this->assertDatabaseHas('journals', $data);
    }

    /**
     * Test deleting a journal.
     *
     * @return void
     */
    public function test_destroy_deletes_journal()
    {
        // Create a journal
        $journal = Journal::factory()->create();

        // Perform the DELETE request to delete the journal
        $response = $this->delete(route('journals.destroy', $journal->id));

        // Assert the response redirects to the index page
        $response->assertRedirect(route('journals.index'));

        // Assert the journal is deleted from the database
        $this->assertDatabaseMissing('journals', ['id' => $journal->id]);
    }
}
