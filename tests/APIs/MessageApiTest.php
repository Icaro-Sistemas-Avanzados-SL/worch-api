<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Message;

class MessageApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_message()
    {
        $message = factory(Message::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/messages', $message
        );

        $this->assertApiResponse($message);
    }

    /**
     * @test
     */
    public function test_read_message()
    {
        $message = factory(Message::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/messages/'.$message->id
        );

        $this->assertApiResponse($message->toArray());
    }

    /**
     * @test
     */
    public function test_update_message()
    {
        $message = factory(Message::class)->create();
        $editedMessage = factory(Message::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/messages/'.$message->id,
            $editedMessage
        );

        $this->assertApiResponse($editedMessage);
    }

    /**
     * @test
     */
    public function test_delete_message()
    {
        $message = factory(Message::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/messages/'.$message->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/messages/'.$message->id
        );

        $this->response->assertStatus(404);
    }
}
