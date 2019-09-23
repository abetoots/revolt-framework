<?php

namespace Revolt_Framework\FrontEnd\Elementor_Widgets;

use DateTime;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WP_Query;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Query_Jobs extends Widget_Base
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
        return 'query_jobs';
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
        return __('Query Jobs', 'revolt-framework');
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
        return 'fas fa-briefcase';
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
        $this->start_controls_section(
            'query_settings',
            [
                'label'     => __('Modify Query Settings', 'revolt-framework'),
                'tab'       => Controls_Manager::TAB_CONTENT
            ]
        );

        /**
         * @return string The chosen date/time in MySQL format (YYYY-mm-dd HH:ii)
         */
        $this->add_control(
            'pick_date',
            [
                'label'         => __('Pick A Date', 'revolt-framework'),
                'type'          => Controls_Manager::DATE_TIME,
            ]
        );

        /**
         * @return string The chosen date/time in MySQL format (YYYY-mm-dd HH:ii)
         */
        $this->add_control(
            'date_retrieve_parameter',
            [
                'label' => __('Retrieve all jobs:', 'revolt-framework'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'before'    => __('Before', 'revolt-framework'),
                    'after'    => __('After', 'revolt-framework'),
                ],
                'default' => 'after',
            ]
        );


        /**
         * @return string The switcher field value.
         */
        $this->add_control(
            'is_inclusive',
            [
                'label' => __('Inclusive?', 'plugin-domain'),
                'description' => __('whether to return jobs/posts on the picked date as well'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-plugin'),
                'label_off' => __('Hide', 'your-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /**
         * @return string The chosen date/time in MySQL format (YYYY-mm-dd HH:ii)
         */
        $this->add_control(
            'date_range_specific',
            [
                'label'         => __('Specific date ranges:', 'revolt-framework'),
                'description'   => __('will override pick a date', 'revolt-framework'),
                'type'          => \Elementor\Controls_Manager::SELECT,
                'options'       => [
                    'today'         => __('Today', 'revolt-framework'),
                    'yesterday'     => __('Yesterday', 'revolt-framework'),
                    'last_seven'    => __('Last 7 Days', 'revolt-framework'),
                    'last_thirty'   => __('Last 30 Days', 'revolt-framework')
                ],
                'default' => 'after',
            ]
        );


        /**
         * @return string The text field value
         */
        $this->add_control(
            'query_jobs_header',
            [
                'label' => __('Heading', 'revolt-framework'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Context', 'revolt-framework'),
            ]
        );


        //end
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $date = '';
        //If user picked date, get the datetime, format to ISO
        if ($settings['pick_date']) {
            $date = new DateTime($settings['pick_date']);
            $date_in_ISO = $date->format('c');
        }

        //Override if specific range has been selected
        if ($settings['date_range_specific']) {
            switch ($settings['date_range_specific']) {
                case 'today':
                    $date = new DateTime('today');
                    break;
                case 'yesterday':
                    $date = new DateTime('yesterday');
                    break;
                case 'last_seven':
                    $date = new DateTime('7 days ago');
                    break;
                case 'last_thirty':
                    $date = new DateTime('30 days ago');
                    break;
                default:
                    $date = new DateTime('yesterday');
            }
            $date_in_ISO = $date->format('c');
        }

        $args = array(
            'post_type'  => 'revolt-job-post',
            'date_query' => array(
                $settings['date_retrieve_parameter']  => $date_in_ISO
            )
        );

        $query = new WP_Query($args);

        ?>
            <div class="QueryJobs">
                <h3 class="QueryJobs__header"><?php echo $settings['query_jobs_header']; ?></h3>
                <?php
                        if ($query->have_posts()) : ?>
                    <div class="Jobs -noPadding">
                        <?php while ($query->have_posts()) : $query->the_post();
                                        get_template_part('/template-parts/content', get_post_type());
                                    endwhile; ?>
                    </div>
                <?php wp_reset_postdata();
                        endif; ?>
            </div>
    <?php
        }
    }
