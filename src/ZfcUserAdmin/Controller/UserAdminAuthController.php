<?php

namespace ZfcUserAdmin\Controller;

use ZfcUser\Controller\UserController;

class UserAdminAuthController extends UserController
{
    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuseradmin_auth_options'));
        }
        return $this->options;
    }

    /**
     * Login form
     */
    public function loginAction()
    {
        $this->getServiceLocator()->get('zfcuser_user_mapper');
        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect'  => $redirect,
            );
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url('zfcuseradmin')->fromRoute('admin/user/login').($redirect ? '?redirect='.$redirect : ''));
        }
        // clear adapters

        return $this->forward()->dispatch('zfcuseradminauth', array('action' => 'authenticate'));
    }
}