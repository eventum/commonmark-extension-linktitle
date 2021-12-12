<?php

namespace Eventum\Extension\CommonMarkLinkTitle\LinkTitle;

use Eventum\Extension\CommonMarkLinkTitle\GitlabClient;
use Gitlab\Exception\RuntimeException as GitlabRuntimeException;
use League\CommonMark\Inline\Element\Link;
use UnexpectedValueException;

class GitlabUnfurl implements UnfurlInterface
{
    /** @var string */
    private $domain;
    /** @var GitlabClient */
    private $client;

    public function __construct(
        GitlabClient $client,
        string $domain
    ) {
        $this->domain = $domain;
        $this->client = $client;
    }

    public function unfurl(Link $link): array
    {
        [$projectPath, $issueId] = $this->getProjectPathAndIssueId($link->getUrl());
        try {
            $result = $this->client->getIssue($projectPath, $issueId);
        } catch (GitlabRuntimeException $e) {
            if ($e->getCode() === 404) {
                return [];
            }
        }

        $title = sprintf(
            'Issue #%d: (%s): %s',
            $result['iid'],
            $result['state'],
            $result['title']
        );

        return [
            'title' => $title,
        ];
    }

    public function accept(Link $link): bool
    {
        $url = $link->getUrl();
        $domain = parse_url($url, PHP_URL_HOST);

        if ($domain !== $this->domain) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH);

        return strpos($path, '/issues/') !== false;
    }

    /**
     * Get Project path and Issue Id from link
     */
    private function getProjectPathAndIssueId(string $url): array
    {
        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/-/issues/', $path, 2);
        if (count($parts) !== 2) {
            $parts = explode('/issues/', $path, 2);
        }
        if (count($parts) !== 2) {
            throw new UnexpectedValueException("Unable to parse: {$path}");
        }
        [$projectPath, $issueId] = $parts;

        return [ltrim($projectPath, '/'), $issueId];
    }
}
