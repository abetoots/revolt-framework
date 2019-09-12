<?php

namespace Revolt_Framework\Inc\Helpers;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
class Columns
{
    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var Plugin The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     *
     * @return Plugin An instance of the class.
     */
    public static function get_instance()
    {
        if (!self::$_instance)
            self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * Register columns for our job post cpt
     * @return Array $new_columns
     */
    public function register_columns_cpt($columns)
    {
        unset($columns['author']);
        /**
         * Arrange our columns while we're at it
         * @link https://www.isitwp.com/change-wordpress-admin-post-columns-order/
         */
        $before = 'date'; //before this
        $new_columns = array();
        foreach ($columns as $key => $value) {
            if ($key == $before) {
                $new_columns['company'] = __('Company', 'revolt-framework');
                $new_columns['taxonomy-employment_types'] = __('Employment', 'revolt-framework');
                $new_columns['salary'] = __('Salary', 'revolt-framework');
            }
            $new_columns[$key] = $value;
        }


        return $new_columns;
    }

    /**
     * Populate CPT custom columns
     */
    public function populate_custom_columns_cpt($column, $post_id)
    {
        switch ($column) {

            case 'company':
                $authorID = get_the_author_meta('ID');
                echo '<p>' . get_field('revolt_emp_name', "user_$authorID") . '</p>';
                break;

            case 'salary':
                $salaryRangeFrom = get_field('job_salary_range_from', $post_id);
                $salaryRangeTo = get_field('job_salary_range_to', $post_id);
                $salaryString = $salaryRangeTo ? "$salaryRangeFrom - $salaryRangeTo" : "$salaryRangeFrom";
                echo "<p>$salaryString</p>";
                break;
        }
    }

    /**
     * Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Init function that handles all hooks and filters
     *
     * @since 1.0.0
     * @access public
     */
    public function init()
    {
        //CPT Columns
        add_filter('manage_revolt-job-post_posts_columns', array($this, 'register_columns_cpt'));
        add_filter('manage_revolt-job-post_posts_custom_column', array($this, 'populate_custom_columns_cpt'), 10, 2);
    }
}
Columns::get_instance();
