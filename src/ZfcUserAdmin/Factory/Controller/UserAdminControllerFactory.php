<?php

namespace ZfcAdmin\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUserAdmin\Controller\UserAdminController;

class UserControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserAdminController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $createUserForm = $serviceLocator->get('zfcuseradmin_createuser_form');
        $editUserForm = $serviceLocator->get('zfcuseradmin_edituser_form');
        $options = $serviceLocator->get('zfcuseradmin_module_options');
        $userMapper = $serviceLocator->get('zfcuser_user_mapper');
        $adminUserService = $serviceLocator->get('zfcuseradmin_user_service');
        $zfcUserOptions = $serviceLocator->get('zfcuser_module_options');

        return new UserAdminController(
            $createUserForm,
            $editUserForm,
            $options,
            $userMapper,
            $adminUserService,
            $zfcUserOptions
        );
    }
}