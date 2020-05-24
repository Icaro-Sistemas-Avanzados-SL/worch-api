<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Follower;

class FollowerApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_follower()
    {
        $follower = factory(Follower::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/followers', $follower
        );

        $this->assertApiResponse($follower);
    }

    /**
     * @test
     */
    public function test_read_follower()
    {
        $follower = factory(Follower::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/followers/'.$follower->id
        );

        $this->assertApiResponse($follower->toArray());
    }

    /**
     * @test
     */
    public function test_update_follower()
    {
        $follower = factory(Follower::class)->create();
        $editedFollower = factory(Follower::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/followers/'.$follower->id,
            $editedFollower
        );

        $this->assertApiResponse($editedFollower);
    }

    /**
     * @test
     */
    public function test_delete_follower()
    {
        $follower = factory(Follower::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/followers/'.$follower->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/followers/'.$follower->id
        );

        $this->response->assertStatus(404);
    }
}
