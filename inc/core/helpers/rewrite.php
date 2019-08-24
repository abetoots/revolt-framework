<?php

namespace Revolt_Framework\Core\Helpers\Rewrite;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Register custom query vars
 *
 * @param array $vars The array of available query variables
 * 
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 */
function register_custom_query_vars($vars)
{
    $vars[] = 'jobseeker';
    return $vars;
}
add_filter('query_vars', __NAMESPACE__ . '\register_custom_query_vars');

// /**
//  * Build a custom query
//  *
//  * @param $query obj The WP_Query instance (passed by reference)
//  *
//  * @link https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
//  */
function modify_query($query)
{
    // check if the user is requesting an admin page 
    // or current query is not the main query
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    // echo '<pre>' . var_export($menus, true) . '</pre>';
    // wp_die();

    //     if ($query->pagename = 'profile') {
    //         if (empty(get_query_var('jobseeker'))) {
    //             if (is_user_logged_in()) {

    //             }
    //         }
    //     }
}
add_action('pre_get_posts', __NAMESPACE__ . '\modify_query', 1);


/**
 * ! add rewrite tags is basically the same as filtering the query vars
 * Add rewrite rules
 *
 * @link https://codex.wordpress.org/Rewrite_API/add_rewrite_tag
 * @link https://codex.wordpress.org/Rewrite_API/add_rewrite_rule
 */
function add_custom_rewrites()
{
    // add_rewrite_tag('%job-author%', '([^&]+)');
    // add_rewrite_rule('^profile/edit/([0-9]+)/?', 'index.php?jobseeker=$matches[1]&pagename=profile/edit', 'top');
    // add_rewrite_rule('^profile/([0-9]+)/?', 'index.php?jobseeker=$matches[1]&pagename=profile', 'top');
}
add_action('init', __NAMESPACE__ .  '\add_custom_rewrites', 10, 0);

// add_filter('author_link', __NAMESPACE__ . '\new_author_link', 10, 3);
// function new_author_link($link, $author_id, $author_nicename)
// {
//     $roles = get_userdata($author_id)->roles;
//     if (in_array('employer', $roles)) {
//         $link = home_url('profile');
//     }
//     return $link;
// }

// function template_loader($template)
// {

//     // load the author template if ?job-author is  found on wordpress url/request
//     if (get_query_var('job-author')) {
//         return get_stylesheet_directory_uri() . 'author.php';
//     }
//     return $template;
// }
// add_filter('template_include', __NAMESPACE__ . '\template_loader');
