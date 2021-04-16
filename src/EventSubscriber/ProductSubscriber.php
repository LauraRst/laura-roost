<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security){
        $this->security = $security;
    }
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUser'],
            BeforeEntityUpdatedEvent::class => ['setUpdatedDate'],
        ];
    }

    public function setUser(BeforeEntityPersistedEvent $event) {

        $entity = $event->getEntityInstance();
        if($entity instanceof Product){
            $entity->setUser($this->security->getUser());
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());

        }
    }

    public function setUpdatedDate(BeforeEntityUpdatedEvent  $event) {
        $entity = $event->getEntityInstance();
        if($entity instanceof Product){
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}
