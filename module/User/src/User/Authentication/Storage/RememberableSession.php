<?php

namespace User\Authentication\Storage;

use Zend\Authentication\Storage\Session;

class RememberableSession extends Session
{
    public function rememberMe($time = 1209600)
    {
        $this->session->getManager()->rememberMe($time);
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}