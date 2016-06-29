<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/29/16
 * Time: 10:59 AM
 */

namespace User\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }
}