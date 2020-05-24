<?php namespace Tests\Repositories;

use App\Models\Favourite;
use App\Repositories\FavouritesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FavouritesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FavouritesRepository
     */
    protected $favouritesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->favouritesRepo = \App::make(FavouritesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_favourites()
    {
        $favourites = factory(Favourite::class)->make()->toArray();

        $createdFavourites = $this->favouritesRepo->create($favourites);

        $createdFavourites = $createdFavourites->toArray();
        $this->assertArrayHasKey('id', $createdFavourites);
        $this->assertNotNull($createdFavourites['id'], 'Created Favourite must have id specified');
        $this->assertNotNull(Favourite::find($createdFavourites['id']), 'Favourite with given id must be in DB');
        $this->assertModelData($favourites, $createdFavourites);
    }

    /**
     * @test read
     */
    public function test_read_favourites()
    {
        $favourites = factory(Favourite::class)->create();

        $dbFavourites = $this->favouritesRepo->find($favourites->id);

        $dbFavourites = $dbFavourites->toArray();
        $this->assertModelData($favourites->toArray(), $dbFavourites);
    }

    /**
     * @test update
     */
    public function test_update_favourites()
    {
        $favourites = factory(Favourite::class)->create();
        $fakeFavourites = factory(Favourite::class)->make()->toArray();

        $updatedFavourites = $this->favouritesRepo->update($fakeFavourites, $favourites->id);

        $this->assertModelData($fakeFavourites, $updatedFavourites->toArray());
        $dbFavourites = $this->favouritesRepo->find($favourites->id);
        $this->assertModelData($fakeFavourites, $dbFavourites->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_favourites()
    {
        $favourites = factory(Favourite::class)->create();

        $resp = $this->favouritesRepo->delete($favourites->id);

        $this->assertTrue($resp);
        $this->assertNull(Favourite::find($favourites->id), 'Favourite should not exist in DB');
    }
}
