<?php

/**

 *@package lofi
 *
 */

if (!defined('ABSPATH')) {
    exit;
}


/* ---------------Menu & Settings ----------------- */
function lofi_admin_add_menu()
{

    //Profile
    add_submenu_page(
        'edit.php?post_type=lofi-job-post',
        'Lofi Profile Sidebar Options',
        'Profile Sidebar',
        is_admin(),
        'lofi_profile',
        'lofi_generate_profile_page'
    );

    //Theme Options
    add_submenu_page(
        'edit.php?post_type=lofi-job-post',
        'Lofi Theme Settings',
        'Theme Settings <span class="dashicons dashicons-admin-settings"></span>',
        is_admin(),
        'lofi_options',
        'lofi_generate_theme_options_page'
    );

    //Board Settings
    add_submenu_page(
        'edit.php?post_type=lofi-job-post',
        'Lofi Job Board Settings',
        'JobBoard Settings <span class="dashicons dashicons-admin-generic"></span>',
        is_admin(),
        'lofi_jobboard',
        'lofi_generate_job_board_settings_page'
    );



    //Activate custom settings
    add_action('admin_init', 'lofi_custom_settings');
}
add_action('admin_menu', 'lofi_admin_add_menu');




//Custom settings stored in the database
function lofi_custom_settings()
{

    $args = array(
        'type'              => 'string',
        'description'       => '',
        'sanitize_callback' => null,
        'show_in_rest' => array(
            'schema' => array(
                'enum' => array('private', 'public')
            ),
        )
    );

    //Job Board Settings Info
    register_setting('lofi-jobboard-settings', 'company_name', $args);
    register_setting('lofi-jobboard-settings', 'company_size');
    register_setting('lofi-jobboard-settings', 'employer_website');
    register_setting('lofi-jobboard-settings', 'employer_email');
    register_setting('lofi-jobboard-settings', 'employer_photo');
    //Job Board Settings Support
    register_setting('lofi-jobboard-settings', 'support_locations');
    //reCaptcha
    register_setting('lofi-jobboard-settings', 'lofi_recaptcha_site_key');
    register_setting('lofi-jobboard-settings', 'lofi_recaptcha_secret_key');

    //Used with do_settings_section( $ the page where our settings reside )
    add_settings_section('lofi-jobboard-info-section', 'Lofi Job Board Default Information', 'lofi_jobboard_render_section_info', 'lofi_jobboard');
    add_settings_section('lofi-jobboard-support-section', 'Toggle Job Board Support', 'lofi_jobboard_render_section_support', 'lofi_jobboard');
    add_settings_section('lofi-jobboard-recaptcha-section', 'Registration Form Activate reCAPTCHA ', 'lofi_jobboard_render_section_recaptcha', 'lofi_jobboard');
    //Info Fields
    add_settings_field('lofi-company-name', 'Employer / Company Name', 'lofi_company_name_render_field', 'lofi_jobboard', 'lofi-jobboard-info-section');
    add_settings_field('lofi-company-size', 'Company Size', 'lofi_company_size_render_field', 'lofi_jobboard', 'lofi-jobboard-info-section');
    add_settings_field('lofi-employer-website', 'Employer Website', 'lofi_employer_website_render_field', 'lofi_jobboard', 'lofi-jobboard-info-section');
    add_settings_field('lofi-employer-email', 'Employer Email', 'lofi_employer_email_render_field', 'lofi_jobboard', 'lofi-jobboard-info-section');
    add_settings_field('lofi-employer-photo', 'Employer Photo', 'lofi_employer_photo_render_field', 'lofi_jobboard', 'lofi-jobboard-info-section');
    //Support Fields
    add_settings_field('lofi-support-locations', 'Enable Locations? ', 'lofi_support_locations_render_field', 'lofi_jobboard', 'lofi-jobboard-support-section');
    //Recaptcha Fields
    add_settings_field('lofi-recaptcha-site-key', 'reCAPTCHA site key ', 'lofi_recaptcha_site_key_render_field', 'lofi_jobboard', 'lofi-jobboard-recaptcha-section');
    add_settings_field('lofi-recaptcha-secret-key', 'reCAPTCHA secret key ', 'lofi_recaptcha_secret_key_render_field', 'lofi_jobboard', 'lofi-jobboard-recaptcha-section');


    //Profile Sidebar Settings
    register_setting('lofi-profile-settings', 'profile_picture');
    register_setting('lofi-profile-settings', 'first_name');
    register_setting('lofi-profile-settings', 'last_name');
    register_setting('lofi-profile-settings', 'description');
    register_setting('lofi-profile-settings', 'twitter_handler');
    //Used with do_settings_section( $ the page where our settings reside )
    add_settings_section('lofi-profile-settings-section', 'Customize Profile', 'lofi_profile_render_section', 'lofi_profile');
    //Fields
    add_settings_field('lofi-profile-pic', 'Profile Picture', 'lofi_profile_pic_render_field', 'lofi_profile', 'lofi-profile-settings-section');
    add_settings_field('lofi-full-name', 'Full Name', 'lofi_full_name_render_field', 'lofi_profile', 'lofi-profile-settings-section');
    add_settings_field('lofi-description', 'Profile Description', 'lofi_description_render_field', 'lofi_profile', 'lofi-profile-settings-section');
    add_settings_field('lofi-twitter-handler', 'Twitter Handler', 'lofi_twitter_handler_render_field', 'lofi_profile', 'lofi-profile-settings-section');
}



/*
          
          ====================================================
          =        JOB BOARD SETTINGS CALLBACKS              = 
          ====================================================
*/

