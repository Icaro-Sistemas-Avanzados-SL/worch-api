<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFavouritesAPIRequest;
use App\Http\Requests\API\UpdateFavouritesAPIRequest;
use App\Models\Favourite;
use App\Repositories\FavouritesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class FavouritesController
 * @package App\Http\Controllers\API
 */

class FavouritesAPIController extends AppBaseController
{
    /** @var  FavouritesRepository */
    private $favouritesRepository;

    public function __construct(FavouritesRepository $favouritesRepo)
    {
        $this->favouritesRepository = $favouritesRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/favourites",
     *      summary="Get a listing of the Favourite.",
     *      tags={"Favourite"},
     *      description="Get all Favourite",
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
     *                  @SWG\Items(ref="#/definitions/Favourite")
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
        $favourites = $this->favouritesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            $request->perPage
        );

        return $this->sendResponse($favourites->toArray(), 'Favourite retrieved successfully');
    }

    /**
     * @param CreateFavouritesAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/favourites",
     *      summary="Store a newly created Favourite in storage",
     *      tags={"Favourite"},
     *      description="Store Favourite",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Favourite that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Favourite")
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
     *                  ref="#/definitions/Favourite"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFavouritesAPIRequest $request)
    {
        $input = $request->all();

        $favourites = $this->favouritesRepository->create($input);

        return $this->sendResponse($favourites->toArray(), 'Favourite saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/favourites/{id}",
     *      summary="Display the specified Favourite",
     *      tags={"Favourite"},
     *      description="Get Favourite",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Favourite",
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
     *                  ref="#/definitions/Favourite"
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
        /** @var Favourite $favourites */
        $favourites = $this->favouritesRepository->find($id);

        if (empty($favourites)) {
            return $this->sendError('Favourite not found');
        }

        return $this->sendResponse($favourites->toArray(), 'Favourite retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFavouritesAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/favourites/{id}",
     *      summary="Update the specified Favourite in storage",
     *      tags={"Favourite"},
     *      description="Update Favourite",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Favourite",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Favourite that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Favourite")
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
     *                  ref="#/definitions/Favourite"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFavouritesAPIRequest $request)
    {
        $input = $request->all();

        /** @var Favourite $favourites */
        $favourites = $this->favouritesRepository->find($id);

        if (empty($favourites)) {
            return $this->sendError('Favourite not found');
        }

        $favourites = $this->favouritesRepository->update($input, $id);

        return $this->sendResponse($favourites->toArray(), 'Favourite updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/favourites/{id}",
     *      summary="Remove the specified Favourite from storage",
     *      tags={"Favourite"},
     *      description="Delete Favourite",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Favourite",
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
        /** @var Favourite $favourites */
        $favourites = $this->favouritesRepository->find($id);

        if (empty($favourites)) {
            return $this->sendError('Favourite not found');
        }

        $favourites->delete();

        return $this->sendSuccess('Favourite deleted successfully');
    }
}
