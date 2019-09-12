<?php

namespace Revolt_Framework\Inc\Helpers\Query;

/**
 * If querying the wp media library, jobseekers & employers will only see their own uploads
 */
add_action('pre_get_posts', __NAMESPACE__ . '\modify_acf_media_query', 1);
function modify_acf_media_query($query)
{

    if (is_user_logged_in()) {
        //if not admin
        if (!current_user_can('delete_others_revolt_job_posts')) {
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'query-attachments') {
                    $query->set('author', get_current_user_id());
                }
            }
        }
    }
    return $query;
}
add_action('template_redirect', __NAMESPACE__ . '\defensive_redirects');
function defensive_redirects($query)
{

    if (is_page('edit') || is_page('profile')) {


        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        if (!current_user_can('read_revolt_job_post')) {
            wp_redirect(home_url());
            exit;
        }
    }

    $dashboard_page = get_option('revolt_react_dashboard_page');
    if ($dashboard_page && is_page($dashboard_page)) {
        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        if (!current_user_can('publish_revolt_job_posts')) {
            wp_redirect(home_url());
            exit;
        }
    }
}
