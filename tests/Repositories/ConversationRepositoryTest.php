<?php namespace Tests\Repositories;

use App\Models\Conversation;
use App\Repositories\ConversationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ConversationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->conversationRepo = \App::make(ConversationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_conversation()
    {
        $conversation = factory(Conversation::class)->make()->toArray();

        $createdConversation = $this->conversationRepo->create($conversation);

        $createdConversation = $createdConversation->toArray();
        $this->assertArrayHasKey('id', $createdConversation);
        $this->assertNotNull($createdConversation['id'], 'Created Conversation must have id specified');
        $this->assertNotNull(Conversation::find($createdConversation['id']), 'Conversation with given id must be in DB');
        $this->assertModelData($conversation, $createdConversation);
    }

    /**
     * @test read
     */
    public function test_read_conversation()
    {
        $conversation = factory(Conversation::class)->create();

        $dbConversation = $this->conversationRepo->find($conversation->id);

        $dbConversation = $dbConversation->toArray();
        $this->assertModelData($conversation->toArray(), $dbConversation);
    }

    /**
     * @test update
     */
    public function test_update_conversation()
    {
        $conversation = factory(Conversation::class)->create();
        $fakeConversation = factory(Conversation::class)->make()->toArray();

        $updatedConversation = $this->conversationRepo->update($fakeConversation, $conversation->id);

        $this->assertModelData($fakeConversation, $updatedConversation->toArray());
        $dbConversation = $this->conversationRepo->find($conversation->id);
        $this->assertModelData($fakeConversation, $dbConversation->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_conversation()
    {
        $conversation = factory(Conversation::class)->create();

        $resp = $this->conversationRepo->delete($conversation->id);

        $this->assertTrue($resp);
        $this->assertNull(Conversation::find($conversation->id), 'Conversation should not exist in DB');
    }
}
