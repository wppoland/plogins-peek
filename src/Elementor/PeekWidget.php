<?php
/**
 * Elementor widget: Quick View button.
 *
 * A thin wrapper around the [peek] shortcode so a quick-view trigger can be
 * placed with the Elementor editor. Kept deliberately minimal (renders the
 * shortcode) so a future migration to Elementor v4 atomic widgets is localized
 * to this class. Loaded only from the `elementor/widgets/register` hook, so the
 * `\Elementor\Widget_Base` base class is guaranteed to exist here.
 *
 * @package Peek
 */

declare(strict_types=1);

namespace Peek\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined('ABSPATH') || exit;

/**
 * Quick View Elementor widget.
 */
final class PeekWidget extends Widget_Base
{
    /**
     * Widget machine name (matches the shortcode).
     */
    public function get_name(): string
    {
        return 'peek';
    }

    /**
     * Widget label shown in the editor.
     */
    public function get_title(): string
    {
        return esc_html__('Quick View', 'plogins-peek');
    }

    /**
     * Editor panel icon.
     */
    public function get_icon(): string
    {
        return 'eicon-time-line';
    }

    /**
     * Editor panel categories.
     *
     * @return string[]
     */
    public function get_categories(): array
    {
        return ['woocommerce-elements', 'general'];
    }

    /**
     * Search keywords in the editor.
     *
     * @return string[]
     */
    public function get_keywords(): array
    {
        return ['peek', 'quick view', 'product', 'modal', 'preview', 'woocommerce'];
    }

    /**
     * Register the editor controls.
     */
    protected function register_controls(): void
    {
        $this->start_controls_section(
            'content',
            ['label' => esc_html__('Quick view', 'plogins-peek')]
        );

        $this->add_control(
            'product_id',
            [
                'label'       => esc_html__('Product ID', 'plogins-peek'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'min'         => 0,
                'description' => esc_html__('The product the quick-view button opens.', 'plogins-peek'),
            ]
        );

        $this->add_control(
            'text',
            [
                'label'       => esc_html__('Button text', 'plogins-peek'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => esc_html__('Leave empty to use the default button label.', 'plogins-peek'),
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => esc_html__('Button style', 'plogins-peek'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''          => esc_html__('Default', 'plogins-peek'),
                    'text'      => esc_html__('Text', 'plogins-peek'),
                    'icon'      => esc_html__('Icon', 'plogins-peek'),
                    'icon_text' => esc_html__('Icon and text', 'plogins-peek'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget on the front end and in the editor preview.
     */
    protected function render(): void
    {
        $settings   = $this->get_settings_for_display();
        $product_id = isset($settings['product_id']) ? absint($settings['product_id']) : 0;
        $text       = isset($settings['text']) ? sanitize_text_field((string) $settings['text']) : '';
        $style      = isset($settings['style']) ? sanitize_key((string) $settings['style']) : '';

        echo do_shortcode(
            sprintf(
                '[peek id="%d" text="%s" style="%s"]',
                $product_id,
                esc_attr($text),
                esc_attr($style)
            )
        );
    }
}
