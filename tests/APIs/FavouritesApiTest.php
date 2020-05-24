<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Favourite;

class FavouritesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_favourites()
    {
        $favourites = factory(Favourite::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/favourites', $favourites
        );

        $this->assertApiResponse($favourites);
    }

    /**
     * @test
     */
    public function test_read_favourites()
    {
        $favourites = factory(Favourite::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/favourites/'.$favourites->id
        );

        $this->assertApiResponse($favourites->toArray());
    }

    /**
     * @test
     */
    public function test_update_favourites()
    {
        $favourites = factory(Favourite::class)->create();
        $editedFavourites = factory(Favourite::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/favourites/'.$favourites->id,
            $editedFavourites
        );

        $this->assertApiResponse($editedFavourites);
    }

    /**
     * @test
     */
    public function test_delete_favourites()
    {
        $favourites = factory(Favourite::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/favourites/'.$favourites->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/favourites/'.$favourites->id
        );

        $this->response->assertStatus(404);
    }
}
