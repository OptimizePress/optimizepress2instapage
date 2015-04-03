<?php

/**
 * Plugin Name:       OptimizePress Instapage fix
 * Plugin URI:        http://www.optimizepress.com/
 * Description:       Removes check404Page from process-optin-form page and fixing the 404 redirect on opt-in form submit
 * Version:           1.0.0
 * Author:            OptimizePress
 * Author URI:        http://www.optimizepress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       op-instapage-fix
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
add_action('init' , 'deactivate_instapage_action', 2);

function deactivate_instapage_action(){
    if ($_SERVER['REQUEST_URI'] == '/process-optin-form/') {
        foreach ( $GLOBALS['wp_filter'] as $tag => $priority_sets ) {
            foreach ($priority_sets as $priority => $idxs) {
                foreach ($idxs as $idx => $callback) {
                    if (gettype($callback['function']) == 'object') {
                        $function = '{ closure }';
                    } else {
                        if (is_array($callback['function'])) {
                            if ( (strpos($callback['function'][1],'check404Page') !== false) ) {
                                remove_action( 'template_redirect', array($callback['function'][0], 'check404Page'), 1 );
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
}