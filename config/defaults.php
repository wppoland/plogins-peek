<?php
/**
 * Default settings, merged under the option key `peek_settings`.
 *
 * The feature ships enabled. The merchant tunes the trigger button label and
 * which parts of the product render inside the modal from the Peek admin
 * screen. These values are the resolved settings the quick view reads.
 *
 * @package Peek
 *
 * @return array<string, mixed>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

// Every customer-facing string below is empty on purpose. A value here is
// written into the option at activation and can never be translated, because a
// config array is not a gettext call, so the packaged text survived even a
// complete language pack. Empty means "use Peek\Service\Texts", which is
// translated; anything a merchant types still wins.
return [
    'enabled' => true,

    // Loop trigger button.
    'show_on_loop' => true,
    'loop_button_placement' => 'below',
    'button_text'  => '',
    // Trigger display style: 'text', 'icon', or 'icon_text'.
    'button_style' => 'text',
    // Where the quick-view assets/button load: 'shop' (shop + product archives)
    // or 'shop_single' (also single-product related/upsell loops).
    'display_scope' => 'shop',

    // Modal chrome.
    'modal_title'        => '',
    'show_modal_label'   => true,
    'show_close_button'  => true,
    'close_label'        => '',
    'show_backdrop_close' => true,

    // Runtime strings (used by the front-end script and the AJAX handler).
    'loading_text'           => '',
    'error_text'             => '',
    'product_not_found_text' => '',

    // What the modal fragment renders.
    'show_title'           => true,
    'show_sku'             => true,
    'show_price'           => true,
    'show_stock'           => true,
    'show_image'           => true,
    'show_gallery'         => true,
    'gallery_limit'        => 4,
    'show_short_description' => true,
    'show_add_to_cart'     => true,

    // "View full product" link.
    'show_view_product_link' => true,
    'view_product_text'      => '',
    'sku_label'              => '',
];
