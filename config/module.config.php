<?php
return array(
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
                    'list' => array(
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
                    'create' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/create',
                            'defaults' => array(
                                'controller' => 'zfcuseradmin',
                                'action'     => 'create'
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/:userId',
                            'defaults' => array(
                                'controller' => 'zfcuseradmin',
                                'action'     => 'edit',
                                'userId'     => 0
                            ),
                        ),
                    ),
                    'remove' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/remove/:userId',
                            'defaults' => array(
                                'controller' => 'zfcuseradmin',
                                'action'     => 'remove',
                                'userId'     => 0
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'admin' => array(
            'user' => array(
                'label' => 'User',
                'route' => 'zfcuseradmin/list',
                'pages' => array(
                    'create' => array(
                        'label' => 'New User',
                        'route' => 'admin/create',
                    ),                        
                ),
            ),
        ),
    ),

    'zfcuseradmin' => array(
        'zfcuseradmin_mapper' => 'ZfcUserAdmin\Mapper\UserZendDb',
    )
);