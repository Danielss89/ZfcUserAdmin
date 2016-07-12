<?php

namespace ZfcUserAdmin\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserMapperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $config \ZfcUserAdmin\Options\ModuleOptions */
        $config = $serviceLocator->get('zfcuseradmin_module_options');

        $mapperClass = $config->getUserMapper();

        if (stripos($mapperClass, 'doctrine') !== false) {
            $mapper = new $mapperClass(
                $serviceLocator->get('zfcuser_doctrine_em'),
                $serviceLocator->get('zfcuser_module_options')
            );
        } else {
            /** @var $zfcUserOptions \ZfcUser\Options\UserServiceOptionsInterface */
            $zfcUserOptions = $serviceLocator->get('zfcuser_module_options');

            /** @var $mapper \ZfcUserAdmin\Mapper\UserZendDb */
            $mapper = new $mapperClass();
            $mapper->setDbAdapter($serviceLocator->get('zfcuser_zend_db_adapter'));

            $entityClass = $zfcUserOptions->getUserEntityClass();
            $mapper->setEntityPrototype(new $entityClass);
            $mapper->setHydrator($serviceLocator->get('zfcuser_user_hydrator'));
            $mapper->setTableName($zfcUserOptions->getTableName());
        }

        return $mapper;
    }
}