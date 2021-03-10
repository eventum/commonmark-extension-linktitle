<?php

namespace Eventum\Delfi\Subscriber;

use Eventum\Delfi\CommonMark\Extension\LinkTitle\LinkTitleExtension;
use Eventum\Event\SystemEvents;
use League\CommonMark\Environment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class MarkdownExtension implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            /** @see markdownExtension */
            SystemEvents::MARKDOWN_ENVIRONMENT_CONFIGURE => 'markdownExtension',
        ];
    }

    public function markdownExtension(GenericEvent $event): void
    {
        $environment = $event->getSubject();
        $this->applyExtensions($environment);
    }

    private function applyExtensions(Environment $environment): void
    {
        $environment->addExtension(new LinkTitleExtension());
    }
}
