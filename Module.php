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
use Zend\ModuleManager\Feature;
use Zend\EventManager\EventInterface;

class Module implements
    Feature\BootstrapListenerInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @{inheritdoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getParam('application');
        $sm  = $app->getServiceManager();
        $em = $app->getEventManager();

        $service = $sm->get('zfcuseradmin_service_authorize');
        $em->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($service, 'onRoute'));
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
                'zfcuseradmin_service_authorize' => 'ZfcUserAdmin\Service\Authorize',
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
                    $config = $sm->get('config');
                    $mapper = $config['zfcuseradmin']['zfcuseradmin_user_mapper'];
                    return new $mapper(
                        $sm->get('zfcuser_doctrine_em'),
                        $sm->get('zfcuser_module_options')
                    );
                },
                'zfcuseradmin_auth_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\UserAuthOptions(isset($config['zfcuseradmin']) ? $config['zfcuseradmin'] : array());
                },
            ),
        );
    }
}
