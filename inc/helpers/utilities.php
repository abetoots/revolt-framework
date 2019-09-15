<?php

namespace Revolt_Framework\Inc\Helpers\Utilities;


if (!defined('ABSPATH')) {
    exit;
}


add_action('admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles_if_admin');
function enqueue_styles_if_admin($hook)
{

    //Enqueue styles to override admin css mainly for our added menu/page
    wp_enqueue_style('revolt-admin-styles', REVOLT_FRAMEWORK_URL . 'admin/css/admin.css', array(), false, 'all');
    // wp_enqueue_media();
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts_frontend');
function enqueue_scripts_frontend()
{
    if (is_page('sign-in')) {
        wp_enqueue_script('token', REVOLT_FRAMEWORK_URL . 'assets/js/token.js', array('jquery'), null, true);
    }
}


/**
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
    $role = $user_info->roles[0];

    if ($role === 'employer' || $role === 'jobseeker') {
        update_user_meta($user_id, 'show_admin_bar_front', 'false');
    }
    return $user_id;
}



/**
 * Redirects users based on their role
 *
 * @uses wp_get_current_user()          Returns a WP_User object for the current user
 * @uses wp_redirect()                  Redirects the user to the specified URL
 */
add_action('admin_init', __NAMESPACE__ . '\redirect_users_by_role');
function redirect_users_by_role()
{

    $current_user   = wp_get_current_user();
    if ($current_user->ID !== 0) {
        $role = $current_user->roles[0];
        if ($role === 'employer' || $role === 'jobseeker') {
            wp_redirect(home_url(), 403);
            exit;
        }
    }
}

/**
 * We redirect after a job has been posted instead of the default behavior of
 * staying in the edit screen.  Since 'employers' do not have capabilities to edit a
 * published post, it doesn't make sense to be on the edit screen as it displays an empty white screen.
 */
add_filter('redirect_post_location', __NAMESPACE__ . '\redirect_on_jobpost_publish_or_save');
function redirect_on_jobpost_publish_or_save($location)
{
    $post_type = get_post_type();
    $current_user   = wp_get_current_user();
    $role_name      = $current_user->roles[0];

    //Only redirect Job Posts and if current user is employer
    if ($post_type === 'revolt-job-post' && $role_name === 'employer') {
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
 * Adds post state to posts with meta 'inserted: revolt'
 */
add_filter('display_post_states', __NAMESPACE__ . '\add_post_state', 10, 2);
function add_post_state($post_states, $post)
{
    $addedBy = get_post_meta($post->ID, 'inserted', true);

    //Only add post states to pages added by our plugin
    if ($addedBy == 'revolt') {
        $post_states[] = 'Revolt';
    }

    return $post_states;
}
