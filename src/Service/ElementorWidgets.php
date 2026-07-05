<?php
/**
 * Elementor integration service.
 *
 * Registers the Peek Elementor widget(s). The `elementor/widgets/register`
 * action only fires when Elementor is active, so this service is self-guarding:
 * nothing loads unless Elementor is present.
 *
 * @package Peek
 */

declare(strict_types=1);

namespace Peek\Service;

use Peek\Contract\HasHooks;
use Peek\Elementor\PeekWidget;

defined('ABSPATH') || exit;

/**
 * Wires the Peek widgets into the Elementor editor.
 */
final class ElementorWidgets implements HasHooks
{
    public function registerHooks(): void
    {
        add_action('elementor/widgets/register', [$this, 'register']);
    }

    /**
     * Register widget instances with Elementor's widgets manager.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register($widgets_manager): void
    {
        // Loaded here (not autoloaded) so \Elementor\Widget_Base always exists.
        require_once __DIR__ . '/../Elementor/PeekWidget.php';
        $widgets_manager->register(new PeekWidget());
    }
}
