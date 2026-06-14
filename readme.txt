=== Peek - Quick View for WooCommerce ===
Contributors: wppoland
Tags: woocommerce, quick view, product modal, ajax, accessibility
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 0.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fast, accessible quick view for WooCommerce — an AJAX product modal with gallery, price, SKU and add-to-cart. No jQuery, no layout shift.

== Description ==

Peek adds a "Quick view" button to your WooCommerce shop and archive product loops. Clicking it opens an accessible modal that loads the product over AJAX — without leaving the listing.

The modal shows the featured image and gallery thumbnails, title, SKU, price, stock status, short description, the native add-to-cart form (including variable products), and a link to the full product page. Each part can be toggled from the settings screen.

= Built for speed and accessibility =

* **No jQuery** in the plugin's own front-end code — the script is vanilla JS, deferred, and loaded in the footer.
* **No layout shift (CLS).** The modal is fully hidden until opened and scrolls internally, so it never reflows the page.
* **Focus-trapped & keyboard friendly.** Focus moves into the dialog on open, is trapped while it is open, closes on Escape or backdrop click, and returns to the trigger button on close. The dialog uses `role="dialog"` with `aria-modal`.
* **Variation aware.** The add-to-cart form supports variable products via WooCommerce's own variation script.

= Settings =

A WooCommerce-capability settings page (Peek menu) lets you:

* Enable or disable the quick view.
* Set the trigger button label and style (text, icon, or icon + text).
* Choose where it loads: shop and product archives only, or also the related/up-sell loops on single product pages.
* Configure the modal chrome: title, close-button label, loading and error text, the "view product" link text and the SKU label, plus toggles for the modal heading, close button and backdrop-click close.
* Choose which parts render in the modal (image, gallery with a configurable thumbnail count, title, SKU, price, stock status, short description, add-to-cart, full-product link).

= Shortcode =

Place a quick-view trigger anywhere with `[peek_quick_view id="123"]`. Optional attributes: `text` (custom label) and `style` (`text`, `icon`, or `icon_text`). The modal and its assets load automatically wherever the shortcode appears.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/peek`, or install via Plugins → Add New.
2. Activate it. WooCommerce must be active.
3. Visit the **Peek** menu in wp-admin to configure the button label and modal contents.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Yes. Peek requires an active WooCommerce installation.

= Does it use jQuery? =

The plugin's own front-end script is vanilla JavaScript with no jQuery dependency. When a product has variations, WooCommerce's bundled variation script (which itself uses jQuery) is enqueued so the variation form works as expected.

= Where does the quick-view button appear? =

On the shop page and product archive loops (categories, tags, taxonomies), after each product. It does not change single product pages.

= Will it cause layout shift? =

No. The modal is hidden until opened and overlays the page, so opening it never reflows existing content.

== Screenshots ==

1. The quick-view modal showing the product gallery, price and add-to-cart form.
2. The Peek settings screen.

== Changelog ==

= 0.3.0 =
* New: loop button placement — below the card or overlay on the thumbnail (hover/focus).
* New: `[peek]` shortcode as an alias for `[peek_quick_view]`.

= 0.2.0 =
* New: `[peek_quick_view]` shortcode to place a quick-view trigger anywhere, with optional `id`, `text` and `style` attributes.
* New: stock status row in the modal, with a toggle.
* New: configurable gallery thumbnail count (0–12).
* New: trigger button style — text, icon, or icon + text (icon-only keeps an accessible name).
* New: display scope — load on shop/archives only, or also single-product related/up-sell loops.
* New: modal chrome controls in the settings page (title, close-button label, loading/error text, view-product link text, SKU label, and toggles for the modal heading, close button and backdrop-click close).
* New: `uninstall.php` removes the plugin's options on delete.
* Added Domain Path for translations.

= 0.1.0 =
* Initial release: accessible AJAX quick-view modal for WooCommerce shop and archive loops, with a settings page for the button label and modal contents.
