<?php

namespace Revolt_Framework;

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
     * Include Class Files
     *
     * @since 1.0.0
     * @access private
     */
    private function include_class_files()
    {
        // Include our classes
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/core/custom-post-type.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/core/employer-registration.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/core/jobseeker-registration.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/core/login.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/core/taxonomies.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/acf.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/api.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/columns.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/registration.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/rewrite.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/utilities.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/react.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'inc/helpers/query.php');
        require_once(REVOLT_FRAMEWORK_DIR . 'admin/settings-jobboard.php');
    }

    /**
     * Include libraries
     *
     * @since 1.0.0
     * @access private
     */
    private function include_libraries()
    {
        if (!class_exists('ACF') || !function_exists('get_field')) {
            include_once(REVOLT_ACF_PATH . 'acf.php');
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
    }
}

// Instantiate Plugin Class

Plugin::instance();
