<?php

namespace LofiFramework\Helpers\Utilities;

use LofiFramework;

if (!defined('ABSPATH')) {
    exit;
}


//Dynamically add class when current page is 'lofi_options'
add_filter('admin_body_class', __NAMESPACE__ . '\add_css_is_admin');
function add_css_is_admin($classes)
{
    $screen = get_current_screen();
    if (is_admin() && get_admin_page_parent() == 'lofi_options') {
        $classes = 'lofi-admin is-lofi-options';
    } elseif ($screen->post_type == 'lofi-job-post') {
        $classes = 'lofi-admin is-lofi-job-post';
    } elseif (is_admin()) {
        $classes = 'lofi-admin';
    } elseif (is_admin_bar_showing()) {
        $classes[] = 'lofi-admin-bar';
    }
    return $classes;
}

add_action('admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles_if_admin');
function enqueue_styles_if_admin($hook)
{
    // wp_die($hook);
    //Add a google font
    /*
    wp_register_style( 'google-fonts-pm', "https://fonts.googleapis.com/css?family=Permanent+Marker" , array(), false, 'all' );
    wp_enqueue_style( 'google-fonts-pm' );
    */
    //Enqueue styles to override admin css mainly for our added menu/page
    wp_enqueue_style('admin-styles', LOFI_FRAMEWORK_URL . 'inc/admin/css/lofi-admin.css', array(), false, 'all');

    //media uploader
    wp_enqueue_media();

    if ($hook === 'post-new.php') {
        wp_enqueue_script(
            'editor-metabox',
            LOFI_FRAMEWORK_URL . 'inc/admin/js/editor-metabox.js',
            array('jquery'),
            '',
            true
        );
    }

    //script
    if ($hook === 'lofi-job-post_page_lofi_profile') {
        wp_enqueue_script('profile-settings', LOFI_FRAMEWORK_URL . 'inc/admin/js/profile-settings.js', array('jquery'), '',  true);
    }

    if ($hook === 'lofi-job-post_page_lofi_jobboard') {
        wp_enqueue_script('jobboard-settings', LOFI_FRAMEWORK_URL . 'inc/admin/js/jobboard-settings.js', array('jquery'), '',  true);
        wp_enqueue_script('profile-settings', LOFI_FRAMEWORK_URL . 'inc/admin/js/profile-settings.js', array('jquery'), '',  true);
    }
    // Add the color picker css file       
    wp_enqueue_style('wp-color-picker');

    // Include our custom jQuery file with WordPress Color Picker dependency
    wp_enqueue_script('color-picker', LOFI_FRAMEWORK_URL . 'inc/admin/js/color-picker.js', array('wp-color-picker'), false, true);
}




/**
 * TODO Don't forget to enable
 * When a new user is created, check if the new user is employer/jobseeker
 * If true, then update user meta to disable admin bar in front
 *
 * @uses wp_get_current_user()          Returns a WP_User object for the current user
 * @uses wp_redirect()                  Redirects the user to the specified URL
 */
add_action('user_register', __NAMESPACE__ . '\update_new_user_meta');
function update_new_user_meta($user_id)
{
    $user_info = get_userdata($user_id);
    $roles = $user_info->roles;

    if (in_array('employer', $roles) || in_array('jobseeker', $roles)) {
        // update_user_meta($user_id, 'show_admin_bar_front', 'false');
    }
    return $user_id;
}



/**
 * TODO Remember to enable
 * Redirects users based on their role
 *
 * @uses wp_get_current_user()          Returns a WP_User object for the current user
 * @uses wp_redirect()                  Redirects the user to the specified URL
 */
add_action('admin_init', __NAMESPACE__ . '\redirect_users_by_role');
function redirect_users_by_role()
{

    $current_user   = wp_get_current_user();
    $role_name      = $current_user->roles[0];

    switch ($role_name) {
        case 'employer':
            //wp_redirect( home_url('employer') );
            break;

        case 'jobseeker':
            wp_redirect(home_url('job-seeker'));
    }
}




/**
 * We redirect after a job has been posted instead of the default behavior of
 * staying in the edit screen.  Since 'employers' do not have capabilities to edit a
 * published post, it doesn't make sense to be on the edit screen and it will cause errors.
 */
add_filter('redirect_post_location', __NAMESPACE__ . '\redirect_on_jobpost_publish_or_save');
function redirect_on_jobpost_publish_or_save($location)
{
    $post_type = get_post_type();
    $current_user   = wp_get_current_user();
    $role_name      = $current_user->roles[0];

    //Only redirect Job Posts and if current user is employer
    if ($post_type === 'lofi-job-post' && $role_name === 'employer') {
        if (isset($_POST['save']) || isset($_POST['publish'])) {
            $post_link = get_post_permalink();
            if ($post_link) {
                wp_redirect($post_link); //redirect to the posts' permalink
                exit;
            }
        }
    }
    return $location;
}


/**
 * Adds post state to posts with meta 'inserted: lofi'
 */
add_filter('display_post_states', __NAMESPACE__ . '\add_post_state', 10, 2);
function add_post_state($post_states, $post)
{
    $addedBy = get_post_meta($post->ID, 'inserted', true);

    //Only add post states to pages added by our plugin
    if ($addedBy == 'lofi') {
        $post_states[] = 'Lofi';
    }

    return $post_states;
}
