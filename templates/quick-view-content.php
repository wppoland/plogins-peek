<?php
/**
 * Quick-view modal content fragment, served over AJAX for a single product.
 *
 * @var \WC_Product          $product
 * @var array<string, mixed> $settings
 * @var list<int>            $images           Attachment IDs (main + gallery).
 * @var string              $add_to_cart_html WooCommerce add-to-cart markup.
 *
 * @package Peek/Templates
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

$peek_price_html   = (bool) ($settings['show_price'] ?? true) ? $product->get_price_html() : '';
$peek_sku          = (bool) ($settings['show_sku'] ?? true) ? (string) $product->get_sku() : '';
$peek_description  = (bool) ($settings['show_short_description'] ?? true)
    ? wpautop(wp_kses_post($product->get_short_description()))
    : '';
$peek_sku_label    = (string) ($settings['sku_label'] ?? __('SKU', 'peek'));
$peek_stock_html   = (bool) ($settings['show_stock'] ?? true)
    ? wc_get_stock_html($product)
    : '';
?>
<div class="peek-quick-view-product product product-type-<?php echo esc_attr($product->get_type()); ?>">
    <div class="peek-quick-view-grid">
        <div class="peek-quick-view-media">
            <?php if ($images !== []) : ?>
                <div class="peek-quick-view-main-image">
                    <?php echo wp_get_attachment_image($images[0], 'large'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                <?php if (count($images) > 1) : ?>
                    <div class="peek-quick-view-gallery">
                        <?php foreach ($images as $peek_image_id) : ?>
                            <span class="peek-quick-view-thumb">
                                <?php echo wp_get_attachment_image($peek_image_id, 'thumbnail'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="peek-quick-view-main-image">
                    <?php echo $product->get_image('large'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="peek-quick-view-summary">
            <?php if ((bool) ($settings['show_title'] ?? true)) : ?>
                <h2 class="product_title entry-title"><?php echo esc_html($product->get_name()); ?></h2>
            <?php endif; ?>

            <?php if ($peek_sku !== '') : ?>
                <div class="peek-quick-view-meta">
                    <span class="peek-quick-view-meta__label"><?php echo esc_html($peek_sku_label); ?>:</span>
                    <span><?php echo esc_html($peek_sku); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($peek_price_html !== '') : ?>
                <div class="peek-quick-view-price price"><?php echo wp_kses_post($peek_price_html); ?></div>
            <?php endif; ?>

            <?php if ($peek_stock_html !== '') : ?>
                <div class="peek-quick-view-stock"><?php echo wp_kses_post($peek_stock_html); ?></div>
            <?php endif; ?>

            <?php if ($peek_description !== '') : ?>
                <div class="peek-quick-view-description"><?php echo wp_kses_post($peek_description); ?></div>
            <?php endif; ?>

            <?php if ($add_to_cart_html !== '') : ?>
                <div class="peek-quick-view-cart">
                    <?php echo $add_to_cart_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            <?php endif; ?>

            <?php if ((bool) ($settings['show_view_product_link'] ?? true)) : ?>
                <a
                    class="button alt peek-quick-view-link"
                    href="<?php echo esc_url(get_permalink($product->get_id()) ?: ''); ?>"
                >
                    <?php echo esc_html((string) ($settings['view_product_text'] ?? __('View full product', 'peek'))); ?>
                </a>
            <?php endif; ?>

            <?php do_action( 'peek_quick_view_content_end', $product, $settings ); ?>
        </div>
    </div>
</div>
