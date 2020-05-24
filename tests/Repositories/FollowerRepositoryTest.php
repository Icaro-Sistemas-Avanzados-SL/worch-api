<?php namespace Tests\Repositories;

use App\Models\Follower;
use App\Repositories\FollowerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FollowerRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FollowerRepository
     */
    protected $followerRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->followerRepo = \App::make(FollowerRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_follower()
    {
        $follower = factory(Follower::class)->make()->toArray();

        $createdFollower = $this->followerRepo->create($follower);

        $createdFollower = $createdFollower->toArray();
        $this->assertArrayHasKey('id', $createdFollower);
        $this->assertNotNull($createdFollower['id'], 'Created Follower must have id specified');
        $this->assertNotNull(Follower::find($createdFollower['id']), 'Follower with given id must be in DB');
        $this->assertModelData($follower, $createdFollower);
    }

    /**
     * @test read
     */
    public function test_read_follower()
    {
        $follower = factory(Follower::class)->create();

        $dbFollower = $this->followerRepo->find($follower->id);

        $dbFollower = $dbFollower->toArray();
        $this->assertModelData($follower->toArray(), $dbFollower);
    }

    /**
     * @test update
     */
    public function test_update_follower()
    {
        $follower = factory(Follower::class)->create();
        $fakeFollower = factory(Follower::class)->make()->toArray();

        $updatedFollower = $this->followerRepo->update($fakeFollower, $follower->id);

        $this->assertModelData($fakeFollower, $updatedFollower->toArray());
        $dbFollower = $this->followerRepo->find($follower->id);
        $this->assertModelData($fakeFollower, $dbFollower->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_follower()
    {
        $follower = factory(Follower::class)->create();

        $resp = $this->followerRepo->delete($follower->id);

        $this->assertTrue($resp);
        $this->assertNull(Follower::find($follower->id), 'Follower should not exist in DB');
    }
}
