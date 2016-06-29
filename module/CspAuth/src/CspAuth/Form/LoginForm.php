<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/25/16
 * Time: 3:28 PM
 */

namespace CspAuth\Form;


use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Csrf;

class LoginForm extends Form
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setAttributes(array('Method' => 'POST', 'class'=>'form-horizontal form-validate', 'role'=>'form'));

        $this->add(array(
            'name'  => 'usr_email',
            'type'  => 'text',
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array('class'=>'control-label')
            ),
            'attributes' => array(
                'id'    => 'usr_email',
                'placeholder' => 'username or email',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name'  =>  'usr_password',
            'type'  =>  'password',
            'options' =>array(
                'label'    =>  'Password',
            ),
            'attributes' => array(
                'id'    => 'usr_email',
                'placeholder' => '********',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name'  => 'usr_remember',
            'type'  => 'checkbox',
            'attributes' => array(
                'id'    => 'usr_remember',
                //'class' => 'checkbox'
            ),
            'options' => array(
                'checked_value' => 1,
                'unchecked_value' => 'no',
            )
        ));

        $this->add(array(
            'name'  =>  'usr_csrf',
            'type'  =>  'Zend\Form\Element\Csrf',
            'options'=>array(
                'csrf_options'=>array(
                    'timeout'=>3600
                )
            )
        ));

        $this->add(array(
            'name'  =>  'usr_login',
            'attributes'   =>array(
                'type'  =>  'submit',
                'value' =>  'Login',
                'class' =>  'btn btn-primary'
            )
        ));

    }
}