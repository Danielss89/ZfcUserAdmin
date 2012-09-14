<?php

namespace ZfcUserAdmin\Options;

interface ModuleOptionsInterface
{
    public function getUserMapper();

    public function setUserMapper($mapper);
}
