<?php

/**
 * The standard WP login/registration-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0.9
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wp-login-registration
 */

/**
 * The woocommerce-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the woocommerce-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wp-login-registration
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_WP {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.0.9
	 * @access   private
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 * @since    1.0.9
	 * @access   private
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.9
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Maybe add fathom analytics script.
		add_action( 'login_footer', [ $this, 'fac_maybe_add_fathom_script' ] );

		// Login form.
		add_action( 'login_footer', [ $this, 'fac_login_footer' ] );

		// Registration form.
		add_action( 'login_footer', [ $this, 'fac_registration_footer' ] );

		// Lost password form.
		add_action( 'login_footer', [ $this, 'fac_lost_password_footer' ] );

	}

	/**
	 * Maybe add the Fathom JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_maybe_add_fathom_script() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-login'] || $fac4wp_options['integrate-wp-registration'] || $fac4wp_options['integrate-wp-lost-password'] ) {
			if ( $fac4wp_options['fac_fathom_analytics_is_active'] && function_exists( 'fathom_enqueue_js_snippet' ) ) {
				fathom_enqueue_js_snippet();
			}
		}
	}

	/**
	 * Add the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_login_footer() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-login'] ) {
			$event_title = apply_filters( 'fac_login_event_title', __( 'WP Login', 'fathom-analytics-conversions' ) );

			$fac_content = '<script id="fac-login-form" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
			$fac_content .= '
	window.addEventListener("load", (event) => {
		const login_form = document.getElementById("loginform");
		if( login_form ) {
			login_form.addEventListener("submit", () => {
                fathom.trackEvent("' . $event_title . '");
            });
        }
	});';
			$fac_content .= '</script>';

			echo $fac_content;
		}
	}

	/**
	 * Add the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_registration_footer() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-registration'] ) {
			$event_title = apply_filters( 'fac_registration_event_title', __( 'WP Registration', 'fathom-analytics-conversions' ) );

			$fac_content = '<script id="fac-registration-form" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
			$fac_content .= '
	window.addEventListener("load", (event) => {
		const register_form = document.getElementById("registerform");
		if(register_form) {
			register_form.addEventListener("submit", () => {
                fathom.trackEvent("' . $event_title . '");
            });
        }
	});';
			$fac_content .= '</script>';

			echo $fac_content;
		}
	}

	/**
	 * Add the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_lost_password_footer() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-lost-password'] ) {
			$event_title = apply_filters( 'fac_lost_password_event_title', __( 'WP Lost Password', 'fathom-analytics-conversions' ) );

			$fac_content = '<script id="fac-lost-password-form" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
			$fac_content .= '
	window.addEventListener("load", (event) => {
		const lost_password_form = document.getElementById("lostpasswordform");
		if(lost_password_form) {
			lost_password_form.addEventListener("submit", () => {
                fathom.trackEvent("' . $event_title . '");
            });
        }
	});';
			$fac_content .= '</script>';

			echo $fac_content;
		}
	}

}
