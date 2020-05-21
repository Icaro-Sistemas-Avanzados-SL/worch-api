<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChallengeAPIRequest;
use App\Http\Requests\API\UpdateChallengeAPIRequest;
use App\Models\Challenge;
use App\Repositories\ChallengeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Response;

/**
 * Class ChallengeController
 * @package App\Http\Controllers\API
 */

class ChallengeAPIController extends AppBaseController
{
    /** @var  ChallengeRepository */
    private $challengeRepository;

    public function __construct(ChallengeRepository $challengeRepo)
    {
        $this->challengeRepository = $challengeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/challenges",
     *      summary="Get a listing of the Challenges.",
     *      tags={"Challenge"},
     *      description="Get all Challenges",
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
     *                  @SWG\Items(ref="#/definitions/Challenge")
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
        $challenges = $this->challengeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            $request->perPage
        );

        return $this->sendResponse($challenges->toArray(), 'Challenges retrieved successfully');
    }

    /**
     * @param CreateChallengeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/challenges",
     *      summary="Store a newly created Challenge in storage",
     *      tags={"Challenge"},
     *      description="Store Challenge",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Challenge that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Challenge")
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
     *                  ref="#/definitions/Challenge"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateChallengeAPIRequest $request)
    {
        $input = $request->all();
        $input['slug'] =  Str::slug($input['title'] , '-');
        if(Challenge::where('slug', $input['slug'])->first()) {
            $input['slug'] = $input['slug'].'-2';
        }
        if(!empty($input['file'])) {
            $imageName = $input['file']['name'];
            $data = $input['file']['base64'];
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            Storage::disk('public')->put('challenges/'. $input['slug'].'/'.$imageName, base64_decode($data));
            $input['file'] =  'challenges/'. $input['slug'].'/'.$imageName;
        }
        $challenge = $this->challengeRepository->create($input);

        return $this->sendResponse($challenge->toArray(), 'Challenge saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/challenges/{id}",
     *      summary="Display the specified Challenge",
     *      tags={"Challenge"},
     *      description="Get Challenge",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Challenge",
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
     *                  ref="#/definitions/Challenge"
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
        /** @var Challenge $challenge */
        $challenge = $this->challengeRepository->find($id);

        if (empty($challenge)) {
            return $this->sendError('Challenge not found');
        }

        return $this->sendResponse($challenge->toArray(), 'Challenge retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateChallengeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/challenges/{id}",
     *      summary="Update the specified Challenge in storage",
     *      tags={"Challenge"},
     *      description="Update Challenge",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Challenge",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Challenge that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Challenge")
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
     *                  ref="#/definitions/Challenge"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateChallengeAPIRequest $request)
    {
        $input = $request->all();

        /** @var Challenge $challenge */
        $challenge = $this->challengeRepository->find($id);

        if (empty($challenge)) {
            return $this->sendError('Challenge not found');
        }

        $challenge = $this->challengeRepository->update($input, $id);

        return $this->sendResponse($challenge->toArray(), 'Challenge updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/challenges/{id}",
     *      summary="Remove the specified Challenge from storage",
     *      tags={"Challenge"},
     *      description="Delete Challenge",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Challenge",
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
        /** @var Challenge $challenge */
        $challenge = $this->challengeRepository->find($id);

        if (empty($challenge)) {
            return $this->sendError('Challenge not found');
        }

        $challenge->delete();

        return $this->sendSuccess('Challenge deleted successfully');
    }
}
