<?php

declare(strict_types=1);

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Element\Link;

final class LinkTitleExtension implements ExtensionInterface
{
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

                $this->linkWalker($node);
            }
        });
    }

    private function linkWalker(Link $link): void
    {
    }
}
