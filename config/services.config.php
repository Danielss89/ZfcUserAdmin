<?php

use ZfcUserAdmin\Factory\Service\UserFactory;
use ZfcUserAdmin\Factory\Service\ModuleOptionsFactory;
use ZfcUserAdmin\Factory\Service\EditUserFactory;
use ZfcUserAdmin\Factory\Service\CreateUserFactory;
use ZfcUserAdmin\Factory\Service\UserMapperFactory;

return array(
    'invokables' => array(
        'ZfcUserAdmin\Form\EditUser' => 'ZfcUserAdmin\Form\EditUser'
    ),
    'factories' => array(
        'zfcuseradmin_user_service' => UserFactory::class,
        'zfcuseradmin_module_options' => ModuleOptionsFactory::class,
        'zfcuseradmin_edituser_form' => EditUserFactory::class,
        'zfcuseradmin_createuser_form' => CreateUserFactory::class,
        'zfcuser_user_mapper' => UserMapperFactory::class
    )
);
