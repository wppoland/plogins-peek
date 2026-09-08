<?php

declare(strict_types=1);

namespace Peek;

defined('ABSPATH') || exit;

/**
 * Idempotent schema/version migrations, run on every boot. Compares a stored
 * option against VERSION and applies forward steps as needed.
 */
final class Migrator
{
    private const OPTION   = 'peek_db_version';
    private const SETTINGS = 'peek_settings';

    public function maybeMigrate(): void
    {
        $current = (string) get_option(self::OPTION, '0');

        if (version_compare($current, VERSION, '>=')) {
            return;
        }

        $this->seedDefaultSettings();
        $this->clearUntranslatableTexts();

        update_option(self::OPTION, VERSION, false);
    }

    /**
     * The English strings that shipped as packaged defaults up to 1.0.18 and
     * were written into the option at activation.
     *
     * @var array<string, string>
     */
    private const LEGACY_TEXTS = [
        'button_text'            => 'Quick view',
        'modal_title'            => 'Product quick view',
        'close_label'            => 'Close',
        'loading_text'           => 'Loading product…',
        'error_text'             => 'Failed to load the product preview.',
        'product_not_found_text' => 'Product not found.',
        'view_product_text'      => 'View full product',
        'sku_label'              => 'SKU',
    ];

    /**
     * Clear a stored label that is byte for byte the English default.
     *
     * Those values could never be translated: they were written into the option
     * before any language pack was consulted, so a shop running in another
     * language showed English however complete the translation was. Empty means
     * "use the translated default", which is what the settings screen already
     * promised for a blank field.
     *
     * Only an exact match is cleared, so a merchant's own label, including a
     * hand translation of the English one, survives untouched.
     */
    private function clearUntranslatableTexts(): void
    {
        $stored = get_option(self::SETTINGS, null);
        if (! is_array($stored)) {
            return;
        }

        $changed = false;
        foreach (self::LEGACY_TEXTS as $key => $legacy) {
            if (isset($stored[$key]) && (string) $stored[$key] === $legacy) {
                $stored[$key] = '';
                $changed      = true;
            }
        }

        if ($changed) {
            update_option(self::SETTINGS, $stored, false);
        }
    }

    /**
     * Seed the default settings once, without clobbering an existing config.
     */
    private function seedDefaultSettings(): void
    {
        if (get_option(self::SETTINGS, null) !== null) {
            return;
        }

        /** @var array<string, mixed> $defaults */
        $defaults = require PEEK_DIR . 'config/defaults.php';

        add_option(self::SETTINGS, $defaults, '', false);
    }
}
