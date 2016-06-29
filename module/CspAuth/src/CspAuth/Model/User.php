<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/27/16
 * Time: 10:38 PM
 */

namespace CspAuth\Model;


class User
{
    public $usr_id;
    public $usr_first_name;
    public $usr_last_name;
    public $usr_name;
    public $usr_password;
    public $usr_email;
    public $lat_id;
    public $lng_id;
    public $usr_active;
    public $usr_question;
    public $usr_answer;
    public $usr_picture;
    public $usr_password_salt;
    public $usr_registration_date;
    public $usr_registration_token;
    public $usr_email_confirmed;
    public $role_id;

    //data exchange array
    public function exchangeArray($data)
    {
        $this->usr_id = (!empty($data['usr_id']))? $data['usr_id'] : null;
        $this->usr_first_name = !empty($data['usr_first_name'])? $data['usr_first_name'] : null;
        $this->usr_last_name = !empty($data['usr_last_name'])? $data['usr_last_name'] : null;
        $this->usr_name = !empty($data['usr_name'])? $data['usr_name'] : null;
        $this->usr_password = !empty($data['usr_password'])? $data['usr_password'] : null;
        $this->usr_email = !empty($data['usr_id'])? $data['usr_email'] : null;
        $this->lat_id = !empty($data['lat_id'])? $data['lat_id'] : null;
        $this->lng_id = !empty($data['lng_id'])? $data['lng_id'] : null;
        $this->usr_active = !empty($data['usr_active'])? $data['usr_active'] : 0;
        $this->usr_question = !empty($data['usr_question'])? $data['usr_question'] : null;
        $this->usr_answer = !empty($data['usr_answer'])? $data['usr_answer'] : null;
        $this->usr_picture = !empty($data['usr_picture'])? $data['usr_picture'] : null;
        $this->usr_password_salt = !empty($data['usr_password_salt'])? $data['usr_password_salt'] : null;
        $this->usr_registration_date = !empty($data['usr_registration_date'])? $data['usr_registration_date'] : date('Y-m-d H:i:s');
        $this->usr_registration_token = !empty($data['usr_registration_token'])? $data['usr_registration_token'] : null;
        $this->usr_email_confirmed = !empty($data['usr_email_confirmed'])? $data['usr_email_confirmed'] : 0;
        $this->role_id = !empty($data['role_id'])? $data['role_id'] : null;
    }
}