<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
//    dd((new \App\XUI\Login('http://london.gouril.xyz:10203'))->setUserName('bit')->setPassword('vafanapoli')->call());

//    dd(
//        (new \App\XUI\Inbound('http://london.gouril.xyz:10203', 'RemoteLimitedSecure'))
//        ->limit(5)
//        ->enableTLS()
//        ->submit()
//    );

//    dd(
//        (new \App\XUI\Delete('http://london.gouril.xyz:10203', 27))
//            ->call()
//    );
//    $repository = new \App\Repositories\AccountRepository();
//    dd($repository->createNewAccountAnSetItUp('Fifth Test')->links->toArray());
});

Route::get('/token/{token}', function ($token) {
    $account = \App\Models\Account::query()
        ->where('token', $token)
        ->first();
    if ($account) {
        $repository = new \App\Repositories\AccountRepository();
        echo $repository->getAccountURLs($account);
        return;
    }
    abort(403);
});
