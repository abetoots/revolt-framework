<?php
    

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Employer_Account{

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
     * A shortcode for rendering the employer dashboard.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_employer_dashboard( $attributes, $content = null ) {

        // Parse shortcode attributes
        $default_attributes = array();
        $attributes = shortcode_atts( $default_attributes, $attributes );    

        if ( ! is_user_logged_in() ) {
            return __( 'Hmmm. Who are you? You\'re not signed in.', 'lofi-framework' );
        }
        
        // Render the dashboard
        return $this->get_template_html( 'employer_dashboard', $attributes );
    }

    /**
     * A shortcode for rendering employer edit profile page.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_employer_edit_profile( $attributes, $content = null ) {

        // Parse shortcode attributes
        $default_attributes = array();
        $attributes = shortcode_atts( $default_attributes, $attributes );    

        if ( ! is_user_logged_in() ) {
            return __( 'Hmmm. Who are you? You\'re not signed in.', 'lofi-framework' );
        }
        
        // Render the login form using an external template
        return $this->get_template_html( 'employer_edit_profile', $attributes );
    }

    /**
     * A shortcode for rendering the employer profile page.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_employer_view_profile( $attributes, $content = null ) {

        // Parse shortcode attributes
        $default_attributes = array(
            'cover_photo'   => true, 
            'logo'   => true,
            'about'         => true,
            'member_since'  => true,
            'location'      => true,
            'num_employees' => true,
            'email_address' => true,
            'website'       => true,
            'contact_num'       => true,
            'contact_form'      => true,
        );
        $attributes = shortcode_atts( $default_attributes, $attributes );    

        if ( ! is_user_logged_in() ) {
            return __( 'Hmmm. Who are you? You\'re not signed in.', 'lofi-framework' );
        }
        
        // Render the login form using an external template
        return $this->get_template_html( 'employer_view_profile', $attributes );
    }

    /**
     * A shortcode for rendering the employer profile page.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_employer_manage_jobs( $attributes, $content = null ) {
        
        $default_attributes = array();
        $attributes = shortcode_atts( $default_attributes, $attributes );    

        if ( ! is_user_logged_in() ) {
            return __( 'Hmmm. Who are you? You\'re not signed in.', 'lofi-framework' );
        }
        
        // Render the login form using an external template
        return $this->get_template_html( 'manage_jobs', $attributes );
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
    public function handle_form_response(){
        

        if ( ! isset( $_POST['lofi-edit-profile-nonce'] ) && ! isset( $_POST['form_employer_submit'] ) ){
            return;
        }

        if( ! wp_verify_nonce( $_POST['lofi-edit-profile-nonce']  , 'lofi_employer_edit_profile_action'  ) ) {
            return;
        }
        wp_redirect( home_url() );
        exit;
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
        add_shortcode( 'lofi-employer-dashboard', array( $this, 'render_employer_dashboard' ) );

        add_shortcode( 'lofi-employer-edit-profile', array( $this, 'render_employer_edit_profile' ) );

        add_shortcode( 'lofi-employer-view-profile', array( $this, 'render_employer_view_profile' ) );

        add_shortcode( 'lofi-employer-manage-jobs', array( $this, 'render_employer_manage_jobs' ) );
     
        // Handle form response
        add_action( 'admin_post_lofi_employer_edit_profile_action', array($this, 'handle_form_response' ));
    }
}
Employer_Account::instance();