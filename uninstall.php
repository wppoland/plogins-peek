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

/**
 * Remove this plugin's options from the current site.
 *
 * @param bool $addon_installed True while the add-on, which keeps its settings in peek_settings, is on disk.
 */
function peek_uninstall_site(bool $addon_installed): void
{
    if (! $addon_installed) {
        delete_option('peek_settings');
    }
    delete_option('peek_db_version');
}

// Options are per site, so a network uninstall has to visit every site. The
// add-on check above is about the plugin folders, which are shared by the whole
// network, so it is read once.
if (is_multisite()) {
    foreach (get_sites(['fields' => 'ids', 'number' => 0]) as $peek_site_id) {
        switch_to_blog((int) $peek_site_id);
        peek_uninstall_site($peek_addon_installed);
        restore_current_blog();
    }
    unset($peek_site_id);
} else {
    peek_uninstall_site($peek_addon_installed);
}

// The PRO banner's dismissal is stored per user, so it belongs to the
// plugin rather than to the site content. User meta is global, not
// per-site, which is why this uses delete_metadata's \$delete_all rather
// than a loop over the users of one blog.
delete_metadata('user', 0, 'peek_pro_banner_dismissed', '', true);
