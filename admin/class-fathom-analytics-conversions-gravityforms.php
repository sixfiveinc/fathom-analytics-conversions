<?php
/**
 * The GravityForms-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.1.3
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/gravityforms
 */

/**
 * The gravityforms-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the gravityforms-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/gravityforms
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_GravityForms {

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

		// Initialize whether Ajax is on or off.
		add_filter( 'gform_form_args', [ $this, 'fac_gform_ajax_only' ], 15 );

		// Add custom form attribute - form title.
		add_filter( 'gform_form_tag', [ $this, 'fac_gform_form_tag' ], 10, 2 );

		add_filter( 'gform_confirmation', [
			$this,
			'fac_gform_confirmation',
		], 100, 2 );

		// Add custom form element - form title.
		add_filter( 'gform_form_after_open', [
			$this,
			'fac_gform_form_after_open',
		], 10, 2 );

		// Add js to track the form submission.
		//add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'enqueue_scripts' ] );

	}

	/**
	 * Sets all forms to Ajax only.
	 *
	 * @param array $form_args The form arguments.
	 *
	 * @since 1.0.0
	 *
	 */
	public function fac_gform_ajax_only( $form_args ) {
		global $fac4wp_options;

		if ( class_exists( 'GFCommon' ) && GFCommon::is_preview() ) {
			return $form_args;
		}

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] && is_fac_fathom_analytic_active() ) {
			$form_args['ajax'] = TRUE;
		}

		return $form_args;
	}

	// Add custom form element - form title.
	public function fac_gform_confirmation( $confirmation, $form ) {
		//echo '<pre>';print_r($confirmation);echo '</pre>';
		//echo '<div id="gform_name_' . $form['id'] . '" data-form-name="' . esc_html( $form['title'] ) . '"></div>';
		if ( is_array( $confirmation ) && ! empty( $confirmation['redirect'] ) ) {
			$confirmation['redirect'] = add_query_arg( [ 'fac_gf' => $form['title'] . ' [' . $form['id'] . ']' ], $confirmation['redirect'] );
		} elseif ( is_string( $confirmation ) ) {
			$confirmation .= '<div id="gform_name_' . $form['id'] . '" data-form-name="' . esc_html( $form['title'] ) . '"></div>';
		}

		return $confirmation;
	}

	// Add custom form element - form title.
	public function fac_gform_form_after_open( $html, $form ) {
		$html .= '<div id="gform_name_' . $form['id'] . '" data-form-name="' . esc_html( $form['title'] ) . '"></div>';

		return $html;
	}

	/**
	 * Add custom form attribute - form title.
	 */
	public function fac_gform_form_tag( $form_tag, $form ) {
		$form_tag = str_replace( '>', ' data-form-name="' . $form['title'] . '">', $form_tag );

		return $form_tag;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] && is_fac_fathom_analytic_active() ) {
			if ( ! fac_fathom_is_excluded_from_tracking() ) { // Track visits by administrators!

				$fac_content = '<script id="fac-gravity-forms" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				$fac_content .= 'jQuery(document).on("gform_confirmation_loaded", function(e, formId, confirmationMessage) {
    var f = document.getElementById("gform_name_"+formId);
    if( f ) {
        var form_name = f.dataset.formName;
        fathom.trackEvent(form_name + " ["+formId+"]");
    }
});';
				$fac_content .= "\nfunction facGfGetUrlParameter(name) {
	name = name.replace(/[\[\]]/g, '\\$&');
	const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
	const results = regex.exec(window.location.href);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}
window.addEventListener('load', (event) => {
	const facGfValue = facGfGetUrlParameter('fac_gf');
	if (facGfValue) {
		fathom.trackEvent(facGfValue);
	}
});";
				$fac_content .= '</script>';

				echo $fac_content;
			}
		}

	}

}
