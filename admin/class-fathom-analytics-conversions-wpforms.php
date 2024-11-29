<?php

/**
 * The WPForms-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpforms
 */

/**
 * The wpforms-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the wpforms-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpforms
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_WPForms {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Add custom form attribute - form title.
		add_filter( 'wpforms_frontend_form_atts', [
			$this,
			'fac_wpforms_frontend_form_atts',
		], 10, 2 );

		// Add js to track the form submission.
		//add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'enqueue_scripts' ] );

	}

	/**
	 * Add custom form attribute - form title.
	 *
	 * @param array $atts Array of attributes.
	 *
	 * @since    1.2
	 */
	public function fac_wpforms_frontend_form_atts( $form_atts, $form_data ) {
		$form_title = isset( $form_data['settings'] ) && isset( $form_data['settings']['form_title'] ) ? $form_data['settings']['form_title'] : '';

		$form_atts['data']['form-name'] = $form_title;

		return $form_atts;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options;

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] && is_fac_fathom_analytic_active() ) {
			if ( ! fac_fathom_is_excluded_from_tracking() ) { // Track visits by administrators!
				$fac_content = '<script id="fac-wpforms" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				$fac_content .= 'window.addEventListener("load", (event) => {' . "\n\t";
				$fac_content .= 'var elementsArray = document.querySelectorAll(\'[id^="wpforms-form-"]\');
    elementsArray.forEach(function(elem) {
        if( elem.tagName === "FORM") {
            elem.addEventListener("submit", function (e) {
                var wpFormsId = e.target.dataset.formid,
                    wpFormsTitle = e.target.dataset.formName,
	                event_name = wpFormsTitle + " [" + wpFormsId + "]";
	                //console.log(event_name);
                fathom.trackEvent(event_name);
            });
        }
    });';
				$fac_content .= '});';
				$fac_content .= '</script>';
				echo $fac_content;
			}
		}
	}

}
