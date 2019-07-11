<?php

namespace LofiFramework\Core\Settings\Profile;

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
        'Lofi Profile Sidebar Options',
        'Profile Sidebar',
        is_admin(),
        'lofi_profile',
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
    //Profile Sidebar Settings
    register_setting('lofi-profile-settings', 'profile_picture');
    register_setting('lofi-profile-settings', 'first_name');
    register_setting('lofi-profile-settings', 'last_name');
    register_setting('lofi-profile-settings', 'description');
    register_setting('lofi-profile-settings', 'twitter_handler');
    //Used with do_settings_section( $ the page where our settings reside )
    add_settings_section(
        'lofi-profile-settings-section',
        'Customize Profile',
        __NAMESPACE__ . '\render_profile_section',
        'lofi_profile'
    );
    //Fields
    add_settings_field(
        'lofi-profile-pic',
        'Profile Picture',
        __NAMESPACE__ . '\render_profile_pic',
        'lofi_profile',
        'lofi-profile-settings-section'
    );
    add_settings_field(
        'lofi-full-name',
        'Full Name',
        __NAMESPACE__ . '\render_full_name',
        'lofi_profile',
        'lofi-profile-settings-section'
    );
    add_settings_field(
        'lofi-description',
        'Profile Description',
        __NAMESPACE__ . '\render_description',
        'lofi_profile',
        'lofi-profile-settings-section'
    );
    add_settings_field(
        'lofi-twitter-handler',
        'Twitter Handler',
        __NAMESPACE__ . '\render_twitter_handler',
        'lofi_profile',
        'lofi-profile-settings-section'
    );
}

function render_profile_section()
{
    echo '<p style="font-style:italic;">Remember to save your settings</p>';
}

//Profile Pic Field
function render_profile_pic()
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
function render_full_name()
{
    $firstName = esc_attr(get_option('first_name'));
    $lastName = esc_attr(get_option('last_name'));
    echo '<input type="text" name="first_name" value="' . $firstName . '" placeholder = "First Name" >
          <input type="text" name="last_name" value="' . $lastName . '" placeholder = "Last Name" >';
}

//Description Field
function render_description()
{
    $description = esc_attr(get_option('description'));
    echo '<input type="text" name="description" value="' . $description . '" placeholder = "About You" >';
}

//Twitter Field
function render_twitter_handler()
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

/**
 * Generate the page
 */
function generate_page()
{
    //Generate the main page
    require_once LOFI_FRAMEWORK_DIR . 'inc/admin/admin-templates/admin-profile.php';
}
