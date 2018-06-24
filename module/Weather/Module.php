<?php

namespace Weather;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\SearchTable::class => function($container) {
                    $tableGateway = $container->get(Model\SearchTableGateway::class);
                    return new Model\SearchTable($tableGateway);
                },
                Model\SearchTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Weather());
                    return new TableGateway('search', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
	
	public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\WeatherController::class => function($container) {
                    return new Controller\WeatherController(
                        $container->get(Model\SearchTable::class)
                    );
                },
            ],
        ];
    }
}