<?php

namespace Revolt_Framework\Core\Settings_JobBoard;

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
        'edit.php?post_type=revolt-job-post',
        'Revolt Job Board Settings',
        'JobBoard Settings <span class="dashicons dashicons-admin-generic"></span>',
        is_admin(),
        'revolt_jobboard',
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
    //Job Board Settings Support
    register_setting('revolt-jobboard-settings', 'support_locations');
    //reCaptcha
    register_setting('revolt-jobboard-settings', 'revolt_recaptcha_site_key');
    register_setting('revolt-jobboard-settings', 'revolt_recaptcha_secret_key');

    //Used with do_settings_section( $ the page where our settings reside )
    add_settings_section(
        'revolt-jobboard-support-section',
        'Toggle Job Board Support',
        __NAMESPACE__ . '\render_support_section',
        'revolt_jobboard'
    );
    add_settings_section(
        'revolt-jobboard-recaptcha-section',
        'Registration Form Activate reCAPTCHA ',
        __NAMESPACE__ . '\render_recaptcha_section',
        'revolt_jobboard'
    );
    //Support Fields
    add_settings_field(
        'revolt-support-locations',
        'Enable Locations? ',
        __NAMESPACE__ . '\render_locations_toggle',
        'revolt_jobboard',
        'revolt-jobboard-support-section'
    );
    //Recaptcha Fields
    add_settings_field(
        'revolt-recaptcha-site-key',
        'reCAPTCHA site key ',
        __NAMESPACE__ . '\render_recaptcha_site_key',
        'revolt_jobboard',
        'revolt-jobboard-recaptcha-section'
    );
    add_settings_field(
        'revolt-recaptcha-secret-key',
        'reCAPTCHA secret key ',
        __NAMESPACE__ . '\render_recaptcha_secret_key',
        'revolt_jobboard',
        'revolt-jobboard-recaptcha-section'
    );
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
<label for="support-locations" class="switch">
    <input type="checkbox" id="support-locations" class="switch__input" name="support_locations" value="1" <?php checked($locationsIsChecked, 1); ?>>
    <span class="switch__slider"></span>
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
    $siteKey = esc_attr(get_option('revolt_recaptcha_site_key'));
    echo '<input type="text" name="revolt_recaptcha_site_key" value="' . $siteKey . '">';
}

function render_recaptcha_secret_key()
{
    $secretKey = esc_attr(get_option('revolt_recaptcha_site_key'));
    echo '<input type="text" name="revolt_recaptcha_secret_key" value="' . $secretKey . '">';
}


/**
 * Generate the page
 */
function generate_page()
{
    require_once REVOLT_FRAMEWORK_DIR . 'inc/admin/admin-templates/admin-jobboard-settings.php';
}
