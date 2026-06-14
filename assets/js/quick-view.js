/**
 * Peek — accessible WooCommerce quick view.
 *
 * Vanilla JS (no jQuery dependency of our own). Opens a focus-trapped modal,
 * fetches the product fragment over admin-ajax, restores focus on close, and
 * closes on Escape / backdrop. Config is provided by the storefront-kit
 * QuickViewEngine via wp_localize_script as `window.peekQuickView`.
 */
document.addEventListener('DOMContentLoaded', () => {
  const config = window.peekQuickView;
  const modal = document.querySelector('[data-peek-quick-view-modal]');
  const content = document.querySelector('[data-peek-quick-view-content]');

  if (!config || !modal || !content) {
    return;
  }

  const dialog = modal.querySelector('.peek-quick-view-dialog') || modal;

  const prefersReducedMotion =
    typeof window.matchMedia === 'function' &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Build a friendly status block (spinner / icon + message) without injecting
  // markup from config strings as HTML — text is set via textContent.
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
    wrap.appendChild(text);

    content.appendChild(wrap);
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
      initVariations();
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
