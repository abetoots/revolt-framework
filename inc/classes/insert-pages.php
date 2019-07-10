<?php


if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Insert
{

    /**
     *
     * Define all WordPress pages needed by the plugin.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @uses $this->insert_pages();
     */
    public function insert_initial_pages()
    {
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            'employer'      => array(
                'title'     => __('Employer Dashboard', 'lofi-framework'),
                'content'   => '[lofi-employer-dashboard]',
                'template'  => 'lofi-employer-template.php',
            ),
            'edit-profile'      => array(
                'title'     => __('Edit Profile', 'lofi-framework'),
                'content'   => '[lofi-employer-edit-profile]',
                'template'  => 'lofi-employer-template.php',
            ),
            'employer-profile'      => array(
                'title'     => __('Employer Profile', 'lofi-framework'),
                'content'   => '[lofi-employer-view-profile]',
                'template'  => 'lofi-employer-template.php',
            ),

            'manage-jobs'      => array(
                'title'     => __('Employer Profile', 'lofi-framework'),
                'content'   => '[lofi-employer-manage-jobs]',
                'template'  => 'lofi-employer-template.php',
            ),

            'job-seeker' => array(
                'title' => __('Jobseeker Account', 'lofi-framework'),
                'content' => '[lofi-jobseeker-account-info]',
                'template'  => 'lofi-jobseeker-template.php',
            ),
            'sign-in' => array(
                'title' => __('Sign In', 'lofi-framework'),
                'content'   => '[lofi-login-form]',
                'template'      => 'lofi-login-template.php',
            ),
            'register-employer' => array(
                'title' => __('Employer Registration', 'lofi-framework'),
                'content'       => '[lofi-employer-registration-form]',
                'template'      => 'lofi-register-template.php',
            ),
            'register-jobseeker' => array(
                'title' => __('Jobseeker Registration', 'lofi-framework'),
                'content'       => '[lofi-jobseeker-registration-form]',
                'template'      => 'lofi-register-template.php',
            ),
        );

        $this->insert_pages($page_definitions);
    }

    /**
     *
     * Creates the pages defined passed in as an array
     * 
     * @param array $page_definitions The array to loop over
     * 
     * @since 1.0.0
     * @access private
     * 
     * @uses wp_insert_post();
     * @uses update_post_meta();
     */

    private function insert_pages($page_definitions)
    {

        if (!is_array($page_definitions)) {
            return;
        }

        foreach ($page_definitions as $slug => $page) {
            // Check that the page doesn't exist already
            $query = new WP_Query('pagename=' . $slug);
            //Assign a page template, defaults to empty if no page_template is set above

            $lofi_template = ($page['template']) ? $page['template'] : '';
            if (!$query->have_posts()) {
                // Add the page using the data from the array above
                $id = wp_insert_post(
                    array(
                        'post_content'   => $page['content'],
                        'post_name'      => $slug,
                        'post_title'     => wp_strip_all_tags($page['title']),
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                        'meta_input'     => array(
                            'inserted'   => 'lofi' //refer to inc/lofi-meta-posts.php : used for adding post states
                        )
                    )
                );

                // For some reason, post_template is not working. We update it manually.
                update_post_meta($id, '_wp_page_template', $lofi_template);
            }
        }
    }

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
        // Add initial custom pages for users (sign-in, register-employer, employer and jobseeker)
        add_action('init', array($this, 'insert_initial_pages'));
    }
}
Insert::instance();
