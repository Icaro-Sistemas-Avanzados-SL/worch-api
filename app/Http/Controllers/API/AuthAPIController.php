<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Client;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class AuthAPIController extends AppBaseController
{

    public function __construct()
    {

    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/admin/login",
     *      summary="Login admin users",
     *      tags={"User"},
     *      description="Login User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          description="Email and password of User",
     *          required=true,
     *          in="body",
     *      @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="email",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string"
     *              )
     *          )
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $user->remember_token = Str::random(32);
            $user->save();
            return $this->sendResponse($user->toArray(), 'Logged successfully');
        }    else  {
            return $this->sendError('User not found', 200);
        }
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/admin/login",
     *      summary="Login admin users",
     *      tags={"User"},
     *      description="Login User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          description="Email and password of User",
     *          required=true,
     *          in="body",
     *      @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="email",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string"
     *              )
     *          )
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function checkLogin(Request $request)
    {

        $user = User::where('remember_token', $request->remember_token)->where('email', $request->email);
        if ($user->exists()) {
            $user = $user->first();
            return $this->sendResponse($user->toArray(), 'Auth successfully');
        }    else  {
            return $this->sendError('User not found', 200);
        }
    }


}
