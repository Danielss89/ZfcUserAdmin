<?php

namespace ZfcUserAdmin\Mapper;

use ZfcUser\Mapper\User as UserMapper;
class User
{
    private $userMapper;
    
    public function findAll() 
    {
        //findall code here;
        return null;
    }

    public function findByEmail($email)
    {
        return $this->userMapper->findByEmail($email);
    }

    public function findByUsername($username)
    {
        return $this->userMapper->findByUsername($username);
    }
    
    public function findById($id)
    {
        return $this->userMapper->findById($id);
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->userMapper->insert($entity, $tableName, $hydrator);
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->userMapper->update($entity, $where, $tableName, $hydrator);
    }

    public function setUserMapper(UserMapper $userMapper) 
    {
        $this->userMapper = $userMapper;
    }
}