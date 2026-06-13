<?php
/**
 * Service wiring. Returns a closure that registers every service in the
 * container. Keep services thin; product logic lives in storefront-kit engines
 * instantiated here with this plugin's text-domain / option prefix / asset URLs.
 *
 * @package Peek
 */

declare(strict_types=1);

use Peek\Admin\Settings;
use Peek\Container;
use Peek\Migrator;
use Peek\Service\PeekService;

defined('ABSPATH') || exit;

return static function (Container $c): void {
    $c->singleton(Migrator::class, static fn (): Migrator => new Migrator());

    // Thin adapter over the storefront-kit QuickViewEngine.
    $c->singleton(PeekService::class, static fn (): PeekService => new PeekService());

    // Admin (only needed in wp-admin context).
    if (is_admin()) {
        $c->singleton(Settings::class, static fn (): Settings => new Settings());
    }
};
