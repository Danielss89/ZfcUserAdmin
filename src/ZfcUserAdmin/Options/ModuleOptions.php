<?php

namespace ZfcUserAdmin\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements
    UserListOptionsInterface,
    UserEditOptionsInterface,
    UserCreateOptionsInterface
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * Array of data to show in the user list
     * Key = Label in the list
     * Value = entity property(expecting a 'getProperty())
     */
    protected $userListElements = array('Id' => 'id', 'Email address' => 'email');

    /**
     * Array of form elements to show when editing a user
     * Key = form label
     * Value = entity property(expecting a 'getProperty()/setProperty()' function)
     */
    protected $editFormElements = array();

    /**
     * Array of form elements to show when creating a user
     * Key = form label
     * Value = entity property(expecting a 'getProperty()/setProperty()' function)
     */
    protected $createFormElements = array();

    /**
     * @var bool
     * true = create password automaticly
     * false = administrator chooses password
     */
    protected $createUserAutoPassword = true;

    /**
     * @var int
     * Length of passwords created automatically
     */
    protected $autoPasswordLength = 8;

    /**
     * @var bool
     * Allow change user password on user edit form.
     */
    protected $allowPasswordChange = true;

    protected $userMapper = 'ZfcUserAdmin\Mapper\UserDoctrine';

    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function getUserMapper()
    {
        return $this->userMapper;
    }

    public function setUserListElements(array $listElements)
    {
        $this->userListElements = $listElements;
    }

    public function getUserListElements()
    {
        return $this->userListElements;
    }

    public function getEditFormElements()
    {
        return $this->editFormElements;
    }

    public function setEditFormElements(array $elements)
    {
        $this->editFormElements = $elements;
    }

    public function setCreateFormElements(array $createFormElements)
    {
        $this->createFormElements = $createFormElements;
    }

    public function getCreateFormElements()
    {
        return $this->createFormElements;
    }

    public function setCreateUserAutoPassword($createUserAutoPassword)
    {
        $this->createUserAutoPassword = $createUserAutoPassword;
    }

    public function getCreateUserAutoPassword()
    {
        return $this->createUserAutoPassword;
    }

    public function getAllowPasswordChange()
    {
        return $this->allowPasswordChange;
    }

    public function setAdminPasswordChange($allowPasswordChange)
    {
        $this->allowPasswordChange = $allowPasswordChange;
    }

    public function setAutoPasswordLength($autoPasswordLength)
    {
        $this->autoPasswordLength = $autoPasswordLength;
    }

    public function getAutoPasswordLength()
    {
        return $this->autoPasswordLength;
    }
}
