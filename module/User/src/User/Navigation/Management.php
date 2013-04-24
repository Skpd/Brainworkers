<?php

namespace User\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class Management extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'user-management';
    }
}