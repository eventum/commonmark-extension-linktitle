<?php

namespace Eventum\Extension\CommonMarkLinkTitle\Subscriber;

use Eventum\Config\Config;
use Eventum\Event\ConfigUpdateEvent;
use Eventum\Event\SystemEvents;
use Eventum\Extension\CommonMarkLinkTitle\EventumExtension;
use Eventum\ServiceContainer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CryptoSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            SystemEvents::CONFIG_CRYPTO_UPGRADE => 'upgrade',
            SystemEvents::CONFIG_CRYPTO_DOWNGRADE => 'downgrade',
        ];
    }

    /**
     * Upgrade config so that values contain EncryptedValue where some secrecy is wanted
     *
     * @see \Eventum\Event\Subscriber\CryptoSubscriber::upgrade
     */
    public function upgrade(ConfigUpdateEvent $event): void
    {
        $config = $this->getConfig();

        $event->encrypt($config['gitlab.api_token']);
    }

    /**
     * Downgrade config: remove all EncryptedValue elements
     *
     * @see \Eventum\Event\Subscriber\CryptoSubscriber::downgrade
     */
    public function downgrade(ConfigUpdateEvent $event): void
    {
        $config = $this->getConfig();

        $event->decrypt($config['gitlab.api_token']);
    }

    private function getConfig(): Config
    {
        return EventumExtension::getConfig();
    }
}