/* --------------------------- JOB BOARD INFO ----------------------- */
function lofi_jobboard_render_section_info()
{
    echo '<p style="font-style:italic">Set your default settings</p>';
}
//Company Name
function lofi_company_name_render_field()
{
    $companyName = esc_attr(get_option('company_name'));
    echo '<input type="text" name="company_name" value="' . $companyName . '" placeholder = "Your Company Name" >';
}
//Company Size
function lofi_company_size_render_field()
{
    $companySize = esc_attr(get_option('company_size'));
    echo '<input type="number" name="company_size" value="' . $companySize . '">';
}
//Employer Website
function lofi_employer_website_render_field()
{
    $companyWebsite = get_option('company_website');
    $companyName = esc_attr(get_option('company_name'));
    //Company name defaults to 'Sample Company Name' if $companyName is null
    $companyName = ($companyName) ? $companyName : 'Sample Company Name';
    $forInput = esc_attr($companyWebsite);
    $forLink = esc_url($companyWebsite);

    if (!empty($companyWebsite)) {
        echo '
        <div>
            <div>Link Preview:</div>
            <a href="' . $forLink . '">' . $companyName . '</a>
        </div>
        ';
    }
    echo '<input type="url" name="company_website" value="' . $forInput . '">';
}
//Employer Email
function lofi_employer_email_render_field()
{
    $companyEmail = esc_attr(get_option('company_email'));
    echo '<input type="email" name="company_email" value="' . $companyEmail . '">';
}

//Employer Email
function lofi_employer_photo_render_field()
{
    $profilePic = esc_attr(get_option('employer_photo'));

    if (empty($profilePic)) {
        echo '<input type="button" class="button" id="upload-button" value="Upload Your Picture">
        <input type="hidden" name="employer_photo" id="employer-photo" value="" >';
    } else {
        echo '<input type="button" class="button" id="upload-button" value="Replace Profile Picture">
        <input type="button" class="button" id="remove-button" value="Remove">
        <input type="hidden" name="employer_photo" id="employer-photo" value="' . $profilePic . '" >';
    }
}

/* --------------------------- JOB BOARD SUPPORT ----------------------- */
function lofi_jobboard_render_section_support()
{
    return;
}

function lofi_support_locations_render_field()
{
    $locationsIsChecked =  esc_attr(get_option('support_locations'));
    ?>
    <label for="support-locations" class="format-switch">
        <input type="checkbox" id="support-locations" class="switch-input" name="support_locations" value="1" <?php checked($locationsIsChecked, 1); ?>>
        <span class="slider"></span>
    </label>
<?php
}


/* --------------------------- JOB BOARD ReCAPTCHA ----------------------- */
function lofi_jobboard_render_section_recaptcha()
{
    return;
}

function lofi_recaptcha_site_key_render_field()
{
    $siteKey = esc_attr(get_option('lofi_recaptcha_site_key'));
    echo '<input type="text" name="lofi_recaptcha_site_key" value="' . $siteKey . '">';
}

function lofi_recaptcha_secret_key_render_field()
{
    $secretKey = esc_attr(get_option('lofi_recaptcha_site_key'));
    echo '<input type="text" name="lofi_recaptcha_secret_key" value="' . $secretKey . '">';
}


/*
          
          =============================================
          =        PROFILE SETTINGS CALLBACKS         = 
          =============================================
*/


//The Section
function lofi_profile_render_section()
{
    echo '<p style="font-style:italic;">Remember to save your settings</p>';
}

//Profile Pic Field
function lofi_profile_pic_render_field()
{
    $profilePic = esc_attr(get_option('profile_picture'));

    if (empty($profilePic)) {
        echo '<input type="button" class="button" id="upload-button" value="Upload Your Picture">
        <input type="hidden" name="profile_picture" id="profile-picture" value="" >';
    } else {
        echo '<input type="button" class="button" id="upload-button" value="Replace Profile Picture">
        <input type="button" class="button" id="remove-button" value="Remove">
        <input type="hidden" name="profile_picture" id="profile-picture" value="' . $profilePic . '" >';
    }
}

//Full Name Field
function lofi_full_name_render_field()
{
    $firstName = esc_attr(get_option('first_name'));
    $lastName = esc_attr(get_option('last_name'));
    echo '<input type="text" name="first_name" value="' . $firstName . '" placeholder = "First Name" >
          <input type="text" name="last_name" value="' . $lastName . '" placeholder = "Last Name" >';
}

//Description Field
function lofi_description_render_field()
{
    $description = esc_attr(get_option('description'));
    echo '<input type="text" name="description" value="' . $description . '" placeholder = "About You" >';
}

//Twitter Field
function lofi_twitter_handler_render_field()
{
    $twitterHandler = esc_attr(get_option('twitter_handler'));
    echo '<input type="text" name="twitter_handler" value="' . $twitterHandler . '" placeholder = "Twitter Handler" >
    <p style="font-style:italic">Input your handler without the @ symbol </p>';
}
//Sanitize this
function sanitize_twitter_handler($input)
{
    $output = sanitize_text_field($input);
    $output = str_replace('@', '', $output);
    return $output;
}




/*
          
          ===========================================
          =        GENERATE PAGE CALLBACKS          = 
          ===========================================
*/


function lofi_generate_profile_page()
{
    //Generate the main page
    require_once LOFI_FRAMEWORK_DIR . 'inc/admin/admin-templates/admin-profile.php';
}

function lofi_generate_job_board_settings_page()
{
    require_once LOFI_FRAMEWORK_DIR . 'inc/admin/admin-templates/admin-jobboard-settings.php';
}
