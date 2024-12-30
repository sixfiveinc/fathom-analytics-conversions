<?php

/**
 * The ninja-forms-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.3
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/ninja-forms
 */

/**
 * The ninja-forms-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the ninja-forms-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/ninja-forms
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_Ninja_Forms {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.3
	 * @access   private
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 * @since    1.3
	 * @access   private
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.3
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Add js to track the form submission.
		//add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'enqueue_scripts' ] );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_NINJAFORMS ] && is_fac_fathom_analytic_active() ) {

			if ( ! fac_fathom_is_excluded_from_tracking() ) { // track visits by administrators!

				$fac_content = '<script id="fac-ninja-forms" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				$fac_content .= 'jQuery(document).ready(function () {
    jQuery(document).on("nfFormSubmitResponse", function (e, response, id) {
        if (response.response && response.response.data && response.response.data.settings && response.response.data.settings.title) {
	        let form_id = response.id,
	            form_title = response.response.data.settings.title;
            fathom.trackEvent(form_title+" ["+form_id+"]");
        }
    });
});';
				$fac_content .= '</script>';

				echo $fac_content;
			}
		}
	}

}
