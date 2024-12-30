<?php

/**
 * The fluentform-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.3
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/fluentform
 */

/**
 * The fluentform-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the fluentform-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/fluentform
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_Fluent_Form {

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

		// Add form attribute.
		add_filter( 'fluent_form_html_attributes', [ $this, 'fac_fluent_form_html_attributes', ], 10, 2 );

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
	public function fac_fluent_form_html_attributes( $atts, $form ) {
		$atts['data-form_name'] = $form->title;

		return $atts;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] && is_fac_fathom_analytic_active() ) {
			if ( ! fac_fathom_is_excluded_from_tracking() ) { // Track visits by administrators!

				$fac_content = '<script id="fac-fluent-form" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				//$fac_content .= 'const gf_d = '.wp_json_encode($gforms_data).';console.log(gf_d);';
				$fac_content .= 'jQuery(document).on("fluentform_submission_success", function (e, form) {
    const this_form = form.form,
        form_id = form.config.id,
        form_id_selector = form.config.form_id_selector;
    var f = document.getElementById(form_id_selector),
        form_name = f.dataset.form_name;
    fathom.trackEvent(form_name + " ["+form_id+"]");
});';
				$fac_content .= '</script>';

				echo $fac_content;
			}
		}
	}

}
