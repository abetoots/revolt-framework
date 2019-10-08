<?php

namespace Revolt_Framework\FrontEnd\Elementor_Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Category_Icons extends Widget_Base
{
    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'category_icons';
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
    public function get_title()
    {
        return __('Category Icon', 'revolt-framework');
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
    public function get_icon()
    {
        return 'fas fa-icons';
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
    public function get_categories()
    {
        return ['general'];
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
    protected function _register_controls()
    {
        $options = [
            ''  => ''
        ];
        $categories = get_terms(array(
            'taxonomy'      => 'job_categories', // to make it simple I use default categories
            'orderby'       => 'name',
            'hide_empty'    => false
        ));
        foreach ($categories as $category) {
            $options[$category->term_id] = __($category->name, 'revolt-framework');
        }

        //STYLE CONTROLS
        $this->start_controls_section(
            'category_icons_style',
            [
                'label'     => __('Style', 'revolt-framework'),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label'     => __('Color', 'plugin-domain'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => [
                    '{{WRAPPER}} .CategoryIcons__category' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'background',
                'label'     => __('Background', 'revolt-framework'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .CategoryIcons__slot'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'text_typography',
                'label'     => __('Typography', 'revolt-framework'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .CategoryIcons__category'
            ]
        );

        $this->end_controls_section();
        //END STYLE CONTROLS



        //CONTENT CONTROLS
        $this->start_controls_section(
            'category_icons_settings',
            [
                'label'     => __('Content', 'revolt-framework'),
                'tab'       => Controls_Manager::TAB_CONTENT
            ]
        );

        $repeater = new Repeater();

        /**
         * @return array An array containing image data: id & url
         */
        $repeater->add_control(
            'image',
            [
                'label'         => __('Choose Image', 'revolt-framework'),
                'description'   => __('Recommended: 64 x 64 images', 'revolt-framework'),
                'type'          => Controls_Manager::MEDIA,
            ]
        );

        /**
         * @return string The text field value.
         */
        $repeater->add_control(
            'category',
            [
                'label'         => __('Select categories', 'revolt-framework'),
                'type'          => Controls_Manager::SELECT,
                'options'       =>  $options,
                'default' => '',
            ]
        );

        $this->add_control(
            'categories_list',
            [
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $repeater->get_controls(),
                'title_field'   => 'Add Item'
            ]
        );


        $this->end_controls_section();
        //END CONTENT CONTROLS
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="CategoryIcons">
            <?php
                    if ($settings['categories_list']) :
                        foreach ($settings['categories_list'] as $item) :
                            $category = get_term(absint($item['category']), 'job_categories');
                            ?>
                    <div class="CategoryIcons__slot">
                        <a class="CategoryIcons__link" href="<?php echo esc_url(get_term_link(absint($item['category']), 'job_categories')) ?>">
                            <img class="CategoryIcons__image" src="<?php echo esc_url($item['image']['url']) ?>">
                            <h4 class="CategoryIcons__category"><?php echo $category->name; ?></h4>
                        </a>
                    </div>

            <?php
                        endforeach;
                    endif; ?>
        </div>
<?php
    }
}
