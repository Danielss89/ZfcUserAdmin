# ZfcUserAdmin Module for Zend Framework 2

Version 0.1

## Introduction

This module provides an interface to create/edit/delete users.

## Installation

### Using composer

1. Add `danielss89/zfc-user-admin` (version `dev-master`) to requirements
2. Run `update` command on composer

### Manually

1. Clone this project into your `./vendor/` directory and enable it in your
   `application.config.php` file.
2. Clone `https://github.com/juriansluiman/ZfcAdmin` into your `./vendor/` directory and enable it in your
   `application.config.php` file.

### Requires

1. ZfcAdmin
2. ZfcUser

## Usage

### Override default module config

Copy `<zfc-user-admin>/config/ZfcUserAdmin.global.php.dist` to `<project root>/autoload/ZfcUserAdmin.global.php` and
edit required module options (full list will be added to doc later, for now you can find all available options in
`<zfc-user-admin>/src/Options/ModuleOptions.php` - just look at class properties and convert upper case to
dash plus lower case, e.g. createUserAutoPassword -> create_user_auto_password). E.g.:

```
<?php
/**
 * ZfcUserAdmin Configuration
 */
$settings = array(
    'user_mapper' => 'ZfcUserAdmin\Mapper\UserZendDb',
    'user_list_elements' => array('Id' => 'id', 'Name' => 'display_name', 'Email address' => 'email'),
    'create_user_auto_password' => false,
    ...
);

/**
 * You do not need to edit below this line
 */
return array(
    'zfcuseradmin' => $settings
);

```

TODO: add more usage information and module options list

## Authors

* [Daniel Strøm](https://github.com/Danielss89)
* [Martin Shwalbe](https://github.com/Hounddog)
* [Vladimir Garvardt](https://github.com/vgarvardt)