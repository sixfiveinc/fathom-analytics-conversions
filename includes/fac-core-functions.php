<?php
/**
 * The Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package Fathom_Analytics_Conversions\Functions
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

global $fac4wp_options, $fac4wp_default_options;

$fac4wp_options = array();

$fac4wp_default_options = array(
    'FATHOM_ADMIN_TRACKING_OPTION_NAME'             => fac_fathom_get_admin_tracking(),
    'fac_fathom_analytics_is_active'                => fac_fathom_analytics_is_active(),
    FAC4WP_OPTION_API_KEY_CODE                      => '',
    FAC4WP_OPTION_INTEGRATE_WPCF7                   => false,
);

function fac4wp_reload_options() {
    global $fac4wp_default_options;

    $stored_options = (array) get_option( FAC4WP_OPTIONS );
    if ( ! is_array( $fac4wp_default_options ) ) {
        $fac4wp_default_options = array();
    }

    $return_options = array_merge( $fac4wp_default_options, $stored_options );

    return $return_options;
}

$fac4wp_options = fac4wp_reload_options();

// get admin tracking from Fathom Analytics
function fac_fathom_get_admin_tracking() {
    if(!defined('FATHOM_ADMIN_TRACKING_OPTION_NAME')) define('FATHOM_ADMIN_TRACKING_OPTION_NAME', 'fathom_track_admin');

    return get_option(FATHOM_ADMIN_TRACKING_OPTION_NAME, '');
}

// is Fathom Analytics active
function fac_fathom_analytics_is_active() {
    return is_plugin_active('fathom-analytics/fathom-analytics.php');
}

// check API key
function fac_api_key() {
    global $fac4wp_options;
    $_api_key   = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
    $url = 'https://api.usefathom.com/v1/sites';
    $wp_request_headers = array(
        'Authorization' => 'Bearer ' . $_api_key
    );
    $request_args = [
        'headers' => $wp_request_headers,
    ];
    $response = wp_remote_get( $url, $request_args );
    //echo '<pre>';print_r($response);echo '</pre>';
    if ( ! is_wp_error( $response ) ) {
        $result = wp_remote_retrieve_body( $response );
    } else $result = '';
    echo '<pre>';print_r($result);echo '</pre>';
}
