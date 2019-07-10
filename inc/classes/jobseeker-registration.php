<?php
    

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Jobseeker_Registration{
    
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
    public static function instance() {
        if ( ! self::$_instance )
            self::$_instance = new self();
        return self::$_instance;
    }


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
    public function render_registration_form( $user_attributes, $content = null ) {

        // normalize attribute keys, lowercase
        $user_attributes = array_change_key_case((array)$user_attributes, CASE_LOWER);
        // Parse shortcode attributes
        $default_attributes = array( 
            'show_title'    => false,
            'title'         => __( 'Register', 'lofi-framework' ),
            'disabled'      => false,
            );
        $attributes = shortcode_atts( $default_attributes, $user_attributes );
        
        // Retrieve recaptcha key
        $attributes['recaptcha_site_key'] = get_option( 'lofi_recaptcha_site_key', null );

       
    
        if ( is_user_logged_in() ) {
            $in_elementor_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
            $in_elementor_preview = \Elementor\Plugin::$instance->preview->is_preview_mode();
            // If we're in edit/preview mode for elementor or you are an admin, safe to render the shortcode
            if ( $in_elementor_editor || $in_elementor_preview ){
                // Since we're in edit mode, no need to retrieve error codes
                // For security, let's disable the register button to prevent form submission
                $attributes['disabled'] = true;
                // Rendering of html is done here
                return $this->get_template_html( 'registration_form_jobseeker', $attributes );
            } elseif ( ! get_option( 'users_can_register' ) ) {
            return __( 'Registering new users is currently not allowed.', 'lofi-framework' );
            } else{
            
            // Retrieve possible errors from request parameters
            $attributes['errors'] = array();
            if ( isset( $_REQUEST['register-errors'] ) ) {
                $error_codes = explode( ',', $_REQUEST['register-errors'] );
        
                foreach ( $error_codes as $error_code ) {
                    $attributes['errors'] []= $this->get_error_message( $error_code );
                }
            }

            // Rendering of html is done here
            return $this->get_template_html( 'registration_form_jobseeker', $attributes );
            }
        }
    }

    /**
     * Handles form when submitted to admin-post.php
     *
     * @since 1.0.0
     * @access public
     * 
     *
     * @uses $this->do_register_jobseeker();
     */
    public function handle_form_response(){
        

        if ( ! isset( $_POST['lofi-registration-nonce'] )  ){
            return;
        }

        if( ! wp_verify_nonce( $_POST['lofi-registration-nonce']  , 'lofi_employer_registration_form_action'  ) ) {
            return;
        }

        if( is_user_logged_in() ){ //prevent submitting of registration form when logged in
            return;
        }


        $username =  $_POST['username'];
        $email =  $_POST['email'] ;
        $password = $_POST['password'];

        //Handles validation and redirect
        $this->do_register_jobseeker( $username, $email, $password );
    }

    /**
     * Handles the registration of a new user.
     *
     * @since 1.0.0
     * @access public
     * 
     * 
     * @uses $this->validate_and_register_jobseeker()
     */
    public function do_register_jobseeker($username, $email, $password) {
            $redirect_url = home_url( 'register-jobseeker' );
    
            if ( ! get_option( 'users_can_register' ) ) {
                $errors = new WP_Error();
                // Registration closed, display error
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } elseif ( ! $this->verify_recaptcha() ) {
                //Recaptcha check failed, display error
                $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
            } else {
    
                $result = $this->validate_and_register_jobseeker( $username, $email, $password );

                if(is_wp_error( $result )){
                    // Parse errors into a string and append as parameter to redirect
                    $errors = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                } else{
                    // Success, redirect to login page.
                    $redirect_url = home_url( 'login' );
                    $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
                }
            }
            wp_redirect( $redirect_url );
            exit;
    }

    /**
     * Validates and then completes the new employer signup process if all went well.
     *
     * @param string $username          The new employer's email address
     * @param string $email             The new employer's username
     * @param string $password          The new employer's password
     *
     * @return int|WP_Error         The id of the user that was created, or error if failed.
     */
    private function validate_and_register_jobseeker( $username, $email, $password ) {
        $errors = new WP_Error();
    
        //Username, password and email are required and must not be left out
        if ( empty( $username ) || empty( $email ) ||empty( $password ) ) {
            $errors->add('empty_field', $this->get_error_message( 'empty_field' ));
            return $errors;
        }

        //Make sure the number of username characters is not less than 4
        if ( 4 > strlen( $username ) ) {
            $errors->add( 'username_length', $this->get_error_message( 'username_length' ) );
            return $errors;
        }

        //Check if the username is already registered
        if ( username_exists( $username ) ){
            $errors->add('username_exists', $this->get_error_message( 'username_exists' ));
            return $errors;
        }

        //Make sure the username is valid
        if ( ! validate_username( $username ) ) {
            $errors->add( 'invalid_username_register', $this->get_error_message( 'invalid_username_register' ) );
            return $errors;
        }

        //Ensure the password entered by users is not less than 5 characters
        if ( 5 > strlen( $password ) ) {
            $errors->add( 'password_length', $this->get_error_message( 'password_length' ) );
            return $errors;
        }

        //Check if valid email
        if ( ! is_email( $email ) ) {
            $errors->add( 'email', $this->get_error_message( 'email' ) );
            return $errors;
        }
    
        //Check if email is already registered
        if (  email_exists( $email ) ) {
            $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
            return $errors;
        }


        //Sanitize before inserting user data
        $email = sanitize_email( $email );
        $username = sanitize_text_field( $username );
        
        $user_data = array(
            'user_login'    => $email,
            'user_email'    => $email,
            'user_pass'     => $password,
            'nickname'      => $username,
            'role'          => 'jobseeker',
        );
    
        $user_id = wp_insert_user( $user_data );
        update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
        //wp_new_user_notification( $user_id, $password );
    
        return $user_id;
    }

    /**
     * An action function used to include the reCAPTCHA JavaScript file
     * at the end of the page.
     */
    public function add_captcha_js_to_footer() {
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
    }

    /**
     * Checks that the reCAPTCHA parameter sent with the registration
     * request is valid.
     *
     * @return bool True if the CAPTCHA is OK, otherwise false.
     */
    private function verify_recaptcha() {
        // This field is set by the recaptcha widget if check is successful
        if ( isset ( $_POST['g-recaptcha-response'] ) ) {
            $captcha_response = $_POST['g-recaptcha-response'];
        } else {
            return false;
        }
    
        // Verify the captcha response from Google
        $response = wp_remote_post(
            'https://www.google.com/recaptcha/api/siteverify',
            array(
                'body' => array(
                    'secret' => get_option( 'personalize-login-recaptcha-secret-key' ),
                    'response' => $captcha_response
                )
            )
        );
    
        $success = false;
        if ( $response && is_array( $response ) ) {
            $decoded_response = json_decode( $response['body'] );
            $success = $decoded_response->success;
        }
    
        return $success;
    }

    /**
     * Renders the contents of the given template to a string and returns it.
     *
     * @param string $template_name The name of the template to render (without .php)
     * @param array  $attributes    The PHP variables for the template
     *
     * @return string               The contents of the template.
     */
    private function get_template_html( $template_name, $attributes = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }
    
        /**
         * Notes:
         * The output buffer collects everything that is printed between 
         * ob_start and ob_end_clean so that it can then be retrieved as a string using ob_get_contents.
         * 
         * Notes: the do actions are called by add action, gives chance to other devs to add further customizations
         */
        ob_start();
    
        do_action( 'lofi_customize_before_' . $template_name );
    
        require( LOFI_FRAMEWORK_DIR . 'inc/frontend/html-templates/' . $template_name . '.php');
    
        do_action( 'lofi_customize_after_' . $template_name );
    
        $html = ob_get_contents();
        ob_end_clean();
    
        return $html;
    }

    
    /**
     * Finds and returns a matching error message for the given error code.
     *
     * @param string $error_code    The error code to look up.
     *
     * @return string               An error message.
     */
    private function get_error_message( $error_code ) {
        switch ( $error_code ) {
            //Registration Error Codes
            case 'username_length':
                return __( 'Username is too short- that\'s what she said', 'lofi-framework' );

            case 'username_exists':
                return __( 'Username already exists', 'lofi-framework' );

            case 'invalid_username_register':
                return __(
                    "Somehow that username is invalid. Maybe use a different one?",
                    'lofi-framework'
                );

            case 'password_length':
                return __( 'Password is too short- that\'s what she said', 'lofi-framework' );
    
            case 'email':
                return __( 'The email address you entered is not valid.', 'lofi-framework' );

            case 'email_exists':
                return __( 'An account exists with this email address.', 'lofi-framework' );
            
            case 'closed':
                return __( 'Registering new users is currently not allowed.', 'lofi-framework' );

            case 'captcha':
                return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'lofi-framework' );

            
                
            //Neutral Error Codes
            case 'empty_field':
                return __( 'You forgot some fields though', 'lofi-framework' );

            default:
            break;
        }
        
        return __( 'An unknown error occurred. Please try again later.', 'lofi-framework' );
    }

    /**
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.0.0
     * @access public
    */
    public function __construct(){
        $this->init();
    }

    /**
     *  Init function that handles all hooks and filters
     * 
     * @since 1.0.0
     * @access public
    */
    public function init(){

        //Registration
        add_shortcode( 'lofi-jobseeker-registration-form', array( $this, 'render_registration_form' ) );
        
        // Add captcha javascript to footer
        add_action( 'wp_print_footer_scripts', array( $this, 'add_captcha_js_to_footer' ) );

        // Handle form response
        add_action( 'admin_post_nopriv_lofi_employer_registration_form_action', array($this, 'handle_form_response' ));
    }

}
Jobseeker_Registration::instance();