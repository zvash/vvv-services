<?php


namespace App\Repositories;


use App\Models\Account;
use App\Models\Link;
use App\Models\Server;
use App\Models\User;
use App\XUI\Inbound;
use Illuminate\Support\Facades\DB;

class AccountRepository
{

    /**
     * @param Account $account
     * @return string
     */
    public function getAccountURLs(Account $account)
    {
        $links = $account->links()
            ->where('still_valid', true)
            ->where('is_proxy', false)
            ->get();
        $urls = [];
        foreach ($links as $link) {
            $urls[] = $link->buildURL();
        }
        return implode("\n", $urls);
    }

    public function createNewAccountAnSetItUp(string $sentTo, ?User $user = null)
    {
        $outServer = Server::query()->where('is_domestic', false)->first();
        $inServer = Server::query()->where('is_domestic', true)->first();
        return $this->createNewAccount($sentTo, $outServer, $inServer, $user);
    }

    public function createNewAccount(string $sentTo, Server $outServer, Server $inServer = null, ?User $user = null)
    {
        DB::beginTransaction();
        try {
            $account = Account::query()->create([
                'sent_to' => $sentTo,
                'token' => $this->findToken()
            ]);
            if ($user) {
                $account->user_id = $user->id;
                $account->save();
            }
            $this->createThreeConfigsForAccount($account, $outServer, $inServer);
            DB::commit();
            return $account;
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    public function createThreeConfigsForAccount(Account $account, Server $outServer, Server $inServer = null)
    {
        try {
            $loginResponse = (new \App\XUI\Login($outServer->console_address))
                ->setUserName($outServer->panel_username)
                ->setPassword($outServer->panel_password)
                ->call();
            if ($loginResponse['success']) {
                $this->createTLSLink($account, $outServer);
                $this->createNormalLinks($account, $outServer, $inServer);
            }
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    private function findToken()
    {
        $candidate = mt_rand(100000, 999999);
        if (
        Account::query()->where('token', $candidate)->count()
        ) {
            return $this->findToken();
        }
        return $candidate;
    }

    private function createTLSLink(Account $account, Server $outServer)
    {
        if (!$outServer->host) {
            return;
        }
        $name = $account->sent_to . ' (TLS)';
        $inbound = new Inbound($outServer->console_address, $name);
        $response = $inbound->enableTLS()->call();
        if ($response['success']) {
            $this->createLink(
                $account,
                $outServer,
                $inbound->getId(),
                $inbound->getPort(),
                $outServer->address,
                $inbound->isTLS()
            );
        }
    }

    private function createNormalLinks(Account $account, Server $outServer, Server $inServer = null)
    {
        $unlimitedName = $account->sent_to . ' (NoTLS - Unlimited)';
        $firstInbound = new Inbound($outServer->console_address, $unlimitedName);
        $firstInboundResponse = $firstInbound->call();
        if ($firstInboundResponse['success']) {
            $this->createLink(
                $account,
                $outServer,
                $firstInbound->getId(),
                $firstInbound->getPort(),
                $outServer->address,
                $firstInbound->isTLS(),
                '',
                $firstInbound->getWSPath()
            );
        }
        $name = $account->sent_to . ' (NoTLS - Limited)';
        $inbound = new Inbound($outServer->console_address, $name);
        $response = $inbound->limit(20)->call();
        if ($response['success'] && $inServer) {
            $this->createLink(
                $account,
                $outServer,
                $inbound->getId(),
                $inbound->getPort(),
                $outServer->address,
                $inbound->isTLS(),
                '20',
                $inbound->getWSPath(),
                true
            );
            $this->createLink(
                $account,
                $inServer,
                $inbound->getId(),
                $inbound->getPort(),
                $inServer->address,
                $inbound->isTLS(),
                '20',
                $inbound->getWSPath()
            );
        }
    }

    private function createLink(Account $account, Server $server, string $settingId, string $settingPort, string $settingAddress, bool $isTLS, string $limit = '', string $path = '', $justProxy = false)
    {
        $name = $server->country;
        if ($limit) {
            $name .= " ({$limit}GB/Month)";
        } else {
            $name .= " (Unlimited)";
        }
        if ($isTLS) {
            $name .= " (TLS)";
        }
        return Link::query()->create([
            'account_id' => $account->id,
            'server_id' => $server->id,
            'has_tls' => true,
            'tunneled' => false,
            'setting_ps' => $name,
            'setting_id' => $settingId,
            'setting_port' => $settingPort,
            'setting_add' => $settingAddress,
            'setting_tls' => $isTLS ? 'tls' : 'none',
            'setting_path' => $path,
            'is_proxy' => $justProxy,
        ]);
    }
}
