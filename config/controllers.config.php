<?php

use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUserAdmin\Controller\UserAdminController;

return array(
    'controllers' => array(
        'factories' => function (ServiceLocatorInterface $sm) {
            $createUserForm = $sm->get('zfcuseradmin_createuser_form');
            $editUserForm = $sm->get('zfcuseradmin_edituser_form');
            $options = $sm->get('zfcuseradmin_module_options');
            $userMapper = $sm->get('zfcuser_user_mapper');
            $adminUserService = $sm->get('zfcuseradmin_user_service');
            $zfcUserOptions = $sm->get('zfcuser_module_options');

            return new UserAdminController(
                $createUserForm,
                $editUserForm,
                $options,
                $userMapper,
                $adminUserService,
                $zfcUserOptions
            );
        }
    )
);