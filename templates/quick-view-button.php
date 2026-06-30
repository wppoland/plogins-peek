<?php
/**
 * Loop quick-view trigger button.
 *
 * Rendered on `woocommerce_after_shop_loop_item`.
 *
 * @var \WC_Product            $product
 * @var array<string, mixed>   $settings
 *
 * @package Peek/Templates
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

$peek_button_text  = (string) ($settings['button_text'] ?? __('Quick view', 'plogins-peek'));
$peek_button_style = (string) ($settings['button_style'] ?? 'text');

if (! in_array($peek_button_style, ['text', 'icon', 'icon_text'], true)) {
    $peek_button_style = 'text';
}

$peek_show_icon  = $peek_button_style === 'icon' || $peek_button_style === 'icon_text';
$peek_show_label = $peek_button_style === 'text' || $peek_button_style === 'icon_text';
$peek_placement = (string) ($settings['loop_button_placement'] ?? 'below');
$peek_overlay = $peek_placement === 'overlay';
// Icon-only triggers need an accessible name.
$peek_aria_label = $peek_show_label ? '' : $peek_button_text;
?>
<div class="peek-quick-view-trigger<?php echo $peek_overlay ? ' peek-quick-view-trigger--overlay' : ''; ?>">
    <button
        type="button"
        class="button peek-quick-view-button peek-quick-view-button--<?php echo esc_attr($peek_button_style); ?>"
        data-peek-quick-view
        data-product-id="<?php echo esc_attr((string) $product->get_id()); ?>"
        <?php if ($peek_aria_label !== '') : ?>aria-label="<?php echo esc_attr($peek_aria_label); ?>"<?php endif; ?>
    >
        <?php if ($peek_show_icon) : ?>
            <span class="peek-quick-view-button__icon" aria-hidden="true"></span>
        <?php endif; ?>
        <?php if ($peek_show_label) : ?>
            <span class="peek-quick-view-button__label"><?php echo esc_html($peek_button_text); ?></span>
        <?php endif; ?>
    </button>
</div>
