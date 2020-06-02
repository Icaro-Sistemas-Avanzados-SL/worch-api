<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Conversation;

class ConversationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_conversation()
    {
        $conversation = factory(Conversation::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/conversations', $conversation
        );

        $this->assertApiResponse($conversation);
    }

    /**
     * @test
     */
    public function test_read_conversation()
    {
        $conversation = factory(Conversation::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/conversations/'.$conversation->id
        );

        $this->assertApiResponse($conversation->toArray());
    }

    /**
     * @test
     */
    public function test_update_conversation()
    {
        $conversation = factory(Conversation::class)->create();
        $editedConversation = factory(Conversation::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/conversations/'.$conversation->id,
            $editedConversation
        );

        $this->assertApiResponse($editedConversation);
    }

    /**
     * @test
     */
    public function test_delete_conversation()
    {
        $conversation = factory(Conversation::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/conversations/'.$conversation->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/conversations/'.$conversation->id
        );

        $this->response->assertStatus(404);
    }
}
