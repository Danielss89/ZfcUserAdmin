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
     * @TODO: change "things" below
     * Array of "things" to show in the user list
     */
    protected $userListElements = array('Id' => 'id', 'Email address' => 'email');

    /**
     * Array of form elements to show when editing a user
     * Key = form label
     * Value = entity property(expecting a 'getProperty()/setProperty()' function)
     */
    protected $editFormElements = array('Email' => 'email', 'Password' => 'password');

    /**
     * Array of form elements to show when creating a user
     * Key = form label
     * Value = entity property(expecting a 'getProperty()/setProperty()' function)
     */
    protected $createFormElements = array('Email' => 'email', 'Password' => 'password');

    /**
     * @var bool
     * true = create password automaticly
     * false = administrator chooses password
     */
    protected $createUserAutoPassword = true;
    
    /**
     * If it sends an email to the user after it's creation
     * 
     * @var bool
     */
    protected $createUserSendsEmail = false;

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

    public function getEditFormElements(){
        return $this->editFormElements;
    }

    public function setEditFormElements(array $elements){
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
    
	public function getCreateUserSendsEmail()
    {
        return $this->createUserSendsEmail;
    }

	public function setCreateUserSendsEmail($createUserSendsEmail)
    {
        $this->createUserSendsEmail = $createUserSendsEmail;
    }

}
