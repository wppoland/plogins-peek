<?php
/**
 * Quick-view modal shell, printed once in the footer by the storefront-kit
 * QuickViewEngine. The product fragment is injected into
 * `[data-peek-quick-view-content]` over AJAX by assets/js/quick-view.js.
 *
 * @var array<string, mixed> $settings
 * @var string               $loading_text
 * @var bool                 $show_modal_label
 * @var bool                 $show_close_button
 *
 * @package Peek/Templates
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

$peek_modal_title = (string) ($settings['modal_title'] ?? __('Product quick view', 'peek'));
$peek_close_label = (string) ($settings['close_label'] ?? __('Close', 'peek'));
?>
<div class="peek-quick-view-modal" data-peek-quick-view-modal hidden>
    <div class="peek-quick-view-backdrop" data-peek-quick-view-backdrop></div>
    <div class="peek-quick-view-dialog" role="dialog" aria-modal="true" tabindex="-1" aria-label="<?php echo esc_attr($peek_modal_title); ?>">
        <?php if ($show_close_button) : ?>
            <button type="button" class="peek-quick-view-close" data-peek-quick-view-close aria-label="<?php echo esc_attr($peek_close_label); ?>">
                &times;
            </button>
        <?php endif; ?>
        <div class="peek-quick-view-content" data-peek-quick-view-content aria-live="polite">
            <?php if ($show_modal_label) : ?>
                <p class="peek-quick-view-content__label"><?php echo esc_html($peek_modal_title); ?></p>
            <?php endif; ?>
            <p><?php echo esc_html($loading_text); ?></p>
        </div>
    </div>
</div>
