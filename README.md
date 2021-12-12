# Markdown LinkTitle Extension for Eventum

This package provides Markdown extension that adds html title attribute to external links.

Currently supported is issue titles for GitLab issue links.

## Setup

1. Checkout this repository
1. Setup composer dependencies: `composer install --no-dev`
1. Register the extension: `/path/to/eventum/bin/console.php eventum:extension:enable /path/to/src/EventumExtension.php "Eventum\Extension\CommonMarkLinkTitle\EventumExtension"`
1. Configure `gitlab.url` and `gitlab.api_token` values under `extension.commonmark-linktitle` in `/path/to/eventum/config/setup.php`
