<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/public
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of the plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fathom_Analytics_Conversions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fathom_Analytics_Conversions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fathom-analytics-conversions-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fathom_Analytics_Conversions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fathom_Analytics_Conversions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fathom-analytics-conversions-public.js', array( 'jquery' ), $this->version, false );

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPCF7 ] && $fac4wp_options['fac_fathom_analytics_is_active'] ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!

				$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_WPCF7, true );
				wp_enqueue_script( 'fac-contact-form-7-tracker', $fac4wp_plugin_url . 'public/js/fac-contact-form-7-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
			}
		}

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] && $fac4wp_options['fac_fathom_analytics_is_active'] ) {
			if ( !( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!

				$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_WPFORMS, true );
				wp_enqueue_script( 'fac-wpforms-tracker', $fac4wp_plugin_url . 'public/js/fac-wpforms-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
			}
		}
	}

	// add event id to hidden form field
	public function fac_cf7_hidden_fields( $hidden_fields ) {
		if ( function_exists( 'wpcf7_get_current_contact_form' ) ) {
			$form = wpcf7_get_current_contact_form();
			$form_id = $form->id();
			$fac_cf7 = get_option( 'fac_cf7_'.$form_id, [] );
			$fac_cf7_event_id = isset($fac_cf7['event_id']) ? $fac_cf7['event_id'] : '';
			$hidden_fields['fac_cf7_event_id'] = $fac_cf7_event_id;
		}
		return $hidden_fields;
	}

    // add event id to hidden form field
	public function fac_wpforms_display_submit_before($form_data) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] ) {
			$settings = $form_data['settings'];
			$fac_wpforms_event_id = isset($settings['fac_wpforms_event_id']) ? $settings['fac_wpforms_event_id'] : '';
			//echo '<pre>';print_r($settings);echo '</pre>';
			echo '<input type="hidden" name="wpforms[fac_event_id]" value="' . esc_attr( $fac_wpforms_event_id ) . '">';
		}
	}

}
