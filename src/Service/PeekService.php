<?php

declare(strict_types=1);

namespace Peek\Service;

use Peek\Contract\HasHooks;
use WPPoland\StorefrontKit\QuickView\QuickViewEngine;

defined('ABSPATH') || exit;

/**
 * Wires {@see QuickViewEngine} with this plugin's text-domain ('peek'), option
 * prefix ('peek_'), asset URLs and labels, and supplies the two closures it
 * needs: one to render templates (loop button + modal shell) and one to build
 * the per-product quick-view HTML fragment served over AJAX. This class supplies
 * localisation, option storage, asset paths and the WooCommerce-specific
 * fragment markup.
 */
final class PeekService implements HasHooks
{
    private const OPTION = 'peek_settings';

    private ?QuickViewEngine $engine = null;

    /**
     * Set when a `[peek_quick_view]` shortcode renders, so the engine loads the
     * modal shell + assets on a request that is not a shop/archive view.
     */
    private bool $forced = false;

    public function __construct()
    {
        // When the quick-view engine is available, wire it with this plugin's
        // text-domain / option prefix / asset URLs. Otherwise leave the service
        // inert (see registerHooks()).
        if (! class_exists(QuickViewEngine::class)) {
            return;
        }

        $this->engine = new QuickViewEngine(
            ajaxAction: 'peek_quick_view',
            nonceAction: 'peek_quick_view',
            scriptObjectName: 'peekQuickView',
            assetHandle: 'peek',
            styleUrl: \Peek\Plugin::instance()->url('assets/css/quick-view.css'),
            scriptUrl: \Peek\Plugin::instance()->url('assets/js/quick-view.js'),
            version: \Peek\VERSION,
            buttonTemplate: 'quick-view-button',
            modalTemplate: 'quick-view-modal',
            labels: [
                'loading'   => __('Loading product…', 'peek'),
                'error'     => __('Failed to load the product preview.', 'peek'),
                'not_found' => __('Product not found.', 'peek'),
            ],
            isEnabled: fn (): bool => $this->isEnabled(),
            shouldRender: fn (): bool => $this->shouldRenderOnCurrentPage(),
            settings: fn (): array => $this->settings(),
            renderTemplate: function (string $template, array $context): void {
                $this->renderTemplate($template, $context);
            },
            renderFragment: fn (\WC_Product $product, array $settings): string => $this->renderFragment($product, $settings),
        );
    }

    public function registerHooks(): void
    {
        if ($this->engine instanceof QuickViewEngine) {
            $this->engine->registerHooks();
            return;
        }

        // The quick-view engine is unavailable; no hooks are registered until it
        // is present.
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->settings()['enabled'] ?? false);
    }

    /**
     * Resolved settings (defaults merged with stored options).
     *
     * @return array<string, mixed>
     */
    public function resolvedSettings(): array
    {
        return $this->settings();
    }

    /**
     * Flag the current request so the engine prints the modal shell + assets
     * even outside the shop/archive context (used by the shortcode).
     */
    public function forceQuickViewOnRequest(): void
    {
        $this->forced = true;
    }

    /**
     * Enqueue the quick-view assets immediately (shortcode fallback for when
     * the shortcode renders after `wp_enqueue_scripts`).
     */
    public function enqueueAssets(): void
    {
        if ($this->engine instanceof QuickViewEngine) {
            $this->engine->enqueueAssets();
        }
    }

    /**
     * Render a quick-view trigger button for a product.
     *
     * @param array<string, mixed> $settings
     */
    public function renderButton(\WC_Product $product, array $settings): string
    {
        ob_start();
        $this->renderTemplate('quick-view-button', [
            'product'  => $product,
            'settings' => $settings,
        ]);

        return (string) ob_get_clean();
    }

    private function shouldRenderOnCurrentPage(): bool
    {
        if ($this->forced) {
            return true;
        }

        $onArchive = is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag();

        if ($onArchive) {
            return true;
        }

        // Single-product pages carry related/upsell loops; load there only when
        // the merchant opts in via the display scope setting.
        if ((string) ($this->settings()['display_scope'] ?? 'shop') === 'shop_single') {
            return is_product();
        }

        return false;
    }

    /**
     * Build the quick-view HTML fragment for a product.
     *
     * @param array<string, mixed> $settings
     */
    private function renderFragment(\WC_Product $product, array $settings): string
    {
        ob_start();
        $this->renderTemplate('quick-view-content', [
            'product'          => $product,
            'settings'         => $settings,
            'images'           => $this->productImages($product, $settings),
            'add_to_cart_html' => $this->addToCartHtml($product, $settings),
        ]);

        return (string) ob_get_clean();
    }

    /**
     * Image attachment IDs (main + a few gallery thumbs) honouring settings.
     *
     * @param array<string, mixed> $settings
     * @return list<int>
     */
    private function productImages(\WC_Product $product, array $settings): array
    {
        $images = [];

        if ((bool) ($settings['show_image'] ?? true)) {
            $mainId = (int) $product->get_image_id();

            if ($mainId > 0) {
                $images[] = $mainId;
            }
        }

        if ((bool) ($settings['show_gallery'] ?? true)) {
            $limit = (int) ($settings['gallery_limit'] ?? 4);
            $limit = max(0, min(12, $limit));

            foreach (array_slice($product->get_gallery_image_ids(), 0, $limit) as $imageId) {
                $imageId = (int) $imageId;

                if ($imageId > 0 && ! in_array($imageId, $images, true)) {
                    $images[] = $imageId;
                }
            }
        }

        return $images;
    }

    /**
     * Native WooCommerce add-to-cart markup (supports variations), rendered with
     * the target product temporarily set as the global product.
     *
     * @param array<string, mixed> $settings
     */
    private function addToCartHtml(\WC_Product $product, array $settings): string
    {
        if (! (bool) ($settings['show_add_to_cart'] ?? true) || ! $product->is_purchasable()) {
            return '';
        }

        // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce single add-to-cart rendering relies on the global product object.
        $previousProduct = $GLOBALS['product'] ?? null;
        $GLOBALS['product'] = $product;

        ob_start();
        woocommerce_template_single_add_to_cart();
        $html = (string) ob_get_clean();

        if ($previousProduct instanceof \WC_Product) {
            $GLOBALS['product'] = $previousProduct;
        } else {
            unset($GLOBALS['product']);
        }
        // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

        return $html;
    }

    /**
     * Stored settings merged over packaged defaults.
     *
     * @return array<string, mixed>
     */
    private function settings(): array
    {
        $stored = get_option(self::OPTION, []);

        if (! is_array($stored)) {
            $stored = [];
        }

        /** @var array<string, mixed> $defaults */
        $defaults = require PEEK_DIR . 'config/defaults.php';

        return array_merge($defaults, $stored);
    }

    /**
     * @param array<string, mixed> $context
     */
    private function renderTemplate(string $template, array $context): void
    {
        $file = PEEK_DIR . 'templates/' . $template . '.php';

        if (! is_readable($file)) {
            return;
        }

        extract($context, EXTR_SKIP);
        require $file;
    }
}
