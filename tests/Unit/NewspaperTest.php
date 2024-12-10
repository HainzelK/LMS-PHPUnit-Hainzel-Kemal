<?php

namespace Tests\Unit;

use App\Models\Newspaper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class NewspaperTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the index method returns a list of newspapers.
     *
     * @return void
     */
    public function test_index_returns_list_of_newspapers()
    {
        // Create 3 newspapers
        $newspapers = Newspaper::factory()->count(3)->create();

        // Perform GET request to the index page
        $response = $this->get(route('newspapers.index'));

        // Assert that the response is OK and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('newspapers.index');
        $response->assertViewHas('newspapers');
        $response->assertViewHas('newspapers', $newspapers);
    }

    /**
     * Test the creation of a new newspaper.
     *
     * @return void
     */
    public function test_create_newspaper()
    {
        // Perform GET request to the create page
        $response = $this->get(route('newspapers.create'));

        // Assert that the response is OK and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('newspapers.create');
    }

    /**
     * Test that storing a new newspaper works.
     *
     * @return void
     */
    public function test_store_newspaper()
    {
        // Prepare valid data
        $data = [
            'title' => 'Test Newspaper',
            'publisher' => 'Test Publisher',
            'publication_date' => '2024-12-01',
            'edition' => 'First Edition',
            'copies' => 10,
        ];

        // Perform POST request to store the newspaper
        $response = $this->post(route('newspapers.store'), $data);

        // Assert the response redirects to the index page
        $response->assertRedirect(route('newspapers.index'));

        // Assert that the newspaper has been added to the database
        $this->assertDatabaseHas('newspapers', [
            'title' => 'Test Newspaper',
            'publisher' => 'Test Publisher',
            'publication_date' => '2024-12-01',
            'edition' => 'First Edition',
            'copies' => 10,
        ]);

        // Assert that a success message is returned
        $response->assertSessionHas('success', 'Newspaper created successfully!');
    }

    /**
     * Test that showing a single newspaper works.
     *
     * @return void
     */
    public function test_show_newspaper()
    {
        // Create a newspaper
        $newspaper = Newspaper::factory()->create();

        // Perform GET request to view the newspaper
        $response = $this->get(route('newspapers.show', $newspaper->id));

        // Assert that the response is OK and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('newspapers.show');
        $response->assertViewHas('newspaper', $newspaper);
    }

    /**
     * Test the editing of a newspaper.
     *
     * @return void
     */
    public function test_edit_newspaper()
    {
        // Create a newspaper
        $newspaper = Newspaper::factory()->create();

        // Perform GET request to edit the newspaper
        $response = $this->get(route('newspapers.edit', $newspaper->id));

        // Assert that the response is OK and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('newspapers.edit');
        $response->assertViewHas('newspaper', $newspaper);
    }

    /**
     * Test that updating a newspaper works.
     *
     * @return void
     */
    public function test_update_newspaper()
    {
        // Create a newspaper
        $newspaper = Newspaper::factory()->create();

        // Prepare valid data to update the newspaper
        $data = [
            'title' => 'Updated Newspaper',
            'publisher' => 'Updated Publisher',
            'publication_date' => '2024-12-02',
            'edition' => 'Updated Edition',
            'copies' => 20,
        ];

        // Perform PUT request to update the newspaper
        $response = $this->put(route('newspapers.update', $newspaper->id), $data);

        // Assert the response redirects to the index page
        $response->assertRedirect(route('newspapers.index'));

        // Assert that the newspaper has been updated in the database
        $this->assertDatabaseHas('newspapers', [
            'title' => 'Updated Newspaper',
            'publisher' => 'Updated Publisher',
            'publication_date' => '2024-12-02',
            'edition' => 'Updated Edition',
            'copies' => 20,
        ]);

        // Assert that a success message is returned
        $response->assertSessionHas('success', 'Newspaper updated successfully!');
    }

    /**
     * Test that deleting a newspaper works.
     *
     * @return void
     */
    public function test_destroy_newspaper()
    {
        // Create a newspaper
        $newspaper = Newspaper::factory()->create();

        // Perform DELETE request to remove the newspaper
        $response = $this->delete(route('newspapers.destroy', $newspaper->id));

        // Assert the response redirects to the index page
        $response->assertRedirect(route('newspapers.index'));

        // Assert that the newspaper has been deleted from the database
        $this->assertDatabaseMissing('newspapers', [
            'id' => $newspaper->id,
        ]);

        // Assert that a success message is returned
        $response->assertSessionHas('success', 'Newspaper deleted successfully!');
    }

    /**
     * Test validation errors when storing a newspaper with invalid data.
     *
     * @return void
     */
    public function test_store_newspaper_validation()
    {
        // Prepare invalid data (title is too short)
        $data = [
            'title' => 'Test',
            'publisher' => 'Test Publisher',
            'publication_date' => '2024-12-01',
            'edition' => 'First Edition',
            'copies' => 10,
        ];

        // Perform POST request to store the newspaper
        $response = $this->post(route('newspapers.store'), $data);

        // Assert that validation errors are returned for the title
        $response->assertSessionHasErrors('title');
    }
}
