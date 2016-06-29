<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/25/16
 * Time: 12:59 AM
 */

namespace CspAuth;

use CspAuth\Model\User;
use CspAuth\Model\UserTable;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //$serviceManager = $e->getApplication()->getServiceManager();

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'boforeDispatch'
        ), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'afterDispatch'
        ), -100);
    }

    public function init(ModuleManager $mm)
    {
        $mm->getEventManager()->getSharedManager()->attach(__NAMESPACE__, 'dispatch', function($e) {
            $e->getTarget()->layout('layout/login');
        });
    }

    function boforeDispatch(MvcEvent $event){

        $request = $event->getRequest();
        $response = $event->getResponse();
        $target = $event->getTarget ();

        /* Offline pages not needed authentication */
        $whiteList = array (
            //'Csp-Auth\Controller\Index-index',
            'Application\Controller\Index-index',
            //'User\Controller\Index-index',
        );

        $serviceManager = $event->getApplication()->getServiceManager();
        $hasIdentity = $serviceManager->get('AuthService')->hasIdentity();

        //get cookie
        $cookie = $event->getRequest()->getCookie();
        print_r($cookie);

        $requestUri = $request->getRequestUri();
        $controller = $event->getRouteMatch ()->getParam ( 'controller' );
        $action = $event->getRouteMatch ()->getParam ( 'action' );

        $requestedResource = $controller . "-" . $action;


        $session = new Container('User');

        if ($session->offsetExists ( 'email' ) && $hasIdentity) {
            if ($requestedResource == 'CspAuth\Controller\Index-index' || in_array ( $requestedResource, $whiteList )) {
                $url = '/user';
                $response->setHeaders ( $response->getHeaders ()->addHeaderLine ( 'Location', $url ) );
                $response->setStatusCode ( 302 );
            }
        }else{

            if ($requestedResource != 'CspAuth\Controller\Index-index' && ! in_array ( $requestedResource, $whiteList )) {
                $url = '/csp-auth';
                $response->setHeaders ( $response->getHeaders ()->addHeaderLine ( 'Location', $url ) );
                $response->setStatusCode ( 302 );
            }
            $response->sendHeaders ();
        }

        //print "Called before any controller action called. Do any operation.";
    }

    function afterDispatch(MvcEvent $event){
        //print "Called after any controller action called. Do any operation.";
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'AuthService' => function ($serviceManager) {
                    $adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $dbAuthAdapter = new DbAuthAdapter($adapter, 'users', 'usr_email', 'usr_password');

                    $auth = new AuthenticationService();
                    $auth->setAdapter($dbAuthAdapter);
                    return $auth;
                },
                'CspAuth\Model\UserTable' => function($sm)
                {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function($sm){
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('users', $adapter, null, $resultSetPrototype);
                }
            ),
        );
    }
}