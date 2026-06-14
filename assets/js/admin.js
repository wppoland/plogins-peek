/**
 * Peek - admin settings page enhancements.
 *
 * Progressive enhancement for the "?" help affordances. Each trigger already
 * carries the help text in aria-describedby (the tip element) and as its title
 * attribute, so the information is fully available with JavaScript disabled.
 * This script adds the visual tooltip: shown on hover/focus, hidden on
 * blur/leave/Escape. No dependencies, no jQuery. Enqueued deferred / in footer.
 */
(function () {
    'use strict';

    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    ready(function () {
        var triggers = document.querySelectorAll('.peek-help');
        if (!triggers.length) {
            return;
        }

        Array.prototype.forEach.call(triggers, function (trigger) {
            var tipId = trigger.getAttribute('data-peek-tip');
            if (!tipId) {
                return;
            }
            var tip = document.getElementById(tipId);
            if (!tip) {
                return;
            }

            // With JS active the title would double up with the visible tip, so
            // remove it to avoid the browser's own delayed native tooltip.
            trigger.removeAttribute('title');

            var show = function () {
                tip.hidden = false;
            };
            var hide = function () {
                tip.hidden = true;
            };

            hide();

            trigger.addEventListener('mouseenter', show);
            trigger.addEventListener('mouseleave', hide);
            trigger.addEventListener('focus', show);
            trigger.addEventListener('blur', hide);
            trigger.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    hide();
                    trigger.blur();
                }
            });
        });
    });
})();
