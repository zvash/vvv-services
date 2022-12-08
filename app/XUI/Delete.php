<?php


namespace App\XUI;


class Delete extends Request
{

    /**
     * Service constructor.
     * @param string $url
     * @param int $id
     */
    public function __construct(string $url, int $id)
    {
        parent::__construct($url, true);
        $this->path = 'xui/inbound/del/' . $id;
        $this->setMethod('POST');
    }
}
