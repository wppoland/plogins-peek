<?php

declare(strict_types=1);

namespace Peek\Admin;

defined('ABSPATH') || exit;

/**
 * PRO upgrade promotion, shown ONLY on the Peek admin screen: a dismissible top
 * banner and a "what PRO adds" locked-card list below the settings form.
 *
 * Pure advertising: no disabled fields, nothing blocks the free workflow, scoped
 * to this one screen and dismissible per user, so it stays inside the
 * WordPress.org guidelines. Content comes from config/pro-upsell.php.
 */
final class ProUpsell
{
    private const META  = 'peek_pro_banner_dismissed';
    public const ACTION = 'peek_dismiss_pro';

    /** @var array<string, mixed>|null */
    private ?array $data = null;

    /** @return array<string, mixed> */
    private function data(): array
    {
        if ($this->data === null) {
            $file = dirname(\Peek\PLUGIN_FILE) . '/config/pro-upsell.php';
            $this->data = is_readable($file) ? (array) require $file : [];
        }
        return $this->data;
    }

    /** Whether to render the promo at all (filterable for white-label builds). */
    public function enabled(): bool
    {
        /**
         * Filters whether the Peek PRO promo is shown on the admin screen.
         *
         * @param bool $show Default true.
         */
        return (bool) apply_filters('peek/show_pro_cta', true) && $this->features() !== [];
    }

    private function url(): string
    {
        $default = (string) ($this->data()['url'] ?? 'https://plogins.com/plogins-peek-pro/pricing/');
        /**
         * Filters the URL the "Upgrade to PRO" buttons point at.
         *
         * @param string $url Default the Peek PRO pricing page.
         */
        return (string) apply_filters('peek/pro_url', $default);
    }

    private function isPolish(): bool
    {
        return str_starts_with((string) get_locale(), 'pl');
    }

    private function priceLabel(): string
    {
        $d = $this->data();
        if ($this->isPolish() && ! empty($d['price_pln'])) {
            /* translators: %d: yearly price in PLN */
            return sprintf(__('od %d zł/rok', 'plogins-peek'), (int) $d['price_pln']);
        }
        if (! empty($d['price_from'])) {
            $cur = ($d['currency'] ?? 'EUR') === 'EUR' ? '€' : (string) $d['currency'] . ' ';
            /* translators: 1: currency symbol, 2: yearly price */
            return sprintf(__('from %1$s%2$d/yr', 'plogins-peek'), $cur, (int) $d['price_from']);
        }
        return '';
    }

    /** @return array<int, array{title: string, desc: string}> */
    private function features(): array
    {
        $lang = $this->isPolish() ? 'pl' : 'en';
        $out  = [];
        foreach ((array) ($this->data()['features'] ?? []) as $f) {
            $x = is_array($f) ? ($f[$lang] ?? $f['en'] ?? null) : null;
            if (is_array($x) && ! empty($x['title'])) {
                $out[] = ['title' => (string) $x['title'], 'desc' => (string) ($x['desc'] ?? '')];
            }
        }
        return $out;
    }

    public function bannerDismissed(): bool
    {
        return (bool) get_user_meta(get_current_user_id(), self::META, true);
    }

    private function dismissUrl(): string
    {
        return wp_nonce_url(admin_url('admin-post.php?action=' . self::ACTION), self::ACTION);
    }

    public function handleDismiss(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('Permission denied.', 'plogins-peek'));
        }
        check_admin_referer(self::ACTION);
        update_user_meta(get_current_user_id(), self::META, 1);
        wp_safe_redirect(wp_get_referer() ?: admin_url('admin.php?page=peek-settings'));
        exit;
    }

    /** Dismissible strip at the top of the admin screen. */
    public function banner(): void
    {
        if (! $this->enabled() || $this->bannerDismissed()) {
            return;
        }
        $name     = (string) ($this->data()['name'] ?? 'Peek Pro');
        $price    = $this->priceLabel();
        $subtitle = implode(', ', array_slice(array_map(
            static fn (array $f): string => $f['title'],
            $this->features(),
        ), 0, 3));
        ?>
        <div class="peek-pro-banner" role="note">
            <span class="peek-pro-banner__tag">PRO</span>
            <p class="peek-pro-banner__text">
                <strong><?php
                /* translators: %s: PRO edition name */
                printf(esc_html__('Do more with %s', 'plogins-peek'), esc_html($name)); ?></strong>
                <?php if ($subtitle !== '') : ?><span class="peek-pro-banner__sub"><?php echo esc_html($subtitle); ?></span><?php endif; ?>
                <?php if ($price !== '') : ?><span class="peek-pro-banner__price"><?php echo esc_html($price); ?></span><?php endif; ?>
            </p>
            <a class="button button-primary peek-pro-banner__cta" href="<?php echo esc_url($this->url()); ?>" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e('Upgrade to PRO', 'plogins-peek'); ?>
            </a>
            <a class="peek-pro-banner__dismiss" href="<?php echo esc_url($this->dismissUrl()); ?>" aria-label="<?php esc_attr_e('Dismiss this notice', 'plogins-peek'); ?>">&times;</a>
        </div>
        <?php
    }

    /** "What PRO adds" locked-card grid, appended after the settings form. */
    public function cards(): void
    {
        if (! $this->enabled()) {
            return;
        }
        $features = $this->features();
        $name     = (string) ($this->data()['name'] ?? 'Peek Pro');
        ?>
        <section class="peek-pro-cards" aria-labelledby="peek-pro-cards-h">
            <h2 id="peek-pro-cards-h" class="peek-pro-cards__title">
                <?php
                /* translators: %s: PRO edition name */
                printf(esc_html__('What %s adds', 'plogins-peek'), esc_html($name)); ?>
            </h2>
            <div class="peek-pro-cards__grid">
                <?php foreach ($features as $f) : ?>
                    <article class="peek-pro-card">
                        <span class="peek-pro-card__badge">PRO</span>
                        <h3 class="peek-pro-card__title"><?php echo esc_html($f['title']); ?></h3>
                        <?php if ($f['desc'] !== '') : ?>
                            <p class="peek-pro-card__desc"><?php echo esc_html($f['desc']); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
