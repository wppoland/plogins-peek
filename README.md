# Peek - Quick View for WooCommerce

Peek adds a "Quick view" button to your WooCommerce shop and archive loops. Clicking it opens an accessible modal that loads the product over AJAX, so shoppers can see the details without leaving the listing.

## Features

- AJAX quick-view modal with the featured image, gallery, title, SKU, price, stock status, short description, add-to-cart form (variations included) and a link to the full product.
- No jQuery in the plugin's own front-end code: a deferred, in-footer vanilla-JS script.
- No layout shift — the modal stays hidden until opened and scrolls internally.
- Focus-trapped and keyboard friendly: opens with focus inside the dialog, closes on Escape or backdrop click, and returns focus to the trigger.
- Configurable trigger label and style, placement scope, and which parts render in the modal.
- `[peek_quick_view]` shortcode (with `[peek]` alias) to place a trigger anywhere.

## Installation

1. Upload the plugin to `/wp-content/plugins/peek`, or install via Plugins → Add New.
2. Activate it. WooCommerce must be active.
3. Visit the **Peek** menu in wp-admin to configure the button label and modal contents.

## Frequently Asked Questions

**Where does the quick-view button appear?**
On the shop page and product archive loops (categories, tags, taxonomies), after each product. Single product pages are left unchanged.

**Does it use jQuery?**
The plugin's own front-end script is vanilla JavaScript. WooCommerce's bundled variation script is only enqueued when a product has variations, so the variation form works as expected.

---

Built by WPPoland — https://plogins.com

License: GPL-2.0-or-later
