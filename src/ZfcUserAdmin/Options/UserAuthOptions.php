<?php

namespace ZfcUserAdmin\Options;

use ZfcUser\Options\ModuleOptions as UserModuleOptions;

class UserAuthOptions extends UserModuleOptions
{
    /**
* @var string
*/
    protected $loginRedirectRoute = 'zfcadmin';

    /**
* @var string
*/
    protected $logoutRedirectRoute = 'zfcuseradminauth/login';
}