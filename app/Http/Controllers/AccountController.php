<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Traits\Responses\ResponseMaker;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ResponseMaker;

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

    /**
     * @param Request $request
     * @param AccountRepository $repository
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createAccountAndLinks(Request $request, AccountRepository $repository)
    {
        $user = $request->user();
        if ($user->account) {
            return $this->success(['subscription_link' => $this->getSubscriptionLink($user->account)]);
        }
        $account = $repository->createNewAccountAnSetItUp($user->name, $user);
        return $this->success(['subscription_link' => $this->getSubscriptionLink($account)]);
    }

    public function getAccountSubscriptionLink(Request $request)
    {
        $user = $request->user();
        if ($user->account) {
            return $this->success(['subscription_link' => $this->getSubscriptionLink($user->account)]);
        }
        return $this->success(['subscription_link' => null]);
    }

    private function getSubscriptionLink(Account $account)
    {
        return rtrim(config('url'), '/') . '/t/' . $account->token;
    }
}
