<?php

namespace App\Events;

use App\Entity\SlugInterface;
use App\Service\SlugService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PrePersistEventSubscriber implements EventSubscriber
{

    public function __construct(
        private SlugService $textService
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void {
        $object = $eventArgs->getObject();

        if ($object instanceof SlugInterface) {
            $object->setSlug($this->textService->slugify($object->getNom()));
        }
    }

}