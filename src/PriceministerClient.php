<?php

namespace Priceminister;

use InvalidArgumentException;

class PriceministerClient
{
    CONST VERSION = '2015-07-05';

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * Client constructor.
     * @param $login
     * @param $password
     */
    public function __construct($login = '', $password = '')
    {
        $this->login    = $login;
        $this->password = $password;

        if (empty($this->login) || empty($this->password)) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
