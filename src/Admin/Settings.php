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
                        $this->checkboxRow('show_gallery', __('Gallery thumbnails', 'peek'), __('Show up to four gallery thumbnails.', 'peek'), $settings);
                        $this->checkboxRow('show_title', __('Title', 'peek'), __('Show the product title.', 'peek'), $settings);
                        $this->checkboxRow('show_sku', __('SKU', 'peek'), __('Show the product SKU.', 'peek'), $settings);
                        $this->checkboxRow('show_price', __('Price', 'peek'), __('Show the product price.', 'peek'), $settings);
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

        $sanitized = array_merge($defaults, [
            'enabled'                => ! empty($raw['enabled']),
            'button_text'            => $buttonText !== '' ? $buttonText : (string) ($defaults['button_text'] ?? __('Quick view', 'peek')),
            'show_image'             => ! empty($raw['show_image']),
            'show_gallery'           => ! empty($raw['show_gallery']),
            'show_title'             => ! empty($raw['show_title']),
            'show_sku'               => ! empty($raw['show_sku']),
            'show_price'             => ! empty($raw['show_price']),
            'show_short_description' => ! empty($raw['show_short_description']),
            'show_add_to_cart'       => ! empty($raw['show_add_to_cart']),
            'show_view_product_link' => ! empty($raw['show_view_product_link']),
        ]);

        return (array) apply_filters('peek_sanitize_settings', $sanitized, $raw);
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
