<?php

namespace LofiFramework\Helpers\Columns;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
class Columns_CPT
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
                $new_columns['title'] = __('Job', 'lofi-framework');
                $new_columns['company'] = __('Company', 'lofi-framework');
                $new_columns['taxonomy-employment_types'] = __('Employment', 'lofi-framework');
                $new_columns['salary'] = __('Salary', 'lofi-framework');
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
                $companyMeta = get_post_meta($post_id, 'lofi-job-post-company-field', true);
                $companyOption = esc_html(get_option('company_name'));
                $companyValue = ($companyMeta) ? esc_html($companyMeta) : $companyOption;
                echo '<p>' . $companyValue . '</p>';
                break;

            case 'salary':
                $salaryMeta = get_post_meta($post_id, 'lofi-job-post-salary-field', true);
                $salaryMetaOptional = get_post_meta($post_id, 'lofi-job-post-salary-field-optional', true);

                if (!$salaryMeta) {
                    $salaryValue = 'DOE';
                } else {
                    if ($salaryMetaOptional) {
                        $salaryValue = '$' . $salaryMeta . '-' . '$' . $salaryMetaOptional;
                    } else {
                        $salaryValue = '$' . $salaryMeta;
                    }
                }

                $salaryValue = esc_html($salaryValue);
                echo '<p>' . $salaryValue . '</p>';
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
        add_filter('manage_lofi-job-post_posts_columns', array($this, 'register_columns_cpt'));
        add_filter('manage_lofi-job-post_posts_custom_column', array($this, 'populate_custom_columns_cpt'), 10, 2);
    }
}
Columns_CPT::get_instance();
