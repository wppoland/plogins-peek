<?php
/**
 * Default settings, merged under the option key `peek_settings`.
 *
 * The feature ships enabled. The merchant tunes the trigger button label and
 * which parts of the product render inside the modal from the Peek admin
 * screen. All product logic lives in the storefront-kit QuickViewEngine; these
 * values are passed through to it as the resolved settings.
 *
 * @package Peek
 *
 * @return array<string, mixed>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

return [
    'enabled' => true,

    // Loop trigger button.
    'show_on_loop' => true,
    'button_text'  => 'Quick view',

    // Modal chrome.
    'modal_title'        => 'Product quick view',
    'show_modal_label'   => true,
    'show_close_button'  => true,
    'close_label'        => 'Close',
    'show_backdrop_close' => true,

    // Runtime strings (used by the front-end script and the AJAX handler).
    'loading_text'           => 'Loading product…',
    'error_text'             => 'Failed to load the product preview.',
    'product_not_found_text' => 'Product not found.',

    // What the modal fragment renders.
    'show_title'           => true,
    'show_sku'             => true,
    'show_price'           => true,
    'show_image'           => true,
    'show_gallery'         => true,
    'show_short_description' => true,
    'show_add_to_cart'     => true,

    // "View full product" link.
    'show_view_product_link' => true,
    'view_product_text'      => 'View full product',
    'sku_label'              => 'SKU',
];
