<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateConversationAPIRequest;
use App\Http\Requests\API\UpdateConversationAPIRequest;
use App\Models\Conversation;
use App\Repositories\ConversationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class ConversationController
 * @package App\Http\Controllers\API
 */

class ConversationAPIController extends AppBaseController
{
    /** @var  ConversationRepository */
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->conversationRepository = $conversationRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/conversations",
     *      summary="Get a listing of the Conversations.",
     *      tags={"Conversation"},
     *      description="Get all Conversations",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Conversation")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $conversations = $this->conversationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            $request->perPage,
            true
        );
        $conversations = $conversations->user($request->user)->guestHost($request->guest_id, $request->host_id)
            ->paginate($request->perPage);

        return $this->sendResponse($conversations->toArray(), 'Conversations retrieved successfully');
    }

    /**
     * @param CreateConversationAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/conversations",
     *      summary="Store a newly created Conversation in storage",
     *      tags={"Conversation"},
     *      description="Store Conversation",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Conversation that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Conversation")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Conversation"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateConversationAPIRequest $request)
    {
        $input = $request->all();

        $conversation = $this->conversationRepository->create($input);

        return $this->sendResponse($conversation->toArray(), 'Conversation saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/conversations/{id}",
     *      summary="Display the specified Conversation",
     *      tags={"Conversation"},
     *      description="Get Conversation",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Conversation",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Conversation"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (empty($conversation)) {
            return $this->sendError('Conversation not found');
        }

        return $this->sendResponse($conversation->toArray(), 'Conversation retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateConversationAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/conversations/{id}",
     *      summary="Update the specified Conversation in storage",
     *      tags={"Conversation"},
     *      description="Update Conversation",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Conversation",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Conversation that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Conversation")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Conversation"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateConversationAPIRequest $request)
    {
        $input = $request->all();

        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (empty($conversation)) {
            return $this->sendError('Conversation not found');
        }

        $conversation = $this->conversationRepository->update($input, $id);

        return $this->sendResponse($conversation->toArray(), 'Conversation updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/conversations/{id}",
     *      summary="Remove the specified Conversation from storage",
     *      tags={"Conversation"},
     *      description="Delete Conversation",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Conversation",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (empty($conversation)) {
            return $this->sendError('Conversation not found');
        }

        $conversation->delete();

        return $this->sendSuccess('Conversation deleted successfully');
    }
}
