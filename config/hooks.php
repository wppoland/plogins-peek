<?php
/**
 * Boot order: services listed here are resolved from the container and have
 * their registerHooks() called during Plugin::boot(). Each must implement
 * Peek\Contract\HasHooks.
 *
 * @package Peek
 *
 * @return array<class-string>
 */

declare(strict_types=1);

use Peek\Admin\Settings;
use Peek\Service\PeekService;

defined('ABSPATH') || exit;

return [
    PeekService::class,
    ...(is_admin() ? [Settings::class] : []),
];
