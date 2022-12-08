<?php


namespace App\XUI;


use Illuminate\Support\Str;

class Inbound extends Request
{
    /**
     * @var string $name
     */
    protected $name;

    protected $id;

    protected $port;

    protected $isTLS = false;

    protected $wsPath = '';

    /**
     * Service constructor.
     * @param string $url
     * @param string $name
     */
    public function __construct(string $url, string $name)
    {
        parent::__construct($url, true);
        $this->path = 'xui/inbound/add';
        $this->setMethod('POST');
        $this->name = $name;
        $this->addParam('remark', $name);
        $this->addCommonParams();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function call()
    {
        for ($i = 0; $i <= 2; $i++) {
            $response = parent::call();
            if ($response['success']) {
                return $response;
            }
            $this->port = mt_rand(1, 64512) + 1024;
            $this->addParam('port', $this->port);
        }
        throw new \Exception('Cannot register this account');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return bool
     */
    public function isTLS(): bool
    {
        return $this->isTLS;
    }

    public function getWSPath()
    {
        return $this->wsPath;
    }


    public function limit($valueInGB)
    {
        $this->addParam('total', $valueInGB * 1024 * 1024 * 1024);
        return $this;
    }

    public function enableTLS()
    {
        $this->isTLS = true;
        $host = $this->extractHostFromUrl();
        $this->addParam('streamSettings', json_encode(
                [
                    "network" => "tcp",
                    "security" => "tls",
                    "tlsSettings" => [
                        "serverName" => $host,
                        "certificates" => [
                            [
                                "certificateFile" => "/etc/letsencrypt/live/{$host}/fullchain.pem",
                                "keyFile" => "/etc/letsencrypt/live/{$host}/privkey.pem"
                            ]
                        ]
                    ],
                    "tcpSettings" => [
                        "header" => [
                            "type" => "none"
                        ]
                    ]
                ]
            , JSON_UNESCAPED_SLASHES)
        );
        return $this;
    }

    protected function addCommonParams(bool $ws = true)
    {
        $this->id = Str::UUID()->toString();
        $this->port = mt_rand(1, 64512) + 1024;
        $this->isTLS = false;
        $this->addParam('down', '0');
        $this->addParam('enable', true);
        $this->addParam('expiryTime', 0);
        $this->addParam('listen', '');
        $this->addParam('port', $this->port);
        $this->addParam('protocol', 'vmess');
        $this->addParam('settings', json_encode(
                [
                    "clients" => [
                        [
                            "id" => $this->id,
                            "alterId" => 0
                        ]
                    ],
                    "disableInsecureEncryption" => false
                ]
            )
        );
        $this->addParam('sniffing', json_encode(
                [
                    "enabled" => true,
                    "destOverride" => [
                        "http",
                        "tls"
                    ]
                ]
            )
        );
        if ($ws) {
            $this->wsPath = $this->randomString();
            $this->addParam('streamSettings', json_encode(
                    [
                        "network" => "ws",
                        "security" => "none",
                        "wsSettings" => [
                            "path" => $this->wsPath,
                            "headers" => []
                        ]
                    ]
                , JSON_UNESCAPED_SLASHES|JSON_FORCE_OBJECT)
            );
        } else {
            $this->addParam('streamSettings', json_encode(
                    [
                        "network" => "tcp",
                        "security" => "none",
                        "tcpSettings" => [
                            "header" => [
                                "type" => "none"
                            ]
                        ]
                    ]
                )
            );
        }
        return $this;
    }

    private function randomString(int $length = 8)
    {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $word = '/';
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, 25);
            $word .= $letters[$index];
        }
        return $word;
    }
}
