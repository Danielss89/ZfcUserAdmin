<?php

namespace ZfcUserAdmin\Mapper;

use ZfcUser\Mapper\User as ZfcUserMapper;
use Zend\Db\ResultSet\HydratingResultSet;

class UserZendDb extends ZfcUserMapper
{
    public function findAll() 
    {
        $select = $this->getSelect($this->tableName);
        $select->order(array('username ASC', 'display_name ASC', 'email ASC'));
        //$resultSet = $this->select($select);

        $resultSet = new HydratingResultSet($this->getHydrator(), $this->getEntityPrototype());
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->getSlaveSql(), $resultSet);
        $paginator = new \Zend\Paginator\Paginator($adapter);

        return $paginator;
    }

    
    public function remove($entity)
    {
        $id = $entity->getId();
        $this->delete(array('user_id' => $id));        
    }
}
