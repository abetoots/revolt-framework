<?php

namespace LofiFramework\Core;


if (!defined('ABSPATH')) exit; // Exit if accessed directly
require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/helpers/meta-premium-terms.php');
require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/helpers/columns/columns-taxonomies.php');

class Taxonomies
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
    public static function instance()
    {
        if (!self::$_instance)
            self::$_instance = new self();
        return self::$_instance;
    }

    public function add_taxonomy_caps_to_roles()
    {
        $roles = array('administrator', 'employer');
        $capabilities = array(
            'manage_lofi_terms',
            'edit_lofi_terms',
            'delete_lofi_terms',
            'assign_lofi_terms'
        );
        foreach ($roles as $role) {
            $getRole = get_role($role);
            switch ($role) {
                case 'administrator':
                    foreach ($capabilities as $cap) {
                        $getRole->add_cap($cap);
                    };
                    break;

                case 'employer':
                    $capabilities = 'assign_lofi_terms';
                    $getRole->add_cap($capabilities);
                    break;
            }
        }
    }

    /**
     * Register a custom taxonomy: Job Categories
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses register_taxonomy();
     */
    public function register_taxonomy_job_categories()
    {

        $labels = array(
            'name'                  => _x('Job Categories', 'taxonomy general name'),
            'singular_name'         => _x('Job Category', 'taxonomy singular name'),
            'search_items'          =>  __('Search Categories'),
            'all_items'             => __('Select Categories'),
            'parent_item'           => __('Parent Category'),
            'parent_item_colon'     => __('Parent Category:'),
            'edit_item'             => __('Edit Category'),
            'update_item'           => __('Update Category'),
            'add_new_item'          => __('Add New Job Category'),
            'new_item_name'         => __('New Job Category')
        );
        $args =      array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_rest'          => true,
            'show_admin_column'     => true,
            'show_in_quick_edit'    => false,
            'query_var'             => true,
            'rewrite'               => array(
                'slug' => 'job-categories'
            ),
            'capabilities'     => array(
                'manage_terms' => 'manage_lofi_terms',
                'edit_terms' => 'edit_lofi_terms',
                'delete_terms' => 'delete_lofi_terms',
                'assign_terms' => 'assign_lofi_terms'
            )
        );
        register_taxonomy('job_categories', array('lofi-job-post'), $args);
    }


    /**
     * Register a custom taxonomy: Employment Types
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses register_taxonomy();
     */
    public function register_taxonomy_employment_types()
    {

        $labels = array(
            'name'              => _x('Employment Types', 'taxonomy general name'),
            'singular_name'     => _x('Employment Type', 'taxonomy singular name'),
            'search_items'      =>  __('Search Types'),
            'all_items'         => __('Choose One'),
            'parent_item'       => __('Parent Type'),
            'parent_item_colon' => __('Parent Type:'),
            'edit_item'         => __('Edit Type'),
            'update_item'       => __('Update Type'),
            'add_new_item'      => __('Add New Employment Type'),
            'new_item_name'     => __('New Type Name')
        );
        $args =      array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_rest'          => true,
            'show_admin_column'     => true,
            'show_in_quick_edit'    => false,
            'query_var'             => true,
            'rewrite' =>            array(
                'slug' => 'type'
            ),
            'capabilities'     => array(
                'manage_terms' => 'manage_lofi_terms',
                'edit_terms' => 'edit_lofi_terms',
                'delete_terms' => 'delete_lofi_terms',
                'assign_terms' => 'assign_lofi_terms'
            )
        );
        register_taxonomy('employment_types', array('lofi-job-post'), $args); // lowercase and underscore only
    }

    /**
     * Insert some default taxonomy terms to : Employment Types
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses wp_insert_term();
     */
    public function insert_initial_employment_terms()
    {
        $taxonomy = 'employment_types';
        $terms = array(
            '0' => array(
                'name'          => 'Full Time',
                'slug'          => 'full-time'
            ),

            '1' => array(
                'name'          => 'Part Time',
                'slug'          => 'part-time'
            ),

            '2' => array(
                'name'          => 'Freelance',
                'slug'          => 'freelance'
            ),

            '3' => array(
                'name'          => 'Any',
                'slug'          => 'any'
            )
        );

        foreach ($terms as $term_key => $term) {
            wp_insert_term($term['name'], $taxonomy, array(
                'slug'          => $term['slug']
            ));
            unset($term);
        } //endforeach
    }


    /**
     * Register a custom taxonomy: Premium Packages
     * 
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses register_taxonomy();
     */
    public function register_taxonomy_premium_packages()
    {
        $labels = array(
            'name'                  => _x('Premium Packages', 'taxonomy general name'),
            'singular_name'         => _x('Premium Package', 'taxonomy singular name'),
            'search_items'          =>  __('Search Premium Packages'),
            'all_items'             => __('Choose One'),
            'parent_item'           => __('Parent'),
            'parent_item_colon'     => __('Parent:'),
            'edit_item'             => __('Edit Item'),
            'update_item'           => __('Update Item'),
            'add_new_item'          => __('Add New Premium Package'),
            'new_item_name'         => __('New Premium Package'),
            'menu_name'             => __('Premium Type')
        );
        $args =      array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_rest'          => true,
            'show_admin_column'     => true,
            'show_in_quick_edit'    => false,
            'query_var'             => true,
            // refer to inc/lofi-metaboxes.php
            'rewrite'               => array(
                'slug' => 'premium-package'
            ),
            'capabilities'     => array(
                'manage_terms' => 'manage_lofi_terms',
                'edit_terms' => 'edit_lofi_terms',
                'delete_terms' => 'delete_lofi_terms',
                'assign_terms' => 'assign_lofi_terms'
            )
        );
        register_taxonomy('premium_packages', array('lofi-job-post'), $args);

        //Avoid permalink issues
        flush_rewrite_rules();
    }

    /**
     * Insert some default taxonomy terms to : Premium Packages
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses wp_insert_term();
     */
    public function insert_initial_premium_packages_terms()
    {
        //use $this-> instead of public variable
        $taxonomy = 'premium_packages';
        $terms = array(
            '0' => array(
                'name'          => 'Featured',
                'slug'          => 'featured'
            ),

            '1' => array(
                'name'          => 'Urgent',
                'slug'          => 'urgent'
            ),

            '2' => array(
                'name'          => 'With Benefits',
                'slug'          => 'with-benefits'
            ),

            '3' => array(
                'name'          => 'Verified',
                'slug'          => 'verified'
            )
        );

        foreach ($terms as $term_key => $term) {
            wp_insert_term($term['name'], $taxonomy, array(
                'slug'          => $term['slug']
            ));
            unset($term);
        } //endforeach
    }

    /**
     * Update some default Premium Package term metas : BG Color and Text Color
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses update_term_meta();
     */
    public function update_premium_packages_terms_metas_color()
    {
        //Get the terms from 'premium_packages' taxonomy
        $terms = get_terms(array(
            'taxonomy'      => 'premium_packages',
            'hide_empty'    => false
        ));


        //Iterate through each, update each color meta as necessary
        foreach ($terms as $term) {
            $name = $term->name;
            $term_id = $term->term_id;
            switch ($name) {

                case 'Featured':
                    //violetish
                    $textColor = '#cc0099';
                    $bgColor = '#d279d2';

                    update_term_meta($term_id, 'premium_color_picker_text', $textColor);
                    update_term_meta($term_id, 'premium_color_picker_bg', $bgColor);
                    break;

                case 'Urgent':
                    //red
                    $textColor = '#ff0000';
                    $bgColor = '#ff9980';


                    update_term_meta($term_id, 'premium_color_picker_text', $textColor);
                    update_term_meta($term_id, 'premium_color_picker_bg', $bgColor);
                    break;

                case 'With Benefits':
                    //green
                    $textColor = '#61D95F';
                    $bgColor = '#D3F6CC';

                    update_term_meta($term_id, 'premium_color_picker_text', $textColor);
                    update_term_meta($term_id, 'premium_color_picker_bg', $bgColor);
                    break;

                case 'Verified':
                    //blue
                    $textColor = '#0099ff';
                    $bgColor = '#4ddbff';

                    update_term_meta($term_id, 'premium_color_picker_text', $textColor);
                    update_term_meta($term_id, 'premium_color_picker_bg', $bgColor);

                    break;
            } //end switch
        } //end foreach
    }

    /**
     *  Plugin class constructor
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
     *  Init function that handles all hooks and filters
     * 
     * @since 1.0.0
     * @access public
     */
    public function init()
    {
        add_action('init', array($this, 'add_taxonomy_caps_to_roles'), 11);
        //Register Taxonomy: Job Categories
        add_action('init', array($this, 'register_taxonomy_job_categories'));
        //Register Taxonomy: Employment Types, Add Initial Terms
        add_action('init', array($this, 'register_taxonomy_employment_types'));
        add_action('init', array($this, 'insert_initial_employment_terms'));
        //Register Taxonomy: Premium Packages, Add Initial Terms, Update Initial Terms' Color Metas 
        add_action('init', array($this, 'register_taxonomy_premium_packages'));
        add_action('init', array($this, 'insert_initial_premium_packages_terms'));
        add_action('init', array($this, 'update_premium_packages_terms_metas_color'));
    }
} //endclass
Taxonomies::instance();
