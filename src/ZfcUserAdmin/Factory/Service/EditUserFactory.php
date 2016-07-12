<?php

namespace ZfcUserAdmin\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\RegisterFilter;
use ZfcUser\Validator\NoRecordExists;
use ZfcUserAdmin\Form\EditUser;

class EditUserFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return EditUser
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $zfcUserOptions \ZfcUser\Options\UserServiceOptionsInterface */
        $zfcUserOptions = $serviceLocator->get('zfcuser_module_options');

        /** @var $zfcUserAdminOptions \ZfcUserAdmin\Options\ModuleOptions */
        $zfcUserAdminOptions = $serviceLocator->get('zfcuseradmin_module_options');

        $form = new EditUser(null, $zfcUserAdminOptions, $zfcUserOptions, $serviceLocator);

        $filter = new RegisterFilter(
            new NoRecordExistsEdit(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key' => 'email'
            )),
            new NoRecordExistsEdit(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key' => 'username'
            )),
            $zfcUserOptions
        );

        if (!$zfcUserAdminOptions->getAllowPasswordChange()) {
            $filter->remove('password')->remove('passwordVerify');
        } else {
            $filter->get('password')->setRequired(false);
            $filter->remove('passwordVerify');
        }

        $form->setInputFilter($filter);

        return $form;
    }
}