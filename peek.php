<?php
/**
 * Plugin Name:       Peek
 * Plugin URI:        https://plogins.com/peek/
 * Description:        Fast, accessible WooCommerce quick view — AJAX product modal (gallery, price, stock, add-to-cart, variations), no jQuery, focus-trapped
 * Version:           0.1.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Requires Plugins:  woocommerce
 * Author:            WPPoland
 * Author URI:        https://plogins.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       peek
 * WC requires at least: 8.0
 *
 * @package Peek
 */

declare(strict_types=1);

namespace Peek;

defined('ABSPATH') || exit;

const VERSION     = '0.1.0';
const PLUGIN_FILE = __FILE__;

define('PEEK_DIR', plugin_dir_path(__FILE__));
define('PEEK_URL', plugin_dir_url(__FILE__));

require_once __DIR__ . '/autoload.php';

// HPOS + cart/checkout blocks compatibility.
add_action('before_woocommerce_init', static function (): void {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
});

add_action('plugins_loaded', static function (): void {
    if (! class_exists('WooCommerce')) {
        add_action('admin_notices', static function (): void {
            echo '<div class="notice notice-error"><p>';
            echo esc_html__('Peek requires WooCommerce to be active.', 'peek');
            echo '</p></div>';
        });
        return;
    }

    Plugin::instance()->boot();
    do_action('peek/booted', Plugin::instance());
});
