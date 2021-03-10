<?php

namespace Eventum\Delfi\CommonMark\Extension\LinkTitle;

use League\CommonMark\Inline\Element\Link;

interface UnfurlInterface
{
    public function accept(Link $link): bool;

    public function unfurl(Link $link): array;
}
