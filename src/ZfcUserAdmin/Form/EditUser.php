<?php

namespace ZfcUserAdmin\Form;

use ZfcUserAdmin\Options\UserEditOptionsInterface;
use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class EditUser extends ProvidesEventsForm
{
    protected $userEditOptions;
    protected $userEntity;
    protected $serviceManager;

    public function __construct($name = null, UserEditOptionsInterface $options, $serviceManager)
    {
        $this->setUserEditOptions($options);
        parent::__construct($name);

        $this->setServiceManager($serviceManager);

        foreach($this->getUserEditOptions()->getEditFormElements() as $name => $element)
        {
            $this->add(array(
                'name' => $element,
                'options' => array(
                    'label' => $name,
                ),
                'attributes' => array(
                    'type' => 'text'
                ),
            ));
        }

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Edit')
            ->setAttributes(array(
                'type'  => 'submit',
            ));
            
        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->add(array(
            'name' => 'userId',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    public function setUser($userEntity)
    {
        $this->userEntity = $userEntity;
        $this->getEventManager()->trigger('userSet', $this, array('user' => $userEntity));
    }

    public function getUser()
    {
        return $this->userEntity;
    }

    public function populateFromUser($user)
    {
        foreach($this->getUserEditOptions()->getEditFormElements() as $element)
        {
            $func = 'get' . ucfirst($element);
            $this->get($element)->setValue($user->$func());
        }
        $this->get('userId')->setValue($user->getId());
    }

    public function setUserEditOptions(UserEditOptionsInterface $userEditOptions)
    {
        $this->userEditOptions = $userEditOptions;
        return $this;
    }

    public function getUserEditOptions()
    {
        return $this->userEditOptions;
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}