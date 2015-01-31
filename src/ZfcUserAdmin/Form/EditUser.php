<?php

namespace ZfcUserAdmin\Form;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;
use ZfcUser\Options\RegistrationOptionsInterface;
use ZfcUser\Entity\UserInterface;
use ZfcUser\Form\Base;
use ZfcUserAdmin\Options\UserEditOptionsInterface;

class EditUser extends Base
{
    /**
     * @var \ZfcUserAdmin\Options\UserEditOptionsInterface
     */
    protected $userEditOptions;
    protected $userEntity;
    protected $serviceManager;

    public function __construct($name = null, UserEditOptionsInterface $options, RegistrationOptionsInterface $registerOptions, $serviceManager)
    {
        $this->setUserEditOptions($options);
        $this->setServiceManager($serviceManager);
        parent::__construct($name, $registerOptions);
        // ZfcUser should have setHydrator() which we replace or extend
        $this->setHydrator($serviceManager->get('zfcuser_user_hydrator'));

        // Render using ZfcAdmin form class
        $this->setAttribute('class', 'zend_form');

        $this->remove('captcha');

        if ($this->userEditOptions->getAllowPasswordChange()) {
            $this->add(array(
                'name' => 'reset_password',
                'type' => 'Zend\Form\Element\Checkbox',
                'required' => true,
                'options' => array(
                    'label' => 'Reset password to random',
                    'exclude' => true,
                ),
            ));

            $password = $this->get('password');
            $password->setAttribute('required', false);
            $password->setOptions(
                array(
                    'label' => 'Password (only if want to change)',
                    'exclude' => true,
                )
            );

            $this->remove('passwordVerify');
        } else {
            $this->remove('password')->remove('passwordVerify');
        }

        foreach ($this->getUserEditOptions()->getEditFormElements() as $name => $element) {
            // avoid adding fields twice (e.g. email)
            // if ($this->get($element)) continue;

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

        $this->get('submit')->setLabel('Save')->setValue('Save');

        $this->getEventManager()->trigger('init', $this);
    }

    protected function getAccessorName($property, $set = true)
    {
        $parts = explode('_', $property);
        array_walk($parts, function (&$val) {
            $val = ucfirst($val);
        });
        return (($set ? 'set' : 'get') . implode('', $parts));
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

    /**
     * Override isValid() to set an validation group of all elements that do not
     * have an 'exclude' option, if at least one element has this option set.
     *
     * @return boolean
     */
    public function isValid()
    {
        if ($this->hasValidated) {
            return $this->isValid;
        }

        if ($this->getValidationGroup() === null) {
            // Add all non-excluded elements to the validation group
            $validationGroup = null;
            foreach ($this->getElements() as $element) {
                if ($element->getOption('exclude') === false or
                    ($element->getAttribute('readonly') !== true && $element->getOption('exclude') !== true))
                {
                    $validationGroup[] = $element->getName();
                }
            }
            if ($validationGroup) {
                $this->setValidationGroup($validationGroup);
            }
        }

        return parent::isValid();
    }
}
