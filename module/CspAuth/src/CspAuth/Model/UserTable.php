<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/27/16
 * Time: 11:02 PM
 */

namespace CspAuth\Model;


use Zend\Db\TableGateway\TableGateway;

class UserTable
{
    protected $tableGateWay;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateWay = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateWay->select();
        return $resultSet;
    }

    public function get($id)
    {
        $id = (int) $id;
        $rowSet = $this->tableGateWay->select(array('usr_id'=>$id));
        $row = $rowSet->current();
        if(!$row){
            throw new \Exception('Could not find $id.');
        }
        return $row;
    }

    public function getByEmail($email)
    {
        $rowSet = $this->tableGateWay->select(array('usr_email'=>$email));
        $row = $rowSet->current();
        if(!$row){
            throw new \Exception('Could not find $id.');
        }
        return $row;
    }

    public function save()
    {

    }

    public function delete($id)
    {
        $this->tableGateWay->delete(array('usr_id'=> (int) $id));
    }
}