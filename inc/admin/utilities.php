<?php

if (!defined('ABSPATH')) {
    exit;
}


//Dynamically add class when current page is 'lofi_options'
function lofi_add_css_is_admin($classes)
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
add_filter('admin_body_class', 'lofi_add_css_is_admin');

function lofi_enqueue_styles_if_admin($hook)
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
add_action('admin_enqueue_scripts', 'lofi_enqueue_styles_if_admin');



/**
 * TODO Don't forget to enable
 * When a new user is created, check if the new user is employer/jobseeker
 * If true, then update user meta to disable admin bar in front
 *
 * @uses wp_get_current_user()          Returns a WP_User object for the current user
 * @uses wp_redirect()                  Redirects the user to the specified URL
 */
function lofi_update_new_user_meta($user_id)
{
    $user_info = get_userdata($user_id);
    $roles = $user_info->roles;

    // if (in_array('employer', $roles) || in_array('jobseeker', $roles)) {
    //     update_user_meta($user_id, 'show_admin_bar_front', 'false');
    // }
    return $user_id;
}
add_action('user_register', 'lofi_update_new_user_meta');


/**
 * Redirects users based on their role
 *
 * @uses wp_get_current_user()          Returns a WP_User object for the current user
 * @uses wp_redirect()                  Redirects the user to the specified URL
 */
function lofi_redirect_users_by_role()
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
} // lofi_redirect_users_by_role
add_action('admin_init', 'lofi_redirect_users_by_role');




function lofi_redirect_to_post_on_publish_or_save_job_post($location)
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
add_filter('redirect_post_location', 'lofi_redirect_to_post_on_publish_or_save_job_post');

//Add some post states
function lofi_add_post_state($post_states, $post)
{
    $addedBy = get_post_meta($post->ID, 'inserted', true);
    $title = $post->post_title;

    //Only add post states to pages added by our plugin
    if ($addedBy == 'lofi') {
        $post_states[] = 'Lofi';
    }

    return $post_states;
}
add_filter('display_post_states', 'lofi_add_post_state', 10, 2);

add_action('rest_api_init', 'add_taxonomy_terms');
function add_taxonomy_terms()
{
    $taxonomies = get_object_taxonomies('lofi-job-post');
    foreach ($taxonomies as $taxonomy) {
        //dynamically calls register_rest_field for each taxonomy
        register_term_rest_fields_dynamic('lofi-job-post', $taxonomy);
    }
}
//Instead of writing each register rest field, use this instead
function register_term_rest_fields_dynamic($post_type, $taxonomy)
{
    return register_rest_field($post_type, $taxonomy . "_terms", array(
        'get_callback'  => function ($obj_arr, $attribute) {
            /**
             * This anonymous callback function receives 2 params, the post object
             * and the name/attribute of the registered field which is $taxonomy."_terms"
             * 
             * We strip the "_terms" so we can dynamically use it in get_the_terms()
             */
            $taxonomy_type =  str_replace('_terms', '', $attribute);
            //Returns an array of objects
            $terms = get_the_terms($obj_arr['id'], $taxonomy_type);
            $term_names = array();
            //extract the term name
            foreach ($terms as $term) {
                $term_names[] = $term->name;
            }
            return $term_names;
        }
    ));
}
