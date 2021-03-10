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

    public function getIssue(string $project_id, int $issue_iid)
    {
        return $this->client->issues->show($project_id, $issue_iid);
    }
}
