<?php

declare(strict_types=1);

namespace Peek\Admin;

defined('ABSPATH') || exit;

use Peek\Contract\HasHooks;

/**
 * Admin settings page registered as a top-level "Peek" menu.
 *
 * Stores settings in the `peek_settings` option (array): the master toggle, the
 * loop trigger button label, and which parts of the product render inside the
 * modal. All output is escaped; all input is sanitised on save.
 */
final class Settings implements HasHooks
{
    private const OPTION = 'peek_settings';
    private const PAGE   = 'peek-settings';

    public function registerHooks(): void
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addMenuPage(): void
    {
        add_menu_page(
            __('Peek Settings', 'peek'),
            __('Peek', 'peek'),
            'manage_woocommerce',
            self::PAGE,
            [$this, 'renderPage'],
            'dashicons-visibility',
            58,
        );
    }

    public function registerSettings(): void
    {
        register_setting(
            self::PAGE,
            self::OPTION,
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'sanitize'],
            ],
        );

        // The menu uses manage_woocommerce; align the options.php save capability
        // so shop managers (not just admins with manage_options) can save.
        add_filter(
            'option_page_capability_' . self::PAGE,
            static fn (): string => 'manage_woocommerce',
        );
    }

    public function renderPage(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            return;
        }

        $settings = $this->settings();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields(self::PAGE); ?>

                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable quick view', 'peek'); ?></th>
                            <td>
                                <label for="peek_enabled">
                                    <input
                                        type="checkbox"
                                        id="peek_enabled"
                                        name="<?php echo esc_attr(self::OPTION); ?>[enabled]"
                                        value="1"
                                        <?php checked((bool) ($settings['enabled'] ?? false), true); ?>
                                    />
                                    <?php esc_html_e('Show the quick-view button on shop and archive product loops.', 'peek'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="peek_button_text"><?php esc_html_e('Button label', 'peek'); ?></label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="peek_button_text"
                                    name="<?php echo esc_attr(self::OPTION); ?>[button_text]"
                                    value="<?php echo esc_attr((string) ($settings['button_text'] ?? '')); ?>"
                                    class="regular-text"
                                />
                                <p class="description"><?php esc_html_e('Text shown on the quick-view trigger button.', 'peek'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="peek_button_style"><?php esc_html_e('Button style', 'peek'); ?></label>
                            </th>
                            <td>
                                <?php $peek_style = (string) ($settings['button_style'] ?? 'text'); ?>
                                <select id="peek_button_style" name="<?php echo esc_attr(self::OPTION); ?>[button_style]">
                                    <option value="text" <?php selected($peek_style, 'text'); ?>><?php esc_html_e('Text only', 'peek'); ?></option>
                                    <option value="icon" <?php selected($peek_style, 'icon'); ?>><?php esc_html_e('Icon only', 'peek'); ?></option>
                                    <option value="icon_text" <?php selected($peek_style, 'icon_text'); ?>><?php esc_html_e('Icon and text', 'peek'); ?></option>
                                </select>
                                <p class="description"><?php esc_html_e('How the trigger appears in the product loop. The icon-only style keeps the label as its accessible name.', 'peek'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="peek_display_scope"><?php esc_html_e('Where to load', 'peek'); ?></label>
                            </th>
                            <td>
                                <?php $peek_scope = (string) ($settings['display_scope'] ?? 'shop'); ?>
                                <select id="peek_display_scope" name="<?php echo esc_attr(self::OPTION); ?>[display_scope]">
                                    <option value="shop" <?php selected($peek_scope, 'shop'); ?>><?php esc_html_e('Shop and product archives', 'peek'); ?></option>
                                    <option value="shop_single" <?php selected($peek_scope, 'shop_single'); ?>><?php esc_html_e('Archives + single-product related/upsell loops', 'peek'); ?></option>
                                </select>
                                <p class="description"><?php esc_html_e('Choose whether the quick-view button also appears in the related and up-sell loops on single product pages.', 'peek'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h2><?php esc_html_e('Modal chrome', 'peek'); ?></h2>
                <p class="description">
                    <?php esc_html_e('Labels and behaviour for the modal dialog itself.', 'peek'); ?>
                </p>

                <table class="form-table" role="presentation">
                    <tbody>
                        <?php
                        $this->textRow('modal_title', __('Modal title', 'peek'), __('Heading announced for the dialog.', 'peek'), $settings);
                        $this->textRow('close_label', __('Close button label', 'peek'), __('Accessible label for the close button.', 'peek'), $settings);
                        $this->textRow('loading_text', __('Loading text', 'peek'), __('Shown while the product is fetched.', 'peek'), $settings);
                        $this->textRow('error_text', __('Error text', 'peek'), __('Shown if the product fails to load.', 'peek'), $settings);
                        $this->textRow('view_product_text', __('View product link text', 'peek'), __('Label for the link to the full product page.', 'peek'), $settings);
                        $this->textRow('sku_label', __('SKU label', 'peek'), __('Prefix shown before the SKU value.', 'peek'), $settings);
                        $this->checkboxRow('show_modal_label', __('Modal heading', 'peek'), __('Show the modal title above the loaded product.', 'peek'), $settings);
                        $this->checkboxRow('show_close_button', __('Close button', 'peek'), __('Show the close (×) button.', 'peek'), $settings);
                        $this->checkboxRow('show_backdrop_close', __('Close on backdrop click', 'peek'), __('Close the modal when the backdrop is clicked.', 'peek'), $settings);
                        ?>
                    </tbody>
                </table>

                <?php do_action( 'peek_admin_settings_after_general_table', $settings ); ?>

                <h2><?php esc_html_e('Modal content', 'peek'); ?></h2>
                <p class="description">
                    <?php esc_html_e('Choose what the quick-view modal shows for each product. The add-to-cart form supports variations.', 'peek'); ?>
                </p>

                <table class="form-table" role="presentation">
                    <tbody>
                        <?php
                        $this->checkboxRow('show_image', __('Product image', 'peek'), __('Show the featured image.', 'peek'), $settings);
                        $this->checkboxRow('show_gallery', __('Gallery thumbnails', 'peek'), __('Show gallery thumbnails.', 'peek'), $settings);
                        $this->numberRow('gallery_limit', __('Gallery thumbnail count', 'peek'), __('Maximum gallery thumbnails to show (0–12).', 'peek'), $settings, 0, 12);
                        $this->checkboxRow('show_title', __('Title', 'peek'), __('Show the product title.', 'peek'), $settings);
                        $this->checkboxRow('show_sku', __('SKU', 'peek'), __('Show the product SKU.', 'peek'), $settings);
                        $this->checkboxRow('show_price', __('Price', 'peek'), __('Show the product price.', 'peek'), $settings);
                        $this->checkboxRow('show_stock', __('Stock status', 'peek'), __('Show the stock availability.', 'peek'), $settings);
                        $this->checkboxRow('show_short_description', __('Short description', 'peek'), __('Show the product short description.', 'peek'), $settings);
                        $this->checkboxRow('show_add_to_cart', __('Add to cart', 'peek'), __('Show the add-to-cart form (with variations).', 'peek'), $settings);
                        $this->checkboxRow('show_view_product_link', __('View full product link', 'peek'), __('Show a link to the full product page.', 'peek'), $settings);
                        ?>
                    </tbody>
                </table>

                <?php do_action( 'peek_admin_settings_after_content_table', $settings ); ?>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render a single checkbox row in the form-table.
     *
     * @param array<string, mixed> $settings
     */
    private function checkboxRow(string $key, string $label, string $help, array $settings): void
    {
        $id = 'peek_' . $key;
        ?>
        <tr>
            <th scope="row"><?php echo esc_html($label); ?></th>
            <td>
                <label for="<?php echo esc_attr($id); ?>">
                    <input
                        type="checkbox"
                        id="<?php echo esc_attr($id); ?>"
                        name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]"
                        value="1"
                        <?php checked((bool) ($settings[$key] ?? false), true); ?>
                    />
                    <?php echo esc_html($help); ?>
                </label>
            </td>
        </tr>
        <?php
    }

    /**
     * Render a single text-input row in the form-table.
     *
     * @param array<string, mixed> $settings
     */
    private function textRow(string $key, string $label, string $help, array $settings): void
    {
        $id = 'peek_' . $key;
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label></th>
            <td>
                <input
                    type="text"
                    id="<?php echo esc_attr($id); ?>"
                    name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]"
                    value="<?php echo esc_attr((string) ($settings[$key] ?? '')); ?>"
                    class="regular-text"
                />
                <p class="description"><?php echo esc_html($help); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Render a single number-input row in the form-table.
     *
     * @param array<string, mixed> $settings
     */
    private function numberRow(string $key, string $label, string $help, array $settings, int $min, int $max): void
    {
        $id = 'peek_' . $key;
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label></th>
            <td>
                <input
                    type="number"
                    id="<?php echo esc_attr($id); ?>"
                    name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]"
                    value="<?php echo esc_attr((string) ($settings[$key] ?? '')); ?>"
                    min="<?php echo esc_attr((string) $min); ?>"
                    max="<?php echo esc_attr((string) $max); ?>"
                    step="1"
                    class="small-text"
                />
                <p class="description"><?php echo esc_html($help); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Sanitises the submitted settings before save, preserving defaults for any
     * field not on the form.
     *
     * @param mixed $raw
     * @return array<string, mixed>
     */
    public function sanitize(mixed $raw): array
    {
        if (! is_array($raw)) {
            $raw = [];
        }

        $defaults = $this->settings();

        $buttonText = isset($raw['button_text']) ? sanitize_text_field((string) $raw['button_text']) : '';

        $buttonStyle = isset($raw['button_style']) ? sanitize_key((string) $raw['button_style']) : 'text';
        if (! in_array($buttonStyle, ['text', 'icon', 'icon_text'], true)) {
            $buttonStyle = 'text';
        }

        $displayScope = isset($raw['display_scope']) ? sanitize_key((string) $raw['display_scope']) : 'shop';
        if (! in_array($displayScope, ['shop', 'shop_single'], true)) {
            $displayScope = 'shop';
        }

        $galleryLimit = isset($raw['gallery_limit']) ? (int) $raw['gallery_limit'] : (int) ($defaults['gallery_limit'] ?? 4);
        $galleryLimit = max(0, min(12, $galleryLimit));

        $sanitized = array_merge($defaults, [
            'enabled'                => ! empty($raw['enabled']),
            'button_text'            => $buttonText !== '' ? $buttonText : (string) ($defaults['button_text'] ?? __('Quick view', 'peek')),
            'button_style'           => $buttonStyle,
            'display_scope'          => $displayScope,
            'modal_title'            => $this->sanitizeText($raw, 'modal_title', $defaults),
            'close_label'            => $this->sanitizeText($raw, 'close_label', $defaults),
            'loading_text'           => $this->sanitizeText($raw, 'loading_text', $defaults),
            'error_text'             => $this->sanitizeText($raw, 'error_text', $defaults),
            'view_product_text'      => $this->sanitizeText($raw, 'view_product_text', $defaults),
            'sku_label'              => $this->sanitizeText($raw, 'sku_label', $defaults),
            'show_modal_label'       => ! empty($raw['show_modal_label']),
            'show_close_button'      => ! empty($raw['show_close_button']),
            'show_backdrop_close'    => ! empty($raw['show_backdrop_close']),
            'show_image'             => ! empty($raw['show_image']),
            'show_gallery'           => ! empty($raw['show_gallery']),
            'gallery_limit'          => $galleryLimit,
            'show_title'             => ! empty($raw['show_title']),
            'show_sku'               => ! empty($raw['show_sku']),
            'show_price'             => ! empty($raw['show_price']),
            'show_stock'             => ! empty($raw['show_stock']),
            'show_short_description' => ! empty($raw['show_short_description']),
            'show_add_to_cart'       => ! empty($raw['show_add_to_cart']),
            'show_view_product_link' => ! empty($raw['show_view_product_link']),
        ]);

        return (array) apply_filters('peek_sanitize_settings', $sanitized, $raw);
    }

    /**
     * Sanitise a single text field, falling back to the packaged default when
     * the submitted value is empty.
     *
     * @param array<string, mixed> $raw
     * @param array<string, mixed> $defaults
     */
    private function sanitizeText(array $raw, string $key, array $defaults): string
    {
        $value = isset($raw[$key]) ? sanitize_text_field((string) $raw[$key]) : '';

        return $value !== '' ? $value : (string) ($defaults[$key] ?? '');
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
}
