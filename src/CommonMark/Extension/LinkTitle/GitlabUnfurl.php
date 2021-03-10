<?php

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use League\CommonMark\Inline\Element\Link;

class GitlabUnfurl implements UnfurlInterface
{
    public function unfurl(Link $link): array
    {
        return [];
    }

    public function accept(Link $link): bool
    {
        return false;
    }
}
