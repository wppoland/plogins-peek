=== Peek — WooCommerce Quick View ===
Contributors: wppoland
Tags: woocommerce, quick view, product modal, ajax, accessibility
Requires at least: 6.5
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fast, accessible WooCommerce quick view. A focus-trapped AJAX product modal — gallery, price, SKU, add-to-cart with variations — no jQuery, no layout shift.

== Description ==

Peek adds a "Quick view" button to your WooCommerce shop and archive product loops. Clicking it opens an accessible modal that loads the product over AJAX — without leaving the listing.

The modal shows the featured image and gallery thumbnails, title, SKU, price, short description, the native add-to-cart form (including variable products), and a link to the full product page. Each part can be toggled from the settings screen.

= Built for speed and accessibility =

* **No jQuery** in the plugin's own front-end code — the script is vanilla JS, deferred, and loaded in the footer.
* **No layout shift (CLS).** The modal is fully hidden until opened and scrolls internally, so it never reflows the page.
* **Focus-trapped & keyboard friendly.** Focus moves into the dialog on open, is trapped while it is open, closes on Escape or backdrop click, and returns to the trigger button on close. The dialog uses `role="dialog"` with `aria-modal`.
* **Variation aware.** The add-to-cart form supports variable products via WooCommerce's own variation script.

= Settings =

A simple WooCommerce-capability settings page (Peek menu) lets you:

* Enable or disable the quick view.
* Set the trigger button label.
* Choose which parts render in the modal (image, gallery, title, SKU, price, short description, add-to-cart, full-product link).

= Engine =

The quick-view orchestration (AJAX, nonce, asset enqueue, markup hooks) is provided by the shared, namespace-neutral `wppoland/storefront-kit` QuickView engine; this plugin is a thin adapter that supplies the text domain, options, asset URLs and WooCommerce fragment markup.

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

= 0.1.0 =
* Initial release: accessible AJAX quick-view modal for WooCommerce shop and archive loops, with a settings page for the button label and modal contents.
