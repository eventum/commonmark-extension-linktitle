<?php

namespace Eventum\Delfi;

use Eventum\ServiceContainer;
use Gitlab;
use Pimple;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Setup;

class ServiceProvider implements Pimple\ServiceProviderInterface
{
    public function register(Container $app): void
    {
        $app['commonmark-linktitle.config'] = static function () {
            $setup = ServiceContainer::getConfig();
            $extensionName = 'commonmark-linktitle';
            $config = $setup['extension'][$extensionName];

            // if no config yet. create it.
            if (!$config) {
                if (!$setup['extension']) {
                    $setup['extension'] = [];
                }
                $setup['extension'][$extensionName] = [];
                Setup::save();
                $config = $setup['workflow'][$extensionName];
            }

            return $config;
        };

        $app[GitlabClient::class] = static function ($app) {
            return new GitlabClient($app[Gitlab\Client::class]);
        };

        $app[Gitlab\Client::class] = static function ($app) {
            $config = $app['commonmark-linktitle.config'];

            $client = new Gitlab\Client();
            $client->setUrl($config['gitlab.url']);
            $client->authenticate((string)$config['gitlab.api_token'], Gitlab\Client::AUTH_HTTP_TOKEN);

            return $client;
        };

        $this->registerMarkdownExtensions($app);
    }

    private function registerMarkdownExtensions(Container $app): void
    {
        $app[Subscriber\MarkdownExtension::class] = static function ($app) {
            return new Subscriber\MarkdownExtension([
                $app[CommonMark\Extension\LinkTitle\LinkTitleExtension::class],
            ]);
        };

        $app[CommonMark\Extension\LinkTitle\LinkTitleExtension::class] = static function ($app) {
            return new CommonMark\Extension\LinkTitle\LinkTitleExtension(
                $app[CommonMark\Extension\LinkTitle\UnfurlResolver::class],
                $app[LoggerInterface::class]
            );
        };

        $app[CommonMark\Extension\LinkTitle\UnfurlResolver::class] = static function ($app) {
            $resolvers = [
                $app[CommonMark\Extension\LinkTitle\GitlabUnfurl::class],
            ];

            return new CommonMark\Extension\LinkTitle\UnfurlResolver(
                $resolvers,
                $app[LoggerInterface::class]
            );
        };

        $app[CommonMark\Extension\LinkTitle\GitlabUnfurl::class] = static function ($app) {
            $config = $app['commonmark-linktitle.config'];
            $domain = parse_url($config['gitlab.url'], PHP_URL_HOST);

            return new CommonMark\Extension\LinkTitle\GitlabUnfurl(
                $app[GitlabClient::class],
                $domain
            );
        };
    }
}
