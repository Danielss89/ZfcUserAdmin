<?php

namespace ZfcUserAdmin\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements
    UserListOptionsInterface
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    protected $userListElements = array('id', 'email');

    public function setUserListElements(array $listElements)
    {
        $this->userListElements = $listElements;
    }

    public function getUserListElements()
    {
        return $this->userListElements;
    }
}