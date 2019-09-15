<?php

namespace Revolt_Framework\Inc\Core;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
class CustomPostType
{

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
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
     * Register Job Custom Post Type
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses register_post_type();
     * @uses flush_rewrite_rules();
     */
    public function register_custom_post_type()
    {
        $image = REVOLT_FRAMEWORK_URL . 'assets/images/suitcase.png';
        $labels = array(
            'name'             => __('Job Posts'),
            'singular_name'    => __('Job Post'),
            'add_new'          => __('Add New Job Post'),
            'add_new_item'     => __('Post A Job'),
            'edit_item'        => __('Edit Job Post'),
        );

        $args = array(
            'label'                 => __('jobs'),
            'labels'                => $labels,
            'public'                => true,
            'show_in_rest'          => true,
            'show_in_nav_menus'     => false,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'supports'              => array('title', 'editor', 'author', 'custom-fields'),
            'menu_icon'             => $image, // refer to inc/revolt-metaboxes.php
            'has_archive'           => true,
            'sort'                  => false,
            'rewrite'               => array(
                'slug'              => 'jobs',
                'feeds'             => 'jobseekers'
            ),
            'capability_type'       => array('revolt_job_post', 'revolt_job_posts'),
            'map_meta_cap'          => false,

        );
        register_post_type('revolt-job-post', $args); //does not accept underscore

        register_post_meta('revolt-job-post', 'applicants', array(
            'type'              => 'integer',
            'show_in_rest'      => false,
            'single'            => false
        ));
    }

    /**
     * Prevents the use of Gutenberg as the default editor experience
     */
    public function do_not_use_gutenberg_editor($use_block_editor, $post_type)
    {
        if ('revolt-job-post' === $post_type) {
            return false;
        }

        return $use_block_editor;
    }



    /**
     * Function called on init that calls other functions to:
     * Register employer role, then map custom capabilities to the role and the administrator
     * 
     * @since 1.0.0
     * @access private
     */
    private function register_role_and_caps()
    {
        // $this->add_job_employer_role();

        // $this->add_job_jobseeker_role();

        $this->add_caps_to_other_roles();
    }

    /**
     * Add Role: Employer then map Custom Capabilities
     * 
     * @since 1.0.0
     * @access private
     * 
     * @uses add_role();
     */
    private function add_job_employer_role()
    {
        //Dev purposes
        if (get_role('employer')) {
            remove_role('employer');
        }
        add_role('employer', 'Employer', array(
            'read'      => true
        ));
    }

    /**
     * Add Role: Jobseeker then map Custom Capabilities
     * 
     * @since 1.0.0
     * @access private
     * 
     * @uses add_role();
     */
    private function add_job_jobseeker_role()
    {

        if (get_role('jobseeker')) {
            remove_role('jobseeker');
        }
        add_role('jobseeker', 'Job Seeker', array(
            'read'      => true,
        ));
    }

    /**
     * Add same custom capabilities to the administrator
     * 
     * @since 1.0.0
     * @access private
     * 
     * @uses get_role();
     * @uses add_cap();
     */
    private function add_caps_to_other_roles()
    {
        //Add our caps to admin only, let the user decide if they want to add the caps to other roles
        $admin = get_role('administrator');
        $admincaps = array(
            //CPT Job Post Capabilities
            'read_revolt_job_post',
            'read_private_revolt_job_posts',
            'edit_revolt_job_post',
            'edit_revolt_job_posts',
            'edit_others_revolt_job_posts',
            'edit_private_revolt_job_posts',
            'edit_published_revolt_job_posts',
            'delete_revolt_job_post',
            'delete_revolt_job_posts',
            'delete_others_revolt_job_posts',
            'delete_private_revolt_job_posts',
            'delete_published_revolt_job_posts',
            'publish_revolt_job_posts',
        );
        foreach ($admincaps as $cap) {
            $admin->add_cap($cap); //defaults
        }

        $employer = get_role('employer');
        $empcaps = array(
            //CPT Job Post Capabilities
            'read_revolt_job_post',
            'edit_revolt_job_post',
            'edit_revolt_job_posts',
            'edit_published_revolt_job_posts',
            'publish_revolt_job_posts',
            'upload_files',
            'list_users'
        );
        foreach ($empcaps as $cap) {
            $employer->add_cap($cap); //defaults
        }

        $jobseeker = get_role('jobseeker');
        $seekercaps = array(
            //CPT Job Post Capabilities
            'read_revolt_job_post',
            'edit_revolt_job_post',
            'edit_revolt_job_posts',
            'upload_files'
        );
        foreach ($seekercaps as $cap) {
            $jobseeker->add_cap($cap); //defaults
        }
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

        //Register Custom Post Type
        add_action('init', array($this, 'register_custom_post_type'));
        //Do not use Gutenberg Editor
        add_filter('use_block_editor_for_post_type', array($this, 'do_not_use_gutenberg_editor'), 10, 2);
        //Add Role and Caps to 'Employer' and 'Administrator'
        $this->register_role_and_caps();
    }
} //endclass
CustomPostType::get_instance();
