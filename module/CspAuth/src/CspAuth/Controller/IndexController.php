<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 6/25/16
 * Time: 1:07 AM
 */

namespace CspAuth\Controller;


use CspAuth\Form\LoginFilter;
use CspAuth\Form\LoginForm;
use CspAuth\Log\AuthEvent;
use CspAuth\Utility\UserPassword;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected $storage;
    protected $authservice;

    protected $userTable;

    public function getUserTable()
    {
        if(!$this->userTable)
        {
            $this->userTable = $this->getServiceLocator()->get('CspAuth\Model\UserTable');
        }
        return $this->userTable;
    }

    public function __construct()
    {
    }

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }

    public function indexAction()
    {

        $request = $this->getRequest();

        $viewModel = new ViewModel();
        $loginForm = new LoginForm('loginform');
        $loginForm->setInputFilter(new LoginFilter());

        //Post data validation, form filter validation
        if($request->isPost()){
            $data = $request->getPost();
            $loginForm->setData($data);

            if($loginForm->isValid())
            {
                $data = $loginForm->getData();

                //encrypt user password
                $userPassword = new UserPassword();
                $encryptPass = $userPassword->create($data['usr_password']);

                //$authService = $this->getServiceLocator()->get('AuthService');


                $this->getAuthService()->getAdapter()
                    ->setIdentity($data['usr_email'])
                    ->setCredential($encryptPass);

                $result = $this->getAuthService()->authenticate();
                if($result->isValid()){
                    $userRow = $this->getUserTable()->getByEmail($data);

                    //create session
                    $session = new Container('User');
                    $session->offsetSet('id',$userRow->usr_id);
                    $session->offsetSet('email',$userRow->usr_email);
                    $session->offsetSet('full_name',trim($userRow->usr_last_name.' '.$userRow->usr_first_name));
                    $session->offsetSet('role_id',$userRow->role_id);

                    //var_dump($session->email);echo 'test';exit;
                    //var_dump("get user from \$session", $session->offsetGet('User'));exit;

                    $this->flashMessenger()->addSuccessMessage('Success login...');
                    $this->redirect()->toRoute('application');
                }else{
                    $this->flashMessenger()->addErrorMessage('Invalid Credential.');
                }

            }
            //print_r($request->getPost());
        }

        $viewModel->setVariables(array('loginForm'=>$loginForm));
        return $viewModel;
    }

    public function loginAction()
    {

        return new ViewModel();
    }

    public function logoutAction()
    {
        $session = new Container('User');
        $session->getManager()->destroy();
        $this->getAuthService()->clearIdentity();

        //$this->flashMessenger()->addSuccessMessage('Success logout.');
        return $this->redirect()->toUrl('/login');
    }

    //service manager integration
    /*$authEvent = new AuthEvent();

                $event  = $e->getName();
                $target = get_class($e->getTarget()); // "Example"
                $params = $e->getParams();
                $authEvent->login('db_action','system_action', 'system_action_desc', $data, 'new_query', 'old_query', '');
                $authEvent->getEventManager()->attach('login', function ($e) {
                    $event = $e->getName();
                    $params = $e->getParams();
                    printf(
                        'Handled event "%s", with parameters %s',
                        $event,
                        json_encode($params)
                    );exit;
                });*/
}