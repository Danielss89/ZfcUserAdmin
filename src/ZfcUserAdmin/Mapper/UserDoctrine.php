<?php

namespace ZfcUserAdmin\Mapper;

use ZfcUserDoctrineORM\Mapper\User as ZfcUserDoctrineMapper;

class UserDoctrine extends ZfcUserDoctrineMapper
{
    public function findAll() 
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return $er->findAll();
    }
}