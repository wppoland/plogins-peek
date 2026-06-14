<?php

declare(strict_types=1);

namespace Peek\Service;

use Peek\Contract\HasHooks;

defined('ABSPATH') || exit;

/**
 * Registers the `[peek_quick_view]` shortcode so a merchant can place a
 * quick-view trigger anywhere (a page, a custom template, a builder widget),
 * not just in the shop loop.
 *
 * Usage: [peek id="123"] or [peek_quick_view id="123" text="Preview" style="icon_text"]
 *
 * The button markup reuses the packaged loop-button template. When the
 * shortcode is present on a request, it flags {@see PeekService} so the engine
 * prints the modal shell and enqueues the quick-view assets in the footer, and
 * it enqueues those assets immediately as a fallback for late shortcode
 * rendering.
 */
final class ShortcodeService implements HasHooks
{
    public function __construct(
        private readonly PeekService $peek,
    ) {
    }

    public function registerHooks(): void
    {
        add_shortcode('peek_quick_view', [$this, 'render']);
        add_shortcode('peek', [$this, 'render']);
    }

    /**
     * Render the quick-view trigger for the given product.
     *
     * @param array<string, mixed>|string $atts
     */
    public function render(array|string $atts): string
    {
        if (! $this->peek->isEnabled()) {
            return '';
        }

        $atts = shortcode_atts(
            [
                'id'    => '0',
                'text'  => '',
                'style' => '',
            ],
            is_array($atts) ? $atts : [],
            'peek',
        );

        $productId = absint($atts['id']);

        if ($productId <= 0) {
            return '';
        }

        $product = wc_get_product($productId);

        if (! $product instanceof \WC_Product) {
            return '';
        }

        // Make the engine print the modal shell + enqueue assets for this view,
        // and enqueue immediately in case the shortcode runs after the normal
        // enqueue hook has fired.
        $this->peek->forceQuickViewOnRequest();
        $this->peek->enqueueAssets();

        $settings = $this->peek->resolvedSettings();

        $style = sanitize_key((string) $atts['style']);
        if (in_array($style, ['text', 'icon', 'icon_text'], true)) {
            $settings['button_style'] = $style;
        }

        $text = sanitize_text_field((string) $atts['text']);
        if ($text !== '') {
            $settings['button_text'] = $text;
        }

        return $this->peek->renderButton($product, $settings);
    }
}
