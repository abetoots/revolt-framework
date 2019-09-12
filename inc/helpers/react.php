<?php

namespace Revolt_Framework\Inc\Helpers\React;

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_react_scripts');
function enqueue_react_scripts()
{

    $dashboard_page = get_option('revolt_react_dashboard_page');
    //only enqueue when using the appropriate dashboard page
    if ($dashboard_page && is_page($dashboard_page)) {
        if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
            wp_enqueue_script('revolt-react-main-script1', 'http://localhost:3000/static/js/bundle.js', array(), null, true);
            wp_enqueue_script('revolt-react-main-script3', 'http://localhost:3000/static/js/0.chunk.js', array(), null, true);
            wp_enqueue_script('revolt-react-main-script4', 'http://localhost:3000/static/js/1.chunk.js', array(), null, true);
            wp_enqueue_script('revolt-react-main-script2', 'http://localhost:3000/static/js/main.chunk.js', array(), null, true);
        } else {
            //PRODUCTION
            //parse assets_manifest
            $asset_manifest = json_decode(file_get_contents(REVOLT_REACT_ASSET_MANIFEST), true)['files'];

            //enqueue our main css
            if (isset($asset_manifest['main.css'])) {
                wp_enqueue_style('revolt-react-main-style', REVOLT_REACT_BUILD_URL . $asset_manifest['main.css']);
            }

            //always enqueue our runtime and main js files
            wp_enqueue_script('revolt-react-runtime', REVOLT_REACT_BUILD_URL . $asset_manifest['runtime~main.js'], array(), null, true);

            wp_enqueue_script('revolt-react-main-script', REVOLT_REACT_BUILD_URL . $asset_manifest['main.js'], array('revolt-react-runtime'), null, true);
            wp_localize_script('revolt-react-main-script', 'revoltReact', array(
                'nonce'     => wp_create_nonce('wp_rest'),
                'user_id'      => get_current_user_id()
            ));
            //enqueue js and css chunks
            foreach ($asset_manifest as $key => $value) {
                if (preg_match('@static/js/(.*)\.chunk\.js@', $key, $matches)) {
                    if ($matches && is_array($matches) && count($matches) === 2) {
                        $name = "revolt-react-" . preg_replace('/[^A-Za-z0-9_]/', '-', $matches[1]);
                        wp_enqueue_script($name, REVOLT_REACT_BUILD_URL . $value, array('revolt-react-main-script'), null, true);
                    }
                }

                if (preg_match('@static/css/(.*)\.chunk\.css@', $key, $matches)) {
                    if ($matches && is_array($matches) && count($matches) == 2) {
                        $name = "revolt-react-" . preg_replace('/[^A-Za-z0-9_]/', '-', $matches[1]);
                        wp_enqueue_style($name, REVOLT_REACT_BUILD_URL . $value, array('revolt-react-main-style'), null);
                    }
                }
            }
        }
    } // end check if page dashboard
}
