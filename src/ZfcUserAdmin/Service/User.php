<?php

namespace ZfcUserAdmin\Service;

use Zend\Form\Form;
use Zend\Math\Rand;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Crypt\Password\Bcrypt;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Entity\UserInterface;
use ZfcUserAdmin\Options\ModuleOptions;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;
use ZfcUser\Options\ModuleOptions as ZfcUserModuleOptions;


class User extends EventProvider
{
    /**
     * @var UserMapperInterface
     */
    protected $userMapper;

    /**
     * @var \ZfcUser\Options\UserServiceOptionsInterface
     */
    protected $options;

    /**
     * @var ZfcUserModuleOptions
     */
    protected $zfcUserOptions;

    public function __construct(
        UserMapperInterface $userMapper = null,
        $options = null,
        ZfcUserModuleOptions $zfcUserOptions = null
    ) {
        $this->userMapper = $userMapper;
        $this->options = $options;
        $this->zfcUserOptions = $zfcUserOptions;
    }

    /**
     * @param Form $form
     * @param array $data
     * @return UserInterface|null
     */
    public function create(Form $form, array $data)
    {
        $zfcUserOptions = $this->getZfcUserOptions();
        $user = $form->getData();

        $argv = array();
        if ($this->getOptions()->getCreateUserAutoPassword()) {
            $argv['password'] = $this->generatePassword();
        } else {
            $argv['password'] = $user->getPassword();
        }
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($zfcUserOptions->getPasswordCost());
        $user->setPassword($bcrypt->create($argv['password']));

        foreach ($this->getOptions()->getCreateFormElements() as $element) {
            call_user_func(array($user, $this->getAccessorName($element)), $data[$element]);
        }

        $argv += array('user' => $user, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
        return $user;
    }

    /**
     * @param Form $form
     * @param array $data
     * @param UserInterface $user
     * @return UserInterface
     */
    public function edit(Form $form, array $data, UserInterface $user)
    {
        // first, process all form fields
        foreach ($data as $key => $value) {
            if ($key == 'password') continue;

            $setter = $this->getAccessorName($key);
            if (method_exists($user, $setter)) call_user_func(array($user, $setter), $value);
        }

        $argv = array();
        // then check if admin wants to change user password
        if ($this->getOptions()->getAllowPasswordChange()) {
            if (!empty($data['reset_password'])) {
                $argv['password'] = $this->generatePassword();
            } elseif (!empty($data['password'])) {
                $argv['password'] = $data['password'];
            }

            if (!empty($argv['password'])) {
                $bcrypt = new Bcrypt();
                $bcrypt->setCost($this->getZfcUserOptions()->getPasswordCost());
                $user->setPassword($bcrypt->create($argv['password']));
            }
        }

        // TODO: not sure if this code is required here - all fields that came from the form already saved
        foreach ($this->getOptions()->getEditFormElements() as $element) {
            call_user_func(array($user, $this->getAccessorName($element)), $data[$element]);
        }

        $argv += array('user' => $user, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->getUserMapper()->update($user);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
        return $user;
    }

    /**
     * @return string
     */
    public function generatePassword()
    {
        return Rand::getString($this->getOptions()->getAutoPasswordLength());
    }

    protected function getAccessorName($property, $set = true)
    {
        $parts = explode('_', $property);
        array_walk($parts, function (&$val) {
            $val = ucfirst($val);
        });
        return (($set ? 'set' : 'get') . implode('', $parts));
    }

    public function getUserMapper()
    {
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
        return $this->zfcUserOptions;
    }
}