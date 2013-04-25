<?php

namespace Brainworkers\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class Team extends Form implements InputFilterProviderInterface
{
    public function getInputFilterSpecification()
    {
        return array(

        );
    }

    public function init()
    {
        $this->setName('team');
        $this->add(
            array(
                 'type'    => 'TeamFieldset',
                 'options' => array(
                     'use_as_base_fieldset' => true
                 )
            )
        );
    }
}