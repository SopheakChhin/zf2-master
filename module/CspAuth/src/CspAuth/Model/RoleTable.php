<?php
/**
 * Created by PhpStorm.
 * User: sopheak
 * Date: 6/30/2016
 * Time: 5:13 PM
 */

namespace CspAuth\Model;


use Zend\Db\TableGateway\TableGateway;

class RoleTable {
    protected $tableGateWay;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateWay = $tableGateway;
    }

    public function fetchAll($status)
    {
        $resultSet = $this->tableGateWay->select();

        if(isset($status))
        {
            $resultSet->where('Status', $status);
        }

        return $resultSet->selectWith($resultSet);
    }
}