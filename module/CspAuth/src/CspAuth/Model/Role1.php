<?php
/**
 * Created by PhpStorm.
 * User: sopheak
 * Date: 6/30/2016
 * Time: 5:09 PM
 */

namespace CspAuth\Model;


class Role1 {
    public $rid;
    public $role_name;
    public $status;

    public function exchangeArray(){
        $this->rid = (!empty($data['rid']))? $data['rid'] : null;
        $this->role_name = (!empty($data['role_name']))? $data['role_name'] : null;
        $this->status = (!empty($data['status']))? $data['status'] : null;
    }
}