<?php
/**
 * Constants needed by PHPStan to analyse the plugin without bootstrapping WordPress.
 *
 * @package Peek
 */

declare(strict_types=1);

namespace {
    if (! defined('ABSPATH')) {
        define('ABSPATH', '/tmp/wordpress/');
    }
    if (! defined('PEEK_DIR')) {
        define('PEEK_DIR', '/tmp/peek/');
    }
    if (! defined('PEEK_URL')) {
        define('PEEK_URL', 'https://example.test/wp-content/plugins/peek/');
    }
}

namespace Peek {
    if (! defined('Peek\\VERSION')) {
        define('Peek\\VERSION', '0.1.0');
    }
    if (! defined('Peek\\PLUGIN_FILE')) {
        define('Peek\\PLUGIN_FILE', '/tmp/peek/peek.php');
    }
}
