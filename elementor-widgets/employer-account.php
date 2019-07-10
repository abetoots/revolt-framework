<?php
namespace Elementor_LofiFramework\Elementor_Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
*/
class Employer_Account extends Widget_Base{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
    */
    public function get_name(){
        return 'lofi-employer-account';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
    */
    public function get_title(){
        return __('Lofi Employer Account', 'lofi-framework');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
    */
    public function get_icon(){
        return 'fa fa-wpforms';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return array Widget categories.
    */
    public function get_categories(){
        return [ 'basic' ];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
    */
    protected function _register_controls() {

        /**
         * Section: Title
         * Adds a toggle for showing/hiding the title
         * Adds an input field for the title text
         */
        $this->start_controls_section(
            'lofi_title',
            [
                'label'         => __( 'Title', 'lofi-framework' ),
                'tab'           => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
    */
    protected function render() {

        $settings = $this->get_settings_for_display();

        echo do_shortcode('[lofi-employer-dashboard]');
    }
}