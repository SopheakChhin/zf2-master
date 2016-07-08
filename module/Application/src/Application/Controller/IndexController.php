<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Services\ServicesSoundcloud;
use Services\TestClass;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $test = new TestClass();
        $test->HelloWorld();
        $soundcloud = new ServicesSoundcloud('13c9349570f6be07045639ca831b1b99','7a68f6a0b0119f7d488fd9abc6dc8c91',null);

        $response = json_decode($soundcloud->get('me'), true);

        var_dump($response);

        return new ViewModel();
    }
}
