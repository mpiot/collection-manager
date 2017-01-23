<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Primer;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PrimerSubscriber implements EventSubscriber
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If the entity is not a Primer return
        if (!$entity instanceof Primer) {
            return;
        } else {
            $primer = $entity;
        }

        $primerNumber = $primer->getTeam()->getLastPrimerNumber() + 1;

        // Determine how many 0 put before the number
        $nbDigit = 4;
        $numberOf0 = $nbDigit - floor(log10($primerNumber) + 1);

        if ($numberOf0 < 0) {
            $numberOf0 = 0;
        }

        $autoName = 'primer'.str_repeat('0', $numberOf0).$primerNumber;

        // Set autoName
        $primer->setAutoName($autoName);
        $primer->getTeam()->setLastPrimerNumber($primerNumber);

        $primer->setAuthor($this->tokenStorage->getToken()->getUser());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If the entity is not a Primer return
        if (!$entity instanceof Primer) {
            return;
        } else {
            $primer = $entity;
        }

        $primer->setLastEditor($this->tokenStorage->getToken()->getUser());
        $primer->setLastEdit(new \DateTime());
    }
}
