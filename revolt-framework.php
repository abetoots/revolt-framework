<?php

namespace Revolt_Framework;

use WP_Query;

/**
 * Plugin Name: Revolt Framework
 * Plugin URI:  https://example.com/plugins/the-basics/
 * Description: Plugin that adds necessary functionality for Revolt Theme to work properly.
 * Version:     1.0.0
 * Author:      Abe Suni M. Caymo
 * Author URI:  https://abecaymo.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: revolt-framework
 * Domain Path: /languages
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Define Constants
 */
define('REVOLT_FRAMEWORK_DIR', plugin_dir_path(__FILE__));
define('REVOLT_FRAMEWORK_URL', plugin_dir_url(__FILE__));

define('REVOLT_ACF_PATH', REVOLT_FRAMEWORK_DIR . '/inc/libraries/acf/');
define('REVOLT_ACF_URL', REVOLT_FRAMEWORK_URL . '/inc/libraries/acf/');

/**
 * Main Revolt Framework Class
 *
 * The init class that runs the Revolt Framework plugin.
 * Intended to make sure that the plugin's minimum requirements are met.
 *
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.0.0
 */


final class Revolt_Framework
{

    /**
     * Plugin Version
     *
     * @since 1.0.0
     * @var string The plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Minimum ACF Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_ACF_VERSION = '5.2.7';


    /**
     * Constructor
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        // Load translation
        add_action('init', array($this, 'i18n'));

        // Init Plugin
        add_action('plugins_loaded', array($this, 'init'));
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     * @access public
     */
    public function i18n()
    {
        load_plugin_textdomain('revolt-framework');
    }


    /**
     * Initialize the plugin
     *
     * Validates that Elementor is already loaded.
     * Checks for basic plugin requirements, if one check fails, don't continue, otherwise
     * if all checks have passed, include the plugin class.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     * @access public
     */
    public function init()
    {

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', array($this, 'admin_notice_minimum_elementor_version'));
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));
            return;
        }

        // Once we get here, We have passed all validation checks so we can safely include our plugin
        require_once('plugin.php');
    }



    /**
     * Admin notice - Missing Plugins
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'revolt-framework'),
            // %1$s
            '<strong>' . esc_html__('Revolt Framework', 'revolt-framework') . '</strong>',
            // %2$s
            '<strong>' . esc_html__('Elementor', 'revolt-framework') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice - Elementor Version
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'revolt-framework'),
            // %1$s
            '<strong>' . esc_html__('Revolt Framework', 'revolt-framework') . '</strong>',
            // %2$s
            '<strong>' . esc_html__('Elementor', 'revolt-framework') . '</strong>',
            // %3$s
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice - PHP Version
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'revolt-framework'),
            '<strong>' . esc_html__('Revolt Framework', 'revolt-framework') . '</strong>',
            '<strong>' . esc_html__('PHP', 'revolt-framework') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}
// Instantiate Revolt Framework
new Revolt_Framework();

register_activation_hook(__FILE__, __NAMESPACE__ . '\rewrite_flush_on_activation');
function rewrite_flush_on_activation()
{

    //Dev purposes
    if (get_role('employer')) {
        remove_role('employer');
    }
    add_role('employer', 'Employer', array(
        'read'      => true
    ));

    if (get_role('jobseeker')) {
        remove_role('jobseeker');
    }
    add_role('jobseeker', 'Job Seeker', array(
        'read'      => true,
    ));

    // Information needed for creating the plugin's pages
    $page_definitions = array(
        'dashboard'      => array(
            'title'     => __('Employer Dashboard ReactJS', 'revolt-framework'),
            'content'   => '',
            'template'  => ''
        ),
        'sign-in' => array(
            'title' => __('Sign In', 'revolt-framework'),
            'content'   => '[revolt-login-form]'
        ),
        'registration'  => array(
            'title' => __('Register', 'revolt-framework'),
            'content'   => '',
        ),
        'profile' => array(
            'title' => __('JobSeeker Profile Page', 'revolt-framework'),
            'content'       => '',
            'template'      => 'revolt-profile.php',
            'child'         => array(
                'slug'      => 'edit',
                'title' => __('Edit Profile', 'revolt-framework'),
                'content'   => ''
            )
        ),
    );

    insert_pages($page_definitions);
    flush_rewrite_rules();
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

function insert_pages($page_definitions)
{

    if (!is_array($page_definitions)) {
        return;
    }

    foreach ($page_definitions as $slug => $page) {
        // Check that the page doesn't exist already
        $query = new WP_Query('pagename=' . $slug);
        //Assign a page template, defaults to empty if no page_template is set above
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
                        'inserted'   => 'revolt' //refer to inc/revolt-meta-posts.php : used for adding post states
                    )
                )
            );

            // For some reason, post_template is not working. We update it manually.
            // update_post_meta($id, '_wp_page_template', $template);
            //Handle inserting of child page if specified
            if (array_key_exists('child', $page)) {
                $childId = wp_insert_post(
                    array(
                        'post_content'   => $page['child']['content'],
                        'post_name'      => $page['child']['slug'],
                        'post_title'     => wp_strip_all_tags($page['child']['title']),
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'post_parent'    => $id,
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                        'meta_input'     => array(
                            'inserted'   => 'revolt' //refer to inc/revolt-meta-posts.php : used for adding post states
                        )
                    )
                );
                // if ($page['child']['template'] !== '') {
                // update_post_meta($childId, '_wp_page_template', $child_template);
                // }
            }
        }
    }
}

function revolt_framework_deactivation()
{
    $taxonomies = get_object_taxonomies('revolt-job-post');
    foreach ($taxonomies as $taxonomy) {
        unregister_taxonomy($taxonomy);
    }
    // unregister the post type, so the rules are no longer in memory
    unregister_post_type('revolt-job-post');
    // clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();

    //Remove custom roles
    if (get_role('employer')) {
        remove_role('employer');
    }

    if (get_role('jobseeker')) {
        remove_role('jobseeker');
    }

    $admin = get_role('administrator');

    $customCaps = array(
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

    //remove custom caps from admin
    foreach ($customCaps as $cap) {
        $admin->remove_cap($cap);
    }
}
register_deactivation_hook(__FILE__,  __NAMESPACE__ . '\revolt_framework_deactivation');
