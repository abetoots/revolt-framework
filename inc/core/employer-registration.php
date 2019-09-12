<?php

namespace Revolt_Framework\Inc\Core;

use WP_Error;
use function Revolt_Framework\Inc\Helpers\Registration\{
    get_template_html,
    get_error_message,
    verify_recaptcha,
    validate_and_register_new_user
};

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Employer_Registration
{
    /**
     * A shortcode for rendering the new user registration form.
     *
     * @since 1.0.0
     * @access public
     * 
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_registration_form($user_attributes, $content = null)
    {
        // normalize attribute keys, lowercase
        $user_attributes = array_change_key_case((array) $user_attributes, CASE_LOWER);
        // Parse shortcode attributes
        $default_attributes = array(
            'title'         => 'Register as an Employer',
            'button_text'   => 'Register',
            'disabled'      => false,
        );
        $attributes = shortcode_atts($default_attributes, $user_attributes);

        // Retrieve recaptcha key
        $attributes['recaptcha_site_key'] = get_option('revolt_recaptcha_site_key', null);

        if (!get_option('users_can_register')) {
            return __('Registering new users is currently not allowed.', 'revolt-framework');
        } elseif (!get_option('allow_jobboard_registration')) {
            return __('Job Board Registration is currently not allowed.', 'revolt-framework');
        } elseif (is_user_logged_in()) {
            return 'You are logged in';
        } else {

            // Retrieve possible errors from request parameters
            $attributes['errors'] = array();
            if (isset($_REQUEST['registration-err'])) {
                $error_codes = explode(',', $_REQUEST['registration-err']);

                foreach ($error_codes as $error_code) {
                    $attributes['errors'][] = get_error_message($error_code);
                }
            }

            // Rendering of html is done here
            return get_template_html('registration_form_employer', $attributes);
        }
    }

    /**
     * Handles form when submitted to admin-post.php
     *
     * @since 1.0.0
     * @access public
     * 
     *
     * @uses $this->do_register_employer();
     */
    public function handle_form_response()
    {
        if (!isset($_POST['register_employer_nonce'])) {
            wp_die('first');
        }

        if (!wp_verify_nonce($_POST['register_employer_nonce'], 'register_employer_form_nonce')) {
            wp_die('second');
        }

        if (is_user_logged_in()) { //prevent submitting of registration form when logged in
            return;
        }
        //sanitization will be handled in function below
        $username =  $_POST['username'];
        $email =  $_POST['email'];
        $password = $_POST['password'];

        //Handles validation and redirect
        $this->do_register_employer($username, $email, $password);
    }

    /**
     * Handles the registration of a new user.
     *
     * @since 1.0.0
     * @access public
     * 
     * 
     * @uses $this->validate_and_register_employer()
     */
    public function do_register_employer($username, $email, $password)
    {
        $redirect_url = home_url('registration');
        $errors = new WP_Error();

        if (!get_option('users_can_register')) {
            // Registration closed, display error
            $redirect_url = add_query_arg('registration-err', 'closed', $redirect_url);
        } elseif (!get_option('allow_jobboard_registration')) {
            // Job board registration disabled, display error
            $redirect_url = add_query_arg('registration-err', 'disabled', $redirect_url);
        } elseif (get_option('revolt_recaptcha_site_key') && get_option('revolt_recaptcha_secret_key')) {
            if (!verify_recaptcha()) {
                //Recaptcha check failed, display error
                $redirect_url = add_query_arg('registration-err', 'captcha', $redirect_url);
            }
        } else {

            //either an error or the user id
            $result = validate_and_register_new_user($username, $email, $password, 'employer');

            if (is_wp_error($result)) {
                // Parse errors into a string and append as parameter to redirect
                $errors = join(',', $result->get_error_codes());
                $redirect_url = add_query_arg('registration-err', $errors, $redirect_url);
            } else {
                // Success, redirect to login page.
                $redirect_url = home_url('sign-in');
                $redirect_url = add_query_arg('registered', $email, $redirect_url);
            }
        }
        wp_redirect($redirect_url);
        exit;
    }

    /**
     * An action function used to include the reCAPTCHA JavaScript file
     * at the end of the page.
     */
    public function add_captcha_js_to_footer()
    {
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
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
        //Registration
        add_shortcode('revolt-reg-form-employer', array($this, 'render_registration_form'));

        // Add captcha javascript to footer
        add_action('wp_print_footer_scripts', array($this, 'add_captcha_js_to_footer'));

        // Handle form response
        add_action('admin_post_nopriv_revolt_register_employer', array($this, 'handle_form_response'));
    }
}
Employer_Registration::instance();
