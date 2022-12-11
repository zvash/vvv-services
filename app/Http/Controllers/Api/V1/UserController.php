<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ErrorCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\Passport\InteractsWithPassport;
use App\Traits\Responses\ResponseMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    use ResponseMaker, InteractsWithPassport;

    /**
     * @param RegisterUserRequest $request
     * @param UserRepository $repository
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(RegisterUserRequest $request, UserRepository $repository)
    {
        return $this->success($repository->register($request->validated()));
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $loginResponse = $this->makeInternalLoginRequest($request);
        $content = json_decode($loginResponse->getContent(), 1);
        if (array_key_exists('error', $content)) {
            return $this->failWithCode('Invalid username or password!', ErrorCodes::WRONG_CREDENTIALS, 401);
        }
        $user = User::findByUserName($request->get('username'));
        return $this->success($content);
    }

    /**
     * @param LoginRequest $request
     * @return mixed
     */
    private function makeInternalLoginRequest(LoginRequest $request)
    {
        $inputs = $request->all();
        $token = Request::create(
            'oauth/token',
            'POST',
            [
                'grant_type' => 'password',
                'client_id' => $inputs['client_id'],
                'client_secret' => $inputs['client_secret'],
                'username' => $inputs['username'],
                'password' => $inputs['password'],
                'scope' => $inputs['scope'],
            ]
        );

        $loginResponse = Route::dispatch($token);
        return $loginResponse;
    }
}
