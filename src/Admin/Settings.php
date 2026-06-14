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
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
    }

    /**
     * Load the settings-page CSS/JS only on the Peek screen.
     *
     * Assets are real files (no inline blobs) so Plugin Check stays clean.
     */
    public function enqueueAssets(string $hookSuffix): void
    {
        if ($hookSuffix !== 'toplevel_page_' . self::PAGE) {
            return;
        }

        wp_enqueue_style(
            'peek-admin',
            \Peek\Plugin::instance()->url('assets/css/admin.css'),
            [],
            \Peek\VERSION,
        );

        wp_enqueue_script(
            'peek-admin',
            \Peek\Plugin::instance()->url('assets/js/admin.js'),
            [],
            \Peek\VERSION,
            ['in_footer' => true, 'strategy' => 'defer'],
        );
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
        <div class="wrap peek-admin">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="peek-admin__intro">
                <span class="peek-admin__intro-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <path fill="currentColor" d="M12 5c-5 0-9 4.5-10 7 1 2.5 5 7 10 7s9-4.5 10-7c-1-2.5-5-7-10-7zm0 11a4 4 0 110-8 4 4 0 010 8zm0-6a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </span>
                <div class="peek-admin__intro-text">
                    <h2><?php esc_html_e('Let shoppers preview products without leaving the page', 'peek'); ?></h2>
                    <p><?php esc_html_e('Peek adds a quick-view button to your shop loops. Clicking it opens an accessible, focus-trapped modal with the image gallery, price, stock, add-to-cart form (variations included) and a link to the full product. Tune everything below — hover a “?” for a quick explanation.', 'peek'); ?></p>
                </div>
            </div>

            <form method="post" action="options.php">
                <?php settings_fields(self::PAGE); ?>

                <div class="peek-admin__section">
                    <h2><?php esc_html_e('Trigger button', 'peek'); ?></h2>
                    <p class="peek-admin__section-intro"><?php esc_html_e('Controls the quick-view button shown in your product loops.', 'peek'); ?></p>

                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <?php esc_html_e('Enable quick view', 'peek'); ?>
                                    <?php $this->helpTip('enabled', __('Master switch. When off, no button or modal is loaded anywhere and the storefront is completely unaffected.', 'peek')); ?>
                                </th>
                                <td>
                                    <label for="peek_enabled">
                                        <input
                                            type="checkbox"
                                            id="peek_enabled"
                                            name="<?php echo esc_attr(self::OPTION); ?>[enabled]"
                                            value="1"
                                            aria-describedby="peek-tip-enabled"
                                            <?php checked((bool) ($settings['enabled'] ?? false), true); ?>
                                        />
                                        <?php esc_html_e('Show the quick-view button on shop and archive product loops.', 'peek'); ?>
                                    </label>
                                </td>
                            </tr>
                            <?php
                            $this->textRow('button_text', __('Button label', 'peek'), __('Text shown on the quick-view trigger button.', 'peek'), $settings, __('The clickable label, e.g. “Quick view” or “Peek inside”. With the icon-only style this becomes the button’s accessible name for screen readers.', 'peek'));
                            ?>
                            <tr>
                                <th scope="row">
                                    <label for="peek_button_style"><?php esc_html_e('Button style', 'peek'); ?></label>
                                    <?php $this->helpTip('button_style', __('Text only is the safest match for most themes. Icon only saves space in tight grids (the eye icon). Icon + text gives the clearest call to action.', 'peek')); ?>
                                </th>
                                <td>
                                    <?php $peek_style = (string) ($settings['button_style'] ?? 'text'); ?>
                                    <select id="peek_button_style" name="<?php echo esc_attr(self::OPTION); ?>[button_style]" aria-describedby="peek-tip-button_style">
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
                                    <?php $this->helpTip('display_scope', __('Loading only where needed keeps pages fast. The second option also covers the “Related products” and “You may also like” loops on single product pages.', 'peek')); ?>
                                </th>
                                <td>
                                    <?php $peek_scope = (string) ($settings['display_scope'] ?? 'shop'); ?>
                                    <select id="peek_display_scope" name="<?php echo esc_attr(self::OPTION); ?>[display_scope]" aria-describedby="peek-tip-display_scope">
                                        <option value="shop" <?php selected($peek_scope, 'shop'); ?>><?php esc_html_e('Shop and product archives', 'peek'); ?></option>
                                        <option value="shop_single" <?php selected($peek_scope, 'shop_single'); ?>><?php esc_html_e('Archives + single-product related/upsell loops', 'peek'); ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e('Choose whether the quick-view button also appears in the related and up-sell loops on single product pages.', 'peek'); ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="peek-admin__section">
                    <h2><?php esc_html_e('Modal chrome', 'peek'); ?></h2>
                    <p class="peek-admin__section-intro"><?php esc_html_e('Labels and behaviour for the modal dialog itself. Empty text fields fall back to the built-in defaults.', 'peek'); ?></p>

                    <table class="form-table" role="presentation">
                        <tbody>
                            <?php
                            $this->textRow('modal_title', __('Modal title', 'peek'), __('Heading announced for the dialog.', 'peek'), $settings, __('Read out to screen-reader users when the dialog opens, and shown as a heading if “Modal heading” is on. Keep it short and descriptive.', 'peek'));
                            $this->textRow('close_label', __('Close button label', 'peek'), __('Accessible label for the close button.', 'peek'), $settings, __('The “×” button is visual only; this text is its accessible name for screen readers and keyboard users.', 'peek'));
                            $this->textRow('loading_text', __('Loading text', 'peek'), __('Shown while the product is fetched.', 'peek'), $settings, __('Displayed for the brief moment between opening the modal and the product fragment arriving over AJAX.', 'peek'));
                            $this->textRow('error_text', __('Error text', 'peek'), __('Shown if the product fails to load.', 'peek'), $settings, __('A friendly message shown if the request fails (network error, product removed). Reassure the shopper they can try again or open the full page.', 'peek'));
                            $this->textRow('view_product_text', __('View product link text', 'peek'), __('Label for the link to the full product page.', 'peek'), $settings, __('Label for the button that takes the shopper to the complete product page for reviews, full description and more.', 'peek'));
                            $this->textRow('sku_label', __('SKU label', 'peek'), __('Prefix shown before the SKU value.', 'peek'), $settings, __('The prefix before the SKU, e.g. “SKU”, “Item no.” or “Ref”. Hidden automatically when a product has no SKU.', 'peek'));
                            $this->checkboxRow('show_modal_label', __('Modal heading', 'peek'), __('Show the modal title above the loaded product.', 'peek'), $settings, __('Prints the modal title as a visible heading inside the dialog. Turn off for a cleaner, image-led layout.', 'peek'));
                            $this->checkboxRow('show_close_button', __('Close button', 'peek'), __('Show the close (×) button.', 'peek'), $settings, __('Keep this on for usability. The modal can always be closed with the Escape key regardless of this setting.', 'peek'));
                            $this->checkboxRow('show_backdrop_close', __('Close on backdrop click', 'peek'), __('Close the modal when the backdrop is clicked.', 'peek'), $settings, __('Lets shoppers dismiss the modal by clicking the dimmed area around it — a familiar pattern most users expect.', 'peek'));
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php do_action( 'peek_admin_settings_after_general_table', $settings ); ?>

                <div class="peek-admin__section">
                    <h2><?php esc_html_e('Modal content', 'peek'); ?></h2>
                    <p class="peek-admin__section-intro"><?php esc_html_e('Choose what the quick-view modal shows for each product. Anything a product is missing (no SKU, no gallery, etc.) is hidden automatically.', 'peek'); ?></p>

                    <table class="form-table" role="presentation">
                        <tbody>
                            <?php
                            $this->checkboxRow('show_image', __('Product image', 'peek'), __('Show the featured image.', 'peek'), $settings, __('The main product photo. Products with no image fall back to the WooCommerce placeholder so the layout never breaks.', 'peek'));
                            $this->checkboxRow('show_gallery', __('Gallery thumbnails', 'peek'), __('Show gallery thumbnails.', 'peek'), $settings, __('Extra product photos shown as a thumbnail strip beneath the main image. Hidden when a product has no gallery.', 'peek'));
                            $this->numberRow('gallery_limit', __('Gallery thumbnail count', 'peek'), __('Maximum gallery thumbnails to show (0–12).', 'peek'), $settings, 0, 12, __('Caps how many gallery thumbnails appear, keeping the modal tidy. Set to 0 to show the main image only.', 'peek'));
                            $this->checkboxRow('show_title', __('Title', 'peek'), __('Show the product title.', 'peek'), $settings, __('The product name as a heading at the top of the summary column.', 'peek'));
                            $this->checkboxRow('show_sku', __('SKU', 'peek'), __('Show the product SKU.', 'peek'), $settings, __('The stock-keeping unit. Useful for catalogues and B2B; hidden when the product has none.', 'peek'));
                            $this->checkboxRow('show_price', __('Price', 'peek'), __('Show the product price.', 'peek'), $settings, __('The formatted price, including any sale price, exactly as WooCommerce renders it elsewhere.', 'peek'));
                            $this->checkboxRow('show_stock', __('Stock status', 'peek'), __('Show the stock availability.', 'peek'), $settings, __('Availability such as “In stock”, “Only 3 left” or “Out of stock”, based on your WooCommerce inventory settings.', 'peek'));
                            $this->checkboxRow('show_short_description', __('Short description', 'peek'), __('Show the product short description.', 'peek'), $settings, __('The product’s short/excerpt description — the perfect length for a quick preview. Hidden when empty.', 'peek'));
                            $this->checkboxRow('show_add_to_cart', __('Add to cart', 'peek'), __('Show the add-to-cart form (with variations).', 'peek'), $settings, __('Lets shoppers buy straight from the modal. Variable products get the full variation picker. Hidden for products that aren’t purchasable.', 'peek'));
                            $this->checkboxRow('show_view_product_link', __('View full product link', 'peek'), __('Show a link to the full product page.', 'peek'), $settings, __('A button to the complete product page. Recommended so shoppers can reach reviews and full details.', 'peek'));
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php do_action( 'peek_admin_settings_after_content_table', $settings ); ?>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render an accessible "?" help affordance with a tooltip.
     *
     * The tooltip is wired to its trigger via aria-describedby and uses the
     * native Popover API (progressively enhanced by assets/js/admin.js); without
     * JS the text stays available as the trigger's title and to assistive tech.
     */
    private function helpTip(string $key, string $text): void
    {
        $tipId = 'peek-tip-' . $key;
        ?>
        <button
            type="button"
            class="peek-help"
            data-peek-tip="<?php echo esc_attr($tipId); ?>"
            aria-label="<?php esc_attr_e('More information', 'peek'); ?>"
            aria-describedby="<?php echo esc_attr($tipId); ?>"
            title="<?php echo esc_attr($text); ?>"
        >?</button>
        <span class="peek-help-tip" id="<?php echo esc_attr($tipId); ?>" role="tooltip" hidden><?php echo esc_html($text); ?></span>
        <?php
    }

    /**
     * Render a single checkbox row in the form-table.
     *
     * @param array<string, mixed> $settings
     */
    private function checkboxRow(string $key, string $label, string $help, array $settings, string $tip = ''): void
    {
        $id = 'peek_' . $key;
        ?>
        <tr>
            <th scope="row">
                <?php echo esc_html($label); ?>
                <?php if ($tip !== '') { $this->helpTip($key, $tip); } ?>
            </th>
            <td>
                <label for="<?php echo esc_attr($id); ?>">
                    <input
                        type="checkbox"
                        id="<?php echo esc_attr($id); ?>"
                        name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]"
                        value="1"
                        <?php if ($tip !== '') : ?>aria-describedby="<?php echo esc_attr('peek-tip-' . $key); ?>"<?php endif; ?>
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
    private function textRow(string $key, string $label, string $help, array $settings, string $tip = ''): void
    {
        $id = 'peek_' . $key;
        ?>
        <tr>
            <th scope="row">
                <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
                <?php if ($tip !== '') { $this->helpTip($key, $tip); } ?>
            </th>
            <td>
                <input
                    type="text"
                    id="<?php echo esc_attr($id); ?>"
                    name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]"
                    value="<?php echo esc_attr((string) ($settings[$key] ?? '')); ?>"
                    class="regular-text"
                    <?php if ($tip !== '') : ?>aria-describedby="<?php echo esc_attr('peek-tip-' . $key); ?>"<?php endif; ?>
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
    private function numberRow(string $key, string $label, string $help, array $settings, int $min, int $max, string $tip = ''): void
    {
        $id = 'peek_' . $key;
        ?>
        <tr>
            <th scope="row">
                <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
                <?php if ($tip !== '') { $this->helpTip($key, $tip); } ?>
            </th>
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
                    <?php if ($tip !== '') : ?>aria-describedby="<?php echo esc_attr('peek-tip-' . $key); ?>"<?php endif; ?>
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
