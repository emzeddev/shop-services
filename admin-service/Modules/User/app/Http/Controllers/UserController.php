<?php

namespace Modules\User\Http\Controllers;

use Modules\User\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\User\Http\Requests\GetTokenRequest;
use \App\Http\Middleware\AuthTokenMiddleware;

class UserController extends MainController
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(AuthTokenMiddleware::class, ['except' => ['getToken']]);
    }

    /**
     * This method for login action for a admin
     *
     * @return \Illuminate\Http\Response
     */
    public function getToken(GetTokenRequest $request): JsonResponse
    {
        $remember = request('remember');
        $token = auth()->guard('admin')->attempt(request(['email', 'password']), $remember);

        if (! $token) {
            return new JsonResponse([
                "message" => trans('user::validation.admin-notfound')
            ] , JsonResponse::HTTP_NOT_FOUND);
        }

        if (! auth()->guard('admin')->user()->status) {
            auth()->guard('admin')->logout();

            return new JsonResponse([
                "message" => trans('user::validation.activate-warning')
            ] , JsonResponse::HTTP_UNAUTHORIZED);
        }


        return new JsonResponse([
            "message" => trans('user::validation.login-success'),
            "access_token" => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard("admin")->factory()->getTTL() * 60
        ] , JsonResponse::HTTP_OK);
    }


    /**
     * This method for logout action for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function unsetToken(Request $request) {
        auth()->guard('admin')->logout();

        return new JsonResponse([
            "message" => trans('user::validation.logout-success'),
        ] , JsonResponse::HTTP_OK);
    }

    /**
     * This method for refresh token of admin
     *
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(Request $request) {
        return new JsonResponse([
            "access_token" => auth()->guard("admin")->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->guard("admin")->factory()->getTTL() * 60
        ] , JsonResponse::HTTP_OK);
    }


    /**
     * This method for get data of token
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataOfToken(Request $request) {
        return new JsonResponse([
            "user" => auth()->guard("admin")->user(),
        ] , JsonResponse::HTTP_OK);
    }


}
