<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZfcUserAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcUserAdmin\Options\UserListOptionsInterface;

class UserAdminController extends AbstractActionController
{
    protected $options, $userMapper;

    public function listAction()
    {
        $userMapper = $this->getUserMapper();
        $users = $userMapper->findAll();
        $paginator = \Zend\Paginator\Paginator::factory($users);
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        return array(
            'users' => $paginator,
            'userlistElements' => $this->getOptions()->getUserListElements()
        );
    }

    public function editAction()
    {

    }

    public function setOptions(UserListOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof UserListOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuseradmin_module_options'));
        }
        return $this->options;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapperInterface $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }
}
