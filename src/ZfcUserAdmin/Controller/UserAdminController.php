<?php

namespace ZfcUserAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator;
use ZfcUser\Mapper\UserInterface;
use ZfcUserAdmin\Options\ModuleOptions;

class UserAdminController extends AbstractActionController
{
    protected $options, $userMapper;
    /**
     * @var \ZfcUserAdmin\Service\User
     */
    protected $adminUserService;

    public function listAction()
    {
        $userMapper = $this->getUserMapper();
        $users = $userMapper->findAll();
        if (is_array($users)) {
            $paginator = new Paginator\Paginator(new Paginator\Adapter\ArrayAdapter($users));
        } else {
            $paginator = $users;
        }

        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        return array(
            'users' => $paginator,
            'userlistElements' => $this->getOptions()->getUserListElements()
        );
    }

    public function createAction()
    {
        $form = $this->getServiceLocator()->get('zfcuseradmin_createuser_form');
        $request = $this->getRequest();

        $user = false;
        /** @var $request \Zend\Http\Request */
        if ($request->isPost()) {
            $user = $this->getAdminUserService()->create((array)$request->getPost());
        }

        if (!$user) {
            return array(
                'createUserForm' => $form
            );
        }

        $this->flashMessenger()->addSuccessMessage('The user was created');
        return $this->redirect()->toRoute('zfcadmin/zfcuseradmin/list');
    }

    public function editAction()
    {
        $userId = $this->getEvent()->getRouteMatch()->getParam('userId');
        $user = $this->getUserMapper()->findById($userId);
        $form = $this->getServiceLocator()->get('zfcuseradmin_edituser_form');
        $form->setUser($user);

        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $form->populateFromUser($user);
            return array(
                'editUserForm' => $form,
                'userId' => $userId
            );
        }

        $this->getAdminUserService()->edit(get_object_vars($request->getPost()), $user);

        $this->flashMessenger()->addSuccessMessage('The user was edited');
        return $this->redirect()->toRoute('zfcadmin/zfcuseradmin/list');
    }

    public function removeAction()
    {
        $userId = $this->getEvent()->getRouteMatch()->getParam('userId');

        /** @var $identity \ZfcUser\Entity\UserInterface */
        $identity = $this->zfcUserAuthentication()->getIdentity();
        if ($identity && $identity->getId() == $userId) {
            $this->flashMessenger()->addErrorMessage('You can not delete yourself');
        } else {
            $user = $this->getUserMapper()->findById($userId);
            if ($user) {
                $this->getUserMapper()->remove($user);
                $this->flashMessenger()->addSuccessMessage('The user was deleted');
            }
        }

        return $this->redirect()->toRoute('zfcadmin/zfcuseradmin/list');
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('zfcuseradmin_module_options'));
        }
        return $this->options;
    }

    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }

    public function setUserMapper(UserInterface $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    public function getAdminUserService()
    {
        if (null === $this->adminUserService) {
            $this->adminUserService = $this->getServiceLocator()->get('zfcuseradmin_user_service');
        }
        return $this->adminUserService;
    }

    public function setAdminUserService($service)
    {
        $this->adminUserService = $service;
        return $this;
    }
}
