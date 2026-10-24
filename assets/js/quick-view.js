/**
 * Peek, accessible WooCommerce quick view.
 *
 * Vanilla JS (no jQuery dependency of our own). Opens a focus-trapped modal,
 * fetches the product fragment over admin-ajax, restores focus on close, and
 * closes on Escape / backdrop. Config is provided via wp_localize_script as
 * `window.peekQuickView`.
 */
document.addEventListener('DOMContentLoaded', () => {
  const config = window.peekQuickView;
  const modal = document.querySelector('[data-peek-quick-view-modal]');
  const content = document.querySelector('[data-peek-quick-view-content]');
  // Small, dedicated polite live region for loading/error announcements. The
  // product fragment is injected into `content` (not a live region) so screen
  // readers don't re-announce the whole product + add-to-cart form on open.
  const statusRegion = document.querySelector('[data-peek-quick-view-status]');

  if (!config || !modal || !content) {
    return;
  }

  const dialog = modal.querySelector('.peek-quick-view-dialog') || modal;

  const prefersReducedMotion =
    typeof window.matchMedia === 'function' &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Build a friendly status block (spinner / icon + message) without injecting
  // markup from config strings as HTML, text is set via textContent.
  const renderStatus = (message, variant) => {
    content.textContent = '';
    const wrap = document.createElement('div');
    wrap.className = 'peek-quick-view-status';

    const indicator = document.createElement('span');
    if (variant === 'error') {
      indicator.className = 'peek-quick-view-status__icon';
      indicator.setAttribute('aria-hidden', 'true');
      indicator.textContent = '⚠';
    } else {
      indicator.className = 'peek-quick-view-status__spinner';
      indicator.setAttribute('aria-hidden', 'true');
    }
    wrap.appendChild(indicator);

    const text = document.createElement('p');
    text.textContent = message || '';
    // The visible status text is decorative for SR users, the dedicated
    // live region below carries the announcement.
    text.setAttribute('aria-hidden', 'true');
    wrap.appendChild(text);

    content.appendChild(wrap);

    // Announce loading/error via the small dedicated polite live region only.
    if (statusRegion) {
      statusRegion.textContent = message || '';
    }
  };

  const FOCUSABLE = [
    'a[href]',
    'button:not([disabled])',
    'input:not([disabled]):not([type="hidden"])',
    'select:not([disabled])',
    'textarea:not([disabled])',
    '[tabindex]:not([tabindex="-1"])',
  ].join(',');

  // Element that had focus before the modal opened, so we can restore it.
  let lastFocused = null;

  const focusableInDialog = () =>
    Array.prototype.slice
      .call(dialog.querySelectorAll(FOCUSABLE))
      .filter((el) => el.offsetParent !== null || el === document.activeElement);

  const focusFirst = () => {
    const items = focusableInDialog();
    if (items.length > 0) {
      items[0].focus();
    } else if (typeof dialog.focus === 'function') {
      dialog.focus();
    }
  };

  const openModal = (trigger) => {
    lastFocused = trigger || (document.activeElement instanceof HTMLElement ? document.activeElement : null);
    modal.hidden = false;
    document.body.classList.add('peek-quick-view-open');

    // Enter transition: start in the animating (hidden) state, then release on
    // the next frame so the CSS transition runs. Skipped under reduced motion.
    if (!prefersReducedMotion) {
      modal.setAttribute('data-peek-animating', '');
      requestAnimationFrame(() => {
        requestAnimationFrame(() => modal.removeAttribute('data-peek-animating'));
      });
    }

    focusFirst();
  };

  const closeModal = () => {
    modal.hidden = true;
    modal.removeAttribute('data-peek-animating');
    document.body.classList.remove('peek-quick-view-open');

    // Return focus to the control that opened the modal (accessibility).
    if (lastFocused && typeof lastFocused.focus === 'function' && document.contains(lastFocused)) {
      lastFocused.focus();
    }
    lastFocused = null;
  };

  const trapFocus = (event) => {
    if (event.key !== 'Tab' || modal.hidden) {
      return;
    }

    const items = focusableInDialog();
    if (items.length === 0) {
      event.preventDefault();
      if (typeof dialog.focus === 'function') {
        dialog.focus();
      }
      return;
    }

    const first = items[0];
    const last = items[items.length - 1];
    const active = document.activeElement;

    if (event.shiftKey && (active === first || active === dialog)) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && active === last) {
      event.preventDefault();
      first.focus();
    }
  };

  // Let WooCommerce wire up the variations form on the freshly injected markup.
  // wc-add-to-cart-variation self-initializes on `wc_variation_form`; we nudge
  // it without taking a hard jQuery dependency of our own.
  const initVariations = () => {
    const jq = window.jQuery;
    if (!jq) {
      return;
    }

    const forms = jq('.peek-quick-view-content form.variations_form');
    if (typeof forms.wc_variation_form === 'function') {
      forms.each(function init() {
        jq(this).wc_variation_form();
      });
    }
  };

  // Wire up the gallery thumbnail switcher so clicking or keyboard-activating
  // a thumbnail swaps the large image in the preview column.
  const initGallery = () => {
    const gallery = content.querySelector('.peek-quick-view-gallery');
    const mainImg = content.querySelector('.peek-quick-view-main-image img');
    if (!gallery || !mainImg) {
      return;
    }

    const switchImage = (thumb) => {
      const targetSrc = thumb.dataset.largeSrc;
      const targetSrcset = thumb.dataset.largeSrcset;

      if (!targetSrc || thumb.getAttribute('aria-current') === 'true') {
        return;
      }

      if (!prefersReducedMotion) {
        mainImg.style.opacity = '0.35';
        const restoreOpacity = () => {
          mainImg.style.opacity = '1';
          mainImg.removeEventListener('load', restoreOpacity);
        };
        mainImg.addEventListener('load', restoreOpacity);
        setTimeout(restoreOpacity, 150);
      }

      mainImg.src = targetSrc;
      if (targetSrcset) {
        mainImg.srcset = targetSrcset;
      } else {
        mainImg.removeAttribute('srcset');
      }

      const thumbImg = thumb.querySelector('img');
      if (thumbImg && thumbImg.alt) {
        mainImg.alt = thumbImg.alt;
      }

      const thumbs = gallery.querySelectorAll('.peek-quick-view-thumb');
      thumbs.forEach((el) => {
        if (el === thumb) {
          el.setAttribute('aria-current', 'true');
          el.classList.add('peek-quick-view-thumb--active');
        } else {
          el.setAttribute('aria-current', 'false');
          el.classList.remove('peek-quick-view-thumb--active');
        }
      });
    };

    gallery.addEventListener('click', (event) => {
      const thumb = event.target.closest('.peek-quick-view-thumb');
      if (thumb && gallery.contains(thumb)) {
        event.preventDefault();
        switchImage(thumb);
      }
    });

    gallery.addEventListener('keydown', (event) => {
      if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') {
        return;
      }
      const activeEl = document.activeElement;
      if (!activeEl || !activeEl.classList.contains('peek-quick-view-thumb')) {
        return;
      }
      const thumbs = Array.from(gallery.querySelectorAll('.peek-quick-view-thumb'));
      const idx = thumbs.indexOf(activeEl);
      if (idx === -1) {
        return;
      }
      event.preventDefault();
      const nextIdx = event.key === 'ArrowRight'
        ? (idx + 1) % thumbs.length
        : (idx - 1 + thumbs.length) % thumbs.length;
      thumbs[nextIdx].focus();
      switchImage(thumbs[nextIdx]);
    });
  };

  content.addEventListener('peek:quick-view-loaded', () => {
    initGallery();
  });

  document.addEventListener('click', async (event) => {
    const trigger = event.target.closest('[data-peek-quick-view]');
    const close = event.target.closest('[data-peek-quick-view-close]');
    const backdrop = event.target.closest('[data-peek-quick-view-backdrop]');

    if (close) {
      event.preventDefault();
      closeModal();
      return;
    }

    if (backdrop && config.showBackdropClose) {
      event.preventDefault();
      closeModal();
      return;
    }

    if (!trigger) {
      return;
    }

    event.preventDefault();

    const productId = trigger.dataset.productId;

    if (!productId) {
      return;
    }

    openModal(trigger);
    content.setAttribute('aria-busy', 'true');
    renderStatus(config.loadingText, 'loading');

    const url = new URL(config.ajaxUrl, window.location.origin);
    url.searchParams.set('action', config.action);
    url.searchParams.set('nonce', config.nonce);
    url.searchParams.set('product_id', productId);

    try {
      const response = await fetch(url.toString(), { credentials: 'same-origin' });

      if (!response.ok) {
        throw new Error('quick-view-http-' + response.status);
      }

      const payload = await response.json();

      if (!payload || !payload.success || !payload.data || !payload.data.html) {
        throw new Error('quick-view-failed');
      }

      // Bail if the user already closed the modal while the request was in flight.
      if (modal.hidden) {
        return;
      }

      content.innerHTML = payload.data.html;
      // Tell add-ons the modal is populated. Peek Pro's analytics counts opens
      // from this, and without it its dashboard could only ever show zeros.
      content.dispatchEvent(
        new CustomEvent('peek:quick-view-loaded', {
          bubbles: true,
          detail: { productId: Number(productId) },
        }),
      );
      // Clear the loading announcement; focus moves into the dialog so the
      // product itself does not need to be announced via the live region.
      if (statusRegion) {
        statusRegion.textContent = '';
      }
      initVariations();
      initGallery();
      // Move focus into the freshly loaded content if the modal is still open.
      focusFirst();
    } catch (error) {
      if (!modal.hidden) {
        renderStatus(config.errorText, 'error');
      }
    } finally {
      content.removeAttribute('aria-busy');
    }
  });

  // Add to cart inside the modal without leaving the listing. The form is
  // posted as-is (variation fields and hidden inputs other plugins add, such as
  // Peek Pro's analytics flag) to its own action, the way the browser would,
  // and the result is read from the WooCommerce notices in the response.
  // Grouped and external products keep their native behaviour.
  const noticeText = (doc, selector) =>
    Array.prototype.map
      .call(doc.querySelectorAll(selector), (el) => el.textContent.replace(/\s+/g, ' ').trim())
      .filter(Boolean);

  const showCartResult = (form, variant, messages) => {
    const old = content.querySelector('.peek-quick-view-notice');
    if (old) {
      old.remove();
    }

    const notice = document.createElement('div');
    notice.className = 'peek-quick-view-notice peek-quick-view-notice--' + variant;
    notice.setAttribute('role', variant === 'error' ? 'alert' : 'status');

    messages.forEach((message) => {
      const line = document.createElement('p');
      line.textContent = message;
      notice.appendChild(line);
    });

    if (variant === 'success' && config.cartUrl) {
      const link = document.createElement('a');
      link.className = 'button peek-quick-view-notice__link';
      link.href = config.cartUrl;
      link.textContent = config.viewCartText || '';
      notice.appendChild(link);
    }

    form.parentNode.insertBefore(notice, form);
  };

  // Refresh mini-carts the way WooCommerce's own add-to-cart script does:
  // fetch fresh fragments, swap them in, then announce the add.
  const refreshCart = async () => {
    let fragments;
    let cartHash;

    if (config.fragmentsUrl) {
      try {
        const response = await fetch(config.fragmentsUrl, { method: 'POST', credentials: 'same-origin' });
        const data = response.ok ? await response.json() : null;
        if (data && data.fragments) {
          fragments = data.fragments;
          cartHash = data.cart_hash;
          Object.keys(fragments).forEach((selector) => {
            document.querySelectorAll(selector).forEach((el) => {
              el.outerHTML = fragments[selector];
            });
          });
        }
      } catch (error) {
        // The cart itself changed; only the mini-cart refresh is lost.
      }
    }

    const jq = window.jQuery;
    if (jq) {
      // wc-cart-fragments stores these, and the Mini-Cart block bridges this
      // jQuery event to its own native one. No button is passed: WooCommerce
      // would append a second View cart link next to it, and the modal notice
      // already carries one.
      jq(document.body).trigger('added_to_cart', [fragments, cartHash]);
    } else {
      document.body.dispatchEvent(
        new CustomEvent('wc-blocks_added_to_cart', { bubbles: true, cancelable: true }),
      );
    }
  };

  content.addEventListener('submit', async (event) => {
    const form = event.target;
    const product = form.closest('.peek-quick-view-product');

    if (
      event.defaultPrevented ||
      !(form instanceof HTMLFormElement) ||
      !form.classList.contains('cart') ||
      form.classList.contains('grouped_form') ||
      !product ||
      product.classList.contains('product-type-grouped') ||
      product.classList.contains('product-type-external')
    ) {
      return;
    }

    event.preventDefault();

    const submitter = event.submitter || form.querySelector('[type="submit"]');
    let data;
    try {
      data = new FormData(form, submitter || undefined);
    } catch (error) {
      data = new FormData(form);
    }
    // A simple product's id travels on the submit button itself.
    if (submitter && submitter.name && !data.has(submitter.name)) {
      data.append(submitter.name, submitter.value);
    }

    if (submitter) {
      submitter.disabled = true;
      submitter.classList.add('loading');
    }
    form.setAttribute('aria-busy', 'true');

    let response;
    try {
      response = await fetch(form.action || window.location.href, {
        method: 'POST',
        body: data,
        credentials: 'same-origin',
      });
    } catch (error) {
      // Network failure before WooCommerce saw the request: submit normally.
      // The button's name/value is not sent by submit(), so carry it over.
      if (submitter && submitter.name && !form.querySelector('input[type="hidden"][name="' + submitter.name + '"]')) {
        const carry = document.createElement('input');
        carry.type = 'hidden';
        carry.name = submitter.name;
        carry.value = submitter.value;
        form.appendChild(carry);
      }
      HTMLFormElement.prototype.submit.call(form);
      return;
    } finally {
      if (submitter) {
        submitter.disabled = false;
        submitter.classList.remove('loading');
        // Disabling the focused button dropped focus out of the dialog.
        if (!modal.hidden && !dialog.contains(document.activeElement)) {
          submitter.focus();
        }
      }
      form.removeAttribute('aria-busy');
    }

    const doc = new DOMParser().parseFromString(await response.text(), 'text/html');
    let errors = noticeText(
      doc,
      '.woocommerce-error li, .wc-block-components-notice-banner.is-error .wc-block-components-notice-banner__content',
    );
    if (errors.length === 0) {
      errors = noticeText(doc, '.woocommerce-error');
    }

    if (!response.ok || errors.length > 0) {
      showCartResult(form, 'error', errors.length > 0 ? errors : [config.errorText]);
      return;
    }

    showCartResult(form, 'success', [config.addedText]);
    content.dispatchEvent(new CustomEvent('peek:added-to-cart', { bubbles: true }));
    await refreshCart();
  });

  document.addEventListener('keydown', (event) => {
    if (modal.hidden) {
      return;
    }

    if (event.key === 'Escape') {
      closeModal();
      return;
    }

    trapFocus(event);
  });
});
