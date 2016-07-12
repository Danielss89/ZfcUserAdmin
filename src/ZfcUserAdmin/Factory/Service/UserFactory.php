<?php

namespace ZfcUserAdmin\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUserAdmin\Service\User;

class UserFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return User
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('zfcuser_user_mapper');
        $options = $serviceLocator->get('zfcuseradmin_module_options');
        $zfcUserOptions = $serviceLocator->get('zfcuser_module_options');

        return new User($userMapper, $options, $zfcUserOptions);
    }
}