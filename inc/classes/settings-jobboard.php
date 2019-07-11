<?php

namespace LofiFramework\Core\Settings\JobBoard;

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Add pages to admin dashboard
 * @uses add_submenu_page
 */
add_action('admin_menu', __NAMESPACE__ . '\add_admin_menu_pages');
function add_admin_menu_pages()
{

    add_submenu_page(
        'edit.php?post_type=lofi-job-post',
        'Lofi Job Board Settings',
        'JobBoard Settings <span class="dashicons dashicons-admin-generic"></span>',
        is_admin(),
        'lofi_jobboard',
        __NAMESPACE__ . '\generate_page'
    );

    //Activate custom settings
    add_action('admin_init', __NAMESPACE__ . '\init_custom_settings');
}

/**
 * Register custom settings in the DB
 * @uses register_setting()
 */
function init_custom_settings()
{
    //Job Board Settings Info
    register_setting('lofi-jobboard-settings', 'company_name');
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
    add_settings_section(
        'lofi-jobboard-info-section',
        'Lofi Job Board Default Information',
        __NAMESPACE__ . '\render_default_info_section',
        'lofi_jobboard'
    );
    add_settings_section(
        'lofi-jobboard-support-section',
        'Toggle Job Board Support',
        __NAMESPACE__ . '\render_support_section',
        'lofi_jobboard'
    );
    add_settings_section(
        'lofi-jobboard-recaptcha-section',
        'Registration Form Activate reCAPTCHA ',
        __NAMESPACE__ . '\render_recaptcha_section',
        'lofi_jobboard'
    );
    //Info Fields
    add_settings_field(
        'lofi-company-name',
        'Employer / Company Name',
        __NAMESPACE__ . '\render_company_name',
        'lofi_jobboard',
        'lofi-jobboard-info-section'
    );
    add_settings_field(
        'lofi-company-size',
        'Company Size',
        __NAMESPACE__ . '\render_company_size',
        'lofi_jobboard',
        'lofi-jobboard-info-section'
    );
    add_settings_field(
        'lofi-employer-website',
        'Employer Website',
        __NAMESPACE__ . '\render_employer_website',
        'lofi_jobboard',
        'lofi-jobboard-info-section'
    );
    add_settings_field(
        'lofi-employer-email',
        'Employer Email',
        __NAMESPACE__ . '\render_employer_email',
        'lofi_jobboard',
        'lofi-jobboard-info-section'
    );
    add_settings_field(
        'lofi-employer-photo',
        'Employer Photo',
        __NAMESPACE__ . '\render_employer_photo',
        'lofi_jobboard',
        'lofi-jobboard-info-section'
    );
    //Support Fields
    add_settings_field(
        'lofi-support-locations',
        'Enable Locations? ',
        __NAMESPACE__ . '\render_locations_toggle',
        'lofi_jobboard',
        'lofi-jobboard-support-section'
    );
    //Recaptcha Fields
    add_settings_field(
        'lofi-recaptcha-site-key',
        'reCAPTCHA site key ',
        __NAMESPACE__ . '\render_recaptcha_site_key',
        'lofi_jobboard',
        'lofi-jobboard-recaptcha-section'
    );
    add_settings_field(
        'lofi-recaptcha-secret-key',
        'reCAPTCHA secret key ',
        __NAMESPACE__ . '\render_recaptcha_secret_key',
        'lofi_jobboard',
        'lofi-jobboard-recaptcha-section'
    );
}

/**
 * *JOB BOARD SETTINGS CALLBACKS
 */


// Default information Section
function render_default_info_section()
{
    echo '<p style="font-style:italic">Set your default settings</p>';
}
//Company Name
function render_company_name()
{
    $companyName = esc_attr(get_option('company_name'));
    echo '<input type="text" name="company_name" value="' . $companyName . '" placeholder = "Your Company Name" >';
}
//Company Size
function render_company_size()
{
    $companySize = esc_attr(get_option('company_size'));
    echo '<input type="number" name="company_size" value="' . $companySize . '">';
}
//Employer Website
function render_employer_website()
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
function render_employer_email()
{
    $companyEmail = esc_attr(get_option('company_email'));
    echo '<input type="email" name="company_email" value="' . $companyEmail . '">';
}

//Employer Photo
function render_employer_photo()
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


//Job Board Support Section
//Used to toggle which features the job board supports
function render_support_section()
{
    return;
}
//Toggle Locations
function render_locations_toggle()
{
    $locationsIsChecked =  esc_attr(get_option('support_locations'));
    ?>
    <label for="support-locations" class="format-switch">
        <input type="checkbox" id="support-locations" class="switch-input" name="support_locations" value="1" <?php checked($locationsIsChecked, 1); ?>>
        <span class="slider"></span>
    </label>
<?php
}


//Recaptcha Section
function render_recaptcha_section()
{
    return;
}

function render_recaptcha_site_key()
{
    $siteKey = esc_attr(get_option('lofi_recaptcha_site_key'));
    echo '<input type="text" name="lofi_recaptcha_site_key" value="' . $siteKey . '">';
}

function render_recaptcha_secret_key()
{
    $secretKey = esc_attr(get_option('lofi_recaptcha_site_key'));
    echo '<input type="text" name="lofi_recaptcha_secret_key" value="' . $secretKey . '">';
}


/**
 * Generate the page
 */
function generate_page()
{
    require_once LOFI_FRAMEWORK_DIR . 'inc/admin/admin-templates/admin-jobboard-settings.php';
}
