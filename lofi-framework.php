<?php

/**
 * Plugin Name: Lofi Framework
 * Plugin URI:  https://example.com/plugins/the-basics/
 * Description: Plugin that adds necessary functionality for Lofi Theme to work properly.
 * Version:     1.0.0
 * Author:      Abe Suni M. Caymo
 * Author URI:  https://abecaymo.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lofi-framework
 * Domain Path: /languages
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Define Constants
 */
define('LOFI_FRAMEWORK_DIR', plugin_dir_path(__FILE__));
define('LOFI_FRAMEWORK_URL', plugin_dir_url(__FILE__));

define('MY_ACF_PATH', LOFI_FRAMEWORK_DIR . '/inc/libraries/acf/');
define('MY_ACF_URL', LOFI_FRAMEWORK_URL . '/inc/libraries/acf/');

/**
 * Main Lofi Framework Class
 *
 * The init class that runs the Lofi Framework plugin.
 * Intended to make sure that the plugin's minimum requirements are met.
 *
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.0.0
 */


final class LofiFramework
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
        load_plugin_textdomain('lofi-framework');
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
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'lofi-framework'),
            // %1$s
            '<strong>' . esc_html__('Lofi Framework', 'lofi-framework') . '</strong>',
            // %2$s
            '<strong>' . esc_html__('Elementor', 'lofi-framework') . '</strong>'
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
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'lofi-framework'),
            // %1$s
            '<strong>' . esc_html__('Lofi Framework', 'lofi-framework') . '</strong>',
            // %2$s
            '<strong>' . esc_html__('Elementor', 'lofi-framework') . '</strong>',
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
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'lofi-framework'),
            '<strong>' . esc_html__('Lofi Framework', 'lofi-framework') . '</strong>',
            '<strong>' . esc_html__('PHP', 'lofi-framework') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}
// Instantiate Lofi Framework
new LofiFramework();

function lofi_framework_deactivation()
{
    $taxonomies = get_object_taxonomies('lofi-job-post');
    foreach ($taxonomies as $taxonomy) {
        unregister_taxonomy($taxonomy);
    }
    // unregister the post type, so the rules are no longer in memory
    unregister_post_type('lofi-job-post');
    // clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();

    $role = get_role('administrator');

    $customCaps = array(
        //CPT Job Post Capabilities
        'edit_others_lofi_job_posts'          => true,
        'delete_others_lofi_job_posts'        => true,
        'delete_private_lofi_job_posts'       => true,
        'edit_private_lofi_job_posts'         => true,
        'read_private_lofi_job_posts'         => true,
        'edit_published_lofi_job_posts'       => true,
        'publish_lofi_job_posts'          => true,
        'delete_published_lofi_job_posts'     => true,
        'edit_lofi_job_posts'             => true,
        'delete_lofi_job_posts'           => true,
        'edit_lofi_job_post'              => true,
        'read_lofi_job_post'              => true,
        'delete_lofi_job_post'            => true,
    );

    foreach ($customCaps as $cap => $bool) {
        $role->remove_cap($cap); //defaults
    }
    $role = get_role('employer');
    foreach ($customCaps as $cap => $bool) {
        $role->remove_cap($cap); //defaults
    }
    $role = get_role('jobseeker');
    foreach ($customCaps as $cap => $bool) {
        $role->remove_cap($cap); //defaults
    }
}
register_deactivation_hook(__FILE__, 'lofi_framework_deactivation');
