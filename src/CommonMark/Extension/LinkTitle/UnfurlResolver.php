<?php

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use Eventum\Logger\LoggerTrait;
use League\CommonMark\Inline\Element\Link;
use Psr\Log\LoggerInterface;
use Throwable;

class UnfurlResolver implements UnfurlInterface
{
    use LoggerTrait;

    /** @var UnfurlInterface[] */
    private $resolvers;

    public function __construct(
        array $resolvers,
        LoggerInterface $logger
    ) {
        $this->resolvers = $resolvers;
        $this->logger = $logger;
    }

    public function accept(Link $link): bool
    {
        return count($this->resolvers) > 0;
    }

    public function unfurl(Link $link): array
    {
        foreach ($this->resolvers as $resolver) {
            if (!$resolver->accept($link)) {
                continue;
            }

            try {
                $result = $resolver->unfurl($link);
            } catch (Throwable $e) {
                $this->error($e->getMessage(), ['e' => $e]);
                continue;
            }

            if ($result['title'] ?? null) {
                $link->data['attributes']['title'] = $result['title'];
            }
        }

        return [];
    }
}
