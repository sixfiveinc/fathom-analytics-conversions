<?php
/**
 * The Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package Fathom_Analytics_Conversions\Functions
 * @version 1.0.9
 */

defined( 'ABSPATH' ) || exit;

global $fac4wp_options, $fac4wp_default_options;

$fac4wp_options = [];

$fac4wp_default_options = [
	FAC4WP_OPTION_API_KEY_CODE           => '',
	FAC_OPTION_INSTALLED_TC              => '',
	FAC4WP_OPTION_INTEGRATE_WPCF7        => FALSE,
	FAC4WP_OPTION_INTEGRATE_WPFORMS      => FALSE,
	FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS => FALSE,
	FAC4WP_OPTION_INTEGRATE_FLUENTFORMS  => FALSE,
	FAC4WP_OPTION_INTEGRATE_NINJAFORMS   => FALSE,
	FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE  => FALSE,
	'integrate-wp-login'                 => FALSE,
	'integrate-wp-registration'          => FALSE,
	'integrate-wp-lost-password'         => FALSE,
];
apply_filters( 'fac4wp_global_default_options', $fac4wp_default_options );

if ( ! function_exists( 'fac_fathom_get_excluded_roles' ) ) {
	function fac_fathom_get_excluded_roles() {
		$excluded_roles = get_option( 'fathom_exclude_roles', [] );

		if ( ! is_array( $excluded_roles ) ) {
			$excluded_roles = [];
		}

		return $excluded_roles;
	}
}

if ( ! function_exists( 'fac_fathom_is_excluded_from_tracking' ) ) {
	function fac_fathom_is_excluded_from_tracking() {
		if ( ! is_user_logged_in() ) {
			return FALSE;
		}

		$user = wp_get_current_user();

		return (bool) array_intersect( fac_fathom_get_excluded_roles(), $user->roles );
	}
}

function fac4wp_reload_options() {
	global $fac4wp_default_options;

	$stored_options = (array) get_option( FAC4WP_OPTIONS );
	if ( ! is_array( $fac4wp_default_options ) ) {
		$fac4wp_default_options = [];
	}

	$return_options = array_merge( $fac4wp_default_options, $stored_options );

	// fathom analytics options.
	$fac_fathom_options = [
		FAC_OPTION_SITE_ID               => fac_fathom_get_site_id(),
		'fac_fathom_analytics_is_active' => fac_fathom_analytics_is_active(),
	];
	$return_options     = array_merge( $return_options, $fac_fathom_options );

	return apply_filters( 'fac4wp_global_reload_options', $return_options );
}

$fac4wp_options = fac4wp_reload_options();

// get Site ID from Fathom Analytics.
function fac_fathom_get_site_id() {
	$fac_options = (array) get_option( FAC4WP_OPTIONS );
	if ( ! empty( $fac_options[ FAC_OPTION_INSTALLED_TC ] ) ) {
		return $fac_options[ FAC_OPTION_SITE_ID ];
	} else { // If not 'installed tracking code elsewhere', get site id from FA plugin.
		return get_option( 'fathom_site_id', '' );
	}
}

// is Fathom Analytics active.
function fac_fathom_analytics_is_active() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	return is_plugin_active( 'fathom-analytics/fathom-analytics.php' );
}

// check API key.
function fac_api_key() {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $_site_id ) ) {
		return '';
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id;
	$result = fac_fathom_api( $url );

	return $result;
}

// array_map recursive.
if ( ! function_exists( 'fac_array_map_recursive' ) ) {
	function fac_array_map_recursive( $callback, $array ) {
		$func = function ( $item ) use ( &$func, &$callback ) {
			return is_array( $item ) ? array_map( $func, $item ) : call_user_func( $callback, $item );
		};

		return array_map( $func, $array );
	}
}

// get Fathom events.
function fac_get_fathom_events() {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $_site_id ) ) {
		return '';
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events';
	$result = fac_fathom_api( $url );

	return $result;
}

