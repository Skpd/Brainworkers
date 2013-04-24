<?php

namespace User\Authentication\Adapter;

use ZfcUser\Authentication\Adapter\AdapterChainEvent;
use Zend\Stdlib\Response as Response;

class Event extends AdapterChainEvent
{
    /**
     * getRequest
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->getParam('response');
    }

    /**
     * setRequest
     *
     * @param Response $response
     * @return Event
     */
    public function setResponse(Response $response)
    {
        $this->setParam('response', $response);
        return $this;
    }
}