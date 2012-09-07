<?php

namespace ZfcUserAdmin\Service;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Router\RouteMatch;

class Authorize implements ServiceManagerAwareInterface
{
    public function onRoute(MvcEvent $e) 
    {
        $response   = $e->getResponse(); 
        $app        = $e->getTarget();
        $match      = $app->getMvcEvent()->getRouteMatch();
        $sm = $this->getServiceManager();

        if($match instanceof RouteMatch) {
            $exp = explode('/', $match->getMatchedRouteName());
            if($exp[0] == 'zfcadmin' || $exp[0] == 'zfcadminuser') {
                $zfcUserAuthentication = $sm
                    ->get('ControllerPluginManager')
                    ->get('zfcuserauthentication');
                if (!$zfcUserAuthentication->hasIdentity()) {
                    $response->getHeaders()->addHeaders(array(
                        'Location' => '/admin/auth/login',
                    ));
                    return $response;
                }
            }
        }
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
            return $this->_serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return void
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->_serviceManager = $serviceManager;
    }
}