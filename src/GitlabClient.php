<?php

namespace Eventum\Delfi;

use Gitlab;

class GitlabClient
{
    /** @var Gitlab\Client */
    private $client;

    public function __construct(Gitlab\Client $client)
    {
        $this->client = $client;
    }
}
