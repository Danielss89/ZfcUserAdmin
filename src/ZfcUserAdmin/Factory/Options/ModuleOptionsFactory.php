<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 1/31/2015
 * Time: 1:13 PM
 */

namespace ZfcUserAdmin\Factory\Options;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUserAdmin\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config');
        return new ModuleOptions(isset($config['zfcuseradmin']) ? $config['zfcuseradmin'] : array());
    }
}