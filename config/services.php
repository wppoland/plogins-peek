<?php
/**
 * Service wiring. Returns a closure that registers every service in the
 * container.
 *
 * @package Peek
 */

declare(strict_types=1);

use Plogins\Peek\Admin\Settings;
use Plogins\Peek\Container;
use Plogins\Peek\Migrator;
use Plogins\Peek\Service\PeekService;
use Plogins\Peek\Service\ShortcodeService;

defined('ABSPATH') || exit;

return static function (Container $c): void {
    $c->singleton(Migrator::class, static fn (): Migrator => new Migrator());

    $c->singleton(PeekService::class, static fn (): PeekService => new PeekService());

    // `[peek_quick_view]` shortcode trigger, sharing the same engine + assets.
    $c->singleton(ShortcodeService::class, static fn (): ShortcodeService => new ShortcodeService($c->get(PeekService::class)));

    // Admin (only needed in wp-admin context).
    if (is_admin()) {
        $c->singleton(Settings::class, static fn (): Settings => new Settings());
    }
};
