<?php

namespace LofiFramework\Core;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/helpers/columns/meta-jobpost.php');
require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/helpers/columns/columns-cpt.php');
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
        $image = LOFI_FRAMEWORK_URL . 'assets/images/suitcase.png';
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
            'supports'              => array('title', 'editor', 'author', 'thumbnail'),
            'menu_icon'             => $image,
            'register_meta_box_cb'  => array('\LofiFramework\Helpers\MetaJobPost', 'register_metabox_cb'), // refer to inc/lofi-metaboxes.php
            'has_archive'           => true,
            'sort'                  => false,
            'rewrite'               => array(
                'slug'              => 'jobs',
                'feeds'             => 'jobseekers'
            ),
            'capability_type'       => array('lofi_job_post', 'lofi_job_posts'),
            'map_meta_cap'          => true,

        );
        register_post_type('lofi-job-post', $args); //does not accept underscore
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
        $this->add_job_employer_role();

        $this->add_job_jobseeker_role();

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
        remove_role('employer'); // For development purposes
        $customCaps = array(
            'read' => true,
            //CPT Job Post Capabilities
            'edit_others_lofi_job_posts'            => false,
            'delete_others_lofi_job_posts'          => false,
            'delete_private_lofi_job_posts'         => false,
            'edit_private_lofi_job_posts'           => false,
            'read_private_lofi_job_posts'           => true,
            'edit_published_lofi_job_posts'         => false,
            'publish_lofi_job_posts'                => true,
            'delete_published_lofi_job_posts'       => false,
            'edit_lofi_job_posts'                   => true,
            'delete_lofi_job_posts'                 => true,
            'edit_lofi_job_post'                    => true,
            'read_lofi_job_post'                    => true,
            'delete_lofi_job_post'                  => true,

        );
        add_role('employer', 'Employer', $customCaps);
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
        remove_role('jobseeker'); // For development purposes
        $customCaps = array(
            'read' => true,
            //CPT Job Post Capabilities
            'edit_others_lofi_job_posts'            => false,
            'delete_others_lofi_job_posts'          => false,
            'delete_private_lofi_job_posts'         => false,
            'edit_private_lofi_job_posts'           => false,
            'read_private_lofi_job_posts'           => true,
            'edit_published_lofi_job_posts'         => false,
            'publish_lofi_job_posts'                => false,
            'delete_published_lofi_job_posts'       => false,
            'edit_lofi_job_posts'                   => false,
            'delete_lofi_job_posts'                 => false,
            'edit_lofi_job_post'                    => false,
            'read_lofi_job_post'                    => true,
            'delete_lofi_job_post'                  => false,

        );
        add_role('jobseeker', 'Job Seeker', $customCaps);
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
        $role = get_role('administrator');

        $customCaps = array(
            'read' => true,
            //CPT Job Post Capabilities
            'edit_others_lofi_job_posts'            => true,
            'delete_others_lofi_job_posts'          => true,
            'delete_private_lofi_job_posts'         => true,
            'edit_private_lofi_job_posts'           => true,
            'read_private_lofi_job_posts'           => true,
            'edit_published_lofi_job_posts'         => true,
            'publish_lofi_job_posts'                => true,
            'delete_published_lofi_job_posts'       => true,
            'edit_lofi_job_posts'                   => true,
            'delete_lofi_job_posts'                 => true,
            'edit_lofi_job_post'                    => true,
            'read_lofi_job_post'                    => true,
            'delete_lofi_job_post'                  => true,
        );

        foreach ($customCaps as $cap => $bool) {
            $role->add_cap($cap); //defaults
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
        //Add Role and Caps to 'Employer' and 'Administrator'
        $this->register_role_and_caps();
    }
} //endclass
CustomPostType::get_instance();
