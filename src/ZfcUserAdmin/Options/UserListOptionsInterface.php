<?php

namespace ZfcUserAdmin\Options;

interface UserListOptionsInterface
{
    public function getUserListElements();

    public function setUserListElements(array $elements);
    
    public function getUserCountPerPage();
    
    public function setUserCountPerPage($UserCountPerPage);
}