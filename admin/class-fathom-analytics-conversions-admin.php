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
                //_e( 'Fathom Analytics Conversions can integrate with several popular plugins. Please check the plugins you would like to integrate with:', $this->plugin_name );

                break;
            }
        }

        echo '</span>';
    }

    // admin output field
    public function fac4wp_admin_output_field($args) {
        global $fac4wp_options;

        switch ( $args['label_for'] ) {
            case FAC4WP_ADMIN_GROUP_API_KEY: {
                $_api_key   = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
                $_input_readonly = '';
                //$_warning_after  = '';

                echo '<input type="text" id="' . esc_attr(FAC4WP_OPTIONS . '[' . FAC4WP_OPTION_API_KEY_CODE . ']').'" name="' . esc_attr(FAC4WP_OPTIONS . '[' . FAC4WP_OPTION_API_KEY_CODE . ']').'" value="' . esc_attr($_api_key) . '" ' . $_input_readonly . ' class="regular-text" /><br />' . $args['description'];
                //echo $_warning_after;
                //fac_api_key();

                break;
            }

            default: {
                $opt_val = $fac4wp_options[ $args['option_field_id'] ];

                switch ( gettype( $opt_val ) ) {
                    case 'boolean': {
                        echo '<input type="checkbox" id="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" name="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" value="1" ' . checked( 1, $opt_val, false ) . ' /><br />' . $args['description'];

                        if ( isset( $args['plugin_to_check'] ) && ( $args['plugin_to_check'] != '' ) ) {
                            if ( is_plugin_active( $args['plugin_to_check'] ) ) {
                                echo '<br />' . __( 'This plugin is <strong class="fac4wp-plugin-active">active</strong>, it is strongly recommended to enable this integration!', $this->plugin_name );
                            } else {
                                echo '<br />' . __( 'This plugin (' . $args['plugin_to_check'] . ') is <strong class="fac4wp-plugin-not-active">not active</strong>, enabling this integration could cause issues on frontend!', $this->plugin_name );
                            }
                        }

                        break;
                    }

                    default: {
                        echo '<input type="text" id="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" name="' . esc_attr(FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']').'" value="' . esc_attr( $opt_val ) . '" size="80" /><br />' . $args['description'];

                        if ( isset( $args['plugin_to_check'] ) && ( $args['plugin_to_check'] != '' ) ) {
                            if ( is_plugin_active( $args['plugin_to_check'] ) ) {
                                echo '<br />' . __( 'This plugin is <strong class="fac4wp-plugin-active">active</strong>, it is strongly recommended to enable this integration!', $this->plugin_name );
                            } else {
                                echo '<br />' . __( 'This plugin is <strong class="fac4wp-plugin-not-active">not active</strong>, enabling this integration could cause issues on frontend!', $this->plugin_name );
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

            // "include" settings
            if ( substr( $optionname, 0, 8 ) == 'include-' ) {

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
                'label'         => __( 'Contact Form 7', $this->plugin_name ),
                'description'   => __( 'Check this to add conversation a successful form submission.', $this->plugin_name ),
                'phase'         => FAC4WP_PHASE_STABLE,
                'plugin_to_check' => 'contact-form-7/wp-contact-form-7.php',
            ),
        );
        global $fac4wp_integrate_field_texts;

        register_setting( FAC4WP_ADMIN_GROUP, FAC4WP_OPTIONS, [
                'sanitize_callback' => [$this, 'fac4wp_sanitize_options'],
            ]
        );

        /*add_settings_section(
            FAC4WP_ADMIN_GROUP_GENERAL,
            __( 'General', $this->plugin_name ),
            'fac4wp_admin_output_section',
            FAC4WP_ADMINSLUG
        );

        add_settings_field(
            FAC4WP_ADMIN_GROUP_API_KEY,
            __( 'API Key', $this->plugin_name ),
            [$this, 'fac4wp_admin_output_field'],
            FAC4WP_ADMINSLUG,
            FAC4WP_ADMIN_GROUP_GENERAL,
            array(
                'label_for'   => FAC4WP_ADMIN_GROUP_API_KEY,
                'description' => __( 'Enter your Fathom API key here.', $this->plugin_name ) . ' Get API key <a href="https://app.usefathom.com/#/settings/api" target="_blank">here</a>.',
            )
        );*/

        add_settings_section(
            FAC4WP_ADMIN_GROUP_INTEGRATION,
            __( 'Integration', $this->plugin_name ),
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
            __( 'Fathom Analytics Conversions settings', $this->plugin_name ),
            __( 'Fathom Analytics Conversions', $this->plugin_name ),
            'manage_options',
            FAC4WP_ADMINSLUG,
            [$this, 'fac4wp_show_admin_page'],
            11
        );

    }


    function fac4wp_show_admin_page() {
        //global $gtp4wp_plugin_url;
        ?>
        <div class="wrap">
            <div id="fac4wp-icon" class="icon32" style="background-image: url(<?php //echo $gtp4wp_plugin_url; ?>admin/images/tag_manager-32.png);"><br /></div>
            <h2><?php _e( 'Fathom Analytics Conversions options', $this->plugin_name ); ?></h2>
            <form action="options.php" method="post">
                <?php settings_fields( FAC4WP_ADMIN_GROUP ); ?>
                <?php do_settings_sections( FAC4WP_ADMINSLUG ); ?>
                <?php submit_button(); ?>

            </form>
        </div>
        <?php
    }

    function fac_admin_notices() {
        if( !file_exists(WP_PLUGIN_DIR.'/fathom-analytics/fathom-analytics.php') ) {

            $notice = '<div class="error" id="messages"><p>';
            $notice .= __('Fathom Analytics plugin must be installed for the <b>Fathom Analytics Conversions</b> to work. <b><a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=fathom-analytics&from=plugins&TB_iframe=true&width=600&height=550').'" class="thickbox" title="Fathom Analytics">Install Fathom Analytics Now.</a></b>', $this->plugin_name);
            $notice .= '</p></div>';
            echo $notice;

        }
        elseif(!is_plugin_active('fathom-analytics/fathom-analytics.php')) {
            $notice = '<div class="error" id="messages"><p>';
            $notice .= __('<b>Please activate Fathom Analytics</b> below for the <b>Fathom Analytics Conversions</b> to work.', $this->plugin_name);
            $notice .= '</p></div>';
            echo $notice;
        }
    }

    // add meta box to CF7 form admin
    function fac_cf7_meta_box($panels) {
        $new_page = array(
            'fac-cf7' => array(
                'title' => __( 'Fathom Analytics', $this->plugin_name ),
                'callback' => [$this, 'fac_cf7_box']
            )
        );

        return array_merge($panels, $new_page);
    }

    public function fac_cf7_box($args) {
        $cf7_id = $args->id;
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
                            <?php echo __('Event ID', $this->plugin_name); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="fac_cf7_event_id" name="fac_cf7[event_id]" class="" value="<?php echo esc_attr($fac_cf7_event_id);?>">
                        <p>
                            <a href="https://app.tango.us/app/workflow/Creating-Events-with-Fathom-94b0b00ff9b04b548bf4910188f97902" target="_blank">
                            <?php echo __('Creating Events with Fathom', $this->plugin_name);?>
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
        if(!empty($_POST)){

            $default = array () ;
            //$fac_cf7 = get_option( 'fac_cf7'.$args->id(), $default );

            $fac_cf7_val = $_POST['fac_cf7'];

            update_option( 'fac_cf7_'.$args->id(), $fac_cf7_val );
        }
    }

}
