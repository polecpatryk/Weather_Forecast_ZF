<?php

namespace Weather\Form;

use Zend\Form\Form;

class WeatherForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('weather');

        $this->add([
            'name' => 'city',
            'type' => 'text',
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Szukaj',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}