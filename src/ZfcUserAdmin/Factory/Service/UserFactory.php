<?php

namespace ZfcUserAdmin\Factory\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUserAdmin\Service\User;

class UserFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userMapper = $container->get('zfcuser_user_mapper');
        $options = $container->get('zfcuseradmin_module_options');
        $zfcUserOptions = $container->get('zfcuser_module_options');

        return new User($userMapper, $options, $zfcUserOptions);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return User
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // return $this->__invoke($serviceLocator, User::class);
        return $this($serviceLocator, User::class);
    }
}