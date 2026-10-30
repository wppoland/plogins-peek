<?php

/**
 * Uninstalling this plugin must not delete peek_settings while an add-on that stores its own settings there is installed.
 *
 * An add-on keeps its settings inside peek_settings. uninstall.php deleted the option
 * unconditionally, so removing this plugin with the add-on still installed wiped
 * the add-on's settings. The edition uninstalled last removes the shared option.
 *
 * Each case runs in its own PHP process against stubbed WordPress functions.
 * get_plugins() answers from a per-request cache that a bulk delete leaves
 * stale, so the cache is primed with a list that no longer matches the disk
 * until wp_cache_delete('plugins', 'plugins') is called.
 *
 *   php tests/uninstall-shared-settings-check.php [path/to/uninstall.php]
 */

declare(strict_types=1);

$target = $argv[1] ?? dirname(__DIR__) . '/uninstall.php';

if (! isset($argv[2])) {
    $cases = [
        // [other edition on disk, other edition in the stale cache, shared key must be deleted]
        'other edition installed'                     => [true, true, false],
        'other edition gone, cache still lists it'    => [false, true, true],
        'other edition gone, cache primed without it' => [false, false, true],
    ];
    $fail = 0;
    foreach ($cases as $name => [$onDisk, $inCache, $expectDelete]) {
        $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . ' ' . escapeshellarg($target)
            . ' ' . (int) $onDisk . ' ' . (int) $inCache;
        $out     = (string) shell_exec($cmd);
        $deleted = json_decode(trim($out), true);
        if (! is_array($deleted)) {
            fwrite(STDERR, "FAIL: {$name}: no result, output was: {$out}\n");
            $fail = 1;
            continue;
        }
        if (in_array('peek_settings', $deleted['options'], true) !== $expectDelete) {
            fwrite(STDERR, "FAIL: {$name}: peek_settings " . ($expectDelete ? 'kept' : 'deleted') . "\n");
            $fail = 1;
        }
    }
    if (0 === $fail) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI script, not a page.
        echo "OK: peek_settings is removed only by the edition uninstalled last\n";
    }
    exit($fail);
}

define('ABSPATH', sys_get_temp_dir() . '/');
define('WP_UNINSTALL_PLUGIN', 'plogins-peek/plogins-peek.php');

$GLOBALS['uss'] = [
    'on_disk'  => '1' === $argv[2],
    'in_cache' => '1' === $argv[3],
    'fresh'    => false,
    'options'  => [],
    'meta'     => [],
];

function wp_cache_delete($key, $group = '')
{
    if ('plugins' === $key && 'plugins' === $group) {
        $GLOBALS['uss']['fresh'] = true;
    }

    return true;
}
function get_plugins()
{
    $other   = $GLOBALS['uss']['fresh'] ? $GLOBALS['uss']['on_disk'] : $GLOBALS['uss']['in_cache'];
    $plugins = ['plogins-peek/plogins-peek.php' => ['TextDomain' => 'plogins-peek']];
    if ($other) {
        $plugins['renamed-folder/plogins-peek-pro.php'] = ['TextDomain' => 'plogins-peek-pro'];
    }

    return $plugins;
}
function delete_option($name)
{
    $GLOBALS['uss']['options'][] = $name;

    return true;
}
function delete_metadata($type, $id, $key, $value = '', $all = false)
{
    $GLOBALS['uss']['meta'][] = $type . ':' . $key;

    return true;
}
function apply_filters($hook, $value)
{
    return $value;
}
function add_action(...$args)
{
    return true;
}
function is_multisite()
{
    return false;
}
function get_posts($args)
{
    return [];
}
function wp_delete_post($id, $force = false)
{
    return true;
}
function wp_clear_scheduled_hook($hook)
{
    return 0;
}

final class FakeWpdb
{
    public string $prefix  = 'wp_';
    public string $options = 'wp_options';

    public function query(string $sql): int
    {
        return 0;
    }

    public function get_col(string $sql): array
    {
        return [];
    }

    public function prepare(string $sql, ...$args): string
    {
        return $sql;
    }

    public function esc_like(string $text): string
    {
        return $text;
    }
}

$GLOBALS['wpdb'] = new FakeWpdb();
include $target;

echo json_encode(['options' => $GLOBALS['uss']['options'], 'meta' => $GLOBALS['uss']['meta']]), "\n";
