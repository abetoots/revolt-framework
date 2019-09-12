<?php

namespace Revolt_Framework\Inc\Core;

use WP_Error;
use function Revolt_Framework\Inc\Helpers\Registration\{
    get_template_html,
    get_error_message
};

if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Login
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

    /**
     * A shortcode for rendering the login form.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_login_form($user_attributes, $content = null)
    {
        // normalize attribute keys, lowercase
        $user_attributes = array_change_key_case((array) $user_attributes, CASE_LOWER);
        // Parse shortcode attributes
        $default_attributes = array(
            'disabled'      => false,
            'title'         => 'Sign in to your account',
            'button_text'   => 'Sign In'
        );
        $attributes = shortcode_atts($default_attributes, $user_attributes);

        if (is_user_logged_in()) {
            return __('You are already signed in.', 'revolt-framework');
        }

        // Pass the redirect parameter to the WordPress login functionality (see wp_login_form()): by default,
        // don't specify a redirect, but if a valid redirect URL has been passed as
        // request parameter, use it.
        $attributes['redirect'] = '';
        if (isset($_REQUEST['redirect_to'])) {
            $attributes['redirect'] = wp_validate_redirect($_REQUEST['redirect_to'], $attributes['redirect']);
        }

        if (isset($_REQUEST['registered'])) {
            $attributes['new_user'] = true;
        }

        // Error messages
        $attributes['errors'] = array();
        if (isset($_REQUEST['login-err'])) {
            $error_codes = explode(',', $_REQUEST['login-err']);

            foreach ($error_codes as $code) {
                $attributes['errors'][] = get_error_message($code);
            }
        }

        // Check if user just logged out
        $attributes['logged_out'] = isset($_REQUEST['logged_out']) && $_REQUEST['logged_out'] == true;

        // Render the login form using an external template
        return get_template_html('login_form', $attributes);
    }

    /**
     * Returns the URL to which the user should be redirected after the (successful) login.
     *
     * @param string           $redirect_to           The redirect destination URL.
     * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
     * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
     *
     * @return string Redirect URL
     */
    public function redirect_successful_login($redirect_to, $requested_redirect_to, $user)
    {
        $redirect_url = home_url();

        if (!isset($user->ID)) {
            return $redirect_url;
        }
        $role_name = $user->roles[0];
        if (user_can($user, 'manage_options')) {
            // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
            if ($redirect_to) {
                $redirect_url = $redirect_to;
            } else {
                $redirect_url = admin_url();
            }
        } else if ($role_name === 'editor') {
            $redirect_url = admin_url();
        } else if ($role_name === 'employer') { // if current user is employer
            $redirect_url = home_url();
        } else if ($role_name === 'jobseeker') { // if current user is jobseeker
            $redirect_url = home_url();
        }
        return wp_validate_redirect($redirect_url, home_url());
    }

    /**
     * Redirect the user to the custom login page instead of wp-login.php. or wp-admin.php
     */
    function redirect_already_logged_in()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (is_user_logged_in()) {
                $this->redirect_logged_in_user();
                exit;
            }
        }
    }

    /**
     * Redirects the user to the correct page depending on whether he / she
     * is an employer/jobseeker.
     *
     * @param string $redirect_to   An optional redirect_to URL for admin users
     */
    private function redirect_logged_in_user($redirect_to = null)
    {
        $user = wp_get_current_user();
        $role_name = $user->roles[0];
        // if current user is employer
        if ($role_name === 'employer') {
            //if a request variable redirect_to is passed in, 
            //if one is set, the user is directed to this URL instead.
            if ($redirect_to !== '') {
                wp_safe_redirect($redirect_to);
            } else {
                wp_redirect(home_url('employer'));
            }
        } else if ($role_name === 'jobseeker') { // if current user is jobseeker
            wp_redirect(home_url('job-seeker'));
        } else {
            wp_redirect(admin_url());
        }
    }

    /**
     * Redirect to custom login page ONLY if current user is employer/jobseeker
     *
     */
    public function redirect_after_logout()
    {
        $user = wp_get_current_user();
        $role_name = $user->roles[0];
        if ($role_name === 'employer' || $role_name === 'jobseeker') {
            $redirect_url = home_url('sign-in?logged_out=true');
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    /**
     * After authentication, if there were any errors, redirect the user to custom page we created
     * instead of the default wp-login.php. This is needed or else errors redirect to wp-login.php
     *
     * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
     * @param string            $username   The user name used to log in.
     * @param string            $password   The password used to log in.
     *
     * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
     */
    function maybe_redirect_at_authenticate($user, $username, $password)
    {
        // Check if the earlier authenticate filter (most likely, 
        // the default WordPress authentication) functions have found errors
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role_name = $user->roles[0];
            if (is_wp_error($user) && $role_name === 'employer' || is_wp_error($user) && $role_name === 'jobseeker') {
                $error_codes = join(',', $user->get_error_codes());

                $login_url = home_url('sign-in');
                $login_url = add_query_arg('login-err', $error_codes, $login_url);

                wp_redirect($login_url); //Redirects to our custom login page even if errors are triggered
                exit;
            }
        }

        return $user;
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

        // Shortcodes for rendering content for each page
        // Login
        add_shortcode('revolt-login-form', array($this, 'render_login_form'));

        /**
         * Before the actual login functionality begins, two actions are fired: login_init and login_form_{action} 
         * where {action} is the name of an action being executed (for example login, postpass, or logout).
         * 
         * We hook into this action to redirect users trying to access wp-login.php 
         * when they are already logged in.
         * 
         */
        add_action('login_form_login', array($this, 'redirect_already_logged_in'));

        /**
         * If login successful, do our own login redirects.
         */
        add_filter('login_redirect', array($this, 'redirect_successful_login'), 10, 3);

        /**
         * Redirect When There Are Errors:
         * If no errors are found, let everything proceed normally so WordPress can finish the login. 
         * If there are errors, Wordpress usually redirects to wp-login.php 
         * Instead of letting WordPress do its regular error handling, redirect to our custom login page.
         * 
         * In the current WordPress version (4.2 at the time of writing), 
         * WordPress has the following two filters hooked to authenticate:
         * add_filter( 'authenticate', 'wp_authenticate_username_password',  20, 3 );
         * add_filter( 'authenticate', 'wp_authenticate_spam_check',         99    )
         * 
         */
        add_filter('authenticate', array($this, 'maybe_redirect_at_authenticate'), 101, 3);

        /**
         * Do our own logout redirects before WordPress redirects the user back to wp-login.php.
         */
        add_action('wp_logout', array($this, 'redirect_after_logout'));
    }
}

Login::instance();
