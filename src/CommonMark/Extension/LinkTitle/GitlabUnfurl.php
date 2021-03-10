<?php

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use League\CommonMark\Inline\Element\Link;

class GitlabUnfurl implements UnfurlInterface
{
    /** @var string */
    private $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    public function unfurl(Link $link): array
    {
        return [];
    }

    public function accept(Link $link): bool
    {
        $domain = parse_url($link->getUrl(), PHP_URL_HOST);

        return $domain === $this->domain;
    }
}
