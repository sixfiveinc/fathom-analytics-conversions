<?php

/**
 * The standard WP login/registration-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.1.1
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
class Fathom_Analytics_Conversions_Classes_IDs {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.1.1
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

		// Add default option.
		add_filter( 'fac4wp_global_reload_options', [
			$this,
			'fac4wp_global_reload_options_classes_ids',
		] );

		// Add the settings field.
		add_action( 'fac_settings_field_before_submit_button', [
			$this,
			'fac_settings_field_classes_ids',
		] );

		// Save the data.
		add_action( 'admin_init', [
			$this,
			'fac_settings_field_save_classes_ids',
		] );

		// Check to add event id to new form.
		add_action( 'wp_footer', [ $this, 'fac_classes_ids_footer_script' ] );

	}

	public function fac4wp_global_reload_options_classes_ids( $default_options ) {
		$default_options['classes_ids'] = [];

		return $default_options;
	}

	/**
	 * Maybe add the Fathom JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_settings_field_classes_ids() {

		$optionsName = 'fac4wp-options';
		$options     = get_option( 'fac4wp-options', [] );
		//echo '<pre>';print_r( $options );echo '</pre>';
		$settings = isset( $options['classes_ids'] ) ? $options['classes_ids'] : [];
		$counter  = 0;
		if ( is_array( $settings ) ) {
			$counter = count( $settings );
		}
		?>
        <table class="form-table">
            <tbody>
            <tr>
                <th><?php echo 'Classes/IDs'; ?></th>
                <td>
                    <table>
                        <thead>
                        <th>Event Name</th>
                        <th>Link Class</th>
                        <th>Value</th>
                        <th></th>
                        </thead>
                        <tbody>
						<?php
						if ( is_array( $settings ) && count( $settings ) > 0 ) {
							$i = 0;
							foreach ( $settings as $k => $row ) {
								$required = '';
								if ( ! empty( $row['name'] ) ) {
									$required = 'required';
								}
								echo '<tr class="table_row">';
								echo '<td>';
								echo '<input type="text"
                                           name="fac4wp-options[classes_ids][' . $i . '][name]" value="' . $row['name'] . '"
                                           class="classes_ids_name" ' . $required . '></td>';
								echo '<td class="classes_ids_class_td">
                                    <input type="text" name="fac4wp-options[classes_ids][' . $i . '][class]" value="' . $row['class'] . '"
                                           id="course_' . $i . '"
                                           class="classes_ids_class" ' . $required . '>';
								echo '</td>';
								echo '<td>';
								echo '<input type="number"
                                           name="fac4wp-options[classes_ids][' . $i . '][value]"
                                           value="' . $row['value'] . '"
                                           class="classes_ids_value">
                                </td>
                                <td>
                                    <a href="#" class="removeRow">Remove</a>
                                </td>
                            </tr>';
								$i ++;
							}
						} else {
							?>
                            <tr class="table_row">
                                <td>
                                    <input type="text"
                                           name="<?php echo $optionsName; ?>[classes_ids][0][name]"
                                           class="classes_ids_name"></td>
                                <td class="classes_ids_class_td">
                                    <input type="text"
                                           name="<?php echo $optionsName; ?>[classes_ids][0][class]"
                                           id="class_0"
                                           class="classes_ids_class">
                                </td>
                                <td>
                                    <input type="number"
                                           name="<?php echo $optionsName; ?>[classes_ids][0][value]"
                                           value=""
                                           class="classes_ids_value">
                                </td>
                                <td>
                                    <a href="#" class="removeRow">Remove</a>
                                </td>
                            </tr>
							<?php
						}
						?>
                        </tbody>
                    </table>
					<?php
					echo '<button name="" value="" class="addNewRow" data-counter="' . ( $counter + 1 ) . '">New Row</button>';
					?>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	// Save.
	public function fac_settings_field_save_classes_ids() {
		if ( ! current_user_can( 'manage_options' ) && ( ! wp_doing_ajax() ) ) {
			return;
		}

		if ( isset( $_POST['fac4wp-options'] ) && isset( $_POST['fac4wp-options']['classes_ids'] ) ) {

			$submittedData = (array) $_POST['fac4wp-options'];
			$submittedData = $this->fac4wp_sanitize_options( $submittedData );
			update_option( 'fac4wp-options', $submittedData );
		}
	}

	// Sanitize.
	public function fac4wp_sanitize_options( $options ) {
		$output = fac4wp_reload_options();
		//echo '<pre>';print_r( $output );echo '</pre>';
		foreach ( $output as $option_name => $option_value ) {
			if ( isset( $options[ $option_name ] ) ) {
				$new_option_value = $options[ $option_name ];
			} else {
				$new_option_value = '';
			}
			// site ID
			if ( $option_name === FAC_OPTION_SITE_ID ) {

				if ( empty( $output[ FAC_OPTION_INSTALLED_TC ] ) ) {
					unset( $output[ $option_name ] );
				} else {
					$output[ $option_name ] = $new_option_value;
				}
			} elseif ( substr( $option_name, 0, 10 ) == 'integrate-' ) {
				$output[ $option_name ] = (bool) $new_option_value;

				// anything else
			} else {
				switch ( gettype( $option_value ) ) {
					case 'boolean':
					{
						$output[ $option_name ] = (bool) $new_option_value;

						break;
					}

					case 'integer':
					{
						$output[ $option_name ] = (int) $new_option_value;

						break;
					}

					case 'array':
					{
						foreach ( $new_option_value as $v ) {
							if ( is_array( $v ) ) {
								$sv = [];
								foreach ( $v as $_k => $_v ) {
									$sv[ $_k ] = sanitize_text_field( $_v );
								}
								$output[ $option_name ][] = $sv;
							} else {
								$output[ $option_name ][] = sanitize_text_field( $v );
							}
						}
						//$output[ $option_name ] = (int) $new_option_value;

						break;
					}

					default:
					{
						$output[ $option_name ] = sanitize_text_field( $new_option_value );
					}
				}
			}

		}

		return $output;
	}

	/**
	 * JavaScript
	 *
	 * @since    1.0.9
	 */
	public function fac_classes_ids_footer_script() {
		//global $fac4wp_options;
		if ( fac_fathom_analytics_is_active() && ! fac_fathom_is_excluded_from_tracking() ) {
			// Admin settings.
			$ad_Options  = get_option( 'fac4wp-options', [] );
			$classes_ids = isset( $ad_Options['classes_ids'] ) ? $ad_Options['classes_ids'] : [];
			if ( is_array( $classes_ids ) && count( $classes_ids ) > 0 ) {
				$track_event = [];
				foreach ( $classes_ids as $row ) {

					$name      = trim( $row['name'] );
					$classes   = trim( $row['class'] );
					$value     = trim( $row['value'] );
					$value     = (int) $value * 100;
					$a_classes = explode( ',', $classes );
					foreach ( $a_classes as $class ) {
						if ( ! empty( $class ) ) {
							$track_event[] = [
								trim( $class ),
								$name,
								$value,
							];
						}
					}
				}

				if ( count( $track_event ) > 0 ) {
					$fac_content = '<script id="fac-classes-ids" data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
					$fac_content .= 'window.addEventListener("load", (event) => {' . "\n\t";
					foreach ( $track_event as $k => $event ) {
                        if( empty( $event ) ) {
                            continue;
                        }
						if ( strpos( $event[0], '#' ) === 0 ) {
							$id          = substr( $event[0], 1 );
							$fac_content .= 'const id_' . $k . ' = document.getElementById("' . $id . '");' . "\n\t";
							$fac_content .= 'if( id_' . $k . ' ) {' . "\n\t\t";
							$fac_content .= 'id_' . $k . '.addEventListener("click", () => {';
							$fac_content .= '
            fathom.trackEvent("' . $event[1] . '", {_value: ' . $event[2] . '});';
							$fac_content .= '
	    });' . "\n\t";
							$fac_content .= '}' . "\n\t";
						} else {
							$fac_content .= 'document.querySelectorAll("' . $event[0] . '").forEach(item => {';
							$fac_content .= '
        item.addEventListener("click", event => {';
							$fac_content .= '
            fathom.trackEvent("' . $event[1] . '", {_value: ' . $event[2] . '});';
							$fac_content .= '
	    });';
							$fac_content .= '
	});' . "\n\t";
						}
					}
					$fac_content .= '});';
					$fac_content .= '</script><!-- END Fathom Analytics Conversions -->';
					echo $fac_content;
				}
			}
		}
	}

}
