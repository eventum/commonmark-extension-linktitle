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
            try {
                if ($resolver->accept($link)) {
                    $result = $resolver->unfurl($link);
                    $link->data['attributes']['title'] = $result['title'];
                }
            } catch (Throwable $e) {
                $this->error($e->getMessage(), ['e' => $e]);
            }
        }

        return [];
    }
}
