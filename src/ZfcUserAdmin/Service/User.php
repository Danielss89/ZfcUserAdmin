<?php

namespace ZfcUserAdmin\Service;

use Zend\Form\Form;
use Zend\Math\Rand;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Crypt\Password\Bcrypt;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Entity\UserInterface;
use ZfcUserAdmin\Options\ModuleOptions;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;
use ZfcUser\Options\ModuleOptions as ZfcUserModuleOptions;


class User extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var UserMapperInterface
     */
    protected $userMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var \ZfcUser\Options\UserServiceOptionsInterface
     */
    protected $options;

    /**
     * @var ZfcUserModuleOptions
     */
    protected $zfcUserOptions;


    public function create(array $data)
    {
        $zfcUserOptions = $this->getZfcUserOptions();
        $class = $zfcUserOptions->getUserEntityClass();
        /** @var $user UserInterface */
        $user = new $class();
        $form = $this->getServiceManager()->get('zfcuseradmin_createuser_form');
        $form->setHydrator(new ClassMethods());
        $form->bind($user);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $user = $form->getData();

        $argv = array();
        if ($this->getOptions()->getCreateUserAutoPassword()) {
            $argv['password'] = Rand::getString(8);
        } else {
            $argv['password'] = $user->getPassword();
        }
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($zfcUserOptions->getPasswordCost());
        $user->setPassword($bcrypt->create($argv['password']));

        if ($zfcUserOptions->getEnableUsername()) {
            $user->setUsername($data['username']);
        }
        if ($zfcUserOptions->getEnableDisplayName()) {
            $user->setDisplayName($data['display_name']);
        }

        foreach ($this->getOptions()->getCreateFormElements() as $element) {
            $parts = explode('_', $element);
            array_walk($parts, function (&$val) {
                $val = ucfirst($val);
            });
            $setter = 'set' . implode('', $parts);
            call_user_func(array($user, $setter), $data[$element]);
        }

        $argv += array('user' => $user, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
        return $user;
    }

    public function edit(array $data, UserInterface $user)
    {
        foreach ($this->getOptions()->getEditFormElements() as $element) {
            if ($element === 'password') {
                if ($data['password'] !== $user->getPassword()) {
                    // Password does not match, so password was changed
                    $bcrypt = new Bcrypt();
                    $bcrypt->setCost($this->getZfcUserOptions()->getPasswordCost());
                    $user->setPassword($bcrypt->create($data['password']));
                }
            } else {
                $func = 'set' . ucfirst($element);
                $user->$func($data[$element]);
            }
        }
        $this->getUserMapper()->update($user);
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'data' => $data));
        return $user;
    }


    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }

    public function setUserMapper(UserMapperInterface $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceManager()->get('zfcuseradmin_module_options'));
        }
        return $this->options;
    }

    public function setZfcUserOptions(ZfcUserModuleOptions $options)
    {
        $this->zfcUserOptions = $options;
        return $this;
    }

    /**
     * @return \ZfcUser\Options\ModuleOptions
     */
    public function getZfcUserOptions()
    {
        if (!$this->zfcUserOptions instanceof ZfcUserModuleOptions) {
            $this->setZfcUserOptions($this->getServiceManager()->get('zfcuser_module_options'));
        }
        return $this->zfcUserOptions;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}