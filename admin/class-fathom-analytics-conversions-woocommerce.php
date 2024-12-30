<?php

/**
 * The woocommerce-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0.9
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/woocommerce
 */

/**
 * The woocommerce-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the woocommerce-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/woocommerce
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_Woocommerce {

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

		if ( function_exists( 'WC' ) ) {
			$GLOBALS['gtm4wp_is_woocommerce3']   = version_compare( WC()->version, '3.0', '>=' );
			$GLOBALS['gtm4wp_is_woocommerce3_7'] = version_compare( WC()->version, '3.7', '>=' );
		} else {
			$GLOBALS['gtm4wp_is_woocommerce3']   = FALSE;
			$GLOBALS['gtm4wp_is_woocommerce3_7'] = FALSE;
		}

		// Check to add event id to new form.
		add_action( 'wp_footer', array( $this, 'fac_woo_footer_script' ) );
		//add_action( 'wp_enqueue_scripts', [ $this, 'fac_woo_script' ] );

	}

	/**
	 * JavaScript
	 *
	 * @since    1.0.9
	 */
	public function fac_woo_footer_script() {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE ] && is_fac_fathom_analytic_active() ) {
			if ( ! fac_fathom_is_excluded_from_tracking() ) { // Track visits by administrators!
				//$woo = WC();

				$fac_content = '<script id="fac-woocommerce" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				if ( is_order_received_page() ) {
					$event_title = apply_filters( 'fac_woocommerce_order_title', __( 'WooCommerce Order', 'fathom-analytics-conversions' ) );

					$fac_is_woocommerce3 = version_compare( WC()->version, '3.0', '>=' );

					$order_id          = empty( $_GET['order'] ) ? ( $GLOBALS['wp']->query_vars['order-received'] ? $GLOBALS['wp']->query_vars['order-received'] : 0 ) : absint( $_GET['order'] );
					$order_id_filtered = apply_filters( 'woocommerce_thankyou_order_id', $order_id );
					if ( '' != $order_id_filtered ) {
						$order_id = $order_id_filtered;
					}

					$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

					if ( $order_id > 0 ) {
						$order = wc_get_order( $order_id );

						if ( $order instanceof WC_Order ) {
							if ( $fac_is_woocommerce3 ) {
								$this_order_key = $order->get_order_key();
							} else {
								$this_order_key = $order->order_key;
							}

							if ( $this_order_key != $order_key ) {
								unset( $order );
							}
						} else {
							unset( $order );
						}
					}

					if ( isset ( $order ) ) {
						$order_total = esc_js( $order->get_total() );
						$order_total *= 100;
						$fac_content .= 'window.addEventListener("load", (event) => {
        fathom.trackEvent("' . $event_title . '", {_value: ' . $order_total . '});
	});';
					}
				}

				$fac_content .= 'window.addEventListener("load", (event) => {
  const addToCartButtons = document.querySelectorAll(".add-to-cart-button");
  addToCartButtons.forEach(button => {
    button.addEventListener("click", (clickEvent) => {
      const eventName = `Add to cart`;
      fathom.trackEvent(eventName);
    });
  });
  const addToCartBtn = document.querySelectorAll("button[name=add-to-cart]");
  addToCartBtn.forEach(button => {
    button.addEventListener("click", (clickEvent) => {
      const eventName = `Add to cart`;
      fathom.trackEvent(eventName);
    });
  });
});';

				$fac_content .= '</script>';

				echo $fac_content;
			}
		}
	}

}
