<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 1/31/2015
 * Time: 1:06 PM
 */

namespace ZfcUserAdmin\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\RegisterFilter;
use ZfcUser\Validator\NoRecordExists;
use ZfcUserAdmin\Form\CreateUser;

class CreateUserFactory implements FactoryInterface {

    function createService(ServiceLocatorInterface $serviceLocator) {
        /** @var $zfcUserOptions \ZfcUser\Options\UserServiceOptionsInterface */
        $zfcUserOptions = $serviceLocator->get('zfcuser_module_options');
        /** @var $zfcUserAdminOptions \ZfcUserAdmin\Options\ModuleOptions */
        $zfcUserAdminOptions = $serviceLocator->get('zfcuseradmin_module_options');
        $form = new CreateUser(null, $zfcUserAdminOptions, $zfcUserOptions, $serviceLocator);
        $filter = new RegisterFilter(
            new NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key' => 'email'
            )),
            new NoRecordExists(array(
                'mapper' => $serviceLocator->get('zfcuser_user_mapper'),
                'key' => 'username'
            )),
            $zfcUserOptions
        );
        if ($zfcUserAdminOptions->getCreateUserAutoPassword()) {
            $filter->remove('password')->remove('passwordVerify');
        }
        $form->setInputFilter($filter);
        return $form;
    }
}