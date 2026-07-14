<?php
/**
 * PRO upsell content, generated from the plogins.com registry by
 * scripts/gen-pro-upsell.mjs. The admin upsell renders this; curate the
 * feature list to fit this plugin's settings screen (do not invent features).
 *
 * @package plogins-peek-pro
 */

defined('ABSPATH') || exit;

return [
    'name'       => 'Peek Pro',
    'url'        => 'https://plogins.com/plogins-peek-pro/pricing/',
    'sellable'   => true,
    'price_from' => 19,
    'currency'   => 'EUR',
    'price_pln'  => 85,
    'lead'       => [
        'en' => 'All Peek PRO roadmap features ship in the current release.',
        'pl' => 'Wszystkie funkcje PRO z roadmapy Peek są dostępne w bieżącym wydaniu.',
    ],
    'features'   => [
        [
            'en' => ['title' => 'Recently viewed', 'desc' => 'Peek → Recently Viewed: product count, columns, heading and auto-insert. [peek_recently_viewed] shortcode anywhere.'],
            'pl' => ['title' => 'Ostatnio oglądane', 'desc' => 'Peek → Recently Viewed: liczba produktów, kolumny, nagłówek, auto-wstawka. Shortcode [peek_recently_viewed] w dowolnym miejscu.'],
        ],
        [
            'en' => ['title' => 'Related carousel', 'desc' => 'At the end of the quick-view modal: related products as a horizontal scroll-snap carousel with prev/next.'],
            'pl' => ['title' => 'Karuzela powiązanych', 'desc' => 'Na końcu modalu: produkty powiązane jako pozioma karuzela scroll-snap z prev/next.'],
        ],
        [
            'en' => ['title' => 'Variation swatches', 'desc' => 'Peek → Settings: colour chips, variation thumbnails or label buttons instead of dropdowns inside the modal.'],
            'pl' => ['title' => 'Swatche wariantów', 'desc' => 'Peek → Settings: chipy koloru, miniatury wariantów lub przyciski z etykietą zamiast list rozwijanych w modalu.'],
        ],
        [
            'en' => ['title' => 'Custom modal sections', 'desc' => 'Peek → Settings: extra content blocks (HTML) before/after add-to-cart or at the bottom of the modal summary.'],
            'pl' => ['title' => 'Własne sekcje modalu', 'desc' => 'Peek → Settings: dodatkowe bloki treści (HTML) przed/po koszyku lub na dole podsumowania modalu.'],
        ],
        [
            'en' => ['title' => 'Quick-view analytics', 'desc' => 'Peek → Quick View Analytics: modal opens, add-to-cart from the modal and conversions with revenue per product.'],
            'pl' => ['title' => 'Analityka podglądów', 'desc' => 'Peek → Quick View Analytics: otwarcia modalu, dodania do koszyka i konwersje z przychodem per produkt.'],
        ],
    ],
];
