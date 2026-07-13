=== Plogins Peek - Product Preview for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, quick view, product quick view, product modal, quick shop
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fast product preview popup for WooCommerce: an AJAX product modal with gallery, price, SKU and add-to-cart. No jQuery.

== Description ==

Peek adds a product quick view button to your WooCommerce shop and archive product loops. Clicking it opens an accessible AJAX product modal, so shoppers can preview products, choose options and add to cart without leaving the listing.

The modal shows the featured image and gallery thumbnails, title, SKU, price, stock status, short description, the native add-to-cart form (including variable products), and a link to the full product page. Each part can be toggled from the settings screen.

= Documentation and links =

* **Documentation** - https://plogins.com/plogins-peek/docs/
* **Plugin page** - https://plogins.com/plogins-peek/
* **Source code** - https://github.com/wppoland/plogins-peek
* **Bug reports and feature requests** - https://github.com/wppoland/plogins-peek/issues


= Built for speed and accessibility =

* **No jQuery** in the plugin's own front-end code, the script is vanilla JS, deferred, and loaded in the footer.
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

Place a quick-view trigger anywhere with `[peek_quick_view id="123"]`, or the shorter `[peek id="123"]` alias. Optional attributes: `text` (custom label) and `style` (`text`, `icon`, or `icon_text`). The modal and its assets load automatically wherever the shortcode appears.

Peek is developed in the open. The code, open issues and release history live at https://github.com/wppoland/plogins-peek, bug reports and patches are welcome there.

= Upgrade to Peek PRO =

Peek PRO extends the free quick view with:

* Recently viewed products.
* A related-products carousel inside the modal.
* Colour and label variation swatches.
* Custom modal sections.
* Previous / next navigation between products without closing the modal.
* Extra styling controls.
* Quick-view analytics.

Learn more at https://plogins.com/plogins-peek-pro/

= More WooCommerce plugins by Plogins =

* **Reel** - hover zoom, an accessible lightbox and product video for WooCommerce galleries: https://plogins.com/plogins-reel/
* **Sizer** - accessible size guides and tables in a modal: https://plogins.com/plogins-sizer/
* **Marks** - automatic and manual product badges, CSS-only: https://plogins.com/plogins-marks/
* **Swatch** - accessible colour and label variation swatches, no jQuery: https://plogins.com/plogins-swatch/

Browse the full family at https://plogins.com/

== Installation ==

1. Upload the plugin to `/wp-content/plugins/plogins-peek`, or install via Plugins → Add New.
2. Activate it. WooCommerce must be active.
3. Visit the **Peek** menu in wp-admin to configure the button label and modal contents.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Yes. Peek requires an active WooCommerce installation.

= Does it use jQuery? =

The plugin's own front-end script is vanilla JavaScript with no jQuery dependency. When a product has variations, WooCommerce's bundled variation script (which itself uses jQuery) is enqueued so the variation form works as expected.

= Where does the quick-view button appear? =

On the shop page and product archive loops (categories, tags, taxonomies), after each product. It does not change single product pages.

= Does the modal support add to cart? =

Yes. Peek renders WooCommerce's native add-to-cart form inside the quick shop modal, including quantity and variable-product choices.

= Does it work with variable products? =

Yes. Variable products use WooCommerce's own variation form inside the product modal, so shoppers can choose a variation before adding to cart.

= Will it cause layout shift? =

No. The modal is hidden until opened and overlays the page, so opening it never reflows existing content.

= Can I place a quick-view button manually? =

Yes. Use `[peek_quick_view id="123"]` or `[peek id="123"]` to add a product quick view trigger in custom layouts.


= Does this plugin work on WordPress Multisite? =

Yes. This plugin is compatible with WordPress Multisite. Network activate it or activate it on individual sites; each site keeps its own settings and data.

== Screenshots ==

1. The quick-view modal showing the product gallery, price and add-to-cart form.
2. The Peek settings screen.

== External Services ==

Peek does not connect to any external services. The quick-view modal fetches its product fragment from your own site over WordPress' `admin-ajax.php` (the `peek_quick_view` action), so no shopper or product data ever leaves your server. Peek's only stored data is two WordPress options it creates, `peek_settings` (your modal and button configuration) and `peek_db_version`, both removed when the plugin is deleted. Peek sends no email and loads no third-party scripts, fonts or analytics.

== Translations ==

Plogins Peek includes Polish, German and Spanish translations for the plugin interface. The text domain is `plogins-peek`, so WordPress.org language packs can also override or extend these bundled translations.

== Changelog ==

= 1.0.6 =
* Reverted Author header to WPPoland.com to match the plugin family (author credit is the motylanogha contributor).

= 1.0.5 =
* Author header now credits Mariusz Szatkowski (motylanogha).

= 1.0.4 =
* Readme: added Peek PRO and related Plogins plugins links.

= 1.0.3 =
* Clearer name: Plogins Peek - Product Preview for WooCommerce.

= 1.0.2 =
* Added bundled Polish, German and Spanish translations for the plugin interface.

= 1.0.1 =
* First stable release.

= 0.3.1 =
* Renamed to Plogins Peek for WooCommerce for a more distinctive plugin name.

= 0.3.0 =
* New: loop button placement, below the card or overlay on the thumbnail (hover/focus).
* New: `[peek]` shortcode as an alias for `[peek_quick_view]`.

= 0.2.0 =
* New: `[peek_quick_view]` shortcode to place a quick-view trigger anywhere, with optional `id`, `text` and `style` attributes.
* New: stock status row in the modal, with a toggle.
* New: configurable gallery thumbnail count (0–12).
* New: trigger button style, text, icon, or icon + text (icon-only keeps an accessible name).
* New: display scope, load on shop/archives only, or also single-product related/up-sell loops.
* New: modal chrome controls in the settings page (title, close-button label, loading/error text, view-product link text, SKU label, and toggles for the modal heading, close button and backdrop-click close).
* New: `uninstall.php` removes the plugin's options on delete.
* Added Domain Path for translations.

= 0.1.0 =
* Initial release: accessible AJAX quick-view modal for WooCommerce shop and archive loops, with a settings page for the button label and modal contents.
