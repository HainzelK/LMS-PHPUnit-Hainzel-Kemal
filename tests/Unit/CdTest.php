<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\CD;
use App\Http\Controllers\CDController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CdTest extends TestCase
{
    /** @test */
    public function it_can_list_cds()
    {
        // Mock CD model
        $cds = CD::factory()->count(5)->make();

        // Mock paginate call
        $cdMock = Mockery::mock('alias:' . CD::class);
        $cdMock->shouldReceive('latest->paginate')
            ->once()
            ->andReturn($cds);

        // Instantiate the controller
        $controller = new CDController();

        // Call the index method
        $response = $controller->index();

        // Assert the view and data
        $this->assertEquals('cds.index', $response->name());
        $this->assertArrayHasKey('cds', $response->getData());
        $this->assertCount(5, $response->getData()['cds']);
    }

    /** @test */
    public function it_can_store_a_cd()
    {
        // Arrange: Mock the request
        $request = Request::create('/cds', 'POST', [
            'title' => 'Greatest Hits',
            'artist' => 'Famous Band',
            'genre' => 'Rock',
            'release_date' => now()->toDateString(),
            'copies' => 5,
        ]);

        // Mock CD model
        $cdMock = Mockery::mock('alias:' . CD::class);
        $cdMock->shouldReceive('create')->once()->andReturn(true);

        // Instantiate the controller
        $controller = new CDController();

        // Act: Call the store method
        $response = $controller->store($request);

        // Assert: Check the response
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('cds.index'), $response->headers->get('Location'));
    }

    /** @test */
    public function it_can_show_a_cd()
    {
        // Mock CD model
        $cd = CD::factory()->make();

        $cdMock = Mockery::mock('alias:' . CD::class);
        $cdMock->shouldReceive('findOrFail')
            ->once()
            ->with($cd->id)
            ->andReturn($cd);

        // Instantiate the controller
        $controller = new CDController();

        // Act: Call the show method
        $response = $controller->show($cd->id);

        // Assert: Check the response
        $this->assertEquals('cds.show', $response->name());
        $this->assertEquals($cd, $response->getData()['cd']);
    }

    /** @test */
    public function it_can_update_a_cd()
    {
        // Arrange: Mock the request
        $request = Request::create('/cds/1', 'PUT', [
            'title' => 'Updated Title',
            'artist' => 'Updated Artist',
            'genre' => 'Pop',
            'release_date' => now()->toDateString(),
            'copies' => 10,
        ]);

        // Mock CD model
        $cd = CD::factory()->make();

        $cdMock = Mockery::mock('alias:' . CD::class);
        $cdMock->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($cd);
        $cdMock->shouldReceive('update')->once()->andReturn(true);

        // Instantiate the controller
        $controller = new CDController();

        // Act: Call the update method
        $response = $controller->update($request, 1);

        // Assert: Check the response
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('cds.index'), $response->headers->get('Location'));
    }

    /** @test */
    public function it_can_delete_a_cd()
    {
        // Mock CD model
        $cd = CD::factory()->make();

        $cdMock = Mockery::mock('alias:' . CD::class);
        $cdMock->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($cd);
        $cdMock->shouldReceive('delete')->once()->andReturn(true);

        // Instantiate the controller
        $controller = new CDController();

        // Act: Call the destroy method
        $response = $controller->destroy(1);

        // Assert: Check the response
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('cds.index'), $response->headers->get('Location'));
    }
}
