<?php

declare(strict_types=1);

namespace Peek\Service;

defined('ABSPATH') || exit;

/**
 * The customer-facing strings a merchant may override, in the language of the
 * site.
 *
 * They used to be English sentences in config/defaults.php, written into
 * `peek_settings` at activation. A string in a config array is never wrapped in
 * a gettext call, so it is not in the .pot and cannot be translated, and once it
 * is in the option even a complete language pack cannot reach it. The settings
 * screen already offered to fall back to a default when a field is left blank;
 * the packaged value meant blank never happened.
 *
 * The packaged default is now empty, meaning "use the string below". A merchant
 * who types their own still wins, and what they typed is stored as typed.
 */
final class Texts
{
    /**
     * Setting key => the translated default.
     *
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'button_text'            => __('Quick view', 'plogins-peek'),
            'modal_title'            => __('Product quick view', 'plogins-peek'),
            'close_label'            => __('Close', 'plogins-peek'),
            'loading_text'           => __('Loading product…', 'plogins-peek'),
            'error_text'             => __('Failed to load the product preview.', 'plogins-peek'),
            'product_not_found_text' => __('Product not found.', 'plogins-peek'),
            'view_product_text'      => __('View full product', 'plogins-peek'),
            'sku_label'              => __('SKU', 'plogins-peek'),
        ];
    }

    /**
     * Fill every empty text key with its translated default.
     *
     * Applied on the way OUT, where the string is about to be shown, and never
     * on the way in: writing the resolved text back to the option would freeze
     * one language into the database, which is the bug this class exists to fix.
     *
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public static function apply(array $settings): array
    {
        foreach (self::defaults() as $key => $text) {
            if (trim((string) ($settings[$key] ?? '')) === '') {
                $settings[$key] = $text;
            }
        }

        return $settings;
    }
}
