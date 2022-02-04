<?php

namespace App\EventSubscriber;

use App\Entity\Annonce;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setAnnonceAndDateAndUser'],
            BeforeCrudActionEvent::class => ['afterEnvet'],
        ];
    }

    public function setAnnonceAndDateAndUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (($entity instanceof Annonce)) {
            $now = new DateTime('now');
            $entity->setCreatedAt($now);

            $user = $this->security->getUser(); 
            $entity->setAuteur($user);
        }
        
        return;
    }
    public function afterEnvet(BeforeCrudActionEvent $event)
    {
        $entity = $event;

        // dd($entity);
        
        return;
    }
}