<?php

namespace Confirmation;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'aliases'   => array(
                'confirmation_doctrine_dm' => 'doctrine.documentmanager.odm_default',
            ),
            'factories' => array(
                'confirmation_module_options' => function ($sm) {
                    $config               = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['confirmation'])
                            ? $config['confirmation']
                            : array());
                },
                'confirmation_mapper' => function(\Zend\ServiceManager\ServiceLocatorInterface $sm) {
                    $options = $sm->get('confirmation_module_options');
                    $dm      = $sm->get('confirmation_doctrine_dm');
                    return new Mapper\Confirmation\DoctrineMongoDB($dm, $options);

//                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $mapper = new Mapper\Confirmation\Db($adapter, $options);
//                    return $mapper;
                },
                'confirmation_service' => function(\Zend\ServiceManager\ServiceLocatorInterface $sm) {
                    return new Service\Confirmation($sm->get('confirmation_mapper'));
                },
            ),
        );
    }

    public function getConsoleUsage(Console $console)
    {
        $return = array(
            $console->colorize('Confirmations:', Color::YELLOW),
            $console->colorize('confirmation clean_expired', Color::GREEN) => 'Remove expired confirmations',
        );

        return $return;
    }

}
