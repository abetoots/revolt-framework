<?php

namespace LofiFramework;

use LofiFramework\Core\CustomPostType;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.0.0
 */
class Plugin
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
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * widget_scripts
     *
     * Load required plugin core files.
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_scripts()
    {
        wp_register_script('lofi-elementor-js', LOFI_FRAMEWORK_URL . 'inc/admin/js/lofi-elementor.js', array('jquery'), false, true);
    }


    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.0.0
     * @access private
     */
    private function include_widgets_files()
    {
        require_once(LOFI_FRAMEWORK_DIR . 'elementor-widgets/registration-form.php');

        require_once(LOFI_FRAMEWORK_DIR . 'elementor-widgets/employer-account.php');

        //require_once( __DIR__ . '/widgets/login-form.php' );

        //require_once( __DIR__ . '/widgets/search-form.php' );
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.0.0
     * @access public
     */
    public function register_widgets()
    {
        // It's is now safe to include Widgets files
        $this->include_widgets_files();

        // Register Widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_Widgets\Registration_Form());

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_Widgets\Employer_Account());
    }

    /**
     * Include Class Files
     *
     * Load widgets files
     *
     * @since 1.0.0
     * @access private
     */
    private function include_class_files()
    {
        // Include our classes
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/custom-post-type.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/taxonomies.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/insert-pages.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/employer-registration.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/jobseeker-registration.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/login.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/employer-account.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/settings-jobboard.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/settings-profile.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/helpers/utilities.php');
        require_once(LOFI_FRAMEWORK_DIR . 'inc/classes/helpers/utilities/api.php');
    }

    /**
     * Include Class Files
     *
     * Load widgets files
     *
     * @since 1.0.0
     * @access private
     */
    private function include_libraries()
    {
        if (!class_exists('ACF') || !function_exists('get_field')) {
            include_once(MY_ACF_PATH . 'acf.php');
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
        $this->include_class_files();
        $this->include_libraries();
        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

        // Register widgets
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
    }
}

// Instantiate Plugin Class
Plugin::instance();
