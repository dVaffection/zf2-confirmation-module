<?php

namespace Confirmation\Controller;

use Zend\EventManager\Event;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        /* @var $cs \Confirmation\Service\Confirmation */
        $cs           = $this->getServiceLocator()->get('confirmation_service');
        $confirmation = $cs->find($this->params()->fromRoute('id'));

        if (!$confirmation) {
            return $this->notFoundAction();
        }

        // pre dispatch action
        $event = 'confirmation.pre_dispatch';
        $argv  = array(
            'confirmation'         => $confirmation,
            'confirmation_service' => $cs,
        );
        $this->getEventManager()->trigger($event, null, $argv);


        // action dispatching
        $name   = $confirmation->getController();
        $params = $confirmation->getParams() + array(
            'action' => $confirmation->getAction(),
        );
        $return = $this->forward()->dispatch($name, $params);


        // post dispatch action
        $event = 'confirmation.post_dispatch';
        $argv  = array(
            'confirmation'         => $confirmation,
            'confirmation_service' => $cs,
        );
        // add listener to remove current comfirmation
        $this->getEventManager()
            ->attach($event, array($this, 'removeConfirmation'), 1);
        $this->getEventManager()->trigger($event, null, $argv);

        return $return;
    }

    public function removeConfirmation(Event $e)
    {
        /* @var $cs \Confirmation\Service\Confirmation */
        $cs           = $e->getParam('confirmation_service');
        /* @var $confirmation \Confirmation\Entity\ConfirmationInterface */
        $confirmation = $e->getParam('confirmation');

        $cs->delete($confirmation->getId());
    }

    public function cleanExpiredAction()
    {
        /* @var $cs \Confirmation\Service\Confirmation */
        $cs = $this->getServiceLocator()->get('confirmation_service');
        $return = $cs->cleanExpired();


        if (is_null($return)) {
            $message = 'Confirmations were deleted: ' . $return;
        } else {
            $message = 'Some confirmations probably deleted';
        }

        /* @var $console Console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine($message, Color::GREEN);
    }

}
