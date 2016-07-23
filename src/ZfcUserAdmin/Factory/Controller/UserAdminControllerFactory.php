<?php

namespace ZfcAdmin\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUserAdmin\Controller\UserAdminController;

class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $createUserForm = $container->get('zfcuseradmin_createuser_form');
        $editUserForm = $container->get('zfcuseradmin_edituser_form');
        $options = $container->get('zfcuseradmin_module_options');
        $userMapper = $container->get('zfcuser_user_mapper');
        $adminUserService = $container->get('zfcuseradmin_user_service');
        $zfcUserOptions = $container->get('zfcuser_module_options');

        return new UserAdminController(
            $createUserForm,
            $editUserForm,
            $options,
            $userMapper,
            $adminUserService,
            $zfcUserOptions
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $pluginManager
     * @return UserAdminController
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        return $this($pluginManager->getServiceLocator(), UserAdminController::class);
    }
}