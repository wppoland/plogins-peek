<?php
/**
 * Uninstall cleanup for Peek.
 *
 * Removes the plugin's own options when it is deleted from wp-admin. Only the
 * options Peek creates are deleted; WooCommerce data is never touched.
 *
 * @package Peek
 */

declare(strict_types=1);

defined('WP_UNINSTALL_PLUGIN') || exit;

// An add-on keeps its own settings inside peek_settings. While it is
// installed, active or not, that option is the add-on's to remove when it is
// uninstalled; with no add-on on disk it is ours. Matched on the text domain,
// so a renamed folder still counts. The plugin list is cached per request and
// a bulk delete runs both uninstalls in one request, so drop the cache first.
if (! function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
wp_cache_delete('plugins', 'plugins');
$peek_addon_installed = in_array('plogins-peek-pro', array_column(get_plugins(), 'TextDomain'), true);

if (! $peek_addon_installed) {
    delete_option('peek_settings');
}
delete_option('peek_db_version');

// The PRO banner's dismissal is stored per user, so it belongs to the
// plugin rather than to the site content. User meta is global, not
// per-site, which is why this uses delete_metadata's \$delete_all rather
// than a loop over the users of one blog.
delete_metadata('user', 0, 'peek_pro_banner_dismissed', '', true);
