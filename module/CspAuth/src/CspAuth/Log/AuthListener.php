<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 7/4/16
 * Time: 9:50 AM
 */

namespace CspAuth\Log;


use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class AuthListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach('CspAuth\Log\AuthEvent', 'login', array($this, 'onLogin'), 100);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onLogin($e)
    {
        echo '<br><br><br>';
        var_dump($e);
        $event  = $e->getName();
        $target = get_class($e->getTarget()); // "Example"
        $params = $e->getParams();
        echo '<br> event name: '.$event;
        echo '<br> event target: '.$target;
        echo '<br> event params: '.$params;

        //$params = $e->getParams();
        //var_dump($params);
        exit;
    }

    public function afterLogin($e)
    {
        echo 'test';
    }

}