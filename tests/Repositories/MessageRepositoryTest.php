<?php namespace Tests\Repositories;

use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MessageRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MessageRepository
     */
    protected $messageRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->messageRepo = \App::make(MessageRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_message()
    {
        $message = factory(Message::class)->make()->toArray();

        $createdMessage = $this->messageRepo->create($message);

        $createdMessage = $createdMessage->toArray();
        $this->assertArrayHasKey('id', $createdMessage);
        $this->assertNotNull($createdMessage['id'], 'Created Message must have id specified');
        $this->assertNotNull(Message::find($createdMessage['id']), 'Message with given id must be in DB');
        $this->assertModelData($message, $createdMessage);
    }

    /**
     * @test read
     */
    public function test_read_message()
    {
        $message = factory(Message::class)->create();

        $dbMessage = $this->messageRepo->find($message->id);

        $dbMessage = $dbMessage->toArray();
        $this->assertModelData($message->toArray(), $dbMessage);
    }

    /**
     * @test update
     */
    public function test_update_message()
    {
        $message = factory(Message::class)->create();
        $fakeMessage = factory(Message::class)->make()->toArray();

        $updatedMessage = $this->messageRepo->update($fakeMessage, $message->id);

        $this->assertModelData($fakeMessage, $updatedMessage->toArray());
        $dbMessage = $this->messageRepo->find($message->id);
        $this->assertModelData($fakeMessage, $dbMessage->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_message()
    {
        $message = factory(Message::class)->create();

        $resp = $this->messageRepo->delete($message->id);

        $this->assertTrue($resp);
        $this->assertNull(Message::find($message->id), 'Message should not exist in DB');
    }
}
