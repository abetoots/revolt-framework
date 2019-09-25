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

        //for job board users only
        if (!current_user_can('read_revolt_job_post')) {
            wp_redirect(home_url());
            exit;
        }

        //for jobseekers only
        if (current_user_can('publish_revolt_job_posts')) {
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


add_action('wp_ajax_search_jobs_hook', __NAMESPACE__ . '\handle_search_form_response');
add_action('wp_ajax_nopriv_search_jobs_hook', __NAMESPACE__ . '\handle_search_form_response');

function handle_search_form_response()
{
    if (!check_ajax_referer('filter-search-jobs-nonce', 'filter_search_nonce')) {
        wp_die('Meh');
    };

    //initialize
    $args = array(
        'post_type'     => 'revolt-job-post'
    );

    if (isset($_POST['search']) && $_POST['search']) {
        $args['s'] = urlencode($_POST['search']);
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            get_template_part('/template-parts/content', get_post_type());
        endwhile; ?>
        <div class="Jobs__pagination">
            <?php
                    the_posts_pagination(array(
                        'mid_size'      => 4
                    )); ?>
        </div>
    <?php
            wp_reset_postdata();
        else :
            get_template_part('template-parts/content', 'none');
        endif;

        die();
    }

    add_action('wp_ajax_filter_jobs_hook', __NAMESPACE__ . '\handle_filter_form_response');
    add_action('wp_ajax_nopriv_filter_jobs_hook', __NAMESPACE__ . '\handle_filter_form_response');

    function handle_filter_form_response()
    {
        if (!check_ajax_referer('filter-search-jobs-nonce', 'filter_search_nonce')) {
            wp_die('Meh');
        };

        //initialize
        $args = array(
            'post_type'     => 'revolt-job-post'
        );
        // TAXONOMIES 
        //no need to check for empty values since jquery serialize bypassess empty vals
        //checks if both taxonomies are checked
        if (isset($_POST['job_categories']) && $_POST['job_categories'] && isset($_POST['employment_types']) && $_POST['employment_types']) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'job_categories',
                    'field' => 'id',
                    'terms' => $_POST['job_categories']
                ),
                array(
                    'taxonomy' => 'employment_types',
                    'field' => 'id',
                    'terms' => $_POST['employment_types']
                ),
                'relation'      => 'AND',
            );
        } else {
            //if only job categories is selected
            if (isset($_POST['job_categories']) && $_POST['job_categories']) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'job_categories',
                        'field' => 'id',
                        'terms' => $_POST['job_categories']
                    )
                );
            }

            //if only job categories is selected
            if (isset($_POST['employment_types']) && $_POST['employment_types']) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'employment_types',
                        'field' => 'id',
                        'terms' => $_POST['employment_types']
                    )
                );
            }
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();

                get_template_part('/template-parts/content', get_post_type());
            endwhile; ?>
        <div class="Jobs__pagination">
            <?php
                    the_posts_pagination(array(
                        'mid_size'      => 4
                    )); ?>
        </div>
<?php
        wp_reset_postdata();
    else :
        get_template_part('template-parts/content', 'none');
    endif;

    die();
}
