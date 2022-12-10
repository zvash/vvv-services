<?php

namespace App\Http\Controllers;

use App\Repositories\AccountRepository;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getUrls(Request $request, string $token, AccountRepository $repository)
    {
        $account = \App\Models\Account::query()
            ->where('token', $token)
            ->first();
        if ($account) {
            echo $repository->getAccountURLs($account);
            return;
        }
        abort(403);
    }
}
