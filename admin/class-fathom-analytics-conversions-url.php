<?php
/**
 * The URL-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.1.3.3
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/url
 */

/**
 * The url-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the url-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/url
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_URL {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.2
	 * @access   private
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 * @since    1.2
	 * @access   private
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.2
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Adds a meta box to one or more screens.
		add_action( 'add_meta_boxes', [ $this, 'fac_add_meta_boxes' ] );
		// Save the meta when post is saved.
		add_action( 'save_post', [ $this, 'fac_save_post' ] );

		// Render JS.
		add_action( 'wp_footer', [ $this, 'fac_url_wp_footer' ], 100 );
	}

	/**
	 * Register meta box(es).
	 *
	 * @since    1.2
	 */
	public function fac_add_meta_boxes() {
		$screens = get_post_types();
		foreach ( $screens as $screen ) {
			if ( is_post_type_viewable( $screen ) ) {
				add_meta_box( 'fac-meta-box',
					__( 'Fathom Analytics', 'fathom-analytics-conversions' ),
					[
						$this,
						'fac_display_callback',
					],
					$screen,
					'side'
				);
			}
		}
	}

	/**
	 * Renders the meta box.
	 *
	 * @param WP_Post $post Post object.
	 */
	public function fac_display_callback( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'fac_box_nonce_action', 'fac_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value      = get_post_meta( $post->ID, '_fac_url_page', TRUE );
		$event_name = get_post_meta( $post->ID, '_fac_url_event_name', TRUE );
		if ( empty( $event_name ) ) {
			$title      = get_the_title( $post );
			$event_name = $title . ' - ' . $post->ID;
		}
		$event_name_css = $value == '1' ? '' : 'display: none !important;';
		?>
        <label class="link_to_fathom_event_title"
               style="margin-bottom: 8px;display: block;font-weight: 500;">
			<?php _e( 'Link to Fathom Event', 'fathom-analytics-conversions' ); ?>
        </label>
        <div class="link_to_fathom_event" style="margin-bottom: 16px;">
            <label for="link_to_fathom_event">
                <input type="checkbox" id="link_to_fathom_event" class=""
                       name="link_to_fathom_event"
                       value="1" <?php checked( $value ); ?> />
				<?php _e( 'Add Fathom Event conversations every time this page is visited.', 'fathom-analytics-conversions' ); ?>
            </label>
        </div>
        <div class="link_to_fathom_event_name_field"
             style="<?php echo $event_name_css; ?>">
            <label for="link_to_fathom_event_name"
                   style="margin-bottom: 8px;display: block;font-weight: 500;">
				<?php _e( 'Event Name', 'fathom-analytics-conversions' ); ?>
            </label>
            <div class="link_to_fathom_event_name components-form-token-field__input-container">
                <input type="text" id="link_to_fathom_event_name"
                       class="components-form-token-field__input"
                       name="link_to_fathom_event_name"
                       value="<?php echo esc_attr( $event_name ); ?>"/>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                $('#link_to_fathom_event').on('change', function () {
                    if (this.checked) $('.link_to_fathom_event_name_field').show(100);
                    else $('.link_to_fathom_event_name_field').hide(100);
                });
            });
        </script>
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function fac_save_post( $post_id ) {
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		// Check meta box nonce.
		if ( $is_autosave || $is_revision || ! isset( $_POST['fac_box_nonce'] ) || ! wp_verify_nonce( $_POST['fac_box_nonce'], 'fac_box_nonce_action' ) ) {
			return;
		}

		// Return if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$link_to_fathom_event_value = filter_input( INPUT_POST, 'link_to_fathom_event', FILTER_SANITIZE_SPECIAL_CHARS );
		$link_to_fathom_event_name  = filter_input( INPUT_POST, 'link_to_fathom_event_name', FILTER_SANITIZE_SPECIAL_CHARS );

		if ( $link_to_fathom_event_value ) {
			update_post_meta( $post_id, '_fac_url_page', $link_to_fathom_event_value );

			if ( ! empty( $link_to_fathom_event_name ) ) {
				update_post_meta( $post_id, '_fac_url_event_name', $link_to_fathom_event_name );
				$title = $link_to_fathom_event_name;
			} else {
				$title = get_the_title( $post_id ) . ' - ' . $post_id;
			}
		} else {
			delete_post_meta( $post_id, '_fac_url_page' );
			delete_post_meta( $post_id, '_fac_url_event_name' );
		}
	}

	/**
	 * Add tracking code.
	 *
	 * @since    1.0.0
	 */
	public function fac_url_wp_footer() {
		global $post;
		if ( is_singular() && is_fac_fathom_analytic_active() && ! fac_fathom_is_excluded_from_tracking() ) {
			$post_id    = $post->ID;
			$track_page = get_post_meta( $post_id, '_fac_url_page', TRUE );
			$event_name = get_post_meta( $post_id, '_fac_url_event_name', TRUE );
			$event_name = ! empty( $event_name ) ? $event_name : get_the_title( $post ) . ' - ' . $post_id;
			if ( $track_page && $event_name ) {
				?>
                <script id="fac-page-url" data-cfasync="false"
                        data-pagespeed-no-defer type="text/javascript">
                    window.addEventListener('load', (event) => {
                        fathom.trackEvent('<?php echo $event_name;?>');
                    });
                </script>
				<?php
			}
		}
	}

}
