<?php

namespace App\Http\Controllers\API;

use App\Events\ChallengeReplied;
use App\Http\Requests\API\CreateChallengeAPIRequest;
use App\Http\Requests\API\UpdateChallengeAPIRequest;
use App\Models\Challenge;
use App\Models\File;
use App\Models\Notification;
use App\Repositories\ChallengeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Response;
use Vimeo\Laravel\Facades\Vimeo;

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
            $request->perPage,
            true
        );
        $challenges = $challenges->near($request->lat, $request->lng)->followed($request->userId)->paginate($request->perPage);

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
        $challenge = $this->challengeRepository->create($input);
        $video = Vimeo::request($input['file']);
        $thumbnail = $video['body']['pictures']['sizes'][3]['link_with_play_button'];
        if(!empty($input['file'])) {
            $file = new File();
            $file->type = 'video';
            $file->url = $input['file'];
            $file->thumbnail = $thumbnail;
            $file->challenge_id = $challenge->id;
            $file->save();
        }


        if($challenge->parent_id) {
            broadcast(new ChallengeReplied('Han realizado tu reto '. $challenge->parent->title,
                'El usuario: '. $challenge->user->name . ' ha realizado tu reto', $challenge->parent->user))->toOthers();
            $notification = new Notification();
            $notification->message = 'El usuario: '. $challenge->user->name . '  ha realizado tu reto '. $challenge->parent->title;
            $notification->notificated_id = $challenge->parent->user->id;
            $notification->notification_user_id = $challenge->user->id;
            $notification->save();
        }
        $imageName = $input['slug'].'.'. $input['mime'];
        Storage::disk('public')->delete($imageName);
        return $this->sendResponse($challenge->toArray(), 'Challenge saved successfully');
    }

    public function uploadVideo(Request $request)
    {
        $input = $request->all();
        $input['slug'] =  Str::slug($input['title'] , '-');
        if(Challenge::where('slug', $input['slug'])->first()) {
            $input['slug'] = $input['slug'].'-2';
        }
        if(!empty($input['file'])) {
            $imageName = $input['slug'].'.'. $input['mime'];
            $data = $input['file'];
            $data = explode(',', $data)[1];
            Storage::disk('public')->put($imageName, base64_decode($data));
            return $this->sendResponse(Vimeo::upload(storage_path('app/public/'.$imageName), ['name' => $input['title']]), 'Video saved successfully');
        } else {
            return $this->sendError('Video cannot be upload');
        }



       // return $this->sendResponse($challenge->toArray(), 'Video saved successfully');
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
