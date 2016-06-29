<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/25/16
 * Time: 4:07 PM
 */

namespace CspAuth\Form;

use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;

        $this->add(array(
            'name'      =>  'usr_email',
            'required'  =>  true,
            'filters'   =>  array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'Validators' =>  array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'Email can not empty.'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                            $invalidEmail => 'Email address is invalid.'
                        )
                    )
                )
            )
        ));

        $this->add(array(
            'name'      => 'usr_password',
            'required'  => true,
            'filters'   => array(
                array(
                    'name' => 'StripTags',
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name'  => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'Password can not be empty.'
                        )
                    )
                )
            )
        ));
    }

}