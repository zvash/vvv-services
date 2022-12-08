<?php


namespace App\XUI;


class Login extends Request
{
    /**
     * Service constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct($url, false);
        $this->path = 'login';
        $this->setMethod('POST');
    }

    public function setUserName(string $username)
    {
        $this->addParam('username', $username);
        return $this;
    }

    public function setPassword(string $password)
    {
        $this->addParam('password', $password);
        return $this;
    }
}
