<?php


namespace App\XUI;


class Request
{

    /**
     * @var string $url
     */
    protected $baseUrl;

    /**
     * @var string$path
     */
    protected $path;

    /**
     * @var array $headers
     */
    protected $headers = [];

    /**
     * @var bool $needsLogin
     */
    protected $needsLogin = true;

    /**
     * @var string $method
     */
    protected $method;

    protected $params = [];

    /**
     * @var string $cookiePath
     */
    protected $cookiePath;

    /**
     * Service constructor.
     * @param string $baseUrl
     * @param bool $needsLogin
     */
    public function __construct(string $baseUrl, bool $needsLogin = true)
    {
        $this->baseUrl = $baseUrl;
        $this->needsLogin = $needsLogin;
        $this->headers = [
            'Accept: application/json, text/plain, */*',
            'X-Requested-With: XMLHttpRequest',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        ];
        $this->cookiePath = storage_path() . '/' . md5($this->extractHostFromUrl()) . '.txt';
    }

    public function addHeader(string $key, string $value)
    {
        $this->headers[] = "$key: $value";
        return $this;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    public function addParam(string $key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function call()
    {
        $curl = $this->buildCurl();
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $errorMessage = curl_error($curl);
            throw new \Exception($errorMessage);
        }
        curl_close($curl);
        return json_decode($response, 1);
    }

    protected function buildCurl()
    {
        $curl = curl_init();
        $url = rtrim(rtrim($this->baseUrl, '/') . '/' . ltrim($this->path, '/'), '/');
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookiePath);
        if ($this->params) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->params));
        }
        if ($this->needsLogin) {
//            $cookie = file_get_contents($this->cookiePath);
//            $this->addHeader('Cookie', $cookie);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookiePath);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        return $curl;
    }

    protected function extractHostFromUrl()
    {
        $parsed = parse_url($this->baseUrl);
        return $parsed['host'];
    }
}
