<?php
namespace Elementor_LofiFramework\Elementor_Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
*/
class Registration_Form extends Widget_Base{

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
        return 'lofi-registration-form';
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
        return __('Lofi Registration Form', 'lofi-framework');
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
            'form_title_section',
            [
                'label'         => __( 'Title', 'lofi-framework' ),
                'tab'           => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', 'lofi-framework' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => true,
				'default' => true,
			]
        );

        $this->add_control(
			'title_text',
			[
                'label' => __( 'Title', 'lofi-framework' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'title'         => __( 'Replace title text', 'lofi-framework' ),
                'placeholder'   => __( 'Input title text', 'lofi-framework' ),
                'default'       => __( 'Register as an Employer', 'lofi-framework' ),
			]
        );


        $this->end_controls_section();

        /**
         * Section: Button
         * Adds a responsive control for aligning the button inside the form
         * Adds an input text for the button text
         */
        $this->start_controls_section(
            'button_section',
            [
                'label'         => __( 'Button', 'lofi-framework' ),
                'tab'           => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        //Input Text Control
        $this->add_control(
            'button_text',
            [
                'label'         => __( 'Text', 'lofi-framework' ),
                'type'          => \Elementor\Controls_Manager::TEXT,
                'default'       =>  __( 'Register', 'lofi-framework' ),
                'title'         => __( 'Replace button text', 'lofi-framework' ),
                'placeholder'   => __( 'Input Button Text', 'lofi-framework' ),
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label' => __( 'Alignment', 'lofi-framework' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lofi-framework' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lofi-framework' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lofi-framework' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'lofi-button-align-%s',
            ]
        );
        

        $this->end_controls_section();

        /**
         * Section: Form
         * Adds a responsive control for aligning the form
         */

        $this->start_controls_section(
            'form_section',
            [
                'label'         => __( 'Form', 'lofi-framework' ),
                'tab'           => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'form_align',
            [
                'label' => __( 'Alignment', 'lofi-framework' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lofi-framework' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lofi-framework' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lofi-framework' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'lofi-form-align-%s',
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


        $button_text =  $settings['button_text'];
        $show_title = $settings['show_title'];
        $title = $settings['title_text'];
        

        echo do_shortcode( '[lofi-employer-registration-form show_title='.$show_title.' title=" '.$title.' "
        button_text=" '.$button_text.' "
        ]' );

    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'title_text', 'none' );
        view.addInlineEditingAttributes( 'button_text', 'none' );
        #>
        <div id="register-form" class="widecolumn" class="lofi-form-align">
            <# if (settings.show_title){ #>
                <h3 {{{ view.getRenderAttributeString( 'title_text' ) }}} >{{{settings.title_text}}} </h3>
            <# } #>

        <form action="" method="post" autocomplete="off">
        
            <div class="form-row">
                <label for="username">Username<strong>*</strong></label>
                <input type="text" name="username" value="">
            </div>
            
            <div class="form-row">
                <label for="email">Email <strong>*</strong></label>
                <input type="email;" name="email" id="email" value="">
            </div>

            <div class="form-row">
                <label for="password">Password<strong>*</strong></label>
                <input type="password" name="password" id="password" value="">
            </div>
    
            <p class="signup-submit">
                <input {{{view.addInlineEditingAttributes( 'button_text' )}}} type="submit" name="submit_lofi_employer" class="register-button"
                    value=" {{{settings.button_text}}} " style="cursor:pointer;" disabled>
            </p>
        </form>
    </div>
        <?php
    }

}
