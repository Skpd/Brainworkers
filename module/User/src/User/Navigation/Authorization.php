<?php

namespace User\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class Authorization extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'user-authorization';
    }
}