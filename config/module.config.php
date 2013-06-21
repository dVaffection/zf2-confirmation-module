<?php

return array(
    'router'       => array(
        'routes' => array(
            'confirmation' => array(
                'type'          => 'hostname',
                'options'       => array(
                    'route' => 'confirmation.rentcolumn.dev',
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'index' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:id[/]',
                            'defaults' => array(
                                'controller' => 'Confirmation\Controller\Index',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'console'      => array(
        'router' => array(
            'routes' => array(
                'confirmation clean_expired' => array(
                    'options' => array(
                        'route'    => 'confirmation clean_expired',
                        'defaults' => array(
                            'controller' => 'Confirmation\Controller\Index',
                            'action'     => 'clean-expired',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers'  => array(
        'invokables' => array(
            'Confirmation\Controller\Index' => 'Confirmation\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'confirmation' => array(
        /**
         * Confirmation Model Entity Class
         */
        'confirmation_entity_class' => 'Confirmation\Entity\Confirmation',
        'expiration_period'         => 'P1M',
    ),
    'doctrine'     => array(
        'driver' => array(
            'odm_default'  => array(
                'drivers' => array(
                    'Confirmation\Entity' => 'confirmation',
                ),
            ),
            'confirmation' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
            ),
        ),
    ),
);
