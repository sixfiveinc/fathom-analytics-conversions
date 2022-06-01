<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/admin
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fathom-analytics-conversions-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fathom-analytics-conversions-admin.js', array( 'jquery' ), $this->version, false );

	}

    // admin output section
    public function fac4wp_admin_output_section( $args ) {
        echo '<span class="tabinfo">';

        switch ( $args['id'] ) {

            case FAC4WP_ADMIN_GROUP_INTEGRATION: {
                //_e( 'Fathom Analytics Conversions can integrate with several popular plugins. Please check the plugins you would like to integrate with:', 'fathom-analytics-conversions' );

                break;
            }
        }

        echo '</span>';
    }

	// admin output field
	public function fac4wp_admin_output_field($args) {
		global $fac4wp_options;
		$_site_id   = $fac4wp_options[ FAC_OPTION_SITE_ID ];

		switch ( $args['label_for'] ) {
			case FAC4WP_ADMIN_GROUP_API_KEY: {
				$_api_key   = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
				$_input_readonly = '';

				echo '<input type="text" id="' . esc_attr(FAC4WP_OPTIONS . '[' . FAC4WP_OPTION_API_KEY_CODE . ']').'" name="' . esc_attr(FAC4WP_OPTIONS . '[' . FAC4WP_OPTION_API_KEY_CODE . ']').'" value="' . esc_attr($_api_key) . '" ' . esc_html($_input_readonly) . ' class="regular-text" />';
				$result = fac_api_key();
				//echo '<pre>';print_r($result);echo '</pre>';
				if ( isset( $result['code'] ) && $result['code'] === 200 ) {
					$body = isset( $result['body'] ) ? json_decode( $result['body'], true ) : array();
					//echo '<pre>';print_r($body);echo '</pre>';
					$r_site_id = isset( $body['id'] ) ? $body['id'] : '';
					$r_site_name = isset( $body['name'] ) ? $body['name'] : '';
					$site_name = get_site_url();
					$site_name = preg_replace('#^https?://#i', '', $site_name);
					if( $_site_id !== $r_site_id || $r_site_name !== $site_name ) $result['error'] = 'ERROR: The API Key you have entered does not have access to this site.';
					else {
						echo '<span class="fac_connected">';
						echo esc_html( __( 'Connected', 'fathom-analytics-conversions' ) );
						echo '</span>';
					}
				}
				echo '<br>';
				echo wp_kses($args['description'],
					array(
						'a' => array(
							'href' => true,
							'target' => true,
							'rel' => true,
						),
					));

				//if(get_current_user_id() === 2) {
				if( isset( $result['error'] ) && ! empty( $result['error'] ) ) {
					echo '<p class="fac_error">' . esc_html( $result['error'] ) . '</p>';
				}
				else {
					// check cf7 forms.
					fac_check_cf7_forms();
					fac_check_wpforms_forms();
				}
				//}

				break;
			}

			case FAC4WP_ADMIN_GROUP_SITE_ID: {
				$_input_readonly = ' readonly="readonly"';

				echo '<input type="text" id="' . esc_attr(FAC4WP_OPTIONS . '[' . FAC_OPTION_SITE_ID . ']').'" name="' . esc_attr(FAC4WP_OPTIONS . '[' . FAC_OPTION_SITE_ID . ']').'" value="' . esc_attr($_site_id) . '" ' . esc_html($_input_readonly) . ' class="regular-text" /><br />' . esc_html($args['description']);
				if(empty($_site_id)) {
					echo '<p class="fac_error">'.
						sprintf(
							wp_kses(
								__('Please enter site ID on <a href="%s" target="_blank" rel="noopener">Fathom Analytics settings page</a>.', 'fathom-analytics-conversions'),
								array(
									'a' => array(
										'href' => true,
										'target' => true,
										'rel' => true,
									),
								)
							),
							'?page=fathom-analytics'
						)
						.'</p>';
				}

				break;
			}

			default: {
                $opt_val = $fac4wp_options[ $args['option_field_id'] ];

                switch ( gettype( $opt_val ) ) {
                    case 'boolean': {
                        echo '<input type="checkbox" id="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" name="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" value="1" ' . checked( 1, $opt_val, false ) . ' /><br />' . esc_html($args['description']);

                        if ( isset( $args['plugin_to_check'] ) && ( $args['plugin_to_check'] != '' ) ) {
							$is_plugin_active = 0;
                            if ( is_array( $args['plugin_to_check'] ) ) {
								foreach ( $args['plugin_to_check'] as $plugin ) {
                                    if ( is_plugin_active( $plugin ) ) {
                                        $is_plugin_active = 1;
                                    }
                                }
                            }
                            elseif ( is_plugin_active( $args['plugin_to_check'] ) ) {
                                $is_plugin_active = 1;
							}
                            if ( $is_plugin_active ) {
                                echo '<br />';
                                echo wp_kses(
                                    __( 'This plugin is <strong class="fac4wp-plugin-active">active</strong>, it is strongly recommended to enable this integration!', 'fathom-analytics-conversions' ),
                                    [
                                        'strong' => [
                                            'class' => true,
                                        ],
                                    ]
                                );
                            } else {
                                echo '<br />';
                                echo sprintf(
                                    wp_kses(
                                        __( 'This plugin (%s) is <strong class="fac4wp-plugin-not-active">not active</strong>, enabling this integration could cause issues on frontend!', 'fathom-analytics-conversions' ),
                                        [
                                            'strong' => [
                                                'class' => true,
                                            ],
                                        ]
                                    ),
                                    is_array( $args['plugin_to_check'] ) ? implode( ' or ', $args['plugin_to_check'] ) : $args['plugin_to_check']
                                );
                            }
                        }

                        break;
                    }

                    default: {
                        echo '<input type="text" id="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" name="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" value="' . esc_attr( $opt_val ) . '" size="80" /><br />' . esc_html($args['description']);

                        if ( isset( $args['plugin_to_check'] ) && ( $args['plugin_to_check'] != '' ) ) {
                            if ( is_plugin_active( $args['plugin_to_check'] ) ) {
                                echo '<br />';
                                echo wp_kses(
                                    __( 'This plugin is <strong class="fac4wp-plugin-active">active</strong>, it is strongly recommended to enable this integration!', 'fathom-analytics-conversions' ),
                                    [
                                         'strong' => [
                                              'class' => true,
                                         ],
                                    ]
                                );
                            } else {
                                echo '<br />';
                                echo wp_kses(
                                    __( 'This plugin is <strong class="fac4wp-plugin-not-active">not active</strong>, enabling this integration could cause issues on frontend!', 'fathom-analytics-conversions' ),
                                    [
                                        'strong' => [
                                            'class' => true,
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    public function fac4wp_sanitize_options($options) {
        $output = fac4wp_reload_options();

        foreach ( $output as $optionname => $optionvalue ) {
            if ( isset( $options[ $optionname ] ) ) {
                $newoptionvalue = $options[ $optionname ];
            } else {
                $newoptionvalue = '';
            }

            // site ID
            if ( $optionname === FAC_OPTION_SITE_ID ) {
                //$output[ $optionname ] = '';
                unset($output[ $optionname ]);
            // integrations
            } elseif ( substr( $optionname, 0, 10 ) == 'integrate-' ) {
                $output[ $optionname ] = (bool) $newoptionvalue;

            // anything else
            } else {
                switch ( gettype( $optionvalue ) ) {
                    case 'boolean': {
                        $output[ $optionname ] = (bool) $newoptionvalue;

                        break;
                    }

                    case 'integer': {
                        $output[ $optionname ] = (int) $newoptionvalue;

                        break;
                    }

                    default: {
                        $output[ $optionname ] = $newoptionvalue;
                    }
                }
            }

        }

        return $output;
    }

    // admin settings sections and fields
    public function fac4wp_admin_init() {
        $GLOBALS['fac4wp_integrate_field_texts'] = array(
            FAC4WP_OPTION_INTEGRATE_WPCF7                 => array(
                'label'         => __( 'Contact Form 7', 'fathom-analytics-conversions' ),
                'description'   => __( 'Check this to add conversation a successful form submission.', 'fathom-analytics-conversions' ),
                'phase'         => FAC4WP_PHASE_STABLE,
                'plugin_to_check' => 'contact-form-7/wp-contact-form-7.php',
            ),
            FAC4WP_OPTION_INTEGRATE_WPFORMS                 => array(
                'label'         => __( 'WPForms', 'fathom-analytics-conversions' ),
                'description'   => __( 'Check this to add conversation a successful form submission.', 'fathom-analytics-conversions' ),
                'phase'         => FAC4WP_PHASE_STABLE,
                'plugin_to_check' => ['wpforms/wpforms.php', 'wpforms-lite/wpforms.php'],
            ),
        );
        global $fac4wp_integrate_field_texts;

        register_setting( FAC4WP_ADMIN_GROUP, FAC4WP_OPTIONS, [
                'sanitize_callback' => [$this, 'fac4wp_sanitize_options'],
            ]
        );

        add_settings_section(
            FAC4WP_ADMIN_GROUP_GENERAL,
            __( 'General', 'fathom-analytics-conversions' ),
            [$this, 'fac4wp_admin_output_section'],
            FAC4WP_ADMINSLUG
        );

        add_settings_field(
            FAC4WP_ADMIN_GROUP_API_KEY,
            __( 'API Key', 'fathom-analytics-conversions' ),
            [$this, 'fac4wp_admin_output_field'],
            FAC4WP_ADMINSLUG,
            FAC4WP_ADMIN_GROUP_GENERAL,
            array(
                'label_for'   => FAC4WP_ADMIN_GROUP_API_KEY,
                'description' => __( 'Enter your Fathom API key here.', 'fathom-analytics-conversions' ) . ' Get API key <a href="https://app.usefathom.com/#/settings/api" target="_blank">here</a>.',
            )
        );

        add_settings_field(
            FAC4WP_ADMIN_GROUP_SITE_ID,
            __( 'Site ID', 'fathom-analytics-conversions' ),
            [$this, 'fac4wp_admin_output_field'],
            FAC4WP_ADMINSLUG,
            FAC4WP_ADMIN_GROUP_GENERAL,
            array(
                'label_for'   => FAC4WP_ADMIN_GROUP_SITE_ID,
                'description' => __( 'Site ID from Fathom Analytics.', 'fathom-analytics-conversions' ),
            )
        );

        add_settings_section(
            FAC4WP_ADMIN_GROUP_INTEGRATION,
            __( 'Integration', 'fathom-analytics-conversions' ),
            [$this, 'fac4wp_admin_output_section'],
            FAC4WP_ADMINSLUG
        );

        foreach ( $fac4wp_integrate_field_texts as $field_id => $field_data ) {
            $phase = isset( $field_data['phase'] ) ? $field_data['phase'] : FAC4WP_PHASE_STABLE;

            add_settings_field(
                'fac4wp-admin-' . $field_id . '-id',
                $field_data['label'] . '<span class="' . $phase . '"></span>',
                [$this, 'fac4wp_admin_output_field'],
                FAC4WP_ADMINSLUG,
                FAC4WP_ADMIN_GROUP_INTEGRATION,
                array(
                    'label_for'     => 'fac4wp-options[' . $field_id . ']',
                    'description'   => $field_data['description'],
                    'option_field_id' => $field_id,
                    'plugin_to_check' => isset( $field_data['plugin_to_check'] ) ? $field_data['plugin_to_check'] : '',
                )
            );
        }
    }

    /**
     * Adds a submenu page to the Settings main menu for the admin area.
     *
     * @since    1.0.0
     */
    public function fac_admin_menu() {
        add_options_page(
            __( 'Fathom Analytics Conversions settings', 'fathom-analytics-conversions' ),
            __( 'Fathom Analytics Conversions', 'fathom-analytics-conversions' ),
            'manage_options',
            FAC4WP_ADMINSLUG,
            [$this, 'fac4wp_show_admin_page'],
            11
        );

    }


    public function fac4wp_show_admin_page() {
        //global $gtp4wp_plugin_url;
        ?>
        <div class="wrap">
            <div id="fac4wp-icon" class="icon32" style="background-image: url(<?php //echo $gtp4wp_plugin_url; ?>admin/images/tag_manager-32.png);"><br /></div>
            <h2><?php _e( 'Fathom Analytics Conversions options', 'fathom-analytics-conversions' ); ?></h2>
            <form action="options.php" method="post">
                <?php settings_fields( FAC4WP_ADMIN_GROUP ); ?>
                <?php do_settings_sections( FAC4WP_ADMINSLUG ); ?>
                <?php submit_button(); ?>

            </form>
        </div>
        <?php
    }

    public function fac_admin_notices() {
        if( !file_exists(WP_PLUGIN_DIR.'/fathom-analytics/fathom-analytics.php') ) {

            $notice = '<div class="error" id="messages"><p>';
            $notice .= sprintf(
                wp_kses(
                __('<b>Fathom Analytics</b> plugin must be installed for the <b>Fathom Analytics Conversions</b> to work. <b><a href="%s" class="thickbox" title="Fathom Analytics">Install Fathom Analytics Now.</a></b>', 'fathom-analytics-conversions'),
                    array(
                        'a' => array(
                            'href' => true,
                            'class' => true,
                            'title' => true,
                        ),
                        'b' => [],
                    )
                ),
                admin_url('plugin-install.php?tab=plugin-information&plugin=fathom-analytics&from=plugins&TB_iframe=true&width=600&height=550')
            );
            $notice .= '</p></div>';
            echo wp_kses($notice,
                [
                    'div' => [
                        'class' => true,
                        'id' => true,
                    ],
                    'p' => [],
                    'a' => array(
                        'href' => true,
                        'class' => true,
                        'title' => true,
                    ),
                    'b' => [],
                ]
            );

        }
        elseif(!is_plugin_active('fathom-analytics/fathom-analytics.php')) {
            $notice = '<div class="error" id="messages"><p>';
            $notice .= wp_kses(__('<b>Please activate Fathom Analytics</b> below for the <b>Fathom Analytics Conversions</b> to work.', 'fathom-analytics-conversions'),
                array(
                    'b' => [],
                )
            );
            $notice .= '</p></div>';
            echo wp_kses($notice,
                [
                    'div' => [
                        'class' => true,
                        'id' => true,
                    ],
                    'p' => [],
                    'b' => [],
                ]
            );
        }
    }

    // add meta box to CF7 form admin
    public function fac_cf7_meta_box($panels) {
        global $fac4wp_options;
        $new_page = [];
        if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPCF7 ] ) {
        $new_page = array(
            'fac-cf7' => array(
                'title' => __( 'Fathom Analytics', 'fathom-analytics-conversions' ),
                'callback' => [$this, 'fac_cf7_box']
            )
        );
        }

        return array_merge($panels, $new_page);
    }

    public function fac_cf7_box($args) {
        $cf7_id = $args->id();
        $fac_cf7_defaults = array();
        $fac_cf7 = get_option( 'fac_cf7_'.$cf7_id, $fac_cf7_defaults );
        $fac_cf7_event_id = isset($fac_cf7['event_id']) ? $fac_cf7['event_id'] : '';
        echo '<div class="cf7-fac-box">';
        ?>
        <fieldset>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="fac_cf7_event_id">
                            <?php echo esc_html__('Event ID', 'fathom-analytics-conversions'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="fac_cf7_event_id" name="fac_cf7[event_id]" class="" value="<?php echo esc_attr($fac_cf7_event_id);?>" readonly>
                        <p>
                            <a href="https://app.tango.us/app/workflow/Creating-Events-with-Fathom-94b0b00ff9b04b548bf4910188f97902" target="_blank">
                            <?php echo esc_html__('Creating Events with Fathom', 'fathom-analytics-conversions');?>
                            </a>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <?php
        echo '</div>';
    }

    // save FAC CF7 options
    public function fac_cf7_save_options($args) {
        if ( ! empty( $_POST ) && isset( $_POST['fac_cf7'] ) ){

            $default = array () ;
            //$fac_cf7 = get_option( 'fac_cf7'.$args->id(), $default );

            $fac_cf7_val = fac_array_map_recursive( 'esc_attr', $_POST['fac_cf7'] );

            update_option( 'fac_cf7_' . $args->id(), $fac_cf7_val );
        }
    }

    // check to add/update event id to new cf7 form
    public function fac_wpcf7_after_save($args) {
        $form_id = $args->id();
        $title = wp_slash( $args->title() );

        $fac_cf7 = get_option( 'fac_cf7_'.$form_id, [] );
        $fac_cf7_event_id = isset($fac_cf7['event_id']) ? $fac_cf7['event_id'] : '';
        if(empty($fac_cf7_event_id)) {
            fa_add_event_id_to_cf7($form_id, $title);
        }
        else {
            // check if event id exist
            $event = fac_get_fathom_event($fac_cf7_event_id);
            if( $event['code'] !== 200 ) {
                fa_add_event_id_to_cf7($form_id, $title);
            }
            else fac_update_fathom_event($fac_cf7_event_id, $title);
        }

    }

    // check to add event id to new WPForms form
    public function fac_wp_insert_post_wpforms($post_ID, $post) {
        // check if is a WPForms form
        if(isset($post->post_type) && $post->post_type === 'wpforms') {
            // get form content
            $form_content = $post->post_content;

            // get form data
            if ( ! $form_content || empty( $form_content ) ) {
                $form_data = false;
            }
            else $form_data =  wp_unslash( json_decode( $form_content, true ) );

            // get form setting
            $form_settings = $form_data['settings'];
            $wpforms_event_id = isset($form_settings['fac_wpforms_event_id']) ? $form_settings['fac_wpforms_event_id'] : '';
            $title = $post->post_title;

            // add/update event id
            if(empty($wpforms_event_id)) {
                fa_add_event_id_to_wpforms($post_ID, $title);
            }
            else {
                // check if event id exist
                $event = fac_get_fathom_event($wpforms_event_id);
                if( $event['code'] !== 200 ) {
                    fa_add_event_id_to_wpforms($post_ID, $title);
                }
                else fac_update_fathom_event($wpforms_event_id, $title);
            }
        }
    }

    // add settings section to WPForms form admin
    public function fac_wpforms_builder_settings_sections($sections) {
        global $fac4wp_options;
        if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] ) {
            $sections['fac-wpforms'] = __( 'Fathom Analytics', 'fathom-analytics-conversions' );
        }

        return $sections;
    }

    // WPForms custom panel
    public function fac_wpforms_form_settings_panel_content($settings) {
        global $fac4wp_options;
        if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] ) {
            echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-fac-wpforms">';
            echo '<div class="wpforms-panel-content-section-title">';
            esc_html_e('Fathom Analytics', 'fathom-analytics-conversions');
            echo '</div>';
            if (function_exists('wpforms_panel_field')) {
                wpforms_panel_field(
                    'text',
                    'settings',
                    'fac_wpforms_event_id',
                    $settings->form_data,
                    esc_html__('Event ID', 'fathom-analytics-conversions'),
                    array(
                        /*'input_id'    => 'wpforms-panel-field-confirmations-redirect-' . $id,
                        'input_class' => 'wpforms-panel-field-confirmations-redirect',
                        'parent'      => 'settings',
                        'subsection'  => $id,*/
                        'after' => '<p class="note"><a href="https://app.tango.us/app/workflow/Creating-Events-with-Fathom-94b0b00ff9b04b548bf4910188f97902" target="_blank">'.
                            esc_html__('Creating Events with Fathom', 'fathom-analytics-conversions')
                            . '</a>' . '</p>',
                    )
                );
            }

            do_action('wpforms_form_settings_fac-wpforms', $this);

            echo '</div>';
        }
    }

}
