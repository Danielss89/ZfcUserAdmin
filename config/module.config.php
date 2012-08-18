<?php
return array(
    'zfcuseradmin' => array(
        'userListElements' => array('id', 'email', 'surname'),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'zfcuseradmin' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'zfcuseradmin' => 'ZfcUserAdmin\Controller\UserAdminController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuseradmin' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/admin/user',
                    'defaults' => array(
                        'controller' => 'zfcuseradmin',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'userlist' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/:p]',
                            'defaults' => array(
                                'controller' => 'zfcuseradmin',
                                'action'     => 'list',
                                'p'          => 0
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);