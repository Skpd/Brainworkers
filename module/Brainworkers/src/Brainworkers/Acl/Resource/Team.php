<?php


namespace Brainworkers\Acl\Resource;

use Zend\Permissions\Acl\Resource\ResourceInterface;

class Team implements ResourceInterface
{

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'team';
    }
}