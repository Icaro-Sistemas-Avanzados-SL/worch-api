<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFileAPIRequest;
use App\Http\Requests\API\UpdateFileAPIRequest;
use App\Models\File;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Response;
use Vimeo\Laravel\Facades\Vimeo;

/**
 * Class FileController
 * @package App\Http\Controllers\API
 */

class FileAPIController extends AppBaseController
{
    /** @var  FileRepository */
    private $fileRepository;

    public function __construct(FileRepository $fileRepo)
    {
        $this->fileRepository = $fileRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/files",
     *      summary="Get a listing of the Files.",
     *      tags={"File"},
     *      description="Get all Files",
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
     *                  @SWG\Items(ref="#/definitions/File")
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
        $files = $this->fileRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            $request->perPage
        );

        return $this->sendResponse($files->toArray(), 'Files retrieved successfully');
    }

    /**
     * @param CreateFileAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/files",
     *      summary="Store a newly created File in storage",
     *      tags={"File"},
     *      description="Store File",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="File that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/File")
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
     *                  ref="#/definitions/File"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFileAPIRequest $request)
    {
        $input = $request->all();
        //$slug = Str::slug($input['title'] , '-');
        $random = Str::random(8);
        if(!empty($input['file'])) {
            $imageName = $random.'-'.$input['title'];
            $data = $input['file'];
            $data = explode(',', $data)[1];
            Storage::disk('public')->put($imageName, base64_decode($data));
            $input['url'] = str_replace( '/var/www/vhosts/mallorcamoves.es/', 'https://', storage_path($imageName));
        } else {
            return $this->sendError('Video cannot be upload');
        }

        $file = $this->fileRepository->create($input);

        return $this->sendResponse($file->toArray(), 'File saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/files/{id}",
     *      summary="Display the specified File",
     *      tags={"File"},
     *      description="Get File",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of File",
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
     *                  ref="#/definitions/File"
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
        /** @var File $file */
        $file = $this->fileRepository->find($id);

        if (empty($file)) {
            return $this->sendError('File not found');
        }

        return $this->sendResponse($file->toArray(), 'File retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFileAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/files/{id}",
     *      summary="Update the specified File in storage",
     *      tags={"File"},
     *      description="Update File",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of File",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="File that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/File")
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
     *                  ref="#/definitions/File"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFileAPIRequest $request)
    {
        $input = $request->all();

        /** @var File $file */
        $file = $this->fileRepository->find($id);

        if (empty($file)) {
            return $this->sendError('File not found');
        }

        $file = $this->fileRepository->update($input, $id);

        return $this->sendResponse($file->toArray(), 'File updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/files/{id}",
     *      summary="Remove the specified File from storage",
     *      tags={"File"},
     *      description="Delete File",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of File",
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
        /** @var File $file */
        $file = $this->fileRepository->find($id);

        if (empty($file)) {
            return $this->sendError('File not found');
        }

        $file->delete();

        return $this->sendSuccess('File deleted successfully');
    }
}
