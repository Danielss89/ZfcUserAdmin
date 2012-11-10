<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZfcUserAdmin;

use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'ZfcUserAdmin\Form\EditUser' => 'ZfcUserAdmin\Form\EditUser',
                'zfcuseradmin_user_service'       => 'ZfcUserAdmin\Service\User',
            ),
            'factories' => array(
                'zfcuseradmin_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['zfcuseradmin']) ? $config['zfcuseradmin'] : array());
                },
                'zfcuseradmin_edituser_form' => function($sm) {
                    $options = $sm->get('zfcuseradmin_module_options');
                    $form = new Form\EditUser(null, $options, $sm);
                    return $form;
                },
                'zfcuseradmin_createuser_form' => function($sm) {
                    $zfcUserOptions = $sm->get('zfcuser_module_options');
                    $zfcUserAdminOptions = $sm->get('zfcuseradmin_module_options');
                    $form = new Form\CreateUser(null, $zfcUserAdminOptions, $zfcUserOptions, $sm);
                    $filter = new \ZfcUser\Form\RegisterFilter(
                        new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'email'
                        )),
                        new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'username'
                        )),
                        $zfcUserOptions
                    );
                    if($zfcUserAdminOptions->getCreateUserAutoPassword())
                    {
                        $filter->remove('password')->remove('passwordVerify');
                    }
                    $form->setInputFilter($filter);
                    return $form;
                },
                'zfcuser_user_mapper' => function ($sm) {
                    $config = $sm->get('zfcuseradmin_module_options');
                    $mapperClass = $config->getUserMapper();
                    if ($mapperClass == 'ZfcUserAdmin\Mapper\UserZendDb') {
                        $zfcUserOptions = $sm->get('zfcuser_module_options');

                        $mapper = new $mapperClass;
                        $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                        $entityClass = $zfcUserOptions->getUserEntityClass();
                        $mapper->setEntityPrototype(new $entityClass);
                        $mapper->setHydrator(new \ZfcUser\Mapper\UserHydrator());
                    } else {
                        $mapper = new $mapperClass(
                            $sm->get('zfcuser_doctrine_em'),
                            $sm->get('zfcuser_module_options')
                        );
                    }

                    return $mapper;
                },
            ),
        );
    }
}
