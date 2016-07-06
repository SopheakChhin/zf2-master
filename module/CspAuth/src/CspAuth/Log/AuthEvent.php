<?php
/**
 * Created by PhpStorm.
 * User: jimmy
 * Date: 7/4/16
 * Time: 9:50 AM
 */

namespace CspAuth\Log;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class AuthEvent implements EventManagerAwareInterface
{
    protected $eventManager;

    public function login($db_aciton, $system_action, $system_action_desc, $params, $new_query, $old_query, $user_id)
    {

        // Action API...
        /*$e = $this->getEventManager();
        $event  = $e->getName();
        $target = get_class($e->getTarget()); // "Example"
        $params = $e->getParams();
        echo '<br> event name: '.$event;
        echo '<br> event target: '.$target;
        echo '<br> event params: '.$params;*/
        //var_dump($event);
        //exit;
        // Trigger an event
        $this->getEventManager()->trigger('login', null, array(
            'db_aciton' => $db_aciton,
            'system_action' => $system_action,
            'system_action_desc' => $system_action_desc,
            'params' => $params,
            'new_query' => $new_query,
            'old_query' => $old_query,
            'user_id' => $user_id,
        ));
    }

    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(
            array(
                'Application\Service\ServiceInterface',
                get_called_class()
            )
        ); //new line

        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }
}