// get new Fathom event.
function fac_get_fathom_event( $id ) {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	$return   = [];
	if ( empty( $_site_id ) ) {
		return [];
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events/' . $id;
	$method = 'POST';
	//$body = ['id' => $id];
	$return = fac_fathom_api( $url );

	return $return;
}

// create new Fathom event.
function fac_create_fathom_event( $name ) {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	$return   = [];
	if ( empty( $_site_id ) ) {
		return [];
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events';
	$method = 'POST';
	$body   = [ 'name' => $name ];
	$return = fac_save_fathom_api( $url, $body );

	return $return;
}

/**
 * Add new fathom event
 *
 * @param string $name Event name.
 */
function fac_add_new_fathom_event( $name ) {
	$event_id = '';
	if ( empty( $name ) ) {
		return $event_id;
	}
	$new_event = fac_create_fathom_event( $name );
	if ( isset( $new_event['error'] ) && empty( $new_event['error'] ) ) {
		$event_body = $new_event['body'];
		if ( fac_is_json( $event_body ) ) {
			$event_body = json_decode( $event_body );
			$event_id   = isset( $event_body->id ) ? $event_body->id : '';
		}
	}

	return $event_id;
}


// update Fathom event name.
function fac_update_fathom_event( $event_id, $name ) {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	$return   = [];
	if ( empty( $_site_id ) || empty( $event_id ) || empty( $name ) ) {
		return [];
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events/' . $event_id;
	$method = 'POST';
	$body   = [ 'name' => $name ];
	$return = fac_save_fathom_api( $url, $body );

	return $return;
}

// get Fathom API.
function fac_fathom_api( $url = '' ) {
	global $fac4wp_options;
	$return   = [];
	$_api_key = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $url ) || empty( $_site_id ) || empty( $_api_key ) ) {
		return $return;
	}
	$wp_request_headers = [
		'Authorization' => 'Bearer ' . $_api_key,
	];
	$request_args       = [
		'headers' => $wp_request_headers,
	];
	$response           = wp_remote_get( $url, $request_args );
	if ( ! is_wp_error( $response ) ) {
		$response_code     = wp_remote_retrieve_response_code( $response );
		$response_message  = wp_remote_retrieve_response_message( $response );
		$result            = wp_remote_retrieve_body( $response );
		$return['code']    = $response_code;
		$return['message'] = $response_message;
		$return['body']    = $result;
		$error_msg         = '';
		if ( $response_code !== 200 ) {
			if ( ! empty( $result ) ) {
				$result = json_decode( $result, TRUE );
				if ( isset( $result['error'] ) && ! empty( $result['error'] ) ) {
					$error_msg = $result['error'];
				}
			}
		} else {
			if ( strpos( $result, '<!DOCTYPE ' ) !== FALSE ) {
				$error_msg      = __( 'ERROR: The API Key you have entered does not have access to this site.', 'fathom-analytics-conversions' );
				$return['body'] = 'html';
			}
		}
		$return['error'] = $error_msg;
	}

	return $return;
}

// get Fathom API.
function fac_save_fathom_api( $url = '', $body = '' ) {
	global $fac4wp_options;
	$return   = [];
	$_api_key = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $url ) || empty( $_site_id ) || empty( $_api_key ) ) {
		return $return;
	}
	$wp_request_headers = [
		'Authorization' => 'Bearer ' . $_api_key,
	];
	$request_args       = [
		'headers' => $wp_request_headers,
		'body'    => $body,
	];
	$response           = wp_remote_post( $url, $request_args );
	if ( ! is_wp_error( $response ) ) {
		$response_code     = wp_remote_retrieve_response_code( $response );
		$response_message  = wp_remote_retrieve_response_message( $response );
		$result            = wp_remote_retrieve_body( $response );
		$return['code']    = $response_code;
		$return['message'] = $response_message;
		$return['body']    = $result;
		$error_msg         = '';
		if ( $response_code !== 200 ) {
			if ( ! empty( $result ) ) {
				$result = json_decode( $result, TRUE );
				if ( isset( $result['error'] ) && ! empty( $result['error'] ) ) {
					$error_msg = $result['error'];
				}
			}
		} else {
			if ( strpos( $result, '<!DOCTYPE ' ) !== FALSE ) {
				$error_msg      = __( 'ERROR: The API Key you have entered does not have access to this site.', 'fathom-analytics-conversions' );
				$return['body'] = 'html';
			}
		}
		$return['error'] = $error_msg;
		//echo '<pre>';print_r($result);echo '</pre>';
	}

	return $return;
}

// check if a string is JSON format.
function fac_is_json( $string ) {
	json_decode( $string );

	return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Check if Fathom Analytics is active.
 */
if ( ! function_exists( 'is_fac_fathom_analytic_active' ) ) {
	function is_fac_fathom_analytic_active() {
		global $fac4wp_options;
		if ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) {
			return TRUE;
		}

		return FALSE;
	}
}
