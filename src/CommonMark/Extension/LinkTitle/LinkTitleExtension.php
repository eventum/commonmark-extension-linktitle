<?php

declare(strict_types=1);

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use Eventum\Logger\LoggerTrait;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Element\Link;
use Psr\Log\LoggerInterface;
use Throwable;

final class LinkTitleExtension implements ExtensionInterface
{
    use LoggerTrait;

    /** @var UnfurlResolver */
    private $resolver;

    public function __construct(
        UnfurlResolver $resolver,
        LoggerInterface $logger
    ) {
        $this->resolver = $resolver;
        $this->logger = $logger;
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

                try {
                    $this->resolver->resolve($node);
                } catch (Throwable $e) {
                    $this->error($e->getMessage(), ['e' => $e]);
                }
            }
        });
    }
}
