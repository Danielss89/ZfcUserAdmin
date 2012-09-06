<?php

namespace ZfcUserAdmin\Service;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Crypt\Password\Bcrypt;
use ZfcBase\EventManager\EventProvider;
use ZfcUserAdmin\Options\ModuleOptions;

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
     * @var UserServiceOptionsInterface
     */
    protected $options;

    public function create(array $data)
    {
        $zfcUserOptions = $this->getServiceManager()->get('zfcuser_module_options');
        $class = $zfcUserOptions->getUserEntityClass();
        $user  = new $class;
        $form  = $this->getServiceManager()->get('zfcuseradmin_createuser_form');
        $form->setHydrator(new ClassMethods());
        $form->bind($user);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $user = $form->getData();

        if($this->getOptions()->getCreateUserAutoPassword())
        {
            $rand = \Zend\Math\Rand::getString(8);
            $user->setPassword($rand);
        } else

        //@TODO: Use ZfcMail(when ready)
        mail($user->getEmail(), 'Password', 'Your password is: ' . $user->getPassword());
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($zfcUserOptions->getPasswordCost());
        $user->setPassword($bcrypt->create($user->getPassword()));

        if ($zfcUserOptions->getEnableUsername()) {
            $user->setUsername($data['username']);
        }
        if ($zfcUserOptions->getEnableDisplayName()) {
            $user->setDisplayName($data['display_name']);
        }

        foreach($this->getOptions()->getCreateFormElements() as $element)
        {
            $func = 'set' . ucfirst($element);
            $user->$func($data[$element]);
        }

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form, 'data' => $data));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user, 'form' => $form, 'data' => $data));
        return $user;
    }

    public function edit(array $data, $user)
    {
        foreach($this->getOptions()->getEditFormElements() as $element)
        {
            if($element === 'password')
            {
                if ($data['password'] !== $user->getPassword()) {
                    // Password does not match, so password was changed
                    $bcrypt = new Bcrypt();
                    $bcrypt->setCost($this->getServiceManager()->get('zfcuser_module_options')->getPasswordCost());
                    $user->setPassword($bcrypt->create($data['password']));
                }
            } else
            {
                $func = 'set' . ucfirst($element);
                $user->$func($data[$element]);
            }
        }
        $this->getUserMapper()->update($user);
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'data' => $data));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user, 'data' => $data));
        return $user;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('zfcuser_user_mapper');
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
     * @param ServiceManager $locator
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}