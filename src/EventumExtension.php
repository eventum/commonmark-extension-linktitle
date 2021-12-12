<?php

namespace Eventum\Extension\CommonMarkLinkTitle;

use Eventum;
use Eventum\Config\Config;
use Eventum\Extension\ClassLoader;
use Eventum\Extension\Provider;
use Eventum\ServiceContainer;
use Pimple\Container;

/**
 * Markdown LinkTitle Extension for Eventum.
 */
class EventumExtension implements
    Provider\AutoloadProvider,
    Provider\FactoryProvider,
    Provider\SubscriberProvider
{
    public const SERVICE_KEY_CONFIG = 'commonmark-linktitle.config';
    public const EXTENSION_CONFIG_KEY =  'commonmark-linktitle';

    /**
     * Method invoked so the extension can setup class loader.
     *
     * @param ClassLoader $loader
     */
    public function registerAutoloader($loader): void
    {
        [$classmap, $psr0, $psr4, $files] = $this->getComposerAutoload();

        // add classmap
        $loader->addClassMap($classmap);

        // add namespaces (psr-0)
        foreach ($psr0 as $namespace => $path) {
            $loader->add($namespace, $path);
        }

        // add namespaces (psr-4)
        foreach ($psr4 as $namespace => $path) {
            $loader->addPsr4($namespace, $path);
        }

        // add files
        foreach ($files as $fileIdentifier => $file) {
            $loader->autoloadFile($fileIdentifier, $file);
        }
    }

    private function getComposerAutoload(): array
    {
        $baseDir = dirname(__DIR__);
        $vendorDir = $baseDir . '/vendor';

        $classmap = [
        ];

        $psr0 = [
        ];

        $psr4 = [
            // Project
            'Eventum\\Extension\\CommonMarkLinkTitle\\' => [$baseDir . '/src'],

            // Dependencies
            'Gitlab\\' => [$vendorDir . '/m4tthumphrey/php-gitlab-api/lib/Gitlab'],
            'GuzzleHttp\\' => [$vendorDir . '/guzzlehttp/guzzle/src'],
            'GuzzleHttp\\Promise\\' => [$vendorDir . '/guzzlehttp/promises/src'],
            'GuzzleHttp\\Psr7\\' => [$vendorDir . '/guzzlehttp/psr7/src'],
            'Http\\Adapter\\Guzzle6\\' => [$vendorDir . '/php-http/guzzle6-adapter/src'],
            'Http\\Client\\' => [$vendorDir . '/php-http/httplug/src'],
            'Http\\Client\\Common\\' => [$vendorDir . '/php-http/client-common/src'],
            'Http\\Discovery\\' => [$vendorDir . '/php-http/discovery/src'],
            'Http\\Message\\' => [$vendorDir . '/php-http/message-factory/src', $vendorDir . '/php-http/message/src'],
            'Http\\Message\\MultipartStream\\' => [$vendorDir . '/php-http/multipart-stream-builder/src'],
            'Http\\Promise\\' => [$vendorDir . '/php-http/promise/src'],
            'Psr\\Http\\Message\\' => [$vendorDir . '/psr/http-factory/src', $vendorDir . '/psr/http-message/src'],
        ];

        $files = [
            '37a3dc5111fe8f707ab4c132ef1dbc62' => $vendorDir . '/guzzlehttp/guzzle/src/functions_include.php',
            '7b11c4dc42b3b3023073cb14e519683c' => $vendorDir . '/ralouphie/getallheaders/src/getallheaders.php',
            '8cff32064859f4559445b89279f3199c' => $vendorDir . '/php-http/message/src/filters.php',
            '9c67151ae59aff4788964ce8eb2a0f43' => $vendorDir . '/clue/stream-filter/src/functions_include.php',
            'a0edc8309cc5e1d60e3047b5df6b7052' => $vendorDir . '/guzzlehttp/psr7/src/functions_include.php',
            'c964ee0ededf28c96ebd9db5099ef910' => $vendorDir . '/guzzlehttp/promises/src/functions_include.php',
        ];

        return [$classmap, $psr0, $psr4, $files];
    }

    /**
     * {@inheritdoc}
     */
    public function factory($className)
    {
        $services = self::getServiceContainer();

        return $services[$className] ?? null;
    }

    /**
     * Get classes implementing EventSubscriberInterface.
     *
     * @see http://symfony.com/doc/current/components/event_dispatcher.html#using-event-subscribers
     * @see \Symfony\Component\EventDispatcher\EventSubscriberInterface
     * @return string[]
     */
    public function getSubscribers(): array
    {
        return [
            Subscriber\CryptoSubscriber::class,
            Subscriber\MarkdownExtension::class,
        ];
    }

    public static function getConfig(): Config
    {
        $container = self::getServiceContainer();

        return $container[self::SERVICE_KEY_CONFIG];
    }

    private static function getServiceContainer(): Container
    {
        static $services;

        if (!$services) {
            $services = ServiceContainer::getInstance();
            $services->register(new ServiceProvider());
        }

        return $services;
    }
}
