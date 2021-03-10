<?php

declare(strict_types=1);

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Element\Link;

final class LinkTitleExtension implements ExtensionInterface
{
    /** @var UnfurlResolver */
    private $resolver;

    public function __construct(UnfurlResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function register(ConfigurableEnvironmentInterface $environment): void
    {
        // Intercept Link elements and add title attribute
        $environment->addEventListener(DocumentParsedEvent::class, function (DocumentParsedEvent $e): void {
            $walker = $e->getDocument()->walker();

            while ($event = $walker->next()) {
                $node = $event->getNode();
                if (!($node instanceof Link) || !$event->isEntering()) {
                    continue;
                }

                $this->resolver->resolve($node);
            }
        });
    }
}
