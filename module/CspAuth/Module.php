<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/25/16
 * Time: 12:59 AM
 */

namespace CspAuth;

use CspAuth\Log\AuthListener;
use CspAuth\Model\PermissionTable;
use CspAuth\Model\ResourceTable;
use CspAuth\Model\Role;
use CspAuth\Model\RolePermissionTable;
use CspAuth\Model\User;
use CspAuth\Model\UserTable;
use CspAuth\Utility\Acl;
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

        //Log event attach
        //$eventManager->attach(new AuthListener());

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

        $requestUri = $request->getRequestUri();
        $controller = $event->getRouteMatch ()->getParam ( 'controller' );
        $action = $event->getRouteMatch ()->getParam ( 'action' );

        $requestedResource = $controller . "-" . $action;


        $session = new Container('User');

/*
        $serviceManager = $event->getApplication()->getServiceManager();
        $hasIdentity = $serviceManager->get('AuthService')->hasIdentity();

        //get cookie
        $cookie = $event->getRequest()->getCookie();
        //print_r($cookie);

        && $hasIdentity*/

        if ($session->offsetExists ( 'email' )) {
            if ($requestedResource == 'CspAuth\Controller\Index-index' || in_array ( $requestedResource, $whiteList )) {
                $url = '/user';
                $response->setHeaders ( $response->getHeaders ()->addHeaderLine ( 'Location', $url ) );
                $response->setStatusCode ( 302 );
            }else{
                //implementation ACL
                $serviceManager = $event->getApplication()->getServiceManager();
                //$userRole = $session->offsetGet('roleName');
                $userRole = "Role1";

                $acl = $serviceManager->get('Acl');
                $acl->initAcl();

                $status = $acl->isAccessAllowed($userRole, $controller, $action);
                if (! $status) {
                    die('Permission denied');
                }
            }
        }else{

            if ($requestedResource != 'CspAuth\Controller\Index-index' && ! in_array ( $requestedResource, $whiteList )) {
                $url = '/login';

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
                },
                'Acl' => function ($serviceManager)
                {
                    return new Acl();
                },
                'UserTable' => function ($serviceManager)
                {
                    return new User($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'RoleTable' => function ($serviceManager)
                {
                    return new Role($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'UserRoleTable' => function ($serviceManager)
                {
                    return new UserRole($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'PermissionTable' => function ($serviceManager)
                {
                    return new PermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'ResourceTable' => function ($serviceManager)
                {
                    return new ResourceTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'RolePermissionTable' => function ($serviceManager)
                {
                    return new RolePermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                }
            ),
        );
    }
}