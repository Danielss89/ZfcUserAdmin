<?php

namespace ZfcUserAdmin\Factory\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\RegisterFilter;
use ZfcUser\Validator\NoRecordExists;
use ZfcUserAdmin\Form\CreateUser;

class CreateUserFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $zfcUserOptions \ZfcUser\Options\UserServiceOptionsInterface */
        $zfcUserOptions = $container->get('zfcuser_module_options');

        /** @var $zfcUserAdminOptions \ZfcUserAdmin\Options\ModuleOptions */
        $zfcUserAdminOptions = $container->get('zfcuseradmin_module_options');

        $form = new CreateUser(null, $zfcUserAdminOptions, $zfcUserOptions, $container);

        $filter = new RegisterFilter(
            new NoRecordExists(array(
                'mapper' => $container->get('zfcuser_user_mapper'),
                'key' => 'email'
            )),
            new NoRecordExists(array(
                'mapper' => $container->get('zfcuser_user_mapper'),
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

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CreateUser
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, CreateUser::class);
    }
}