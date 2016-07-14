<?php

namespace ZfcUserAdmin\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUserAdmin\Mapper\UserZendDb;

class UserMapperFactory implements FactoryInterface
{
    public function __invoke($container, $requestedName, array $options = null)
    {
        /** @var $config \ZfcUserAdmin\Options\ModuleOptions */
        $config = $container->get('zfcuseradmin_module_options');

        $mapperClass = $config->getUserMapper();

        if (stripos($mapperClass, 'doctrine') !== false) {
            $mapper = new $mapperClass(
                $container->get('zfcuser_doctrine_em'),
                $container->get('zfcuser_module_options')
            );
        } else {
            /** @var $zfcUserOptions \ZfcUser\Options\UserServiceOptionsInterface */
            $zfcUserOptions = $container->get('zfcuser_module_options');

            /** @var $mapper \ZfcUserAdmin\Mapper\UserZendDb */
            $mapper = new $mapperClass();
            $mapper->setDbAdapter($container->get('zfcuser_zend_db_adapter'));

            $entityClass = $zfcUserOptions->getUserEntityClass();
            $mapper->setEntityPrototype(new $entityClass);
            $mapper->setHydrator($container->get('zfcuser_user_hydrator'));
            $mapper->setTableName($container->getTableName());
        }

        return $mapper;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, 'UserMapper');
    }
}