<?php

namespace App\Http\Controllers\API;

use App\Events\UserFollowed;
use App\Http\Requests\API\CreateFollowerAPIRequest;
use App\Http\Requests\API\UpdateFollowerAPIRequest;
use App\Models\Follower;
use App\Repositories\FollowerRepository;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class FollowerController
 * @package App\Http\Controllers\API
 */

class FollowerAPIController extends AppBaseController
{
    /** @var  FollowerRepository */
    private $followerRepository;

    public function __construct(FollowerRepository $followerRepo)
    {
        $this->followerRepository = $followerRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/followers",
     *      summary="Get a listing of the Followers.",
     *      tags={"Follower"},
     *      description="Get all Followers",
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
     *                  @SWG\Items(ref="#/definitions/Follower")
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
        $followers = $this->followerRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            $request->perPage
        );

        return $this->sendResponse($followers->toArray(), 'Followers retrieved successfully');
    }

    /**
     * @param CreateFollowerAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/followers",
     *      summary="Store a newly created Follower in storage",
     *      tags={"Follower"},
     *      description="Store Follower",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Follower that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Follower")
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
     *                  ref="#/definitions/Follower"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFollowerAPIRequest $request)
    {
        $input = $request->all();

        $follower = $this->followerRepository->create($input);

        $user = User::find($follower->followed_id);
        $followerUser = User::find($follower->follower_id);

        broadcast(new UserFollowed('Nuevo seguidor',
            'El usuario: '. $followerUser->name . ' ha comenzado a seguirte', $user))->toOthers();

        return $this->sendResponse($follower->toArray(), 'Follower saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/followers/{id}",
     *      summary="Display the specified Follower",
     *      tags={"Follower"},
     *      description="Get Follower",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Follower",
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
     *                  ref="#/definitions/Follower"
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
        /** @var Follower $follower */
        $follower = $this->followerRepository->find($id);

        if (empty($follower)) {
            return $this->sendError('Follower not found');
        }

        return $this->sendResponse($follower->toArray(), 'Follower retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFollowerAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/followers/{id}",
     *      summary="Update the specified Follower in storage",
     *      tags={"Follower"},
     *      description="Update Follower",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Follower",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Follower that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Follower")
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
     *                  ref="#/definitions/Follower"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFollowerAPIRequest $request)
    {
        $input = $request->all();

        /** @var Follower $follower */
        $follower = $this->followerRepository->find($id);

        if (empty($follower)) {
            return $this->sendError('Follower not found');
        }

        $follower = $this->followerRepository->update($input, $id);

        return $this->sendResponse($follower->toArray(), 'Follower updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/followers/{id}",
     *      summary="Remove the specified Follower from storage",
     *      tags={"Follower"},
     *      description="Delete Follower",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Follower",
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
        /** @var Follower $follower */
        $follower = $this->followerRepository->find($id);

        if (empty($follower)) {
            return $this->sendError('Follower not found');
        }

        $follower->delete();

        return $this->sendSuccess('Follower deleted successfully');
    }
}
