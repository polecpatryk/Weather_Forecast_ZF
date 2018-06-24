<?php

namespace Weather;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'weather' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/weather[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\WeatherController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'weather' => __DIR__ . '/../view',
        ],
    ],
];