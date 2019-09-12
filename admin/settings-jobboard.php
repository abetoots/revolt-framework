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
        'Job Board Settings <span class="dashicons dashicons-admin-generic"></span>',
        is_admin(),
        'revolt_jobboard_settings_page',
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

    //Used with do_settings_section( $ the page where our settings reside )
    add_settings_section(
        'revolt-jobboard-support-section',
        'Toggle Job Board Support',
        __NAMESPACE__ . '\render_support_section',
        'revolt_jobboard_settings_page'
    );
    add_settings_section(
        'revolt-jobboard-recaptcha-section',
        'Registration Form Activate reCAPTCHA ',
        __NAMESPACE__ . '\render_recaptcha_section',
        'revolt_jobboard_settings_page'
    );
    //Job Board Support
    add_settings_field(
        'revolt-support-locations',
        'Enable Locations? ',
        __NAMESPACE__ . '\render_locations_toggle',
        'revolt_jobboard_settings_page',
        'revolt-jobboard-support-section'
    );
    add_settings_field(
        'revolt-allow-jobboard-registration',
        'New users can register?',
        __NAMESPACE__ . '\render_registration_toggle',
        'revolt_jobboard_settings_page',
        'revolt-jobboard-support-section'
    );
    //Recaptcha Fields
    add_settings_field(
        'revolt-recaptcha-site-key',
        'reCAPTCHA site key ',
        __NAMESPACE__ . '\render_recaptcha_site_key',
        'revolt_jobboard_settings_page',
        'revolt-jobboard-recaptcha-section'
    );
    add_settings_field(
        'revolt-recaptcha-secret-key',
        'reCAPTCHA secret key ',
        __NAMESPACE__ . '\render_recaptcha_secret_key',
        'revolt_jobboard_settings_page',
        'revolt-jobboard-recaptcha-section'
    );

    //Job Board Settings Support
    register_setting('revolt-jobboard-settings', 'support_locations');
    register_setting('revolt-jobboard-settings', 'allow_jobboard_registration');
    //reCaptcha
    register_setting('revolt-jobboard-settings', 'revolt_recaptcha_site_key');
    register_setting('revolt-jobboard-settings', 'revolt_recaptcha_secret_key');
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
//Toggle Allow Registration
function render_registration_toggle()
{
    $canRegister =  esc_attr(get_option('allow_jobboard_registration'));
    ?>
    <label for="allow-jobboard-registration" class="switch">
        <input type="checkbox" id="allow-jobboard-registration" class="switch__input" name="allow_jobboard_registration" value="1" <?php checked($canRegister, 1); ?>>
        <span class="switch__slider"></span>
    </label>
<?php
}


//Recaptcha Section
function render_recaptcha_section()
{
    echo '<h4 style="font-style: italic;"><span role="img" aria-lable="prevent-spam">🛑</span> Prevent spam registration</h4>';
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
    require_once REVOLT_FRAMEWORK_DIR . 'admin/admin-templates/admin-jobboard-settings.php';
}
