<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MappingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function ($router) {

    $router->group(['namespace' => 'Api\V1'], function ($router) {

        $router->get('/mappings/{ip}', [MappingController::class, 'all']);

        $router->post('/register', [UserController::class, 'register']);
        $router->post('/login', [UserController::class, 'login']);

        $router->group(['middleware' => 'auth:api'], function ($router) {

            $router->get('/me', [UserController::class, 'me']);

            $router->get('/users/remained-invitations', [UserController::class, 'invitationCount']);

            $router->post('/accounts', [AccountController::class, 'createAccountAndLinks']);
            $router->get('/accounts/subscription', [AccountController::class, 'getAccountSubscriptionLink']);

        });

    });
});
