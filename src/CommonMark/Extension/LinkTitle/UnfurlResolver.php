<?php

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use League\CommonMark\Inline\Element\Link;

class UnfurlResolver
{
    /** @var UnfurlInterface[] */
    private $resolvers;

    public function __construct(array $resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolve(Link $link): void
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->accept($link)) {
                $result = $resolver->unfurl($link);
                $link->data['attributes']['title'] = $result['title'];
            }
        }
    }
}
