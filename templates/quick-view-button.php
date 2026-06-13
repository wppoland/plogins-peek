<?php
/**
 * Loop quick-view trigger button.
 *
 * Rendered by the storefront-kit QuickViewEngine on
 * `woocommerce_after_shop_loop_item`.
 *
 * @var \WC_Product            $product
 * @var array<string, mixed>   $settings
 *
 * @package Peek/Templates
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

$peek_button_text = (string) ($settings['button_text'] ?? __('Quick view', 'peek'));
?>
<div class="peek-quick-view-trigger">
    <button
        type="button"
        class="button peek-quick-view-button"
        data-peek-quick-view
        data-product-id="<?php echo esc_attr((string) $product->get_id()); ?>"
    >
        <?php echo esc_html($peek_button_text); ?>
    </button>
</div>
