<?php

/**
 * Deleting the plugin on a network must clean every site, not only the one
 * the uninstall runs in. Whether the add-on is on disk is a network-wide fact,
 * read once, and it keeps peek_settings on every site while installed.
 *
 * Run: php tests/uninstall-multisite-check.php
 */

declare(strict_types=1);

if (! isset($argv[1])) {
    $fail = 0;
    foreach (['0', '1'] as $addon) {
        passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . ' ' . $addon, $code);
        $fail |= $code;
    }
    exit($fail);
}

define('ABSPATH', sys_get_temp_dir() . '/');
define('WP_UNINSTALL_PLUGIN', 'peek/plogins-peek.php');

$addon   = '1' === $argv[1];
$blog    = 1;
$lists   = 0;
$deleted = [];

function is_multisite(): bool { return true; }
function get_sites(array $args): array { return [1, 2, 3]; }
function switch_to_blog(int $id): void { $GLOBALS['blog'] = $id; }
function restore_current_blog(): void { $GLOBALS['blog'] = 1; }
function wp_cache_delete(string $key, string $group = ''): bool { return true; }
function get_plugins(): array
{
    ++$GLOBALS['lists'];
    $plugins = ['peek/plogins-peek.php' => ['TextDomain' => 'x']];
    if ($GLOBALS['addon']) {
        $plugins['renamed/addon.php'] = ['TextDomain' => 'plogins-peek-pro'];
    }
    return $plugins;
}
function delete_option(string $name): void { $GLOBALS['deleted'][] = $GLOBALS['blog'] . ':' . $name; }
function delete_metadata(string $type, int $id, string $key, string $value = '', bool $all = false): void { $GLOBALS['deleted'][] = 'user:' . $key; }
function get_posts(array $args): array { return [$GLOBALS['blog'] * 10]; }
function wp_delete_post(int $id, bool $force = false): void { $GLOBALS['deleted'][] = $GLOBALS['blog'] . ':post:' . $id; }

require __DIR__ . '/../uninstall.php';

$expected = ['user:peek_pro_banner_dismissed'];
foreach ([1, 2, 3] as $site) {
    array_push($expected, "{$site}:peek_db_version");
    if (! $addon) {
        $expected[] = "{$site}:peek_settings";
    }
}
sort($expected);
sort($deleted);

$case = $addon ? 'add-on installed' : 'no add-on';
if ($deleted !== $expected || 1 !== $lists) {
    echo "FAIL ({$case}): plugin list read {$lists} times, deleted " . implode(', ', $deleted) . "\n";
    exit(1);
}

echo "OK ({$case}): uninstall cleans every site of the network\n";
