<?php
/**
 * User: Vladimir Garvardt
 * Date: 3/18/13
 * Time: 6:39 PM
 */

return array(
    'invokables' => array(
        'ZfcUserAdmin\Form\EditUser'        => 'ZfcUserAdmin\Form\EditUser',
        'zfcuseradmin_user_service'         => 'ZfcUserAdmin\Service\User',
    ),
    'factories' => array(
        'zfcuseradmin_module_options'       => 'ZfcUserAdmin\Factory\Options\ModuleOptionsFactory',
        'zfcuser_user_mapper'               => 'ZfcUserAdmin\Factory\Mapper\UserZendDbFactory',
        'zfcuseradmin_createuser_form'      => 'ZfcUserAdmin\Factory\Form\CreateUserFactory',
        'zfcuseradmin_edituser_form'        => 'ZfcUserAdmin\Factory\Form\EditUserFactory',
    ),
);
