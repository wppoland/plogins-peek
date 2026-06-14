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

delete_option('peek_settings');
delete_option('peek_db_version');
