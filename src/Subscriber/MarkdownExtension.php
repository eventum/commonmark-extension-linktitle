<?php

namespace Eventum\Delfi\Subscriber;

use Eventum\Event\SystemEvents;
use League\CommonMark\Environment;
use League\CommonMark\Extension\ExtensionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class MarkdownExtension implements EventSubscriberInterface
{
    /** @var ExtensionInterface[] */
    private $extensions;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            /** @see registerExtensions */
            SystemEvents::MARKDOWN_ENVIRONMENT_CONFIGURE => 'registerExtensions',
        ];
    }

    public function registerExtensions(GenericEvent $event): void
    {
        /** @var Environment $environment */
        $environment = $event->getSubject();
        foreach ($this->extensions as $extension) {
            $environment->addExtension($extension);
        }
    }
}
