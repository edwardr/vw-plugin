<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/admin
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Admin {

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
		 * defined in VisitWeho_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The VisitWeho_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/visitweho-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-datetimepicker', plugin_dir_url( __FILE__ ) . 'css/jquery.datetimepicker.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-visit-weho-icons', plugin_dir_url( __FILE__ ) . '../public/icons/style.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-chosen-css', plugin_dir_url( __FILE__ ) . 'js/chosen/chosen.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker');
		// wp_enqueue_style('thickbox');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'datetime-picker', plugin_dir_url( __FILE__ ) . 'js/jquery.datetimepicker.full.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-chosen-js', plugin_dir_url( __FILE__ ) . 'js/chosen/chosen.jquery.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/visitweho-admin.js', array
			( 'jquery', 'datetime-picker', $this->plugin_name . '-chosen-js' ), $this->version, true );

		$data_main = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'wp_rest' )
		);

		wp_localize_script( $this->plugin_name, 'wehoMain', $data_main );

		$protocol = is_ssl() ? 'https:' : 'http:';
		$google_api_key = get_option('google_api_key');
		wp_enqueue_script( $this->plugin_name . '-google-maps-api', $protocol . '//maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=places&callback=wehoAutocomplete', array( 'jquery', $this->plugin_name ), $this->version, true );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'wp-color-picker');
		// wp_enqueue_script('media-upload');
		// wp_enqueue_script('thickbox');
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name . '-slideshow', plugin_dir_url( __FILE__ ) . 'js/cw-slideshow-admin.js', array( 'jquery', 'editor' ), $this->version, true );

		wp_enqueue_script('jquery-ui-sortable');

	}

	public function wp_enqueue_editor() {
		wp_enqueue_editor();
	}

	public function add_venue_meta( $taxonomy ) {
			$visit_weho = new VisitWeho();

			$available_venues = json_decode( $visit_weho->available_dola_venues );
			usort( $available_venues, array( $visit_weho, 'sort_venues_by_name' ) );
			echo '
				<div class="form-field term-group venue-import-row">
					<label for="import-row">Import from DOLA</label>';
					if( $available_venues ) {
						echo '<select id="dola_venue" name="dola_venue">';
						echo '<option value="">Select a DOLA Venue</option>';
						foreach( $available_venues as $venue ) {
							echo '<option
											value="' . $venue->id . '"
											data-dola-permalink="' . $venue->permalink . '"
											data-latitude="' . $venue->latitude . '"
											data-longitude="' . $venue->longitude . '"
											data-address="' . $venue->address . '"
											data-city="' . $venue->city . '"
											data-state="' . $venue->state . '"
											data-zip="' . $venue->zip . '"
											data-name="' . $venue->name . '"
											data-place-id="' . $venue->google_place_id . '"
											>' . $venue->name . '</option>';
						}
						echo '</select>';
					}

			echo '</div>';

			echo '
				<div class="form-field term-group">
					<label>DOLA Slug</label>
						<input name="dola_permalink" id="dola_permalink" type="text" value="" size="40">
						<p class="description">The last part of the DOLA venue URL, i.e. whisky-a-go-go -- used for the daily events fetch.</p>
				</div>';

			echo '
				<div class="form-field term-group">
					<label>DOLA ID</label>
						<input name="dola_id" id="dola_id" type="text" value="" size="40">
				</div>';

			echo '
				<div class="form-field term-group">
					<label>Google Places ID</label>
						<input name="google_place_id" id="google_place_id" type="text" value="" size="40">
				</div>';

			echo '
				<div class="form-field term-group">
						<label>Address</label><input name="address" id="address" type="text" value="" size="40">
						<label>City</label><input name="city" id="city" type="text" value="" size="40">';

						$states = $visit_weho->get_states();
						echo '<label>State</label>';
						echo '<select class="postform" id="state" name="state">';
						foreach( $states as $k => $v ) {
							$selected = $k == 'CA' ? 'selected' : '';
							echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
						}

						echo '</select>';

			echo '<label>Zip</label><input name="zip" id="zip" type="text" value="" size="40">';

			echo '</div>';

			echo '<div class="form-field term-group">';

			$states = $visit_weho->get_states();
			echo '<label>Neighborhood</label>';
			echo '<select class="postform" id="neighborhood_id" name="neighborhood_id">';
			echo '<option value="">No Neighborhood</option>';
			$neighborhoods = $visit_weho->get_neighborhoods();
			foreach( $neighborhoods as $neighborhood ) {
				echo '<option value="' . $neighborhood->term_id . '">' . $neighborhood->name . '</option>';
			}

			echo '</select>';

			echo '</div>';

			echo '
				<div class="form-field term-group">
					<label>Phone</label>
						<input name="phone" id="phone" type="text" value="" size="40">
				</div>';

			echo '
				<div class="form-field term-group">
					<label>Facebook URL</label>
						<input name="facebook_url" id="facebook_url" type="url" value="" size="40">
				</div>';

			echo '
				<div class="form-field term-group">
					<label>Facebook URL</label>
						<input name="twitter_url" id="twitter_url" type="url" value="" size="40">
				</div>';

			echo '
				<div class="form-field term-group">
					<label>Website URL</label>
						<input name="website_url" id="website_url" type="url" value="" size="40">
				</div>';

			echo '
				<div class="form-field term-group">
					<label>Latitude</label>
						<input name="latitude" id="latitude" type="text" value="" size="40">
				</div>';

			echo '
				<div class="form-field term-group">
					<label>Longitude</label>
						<input name="longitude" id="longitude" type="text" value="" size="40">
				</div>';
	}

	public function edit_venue_meta( $term, $taxonomy ) {
			$visit_weho = new VisitWeho();
			$venue = new VisitWeho_Venue( $term->term_id );

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>DOLA Slug</label></th>
					<td>
						<input name="dola_permalink" id="dola_permalink" type="text" value="' . $venue->dola_permalink . '" size="40">
						<p class="description">The last part of the DOLA venue URL, i.e. whisky-a-go-go -- used for the daily events fetch.</p>
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>DOLA ID</label></th>
					<td>
						<input name="dola_id" id="dola_id" type="text" value="' . $venue->dola_id . '" size="40">
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Google Places ID</label></th>
					<td>
						<input name="google_place_id" id="google_place_id" type="text" value="' . $venue->google_place_id . '" size="40">
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Venue Address</label></th>
					<td>
						<label><h5>Address</h5><input name="address" id="address" type="text" value="' . $venue->address . '" size="40"></label>
						<label><h5>City</h5><input name="city" id="city" type="text" value="' . $venue->city . '" size="40"></label>';

						$states = $visit_weho->get_states();
						echo '<label><h5>State</h5>';
						echo '<select id="state" name="state">';
						foreach( $states as $k => $v ) {
							$selected = $k == $venue->state ? 'selected' : '';
							echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
						}

			echo '</select></label>';

			echo '<label><h5>Zip</h5><input name="zip" id="zip" type="text" value="' . $venue->zip . '" size="40"></label>
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Neighborhood</label></th>
					<td>';
			echo '<select id="neighborhood_id" name="neighborhood_id">';
			echo '<option value="">None</option>';
						$neighborhoods = $visit_weho->get_neighborhoods();
						foreach( $neighborhoods as $neighborhood ) {
							$selected = $neighborhood->term_id == $venue->neighborhood_id ? 'selected' : '';
							echo '<option value="' . $neighborhood->term_id . '" ' . $selected . '>' . $neighborhood->name . '</option>';
						}

			echo '</select>
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Media</label></th>
					<td>
						<button class="button" id="add-item-image">Select Image</button>
						<div class="item-img-container">';

						if( $venue->media_id ) {
							$img_url = wp_get_attachment_image_src( $venue->media_id, 'thumbnail');
							echo '<img src="' . $img_url[0] . '" /><span class="dashicons dashicons-no-alt img-delete"></span>';
						}


			echo '</div><input name="media_id" id="item-media" type="hidden" value="' . $venue->media_id . '" size="40">
					</td>
				</tr>';


			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Phone</label></th>
					<td>
						<input name="phone" id="phone" type="text" value="' . $venue->phone . '" size="40">
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Facebook URL</label></th>
					<td>
						<input name="facebook_url" id="facebook_url" type="url" value="' . $venue->facebook_url . '" size="40">
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Twitter URL</label></th>
					<td>
						<input name="twitter_url" id="twitter_url" type="url" value="' . $venue->twitter_url . '" size="40">
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Website URL</label></th>
					<td>
						<input name="website_url" id="website_url" type="url" value="' . $venue->website_url . '" size="40">
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Latitude</label></th>
					<td>
						<input name="latitude" id="latitude" type="text" value="' . $venue->latitude . '" size="40" disabled="true">
					</td>
				</tr>';

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Longitude</label></th>
					<td>
						<input name="longitude" id="longitude" type="text" value="' . $venue->longitude . '" size="40" disabled="true">
					</td>
				</tr>';

	}

	public function save_venue_meta( $term_id, $tt_id ) {

		$zip = preg_replace('/[^0-9]/','', $_POST['zip'] );
		$phone = preg_replace('/[^0-9]/','', $_POST['phone'] );

		$fields = array(
			'dola_permalink' => esc_attr( $_POST['dola_permalink'] ),
			'dola_id' => esc_attr( $_POST['dola_id'] ),
			'google_place_id' => esc_attr( $_POST['google_place_id'] ),
			'address' => esc_attr( $_POST['address'] ),
			'city' => esc_attr( $_POST['city'] ),
			'state' => esc_attr( $_POST['state'] ),
			'zip' => $zip,
			'phone' => $phone,
			'facebook_url' => esc_url( $_POST['facebook_url'] ),
			'twitter_url' => esc_url( $_POST['twitter_url'] ),
			'website_url' => esc_url( $_POST['website_url'] ),
			'latitude' => esc_attr( $_POST['latitude'] ),
			'longitude' => esc_attr( $_POST['longitude'] ),
			'neighborhood_id' => esc_attr( $_POST['neighborhood_id'] ),
			'media_id' => esc_attr( $_POST['media_id'] ),
		);

		foreach( $fields as $k => $v ) {
			add_term_meta($term_id, $k, $v );
		}
	}

	public function edit_save_venue_meta( $term_id, $tt_id ) {

		$visit_weho = new VisitWeho();
		$venue = new VisitWeho_Venue( $term_id );

		$latitude = $venue->latitude;
		$longitude = $venue->longitude;

		$current = array( $venue->address, $venue->city, $venue->state, $venue->zip );
		$new     = array( $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'] );

		$has_lat_lng = empty( $latitude ) || empty( $longitude ) ? false : true;

		if( $current !== $new || !$has_lat_lng ) {
			$address_string = $_POST['address'] . ',' . $_POST['city'] . ',' . $_POST['state'] . ',' . $_POST['zip'];
			$o = $visit_weho->get_geolocation( $address_string );

			if( $o ) {
				$latlng = $o[0]['geometry']['location'];
				$latitude = $latlng['lat'];
				$longitude = $latlng['lng'];
			} else {
				error_log( 'Could not fetch Google Geo API for ' . $post_id, 0 );
			}
		}

		$zip = preg_replace('/[^0-9]/','', $_POST['zip'] );
		$phone = preg_replace('/[^0-9]/','', $_POST['phone'] );

		$fields = array(
			'dola_permalink' => esc_attr( $_POST['dola_permalink'] ),
			'dola_id' => esc_attr( $_POST['dola_id'] ),
			'google_place_id' => esc_attr( $_POST['google_place_id'] ),
			'address' => esc_attr( $_POST['address'] ),
			'city' => esc_attr( $_POST['city'] ),
			'state' => esc_attr( $_POST['state'] ),
			'zip' => $zip,
			'phone' => $phone,
			'facebook_url' => esc_url( $_POST['facebook_url'] ),
			'twitter_url' => esc_url( $_POST['twitter_url'] ),
			'website_url' => esc_url( $_POST['website_url'] ),
			'latitude' => $latitude,
			'longitude' => $longitude,
			'neighborhood_id' => esc_attr( $_POST['neighborhood_id'] ),
			'media_id' => esc_attr( $_POST['media_id'] ),
		);

		foreach( $fields as $k => $v ) {
			update_term_meta($term_id, $k, $v );
		}
	}

	public function add_neighborhood_meta( $taxonomy ) {
			$visit_weho = new VisitWeho();

			echo '
				<div class="form-field term-group">
					<label>Media ID</label>
						<input name="media_id" id="media_id" type="text" value="" size="40">
				</div>';
	}

	public function edit_neighborhood_meta( $term, $taxonomy ) {
			$visit_weho = new VisitWeho();
			$media_id = get_term_meta( $term->term_id, 'media_id', true );

			echo '
				<tr class="form-field term-name-wrap">
					<th scope="row"><label>Media</label></th>
					<td>
						<button class="button" id="add-item-image">Select Image</button>
						<div class="item-img-container">';

						if( $media_id ) {
							$img_url = wp_get_attachment_image_src( $media_id, 'thumbnail');
							echo '<img src="' . $img_url[0] . '" /><span class="dashicons dashicons-no-alt img-delete"></span>';
						}


			echo '</div><input name="media_id" id="item-media" type="hidden" value="' . $media_id . '" size="40">
					</td>
				</tr>';

	}

	public function save_neighborhood_meta( $term_id, $tt_id ) {

		$fields = array(
			'media_id' => esc_attr( $_POST['media_id'] ),
		);

		foreach( $fields as $k => $v ) {
			add_term_meta($term_id, $k, $v );
		}
	}

	public function edit_save_neighborhood_meta( $term_id, $tt_id ) {

		$visit_weho = new VisitWeho();

		$fields = array(
			'media_id' => esc_attr( $_POST['media_id'] ),
		);

		foreach( $fields as $k => $v ) {
			update_term_meta($term_id, $k, $v );
		}
	}

	public function weho_meta_boxes() {
		add_meta_box(
			'hotel-meta-box',
			__( 'Hotel Details', 'visitweho' ),
			array( $this, 'hotel_meta_callback' ),
			'hotel',
			'normal',
			'high'
		);
		add_meta_box( 'event-meta-box', __( 'Event Details', 'visitweho' ), array( $this, 'event_meta_callback' ), 'event', 'normal', 'high' );
		add_meta_box( 'location-meta-box', __( 'Location Details', 'visitweho' ), array( $this, 'location_meta_callback' ), 'location', 'normal', 'high' );
		add_meta_box( 'offer-meta-box', __( 'Offer Details', 'visitweho' ), array( $this, 'offer_meta_callback' ), 'offer', 'normal', 'high' );
		add_meta_box( 'story-meta-box', __( 'Story Extras', 'visitweho' ), array( $this, 'story_meta_callback' ), 'post', 'normal', 'high' );
		add_meta_box( 'page-meta-box', __( 'Page Extras', 'visitweho' ), array( $this, 'page_meta_callback' ), 'page', 'normal', 'high' );
		add_meta_box( 'video-meta-box', __( 'Video Details', 'visitweho' ), array( $this, 'video_meta_callback' ), 'video', 'normal', 'high' );
		add_meta_box( 'video-meta-box', __( 'Slide Details', 'visitweho' ), array( $this, 'slide_meta_callback' ), 'home-slide', 'normal', 'high' );
		add_meta_box( 'activity-meta-box', __( 'Activity Details', 'visitweho' ), array( $this, 'activity_meta_callback' ), 'activity', 'normal', 'high' );
		add_meta_box( 'collection-meta-box', __( 'Collection Details', 'visitweho' ), array( $this, 'collection_meta_callback' ), 'collection', 'normal', 'high' );

		$front_page = get_option( 'page_on_front' );
		global $post;

		if( $post->ID == $front_page ) {
			add_meta_box( 'home-meta-box', __( 'Home Page Settings', 'visitweho' ), array( $this, 'home_meta_callback' ), 'page', 'normal', 'high' );
		}
	}

	public function hotel_meta_callback( $post ) {

		wp_nonce_field( 'hotel_meta', 'hotel_meta_nonce' );

		$visit_weho = new VisitWeho();
		$icons = $visit_weho->get_icons();
		$hotel = new VisitWeho_Hotel( $post->ID );
		$post_type = get_post_type( $post->ID );

		$tile_message = get_post_meta( $post->ID, 'tile_message', true );
		echo '<div><label for="tile_message"><h4>Tile Message</h4><input class="widefat" type="text" name="tile_message" id="tile_message" value="' . $tile_message . '" /></label></div>';
		echo '<br>';

		$status_message = get_post_meta( $post->ID, 'status_message', true );
		echo '<p><label for="status_message"><h4>Status Message</h4></label></p>';
		wp_editor( $status_message, 'status_message', array('teeny' => true, 'media_buttons' => false));

		echo '<p><label for="place-search"><h4>Google Places API</h4><input data-input-type="' . $post_type . '" class="widefat" type="text" placeholder="Start typing a location name or address" name="place_search" id="place_search" value="' . $hotel->google_place_id . '" /></label></p>';

		echo '<p><label for="ares_id"><h4>ARES Hotel ID</h4><input class="widefat" type="text" name="ares_id" id="ares_id" value="' . $hotel->ares_id . '" /></label></p>';

		echo '<p><label for="subtitle"><h4>Subheading</h4><input class="widefat" type="text" name="subtitle" id="subtitle" value="' . $hotel->subtitle . '" /></label></p>';
		echo '<br>';
		echo '<p><label for="address"><h4>Address</h4><input class="widefat" type="text" name="address" id="address" value="' . $hotel->address . '" /></label></p>';
		echo '<p><label for="city"><h4>City</h4><input class="widefat" type="text" name="city" id="city" value="' . $hotel->city . '" /></label></p>';

		$states = $visit_weho->get_states();
		echo '<p><label for="state"><h4>State</h4>';
		echo '<select class="widefat" id="state" name="state">';
		foreach( $states as $k => $v ) {
			$selected = $k == $hotel->state ? 'selected' : '';
			echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
		}
		echo '</select>';
		echo '<p><label for="zip"><h4>Zip</h4><input class="widefat" type="text" name="zip" id="zip" value="' . $hotel->zip . '" /></label></p>';
		echo '<br>';
		echo '<p><label for="phone"><h4>Phone</h4><input class="widefat" type="text" name="phone" id="phone" value="' . $hotel->phone . '" /></label></p>';
		echo '<p><label for="secondary_phone"><h4>Secondary Phone</h4><input class="widefat" type="text" name="secondary_phone" id="secondary_phone" value="' . $hotel->secondary_phone . '" /></label></p>';
		echo '<br>';
		echo '<p><label for="from_price"><h4>Starting Price</h4><span style="font-size: 1.25em;">$</span><input type="number" min="0" name="from_price" id="from_price" value="' . $hotel->from_price . '" /></label></p>';
		echo '<p><label for="website_url"><h4>Website</h4><input class="widefat" type="url" name="website_url" id="website_url" value="' . $hotel->website_url . '" /></label></p>';
		echo '<p><label for="facebook_url"><h4>Facebook</h4><input class="widefat" type="url" name="facebook_url" id="facebook_url" value="' . $hotel->facebook_url . '" /></label></p>';
		echo '<p><label for="twitter_url"><h4>Twitter</h4><input class="widefat" type="url" name="twitter_url" id="twitter_url" value="' . $hotel->twitter_url . '" /></label></p>';
		echo '<p><label for="instagram_url"><h4>Instagram</h4><input class="widefat" type="url" name="instagram_url" id="instagram_url" value="' . $hotel->instagram_url . '" /></label></p>';
		echo '<p><label for="yelp_url"><h4>Yelp</h4><input class="widefat" type="url" name="yelp_url" id="yelp_url" value="' . $hotel->yelp_url . '" /></label></p>';
		echo '<br>';
		echo '<p><label for="latitude"><h4>Latitude</h4><input class="widefat" type="text" name="latitude" id="latitude" value="' . $hotel->latitude . '" disabled/></label></p>';
		echo '<p><label for="longitude"><h4>Longitude</h4><input class="widefat" type="text" name="longitude" id="longitude" value="' . $hotel->longitude . '" disabled/></label></p>';
		echo '<p><label for="social_embed"><h4>Social Embed</h4><textarea rows="10" class="widefat" name="social_embed" id="social_embed">' . $hotel->social_embed . '</textarea>';

		echo '<br>';

		echo '<div class="hotel-features-wrap">';
		echo '<p><label><h4>Hotel Features <span class="dashicons dashicons-plus add-hotel-feature"></span></h4></label></h4>';
		echo '<table id="hotel-features" class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th scope="col">Icon</th>
						<th scope="col">Title</th>
						<th scope="col" colspan="2">Description</th>
						<th scope="col" class="check-column"></th>
					</tr>
				</thead>
				<tbody>';

			$count = 0;
			if( empty( $hotel->features ) ) {
				echo '<tr class="placeholder">
								<td colspan="4">This hotel has no features. Maybe you should add some... 🤔</td>
							</tr>';
			} else {
				foreach( $hotel->features as $feature ) {
					$count++;
					$icon = $feature['icon'];
					$title = $feature['title'];
					$desc = $feature['desc'];
					$tr_class = $count % 2 == 0 ? 'alternate' : '';
					echo '<tr data-row="' . $count . '" class="' . $tr_class . '">
									<td class="select-icon">
									<input class="selected_icon" type="hidden" name="selected_icon[]" value="' . $icon . '" />
									<a href="#" data-row="' . $count . '"><div class="icon-placeholder"><span class="' . $icon . '"</div></div></a></td>
									<td><input name="feature_name[]" value="' . $title . '" type="text" class="widefat" /></td>
									<td colspan="2"><textarea name="feature_desc[]" rows="5" class="widefat">' . $desc . '</textarea></td>
									<td class="check-column"><span class="dashicons dashicons-no-alt delete-hotel-feature"></span></td>
								</tr>';
				}
			}

			echo '</tbody>';
		echo '</table>';

		echo '</div>';
		echo '<a id="open-thickbox" href="#TB_inline?width=100%&height=550&inlineId=visitweho-icon-select-modal" class="thickbox"></a>';
		add_thickbox();
		echo '<div id="visitweho-icon-select-modal" style="display: none;">';
			echo '<input type="hidden" id="icon-data-row" value="" />';
			echo '<div class="visitweho-icon-selector">';
			foreach( $icons as $icon ) {
				echo '<span class="insert-new-icon visit-weho-icon vw-icon-' . $icon . '"></span>';
			}
			echo '</div>';
		echo '</div>';

		// Media Management
		$saved_images = get_post_meta( $post->ID, 'gallery_images', true );
		?>
		<div><label><h4>Gallery Images</h4></label>
		<button class="button js-add-gallery-image">Add Image</button>
		<div class="thumbnail-manager drag-sort-enable">
			<?php
				if( $saved_images ):
					foreach( $saved_images as $id ) {
						$img_url = wp_get_attachment_image_src( $id, 'medium', false )[0];
						echo '<div style="background-image:url(' . $img_url . ');" class="thumbnail">';
						echo '<input type="hidden" name="gallery_images[]" value="' . $id . '" />';
						echo '<span class="dashicons dashicons-no-alt js-remove-gallery-image"></span>';
						echo '</div>';
					}
				endif;
			?>
		</div>
		<?php
	}

	public function hotel_meta_save( $post_id ) {

		$visit_weho = new VisitWeho();
		$hotel = new VisitWeho_Hotel( $post_id );

		if ( ! isset( $_POST['hotel_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['hotel_meta_nonce'], 'hotel_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'hotel' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$zip = preg_replace('/[^0-9]/','', $_POST['zip'] );
		$phone = preg_replace('/[^0-9]/','', $_POST['phone'] );
		$secondary_phone = preg_replace('/[^0-9]/','', $_POST['secondary_phone'] );
		$from_price = preg_replace('/[^0-9]/','', $_POST['from_price'] );

		/**
		 * Checks the current address + new to see if lat/lng need to be re-fetched
		 */

		$latitude = $hotel->latitude;
		$longitude = $hotel->longitude;

		$current = array( $hotel->address, $hotel->city, $hotel->state, $hotel->zip );
		$new     = array( $_POST['address'], $_POST['city'], $_POST['state'], $zip );

		$has_lat_lng = empty( $latitude ) || empty( $longitude ) ? false : true;

		if( $current !== $new || !$has_lat_lng ) {
			$address_string = $_POST['address'] . ',' . $_POST['city'] . ',' . $_POST['state'] . ',' . $zip;
			$o = $visit_weho->get_geolocation( $address_string );

			if( $o ) {
				$latlng = $o[0]['geometry']['location'];
				$latitude = $latlng['lat'];
				$longitude = $latlng['lng'];
			} else {
				error_log( 'Could not fetch Google Geo API for ' . $post_id, 0 );
			}
		}

		$data = array(
			'subtitle' => esc_attr( $_POST['subtitle'] ),
			'ares_id' => esc_attr( $_POST['ares_id'] ),
			'address' => esc_attr( $_POST['address'] ),
			'city' => esc_attr( $_POST['city'] ),
			'state' => esc_attr( $_POST['state'] ),
			'zip' => $zip,
			'phone' => $phone,
			'secondary_phone' => $secondary_phone,
			'from_price' => $from_price,
			'website_url' => esc_url( $_POST['website_url'] ),
			'facebook_url' => esc_url( $_POST['facebook_url'] ),
			'twitter_url' => esc_url( $_POST['twitter_url'] ),
			'instagram_url' => esc_url( $_POST['instagram_url'] ),
			'yelp_url' => esc_url( $_POST['yelp_url'] ),
			'latitude' => $latitude,
			'longitude' => $longitude,
			'google_place_id' => esc_attr( $_POST['place_search'] ),
			'social_embed' => $_POST['social_embed'],
			'status_message' => wpautop( $_POST['status_message'] ),
			'tile_message' => esc_attr( $_POST['tile_message'] ),
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

		// Process hotel feature selections

		$icon_count = count( array_filter( $_POST['selected_icon'] ) );
		$icons = array_filter( $_POST['selected_icon'] );
		$feature_name = $_POST['feature_name'];
		$feature_desc = $_POST['feature_desc'];

		$feature_r = array();

		if( $icon_count == 0 ) {
			update_post_meta( $post_id, 'hotel_features', $feature_r );
		} else {
			$icon_match_key = $icon_count - 1;
			$c = 0;
			foreach( $icons as $icon ) {
				$icon = esc_attr( $icon );
				$title = isset( $feature_name[$c] ) ? esc_attr( $feature_name[$c] ) : '';
				$desc = isset( $feature_desc[$c] ) ? esc_attr( $feature_desc[$c] ) : '';
				$feature_r[] = array( 'icon' => $icon, 'title' => $title, 'desc' => $desc );
				$c++;
			}

			update_post_meta( $post_id, 'hotel_features', $feature_r );
		}

		// Gallery images
		if( isset( $_POST['gallery_images'] ) ) {
			$saved_images = $_POST['gallery_images'];
			$saved_ids = array();

			if( $saved_images ) {
				$saved_images = array_filter( $saved_images );

				foreach( $saved_images as $id ) {
					$saved_ids[] = (int)esc_attr( $id );
				}
			}

			update_post_meta( $post_id, 'gallery_images', $saved_images );
		}

	}

	public function event_meta_callback( $post ) {

		wp_nonce_field( 'event_meta', 'event_meta_nonce' );

		$visit_weho = new VisitWeho();
		$event = new VisitWeho_Event( $post->ID );
		echo '<p><label for="presented_by"><h4>Presented By</h4><input class="widefat" type="text" name="presented_by" id="presented_by" value="' . $event->presented_by . '" /></label></p>';
		echo '<p><label for="buy_url"><h4>Ticket URL</h4><input class="widefat" type="url" name="buy_url" id="buy_url" value="' . $event->buy_url . '" /></label></p>';
		echo '<p><label for="dola_event_id"><h4>DOLA ID</h4><input class="widefat" type="text" name="dola_event_id" id="dola_event_id" value="' . $event->dola_id . '" /></label></p>';

		$process_begin_time = empty( $event->begin_time ) ? '' : date( 'm/d/Y h:i A', $event->begin_time );
		echo '<p><label for="begin_time"><h4>Begin Time</h4><input class="datetime-select" type="text" name="begin_time" id="begin_time" value="' . $process_begin_time . '" /></label></p>';

		$process_end_time = empty( $event->end_time ) ? '' : date( 'm/d/Y h:i A', $event->end_time );
		echo '<p><label for="end_time"><h4>End Time</h4><input class="datetime-select" type="text" name="end_time" id="end_time" value="' . $process_end_time . '" /></label></p>';

		echo '<p><label for="is_free"><h4>Featured Event?</h4><input ' . checked( $event->is_featured, 1, false ) . 'class="widefat" type="checkbox" name="is_featured" id="is_featured" value="1" /></label></p>';

		echo '<p><label for="is_free"><h4>Free Event</h4><input ' . checked( $event->is_free, 1, false ) . 'class="widefat" type="checkbox" name="is_free" id="is_free" value="1" /></label></p>';
		echo '<p><label for="sold_out"><h4>Sold Out</h4><input ' . checked( $event->sold_out, 1, false ) . 'class="widefat" type="checkbox" name="sold_out" id="sold_out" value="1" /></label></p>';
		echo '<p><label for="is_ongoing"><h4>Is Ongoing</h4><input ' . checked( $event->is_ongoing, 1, false ) . 'class="widefat" type="checkbox" name="is_ongoing" id="is_ongoing" value="1" /></label></p>';
		echo '<p><label for="rsvp"><h4>RSVP</h4><input ' . checked( $event->rsvp, 1, false ) . 'class="widefat" type="checkbox" name="rsvp" id="rsvp" value="1" /></label></p>';

	}

	public function event_meta_save( $post_id ) {
		if ( ! isset( $_POST['event_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['event_meta_nonce'], 'event_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'event' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$begin_time = esc_attr( $_POST['begin_time'] );

		if( empty( $begin_time ) ) {
			$begin_timestamp = '';
		} else {
			$begin_time_obj = DateTime::createFromFormat('m/d/Y h:i A', $begin_time);
			$begin_timestamp = $begin_time_obj->getTimestamp();
		}

		$end_time = esc_attr( $_POST['end_time'] );

		if( empty( $end_time ) ) {
			$end_timestamp = '';
		} else {
			$end_time_obj = DateTime::createFromFormat('m/d/Y h:i A', $end_time);
			$end_timestamp = $end_time_obj->getTimestamp();
		}

		$zip = preg_replace('/[^0-9]/','', $_POST['zip'] );
		$phone = preg_replace('/[^0-9]/','', $_POST['phone'] );
		$secondary_phone = preg_replace('/[^0-9]/','', $_POST['secondary_phone'] );
		$from_price = preg_replace('/[^0-9]/','', $_POST['from_price'] );

		$data = array(
			'presented_by' => esc_attr( $_POST['presented_by'] ),
			'buy_url' => esc_url( $_POST['buy_url'] ),
			'dola_event_id' => esc_attr( $_POST['dola_event_id'] ),
			'end_time' => $end_timestamp,
			'begin_time' => $begin_timestamp,
			'is_free' => esc_attr( $_POST['is_free'] ),
			'is_featured' => esc_attr( $_POST['is_featured'] ),
			'rsvp' => esc_attr( $_POST['rsvp'] ),
			'is_ongoing' => esc_attr( $_POST['is_ongoing'] ),
			'sold_out' => esc_attr( $_POST['sold_out'] ),
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}


	public function location_meta_callback( $post ) {

		wp_nonce_field( 'location_meta', 'location_meta_nonce' );

		$visit_weho = new VisitWeho();
		$location = new VisitWeho_Location( $post->ID );

		$otlistings = get_option('opentable_listings');

		// echo '<pre>';
		// print_r( $otlistings);
		// echo '</pre>';

		$venues = $visit_weho->get_venues();

		$post_type = get_post_type( $post->ID );

		// echo '<p><label for="opentable_select"><h4>OpenTable Listing</h4>';
		// echo '<select name="opentable_select">';
		// echo '<option value="0">No Listing</option>';
		$otid = get_post_meta( $post->ID, 'opentable_id', true );

		// foreach( $otlistings as $listing ) {
		// 	$selected = $otid == $listing->rid ? 'selected' : '';
		// 	echo '<option value="' . $listing->rid . '"' . $selected . '>' . $listing->name . '</option>';
		// }
		// echo '</select></label></p>';

		echo '<p><label for="opentable_select"><h4>OpenTable Listing</h4>';
		echo '<select data-placeholder="Choose OpenTable Listing" class="chosen-select opentable" id="opentable_select" name="opentable_select" multiple>';
		echo '<option value="0">No Listing</option>';
		foreach( $otlistings as $listing ) {
			$selected = $otid == $listing->rid ? 'selected' : '';
			echo '<option value="' . $listing->rid . '"' . $selected . '>' . $listing->name . '</option>';
		}

		echo '</select></label></p>';

		if( $otdata ) {
			$name = $otdata->name;
		} else {
			$name = '';
		}

		echo '<p><label for="place-search"><h4>Google Places API</h4><input data-input-type="' . $post_type . '" placeholder="Start typing a location name or address" class="widefat" type="text" name="place_search" id="place_search" value="' . $location->google_place_id . '" /></label></p>';

		echo '<p><label for="venue"><h4>Attach to a Venue</h4>';
		echo '<select class="widefat" id="venue" name="venue">';
		echo '<option value="">Select a Venue</option>';
		$saved_venue_id = get_post_meta( $post->ID, 'venue_id', true );
		// echo '<pre>';
		// print_r( get_post_meta( $post->ID ) );
		// echo '</pre>';
		foreach( $venues as $venue ) {
			$selected = $venue->id == $saved_venue_id ? 'selected' : '';
			echo '<option value="' . $venue->id . '" ' . $selected . '>' . $venue->name . '</option>';
		}
		echo '</select>';

		echo '<p><label for="address"><h4>Address</h4><input class="widefat" type="text" name="address" id="address" value="' . $location->address . '" /></label></p>';
		echo '<p><label for="city"><h4>City</h4><input class="widefat" type="text" name="city" id="city" value="' . $location->city . '" /></label></p>';

		$states = $visit_weho->get_states();
		echo '<p><label for="state"><h4>State</h4>';
		echo '<select class="widefat" id="state" name="state">';
		foreach( $states as $k => $v ) {
			$selected = $k == $location->state ? 'selected' : '';
			echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
		}

		echo '</select>';
		echo '<p><label for="zip"><h4>Zip</h4><input class="widefat" type="text" name="zip" id="zip" value="' . $location->zip . '" /></label></p>';
		echo '<p><label for="phone"><h4>Phone</h4><input class="widefat" type="text" name="phone" id="phone" value="' . $location->phone . '" /></label></p><br>';

		echo '<p><label for="website_url"><h4>Website</h4><input class="widefat" type="url" name="website_url" id="website_url" value="' . $location->website_url . '" /></label></p>';
		echo '<p><label for="facebook_url"><h4>Facebook</h4><input class="widefat" type="url" name="facebook_url" id="facebook_url" value="' . $location->facebook_url . '" /></label></p>';
		echo '<p><label for="twitter_url"><h4>Twitter</h4><input class="widefat" type="url" name="twitter_url" id="twitter_url" value="' . $location->twitter_url . '" /></label></p>';
		echo '<p><label for="instagram_url"><h4>Instagram</h4><input class="widefat" type="url" name="instagram_url" id="instagram_url" value="' . $location->instagram_url . '" /></label></p>';
		echo '<p><label for="yelp_url"><h4>Yelp</h4><input class="widefat" type="url" name="yelp_url" id="yelp_url" value="' . $location->yelp_url . '" /></label></p>';
		echo '<br>';
		echo '<h4>Geocoded Responses</h4>';
		echo '<hr>';
		echo '<p><label for="latitude"><h4>Latitude</h4><input class="widefat" type="text" name="latitude" id="latitude" value="' . $location->latitude . '" disabled/></label></p>';
		echo '<p><label for="longitude"><h4>Longitude</h4><input class="widefat" type="text" name="longitude" id="longitude" value="' . $location->longitude . '" disabled/></label></p>';
		echo '<p><label for="distance"><h4>Distance (meters)</h4><input class="widefat" type="text" name="distance" id="distance" value="' . $location->distance . '" disabled/></label></p>';


	}

	public function location_meta_save( $post_id ) {

		$visit_weho = new VisitWeho();
		$location = new VisitWeho_Location( $post_id );

		if ( ! isset( $_POST['location_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['location_meta_nonce'], 'location_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'location' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$zip = preg_replace('/[^0-9]/','', $_POST['zip'] );
		$phone = preg_replace('/[^0-9]/','', $_POST['phone'] );

		/**
		 * Checks the current address + new to see if lat/lng need to be re-fetched
		 */

		$latitude = $location->latitude;
		$longitude = $location->longitude;
		$distance = $location->distance;

		$current = array( $location->address, $location->city, $location->state, $location->zip );
		$new     = array( $_POST['address'], $_POST['city'], $_POST['state'], $zip );

		$has_lat_lng = empty( $latitude ) || empty( $longitude ) ? false : true;

		if( $current !== $new || !$has_lat_lng ) {
			$address_string = $_POST['address'] . ',' . $_POST['city'] . ',' . $_POST['state'] . ',' . $zip;
			$o = $visit_weho->get_geolocation( $address_string );

			if( $o ) {
				$latlng = $o[0]['geometry']['location'];
				$latitude = $latlng['lat'];
				$longitude = $latlng['lng'];
			} else {
				error_log( 'Could not fetch Google Geo API for ' . $post_id, 0 );
			}

			$from = array(
				'lat' => $visit_weho->latitude,
				'lng' => $visit_weho->longitude,
			);

			$to = array(
				'lat' => $latitude,
				'lng' => $longitude,
			);

			$distance = $visit_weho->get_distance( $from, $to );

		}

		if( $_POST['opentable_select'] ) {

		}

		$opentable_id = $_POST['opentable_select'] !== 0 ? esc_attr( $_POST['opentable_select'] ) : false;

		$data = array(
			'address' => esc_attr( $_POST['address'] ),
			'city' => esc_attr( $_POST['city'] ),
			'state' => esc_attr( $_POST['state'] ),
			'zip' => $zip,
			'phone' => $phone,
			'website_url' => esc_url( $_POST['website_url'] ),
			'facebook_url' => esc_url( $_POST['facebook_url'] ),
			'twitter_url' => esc_url( $_POST['twitter_url'] ),
			'instagram_url' => esc_url( $_POST['instagram_url'] ),
			'yelp_url' => esc_url( $_POST['yelp_url'] ),
			'latitude' => $latitude,
			'longitude' => $longitude,
			'google_place_id' => esc_attr( $_POST['place_search'] ),
			'venue_id' => esc_attr( $_POST['venue'] ),
			'distance' => $distance,
			'opentable_id' => $opentable_id,
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

		if( $opentable_id ) {
			$listings = get_option('opentable_listings');

			foreach( $listings as $listing ) {
				$rid = $listing->rid;

				if( (int)$rid == (int)$opentable_id ) {
					update_post_meta( $post_id, 'opentable_data', $listing );
					break;
				}
			}
		} else {
			update_post_meta( $post_id, 'opentable_data', false );
		}

	}


	public function offer_meta_callback( $post ) {

		wp_nonce_field( 'offer_meta', 'offer_meta_nonce' );

		$visit_weho = new VisitWeho();
		$offer = new VisitWeho_Offer( $post->ID );

		echo '<p><label for="display_name"><h4>Display Name</h4><input class="widefat" type="display_name" name="display_name" id="display_name" value="' . $offer->display_name . '" /></label></p>';

		echo '<p><label for="campaign_slug"><h4>Campaign Slug</h4><input class="widefat" type="campaign_slug" name="campaign_slug" id="display_name" value="' . $offer->campaign_slug . '" /></label></p>';

		$process_exp_time = empty( $offer->expiration ) ? '' : date( 'm/d/Y h:i A', $offer->expiration  );
		echo '<p><label for="expiration"><h4>Expiration</h4><input class="datetime-select" type="text" name="expiration" id="expiration" value="' . $process_exp_time . '" /></label></p>';

		echo '<p><label for="short_description"><h4>Short Description</h4>';
		echo '<textarea class="widefat" id="short_description" name="short_description">' . $offer->short_description . '</textarea>';
		echo '</label></p>';

		echo '<br>';

		echo '<p><label for="fine_print"><h4>Fine Print</h4>';
		echo '<textarea class="widefat" id="fine_print" name="fine_print">' . $offer->fine_print . '</textarea>';
		echo '</label></p>';

		echo '<p><label for="upload_image"><h4>Menu Widget Image</h4></label></p>';
		echo '<p><input class="widefat" style="width: auto;" id="upload_menu_image" type="text" size="36" name="menu_image" value="' . $offer->menu_image . '" />';
		echo ' <input class="button" id="upload_menu_image_button" type="button" value="Select Image" /></p>';

		echo '<br>';
		?>
		<label>
			<h4>Hide Event CTA?</h4>
			<input
				type="checkbox"
				name="hide_events_cta"
				id="hide_events_cta"
				value="1"
				<?php checked(1, $offer->hide_events_cta, true ); ?>
				/>
		</label>
		<?php
		$args = array(
			'post_type' => 'hotel',
			'posts_per_page' => -1,
		);

		$hotels = get_posts( $args );

		echo '<div class="offer-hotels-wrap">';
		echo '<p><label><h4>Hotels <span class="dashicons dashicons-plus add-offer-hotel"></span></h4></label></h4>';
		echo '<table id="offer-hotels" class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th scope="col">Hotel</th>
						<th scope="col" colspan="2">Details</th>
						<th scope="col" class="check-column"></th>
					</tr>
				</thead>
				<tbody>';
			$count = 0;
			if( empty( $offer->hotel_ids ) ) {
				echo '<tr class="placeholder">
								<td colspan="4">This offer has no hotels. Maybe you should add some... 🤔</td>
							</tr>';
			} else {
				foreach( $offer->hotel_ids as $k => $v ) {
					$count++;
					$title = $v['title'];
					$desc = $v['desc'];
					$promo = $v['promo'];
					$url = $v['url'];
					$tr_class = $count % 2 == 0 ? 'alternate' : '';
					echo '<tr data-row="' . $count . '" class="' . $tr_class . '">
									<td><select name="selected_hotel[]">';

					foreach( $hotels as $hotel ) {
						$selected = $hotel->ID == $k ? 'selected' : '';
						echo '<option value="' . $hotel->ID . '" ' . $selected . '>' . $hotel->post_title . '</option>';
					}

					echo 		'</select></td>
									<td colspan="2">
										<input placeholder="Offer Name" name="hotel_offer_title[]" value="' . $title . '" type="text" class="widefat hotel-offer-title" />
										<input placeholder="Promo Code" name="hotel_offer_promo_code[]" value="' . $promo . '" type="text" class="widefat promo-code" />
										<input placeholder="URL" name="hotel_offer_url[]" value="' . $url . '" type="url" class="widefat promo-code" />
										<textarea placeholder="Offer Description" name="hotel_offer_desc[]" rows="5" class="widefat">' . $desc . '</textarea>
										</td>
									<td class="check-column"><span class="dashicons dashicons-no-alt delete-hotel-feature"></span></td>
								</tr>';
				}
			}

			echo '</tbody>';
		echo '</table>';

		echo '</div>';

		//$locations = $visit_weho->get_locations();

		$args = array(
			'post_type' => 'location',
			'posts_per_page' => -1,
		);

		$locations = get_posts( $args );

		echo '<div class="offer-locations-wrap">';
		echo '<p><label><h4>Locations <span class="dashicons dashicons-plus add-offer-location"></span></h4></label></h4>';
		echo '<table id="offer-locations" class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th scope="col">Location</th>
						<th scope="col" class="check-column"></th>
					</tr>
				</thead>
				<tbody>';
			$count = 0;
			if( empty( $offer->location_ids ) ) {
				echo '<tr class="placeholder">
								<td colspan="4">This offer has no locations. Maybe you should add some... 🤔</td>
							</tr>';
			} else {
				foreach( $offer->location_ids as $id ) {
					$count++;
					$tr_class = $count % 2 == 0 ? 'alternate' : '';
					echo '<tr data-row="' . $count . '" class="' . $tr_class . '">
									<td><select name="selected_location[]">';

					foreach( $locations as $location ) {
						$selected = $location->ID == $id ? 'selected' : '';
						echo '<option value="' . $location->ID . '" ' . $selected . '>' . $location->post_title . '</option>';
					}

					echo 		'</select></td>
									<td class="check-column"><span class="dashicons dashicons-no-alt delete-offer-location"></span></td>
								</tr>';
				}
			}

			echo '</tbody>';
		echo '</table>';

		echo '</div>';

		echo '<p><label for="has_landing_page"><h4>Has Landing Page?</h4><input ' . checked( $offer->has_landing_page, 1, false ) . 'class="widefat" type="checkbox" name="has_landing_page" id="has_landing_page" value="1" /></label></p>';

		$lstyle = $offer->has_landing_page == false ? ' style="display: none;"' : '';
		echo '<div ' . $lstyle . ' class="offer-landing-page-fields">';

		echo '<p><label for="landing_page_title"><h4>Landing Page Title</h4><input class="widefat" type="text" name="landing_page_title" id="landing_page_title" value="' . $offer->landing_page_title . '" /></label></p>';

		echo '<p><label for="landing_page_subtitle"><h4>Landing Page Subtitle</h4><input class="widefat" type="text" name="landing_page_subtitle" id="landing_page_subtitle" value="' . $offer->landing_page_subtitle . '" /></label></p>';

		echo '<p><label for="landing_page_description"><h4>Landing Page Description</h4></label></p>';
		wp_editor( $offer->landing_page_description, 'landing_page_description', array('teeny' => true, 'media_buttons' => false));

		echo '<p><label for="terms"><h4>Terms & Conditions</h4></label></p>';
		//echo '<textarea rows="10" class="widefat" id="terms" name="terms">' . $offer->terms . '</textarea>';
		wp_editor( $offer->terms, 'terms', array('teeny' => true, 'media_buttons' => false));

		echo '<label for="related_content"><h4>Related Content</h4></label>';
		$saved_related = get_post_meta( $post->ID, 'related_content', true );
		//$stories = $visit_weho->get_stories();

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => -1,
		);

		$stories = get_posts( $args );

		echo '<select data-placeholder="Select Content" class="chosen-select" id="related_content" name="related_content[]" multiple>';
		foreach( $stories as $story ) {
			$selected = in_array( $story->ID, $saved_related ) ? 'selected' : '';
			echo '<option value="' . $story->ID . '"' . $selected . '>' . $story->post_title . '</option>';
		}
		echo '</select>';
		echo '</div>';
	}

	public function offer_meta_save( $post_id ) {

		if ( ! isset( $_POST['offer_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['offer_meta_nonce'], 'offer_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'offer' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$expiration = esc_attr( $_POST['expiration'] );

		if( empty( $expiration ) ) {
			$expiration = '';
		} else {
			$expiration_obj = DateTime::createFromFormat('m/d/Y h:i A', $expiration);
			$expiration_timestamp = $expiration_obj->getTimestamp();
		}

		$has_landing_page = isset( $_POST['has_landing_page'] ) ? true : false;

		if( $has_landing_page ) {
			$terms = wpautop( $_POST['terms'] );
			$related_content = $_POST['related_content'];
			$lp_description = wpautop( $_POST['landing_page_description'] );
			$lp_subtitle = $_POST['landing_page_subtitle'];
			$lp_title = $_POST['landing_page_title'];
		} else {
			$terms = '';
			$related_content = '';
			$lp_description = '';
			$lp_subtitle = '';
			$lp_title = '';
		}

		$menu_image = esc_attr( $_POST['menu_image'] );

		$data = array(
			//'hotel_ids' => $_POST['hotel_ids'],
			'short_description' => $_POST['short_description'],
			'fine_print' => $_POST['fine_print'],
			'expiration' => $expiration_timestamp,
			'display_name' => esc_attr( $_POST['display_name'] ),
			'has_landing_page' => $has_landing_page,
			'menu_image' => $menu_image,
			'terms' => $terms,
			'related_content' => $related_content,
			'landing_page_description' => $lp_description,
			'landing_page_subtitle' => $lp_subtitle,
			'landing_page_title' => $lp_title,
			'campaign_slug' => esc_attr( $_POST['campaign_slug'] ),
			'hide_events_cta' => esc_attr( $_POST['hide_events_cta'] ),
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

		$hotel_count = count( array_filter( $_POST['selected_hotel'] ) );
		$hotels = array_filter( $_POST['selected_hotel'] );
		$hotel_offer_title = $_POST['hotel_offer_title'];
		$hotel_offer_desc = $_POST['hotel_offer_desc'];
		$hotel_promo_codes = $_POST['hotel_offer_promo_code'];
		$hotel_offer_urls = $_POST['hotel_offer_url'];

		$hotels_r = array();

		if( $hotel_count == 0 ) {
			update_post_meta( $post_id, 'hotel_ids', $hotels_r );
		} else {
			$hotel_match_key = $hotel_count - 1;
			$c = 0;
			foreach( $hotels as $hotel ) {
				$hotel_id = esc_attr( $hotel );
				$title = isset( $hotel_offer_title[$c] ) ? $hotel_offer_title[$c] : '';
				$desc = isset( $hotel_offer_desc[$c] ) ? $hotel_offer_desc[$c] : '';
				$promo = isset( $hotel_promo_codes[$c] ) ? $hotel_promo_codes[$c] : '';
				$url = isset( $hotel_offer_urls[$c] ) ? $hotel_offer_urls[$c] : '';
				$hotels_r[$hotel_id] = array( 'title' => $title, 'desc' => $desc, 'promo' => $promo, 'url' => $url );
				$c++;
			}

			update_post_meta( $post_id, 'hotel_ids', $hotels_r );
		}

		$location_count = count( array_filter( $_POST['selected_location'] ) );
		$locations = array_filter( $_POST['selected_location'] );

		$locations_r = array();

		if( $location_count == 0 ) {
			update_post_meta( $post_id, 'location_ids', $locations_r );
		} else {
			$location_match_key = $location_count - 1;
			$c = 0;
			foreach( $locations as $location ) {
				$location_id = esc_attr( $location );
				$locations_r[] = $location_id;
				$c++;
			}

			update_post_meta( $post_id, 'location_ids', $locations_r );
		}

	}

	public function story_meta_callback( $post ) {

		wp_nonce_field( 'story_meta', 'story_meta_nonce' );

		$visit_weho = new VisitWeho();
		$story = new VisitWeho_Story( $post->ID );

		$saved_related = get_post_meta( $post->ID, 'related_stories', true );
		if( empty( $saved_related ) ) {
			$saved_related = array();
		}

		//$stories = $visit_weho->get_stories();

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => -1,
		);

		$stories = get_posts( $args );

		$show_author_box = get_post_meta( $post->ID, 'show_author_box', true );

		$checked = checked( $show_author_box, 1, false );
		echo '<label for="show_author_box">';
		echo '<h4><input type="checkbox" value="1" id="show_author_box" name="show_author_box" ' . $checked . '/>';
		echo 'Show Author Box?</h4></label>';

		echo '<label for="related_content"><h4>Related Content</h4></label>';

		echo '<select data-placeholder="Choose Related Stories" class="chosen-select" id="related_content" name="related_content[]" multiple>';
		foreach( $stories as $s ) {
			//$story = new VisitWeho_Story( $s->ID );
			$selected = in_array( $s->ID, $saved_related ) ? 'selected' : '';
			echo '<option value="' . $s->ID . '"' . $selected . '>' . $s->post_title . '</option>';
		}
		echo '</select>';

		$attached_locations = get_post_meta( $post->ID, 'attached_locations', true );

		if( empty( $attached_locations ) ) {
			$attached_locations = array();
		}

		$args = array(
			'post_type' => array('location', 'hotel'),
			'posts_per_page' => -1,
		);

		$locations = get_posts( $args );

		echo '<label for="attached_locations"><h4>Attach Locations</h4></label>';

		echo '<select data-placeholder="Choose Locations" class="chosen-select" id="attached_locations" name="attached_locations[]" multiple>';
		foreach( $locations as $location ) {
			$selected = in_array( $location->ID, $attached_locations ) ? 'selected' : '';
			echo '<option value="' . $location->ID . '"' . $selected . '>' . $location->post_title . '</option>';
		}
		echo '</select>';

		echo '<p><label for="subtitle"><h4>Subtitle</h4><input class="widefat" type="text" name="subtitle" id="subtitle" value="' . $story->subtitle . '" /></label></p>';

		$slides = get_post_meta( $post->ID, 'cw_slides', true );

		echo '<label for="add_slideshow"><h4>Story Slideshow</h4></label>';

		$i = 0;

		if( $slides ):

			foreach( $slides as $slide ) {
				$i++;
				echo '<div class="wrap cw-slide-instance">';
				echo '<p><label><h4>Slide Title</h4>';
				echo '<input type="text" class="widefat" name="cw_slide_title[]" value="' . $slide['title'] . '" />';
				echo '</label></p>';
				$btn_text = $slide['image'] ? __('Change Image', 'cw-slideshow') : __('Attach Image', 'cw-slideshow');
				echo '<button class="button cw-attach-image">' . $btn_text . '</button>';
				echo '<input type="hidden" name="cw_slide_image[]" value="' . $slide['image'] . '" class="upload-image" />';
				if( $slide['image'] ) {
					$img = wp_get_attachment_image_src( $slide['image'], 'medium' );
					if( isset( $img[0] ) ) {
						$url = $img[0];
						echo '<div class="cw-slideshow-img-preview">
										<div class="cw-preview-inner">
											<span class="dashicons dashicons-no remove-slide-img"></span><img src="' . $url . '" alt="" />
										</div>
									</div>';
					}
				}

				$editor_settings = array( 'teeny' => false, 'media_buttons' => false, 'textarea_name' => 'cw_slide_description[]' );
				wp_editor( $slide['desc'], 'test_' . $i, $editor_settings );
				echo '</div>';
			}

		endif;

		echo '<p><button type="button" class="button cw-add-slide">' . __('Add Slide', 'visitweho') . '</button></p>';

		// global $wpdb;
		// echo "<pre>";
		// print_r($wpdb->queries);
		// echo "</pre>";
	}

	public function story_meta_save( $post_id ) {

		if ( ! isset( $_POST['story_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['story_meta_nonce'], 'story_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'post' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$show_author_box = isset( $_POST['show_author_box'] ) ? true : false;

		$titles = array_filter( $_POST['cw_slide_title'] );
		$images = $_POST['cw_slide_image'];
		$descs  = $_POST['cw_slide_description'];

		$slides = array();

		$i = 0;
		foreach( $titles as $k => $v ) {
			$r = array();
			$r['title'] = $v;
			$r['image'] = esc_attr( $images[$i] );
			$r['desc'] = wp_kses_post( $descs[$i] );

			$slides[] = $r;

			$i++;
		}

		$data = array(
			'related_stories' => $_POST['related_content'],
			'attached_locations' => $_POST['attached_locations'],
			'show_author_box' => $show_author_box,
			'cw_slides' => $slides,
			'subtitle' => esc_attr( $_POST['subtitle'] ),
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}

	public function home_meta_callback( $post ) {

		wp_nonce_field( 'home_meta', 'home_meta_nonce' );

		$visit_weho = new VisitWeho();
		$headline = get_post_meta( $post->ID, 'hero_headline', true );
		$cta_text = get_post_meta( $post->ID, 'cta_text', true );
		$cta_url = get_post_meta( $post->ID, 'cta_url', true );

		echo '<p><label for="hero_headline"><h4>Hero Headline</h4><input class="widefat" type="text" name="hero_headline" id="hero_headline" value="' . $headline . '" /></label></p>';
		echo '<p><label for="cta_text"><h4>CTA Text</h4><input class="widefat" type="text" name="cta_text" id="cta_text" value="' . $cta_text . '" /></label></p>';
		echo '<p><label for="cta_url"><h4>CTA URL</h4><input class="widefat" type="url" name="cta_url" id="cta_url" value="' . $cta_url . '" /></label></p>';

	}

	public function home_meta_save( $post_id ) {

		$front_page = get_option( 'page_on_front' );

		if( $post_id != $front_page ) {
			return;
		}

		if ( ! isset( $_POST['home_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['home_meta_nonce'], 'home_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$data = array(
			'cta_url' => esc_url( $_POST['cta_url'] ),
			'cta_text' => esc_attr( $_POST['cta_text'] ),
			'hero_headline' => esc_attr( $_POST['hero_headline'] ),
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}

	public function page_meta_callback( $post ) {

		wp_nonce_field( 'page_meta', 'page_meta_nonce' );
		$posts_page = get_option( 'page_for_posts' );
		$page_template = get_post_meta( $post->ID, '_wp_page_template', true );
		$subtitle = get_post_meta( $post->ID, 'subtitle', true );
		$script = get_post_meta( $post->ID, 'page_script', true );
		$header_type = get_post_meta( $post->ID, 'header_type', true );
		$neighborhood_description = get_post_meta( $post->ID, 'neighborhood_description', true );

		echo '<p><label for="subtitle"><h4>Subtitle</h4><input class="widefat" type="text" name="subtitle" id="subtitle" value="' . $subtitle . '" /></label></p>';

		if( $page_template == 'neighborhoods/template-sunset-strip.php' || $page_template == 'neighborhoods/template-design-district.php' || $page_template == 'neighborhoods/template-santa-monica.php') {
			echo '<p><label for="neighborhood_description"><h4>Neighborhood Description</h4><textarea rows="10" class="widefat" name="neighborhood_description" id="neighborhood_description">' . $neighborhood_description . '</textarea>';

			$saved_collection = get_post_meta( $post->ID, 'itinerary_collection', true );

			$args = array(
				'post_type' => 'collection',
				'posts_per_page' => -1,
			);

			$output = array();

			$items = get_posts( $args );

			foreach( $items as $item ) {
				$suggestion = array();
				$suggestion['id'] = $item->ID;
				$suggestion['name'] = $item->post_title;

				$output[] = $suggestion;
			}

			echo '<label for="itinerary_collection"><h4>Itinerary Collection</h4></label>';
			echo '<select data-placeholder="Choose Item" class="chosen-select" id="itinerary_collection" name="itinerary_collection">';
			echo '<option value="0">None</option>';
			foreach( $output as $item ) {
				$selected = $item['id'] == $saved_collection ? 'selected' : '';
				echo '<option value="' . $item['id'] . '"' . $selected . '>' . $item['name'] . '</option>';
			}
			echo '</select>';

			echo '<label for="experiences"><h4>Experiences</h4></label>';
			$saved_experiences = get_post_meta( $post->ID, 'experiences', true );

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
			);

			$stories = get_posts( $args );

			echo '<select data-placeholder="Select Content" class="chosen-select" id="experiences" name="experiences[]" multiple>';
			foreach( $stories as $story ) {
				$selected = in_array( $story->ID, $saved_experiences ) ? 'selected' : '';
				echo '<option value="' . $story->ID . '"' . $selected . '>' . $story->post_title . '</option>';
			}
			echo '</select>';

			$attached_locations = get_post_meta( $post->ID, 'attached_locations', true );

			if( empty( $attached_locations ) ) {
				$attached_locations = array();
			}

			$args = array(
				'post_type' => array('location'),
				'posts_per_page' => -1,
			);

			$locations = get_posts( $args );

			echo '<label for="attached_locations"><h4>Attach Locations</h4></label>';

			echo '<select data-placeholder="Choose Locations" class="chosen-select" id="attached_locations" name="attached_locations[]" multiple>';
			foreach( $locations as $location ) {
				$selected = in_array( $location->ID, $attached_locations ) ? 'selected' : '';
				echo '<option value="' . $location->ID . '"' . $selected . '>' . $location->post_title . '</option>';
			}
			echo '</select>';

		}

		echo '<p><label for="page_script"><h4>Page Script</h4><textarea rows="10" class="widefat" name="page_script" id="page_script">' . $script . '</textarea>';

		if( $page_template == 'template-taste.php' || $page_template == 'template-indulge.php' ) {

			$field_name  = $page_template == 'template-taste.php' ? 'taste_featured' : 'indulge_featured';
			$field_label = $page_template == 'template-taste.php' ? 'Taste' : 'Indulge';

			$saved_items = get_post_meta( $post->ID, $field_name, true );

			$args = array(
				'post_type' => array('location','hotel', 'post'),
				'posts_per_page' => -1,
			);

			$output = array();

			$items = get_posts( $args );

			foreach( $items as $item ) {
				$suggestion = array();
				$suggestion['id'] = $item->ID;
				$suggestion['name'] = $item->post_title;

				$output[] = $suggestion;
			}

			echo '<label for="indulge_featured"><h4>' . ucfirst( $field_label ) . ' Featured Items</h4></label>';

			echo '<select data-placeholder="Choose Items" class="chosen-select" id="' . $field_name . '" name="' . $field_name . '[]" multiple>';

			foreach( $output as $item ) {
				$selected = in_array( $item['id'], $saved_items ) ? 'selected' : '';
				echo '<option value="' . $item['id'] . '"' . $selected . '>' . $item['name'] . '</option>';
			}
			echo '</select>';

			$recommended = get_post_meta( $post->ID, 'recommended_block', true );

			if( !$recommended ) {
				$recommended = array(
					'title' => '',
					'description' => '',
					'cta_text' => '',
					'cta_link' => '',
					'bg_color' => '',
					'image' => '',
				);
			}

			echo '<label><h4>Recommended Block</h4></label>';
			echo '<p><label for="recommended_title"><h4>Title</h4><input class="widefat" type="text" name="recommended_title" id="recommended_title" value="' . $recommended['title'] . '" /></label></p>';
			echo '<p><label for="recommended_description"><h4>Description</h4><textarea rows="10" class="widefat" name="recommended_description" id="recommended_description">' . $recommended['description'] . '</textarea>';
			echo '<p><label for="recommended_cta_text"><h4>CTA Text</h4><input class="widefat" type="text" name="recommended_cta_text" id="recommended_cta_text" value="' . $recommended['cta_text'] . '" /></label></p>';
			echo '<p><label for="recommended_cta_link"><h4>URL</h4><input class="widefat" type="url" name="recommended_cta_link" id="recommended_cta_link" value="' . $recommended['cta_link'] . '" /></label></p>';
			echo '<p><label for="recommended_bg_color"><h4>Background Color</h4><input class="widefat color-picker" type="hidden" name="recommended_bg_color" id="recommended_bg_color" value="' . $recommended['bg_color'] . '" /></label></p>';
			echo '<p><label for="upload_image"><h4>Image</h4></label></p>';
			echo '<p><input class="widefat" style="width: auto;" id="upload_image" type="text" size="36" name="recommended_image" value="' . $recommended['image'] . '" />';
			echo ' <input class="button" id="upload_image_button" type="button" value="Select Image" /></p>';

		}

		if( $page_template == 'template-taste.php' || $page_template == 'template-indulge.php' || $post->ID == $posts_page ) {

			if( $post->ID == $posts_page ) {
				$field_name = 'blog_subscribe_stories';
				$field_label = 'Blog';
			} elseif( $page_template == 'template-taste.php' ) {
				$field_name = 'taste_subscribe_stories';
				$field_label = 'Taste';
			} elseif( $page_template == 'template-indulge.php' ) {
				$field_name = 'indulge_subscribe_stories';
				$field_label = 'Indulge';
			}

			$saved_stories = get_post_meta( $post->ID, $field_name, true );

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
			);

			$output = array();

			$items = get_posts( $args );

			foreach( $items as $item ) {
				$suggestion = array();
				$suggestion['id'] = $item->ID;
				$suggestion['name'] = $item->post_title;

				$output[] = $suggestion;
			}

			echo '<label><h4>' . ucfirst( $field_label ) . ' Subscription Content</h4></label>';

			echo '<select data-placeholder="Choose Items" class="chosen-select" id="' . $field_name . '" name="' . $field_name . '[]" multiple>';

			foreach( $output as $item ) {
				$selected = in_array( $item['id'], $saved_stories ) ? 'selected' : '';
				echo '<option value="' . $item['id'] . '"' . $selected . '>' . $item['name'] . '</option>';
			}
			echo '</select>';

		}

		if( $posts_page == $post->ID ) {
			$secondary = get_post_meta( $post->ID, 'secondary_block', true );

			if( !$secondary ) {
				$secondary = array(
					'title' => '',
					'description' => '',
					'cta_text' => '',
					'cta_link' => '',
					'bg_color' => '',
					'image' => '',
				);
			}

			echo '<label><h4>Interstitial Block</h4></label>';
			echo '<p><label for="secondary_title"><h4>Title</h4><input class="widefat" type="text" name="secondary_title" id="recommended_title" value="' . $secondary['title'] . '" /></label></p>';
			echo '<p><label for="secondary_description"><h4>Description</h4><textarea rows="10" class="widefat" name="secondary_description" id="recommended_description">' . $secondary['description'] . '</textarea>';
			echo '<p><label for="secondary_cta_text"><h4>CTA Text</h4><input class="widefat" type="text" name="secondary_cta_text" id="recommended_cta_text" value="' . $secondary['cta_text'] . '" /></label></p>';
			echo '<p><label for="secondary_cta_link"><h4>URL</h4><input class="widefat" type="url" name="secondary_cta_link" id="recommended_cta_link" value="' . $secondary['cta_link'] . '" /></label></p>';
			echo '<p><label for="secondary_bg_color"><h4>Background Color</h4><input class="widefat color-picker" type="hidden" name="secondary_bg_color" id="secondary_bg_color" value="' . $secondary['bg_color'] . '" /></label></p>';
			echo '<p><label for="upload_image"><h4>Image</h4></label></p>';
			echo '<p><input class="widefat" style="width: auto;" id="upload_image" type="text" size="36" name="secondary_image" value="' . $secondary['image'] . '" />';
			echo ' <input class="button" id="upload_image_button" type="button" value="Select Image" /></p>';
		}

		if( $page_template != 'template-taste.php' && $page_template != 'template-indulge.php' && $page_template != 'neighborhoods/template-sunset-strip.php' && $page_template != 'neighborhoods/template-design-district.php' && $page_template != 'neighborhoods/template-santa-monica.php' ) {
			$opts = array(
				'hero' => 'Hero',
				'standard' => 'Standard',
			);

			echo '<p><h4>Header Type</h4><select name="header_type">';
			foreach( $opts as $k => $v ) {
				$selected = $k == $header_type ? 'selected' : '';
				echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
			}
			echo '</select></p>';
		}

		if( $page_template == 'template-events.php' ) {
			// Events banner ad
			$banner_link = get_post_meta( $post->ID, 'banner_link', true );
			$banner_link_image = get_post_meta( $post->ID, 'banner_link_image', true );
			echo '<p><label for="banner_link"><h4>Banner Link</h4><input class="widefat" type="url" name="banner_link" id="banner_link" value="' . $banner_link . '" /></label></p>';
			echo '<p><label for="upload_image"><h4>Banner Ad</h4></label></p>';
			echo '<p><input class="widefat" style="width: 600px;" id="banner_link_image" type="text" size="36" name="banner_link_image" value="' . $banner_link_image . '" />';
			echo ' <input class="button" id="banner_link_image_upload_button" type="button" value="Select Image" /></p>';
		}

	}

	public function page_meta_save( $post_id ) {

		$page_template = get_post_meta( $post_id, '_wp_page_template', true );
		$posts_page    = get_option('page_for_posts');

		if ( ! isset( $_POST['page_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['page_meta_nonce'], 'page_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$data = array(
			'subtitle' => esc_attr( $_POST['subtitle'] ),
			'page_script' => $_POST['page_script'],
			'header_type' => esc_attr( $_POST['header_type'] ),
		);

		if( $page_template == 'template-taste.php' || $page_template == 'template-indulge.php' ) {
			$field_name = $page_template == 'template-taste.php' ? 'taste_featured' : 'indulge_featured';
			$data[$field_name] = $_POST[$field_name];

			$recommended = array(
				'title' => '',
				'description' => '',
				'cta_text' => '',
				'cta_link' => '',
				'bg_color' => '',
				'image' => '',
			);

			$recommended['title'] = isset( $_POST['recommended_title'] ) ? $_POST['recommended_title'] : '';
			$recommended['description'] = isset( $_POST['recommended_description'] ) ? $_POST['recommended_description'] : '';
			$recommended['cta_text'] = isset( $_POST['recommended_cta_text'] ) ? $_POST['recommended_cta_text'] : '';
			$recommended['cta_link'] = isset( $_POST['recommended_cta_link'] ) ? $_POST['recommended_cta_link'] : '';
			$recommended['bg_color'] = isset( $_POST['recommended_bg_color'] ) ? $_POST['recommended_bg_color'] : '';
			$recommended['image'] = isset( $_POST['recommended_image'] ) ? $_POST['recommended_image'] : '';

			$data['recommended_block'] = $recommended;
		}


		if( $page_template == 'template-taste.php' || $page_template == 'template-indulge.php' || $post_id == $posts_page ) {

			if( $post->ID == $posts_page ) {
				$field_name = 'blog_subscribe_stories';
				$field_label = 'Blog';
			} elseif( $page_template == 'template-taste.php' ) {
				$field_name = 'taste_subscribe_stories';
				$field_label = 'Taste';
			} elseif( $page_template == 'template-indulge.php' ) {
				$field_name = 'indulge_subscribe_stories';
				$field_label = 'Indulge';
			}


			$data[$field_name] = $_POST[$field_name];

		}


		if( $posts_page == $post_id ) {
			$secondary = array(
				'title' => '',
				'description' => '',
				'cta_text' => '',
				'cta_link' => '',
				'bg_color' => '',
				'image' => '',
			);

			$secondary['title'] = isset( $_POST['secondary_title'] ) ? $_POST['secondary_title'] : '';
			$secondary['description'] = isset( $_POST['secondary_description'] ) ? $_POST['secondary_description'] : '';
			$secondary['cta_text'] = isset( $_POST['secondary_cta_text'] ) ? $_POST['secondary_cta_text'] : '';
			$secondary['cta_link'] = isset( $_POST['secondary_cta_link'] ) ? $_POST['secondary_cta_link'] : '';
			$secondary['bg_color'] = isset( $_POST['secondary_bg_color'] ) ? $_POST['secondary_bg_color'] : '';
			$secondary['image'] = isset( $_POST['secondary_image'] ) ? $_POST['secondary_image'] : '';

			$data['secondary_block'] = $secondary;
		}

		if( $page_template == 'template-events.php' ) {
			$banner_link = esc_attr( $_POST['banner_link'] );
			$banner_image = esc_attr( $_POST['banner_link_image'] );
			$data['banner_link'] = $banner_link;
			$data['banner_link_image'] = $banner_image;
		}

		if( $page_template == 'neighborhoods/template-sunset-strip.php' || $page_template == 'neighborhoods/template-design-district.php' || $page_template == 'neighborhoods/template-santa-monica.php') {
			$neighborhood_description = esc_attr( $_POST['neighborhood_description'] );
			$data['neighborhood_description'] = $neighborhood_description;

			$data['itinerary_collection'] = esc_attr( $_POST['itinerary_collection'] );
			$data['experiences'] = $_POST['experiences'];
			$data['attached_locations'] = $_POST['attached_locations'];
		}

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}

	public function video_meta_callback( $post ) {

		$visit_weho = new VisitWeho();

		wp_nonce_field( 'video_meta', 'video_meta_nonce' );

		$video_url = get_post_meta( $post->ID, 'video_url', true );

		echo '<p><label for="video_url"><h4>Video URL</h4><input class="widefat" type="text" name="video_url" id="video_url" value="' . $video_url . '" /></label></p>';

		$args = array(
			'post_type' => array('location','hotel'),
			'posts_per_page' => -1,
		);

		$output = array();

		$locations = get_posts( $args );

		foreach( $locations as $location ) {
			$suggestion = array();
			$suggestion['id'] = $location->ID;
			$suggestion['name'] = $location->post_title;

			$output[] = $suggestion;
		}

		$saved_locations = get_post_meta( $post->ID, 'location_ids', true );

		echo '<label for="attached_locations"><h4>Attached Locations</h4></label>';

		echo '<select data-placeholder="Choose Locations" class="chosen-select" id="location_ids" name="location_ids[]" multiple>';

		foreach( $output as $location ) {
			$selected = in_array( $location['id'], $saved_locations ) ? 'selected' : '';
			echo '<option value="' . $location['id'] . '"' . $selected . '>' . $location['name'] . '</option>';
		}
		echo '</select>';

	}

	public function video_meta_save( $post_id ) {

		if ( ! isset( $_POST['video_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['video_meta_nonce'], 'video_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'video' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$data = array(
			'video_url' => esc_url( $_POST['video_url'] ),
			'location_ids' => $_POST['location_ids'],
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}


	public function slide_meta_callback( $post ) {
		$visit_weho = new VisitWeho();

		wp_nonce_field( 'slide_meta', 'slide_meta_nonce' );

		$slide_url = get_post_meta( $post->ID, 'slide_url', true );
		$slide_cta = get_post_meta( $post->ID, 'slide_cta', true );
		$slide_type = get_post_meta( $post->ID, 'slide_type', true );

		echo '<p><label for="slide_url"><h4>Slide URL</h4><input class="widefat" type="url" name="slide_url" id="slide_url" value="' . $slide_url . '" /></label></p>';

		echo '<p><label for="slide_cta"><h4>Slide CTA</h4><input class="widefat" type="text" name="slide_cta" id="slide_cta" value="' . $slide_cta . '" /></label></p>';

		echo '<p><label for="slide_type"><h4>Slide Type</h4><input class="widefat" type="text" name="slide_type" id="slide_type" value="' . $slide_type . '" /></label></p>';

	}

	public function slide_meta_save( $post_id ) {

		if ( ! isset( $_POST['slide_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['slide_meta_nonce'], 'slide_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'home-slide' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$data = array(
			'slide_url' => esc_url( $_POST['slide_url'] ),
			'slide_cta' => esc_attr( $_POST['slide_cta'] ),
			'slide_type' => esc_attr( $_POST['slide_type'] ),
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}

	public function activity_meta_callback( $post ) {
		$visit_weho = new VisitWeho();

		$activity = new VisitWeho_Activity( $post->ID );

		// echo '<pre style="height: 300px; overflow: auto;">';
		// print_r( $activity );
		// echo '</pre>';

		wp_nonce_field( 'activity_meta', 'activity_meta_nonce' );

		$subheading = get_post_meta( $post->ID, 'subheading', true );

		echo '<p><label for="subheading"><h4>Subheading</h4><input class="widefat" type="text" name="subheading" id="subheading" value="' . $subheading . '" /></label></p>';

	}

	public function activity_meta_save( $post_id ) {

		if ( ! isset( $_POST['activity_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['activity_meta_nonce'], 'activity_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'activity' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$data = array(
			'subheading' => esc_attr( $_POST['subheading'] ),
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}

	public function collection_meta_callback( $post ) {
		$visit_weho = new VisitWeho();

		$collection = new VisitWeho_Collection( $post->ID );

		echo '<pre style="display: none;">';
		print_r( $collection );
		echo '</pre>';

		wp_nonce_field( 'collection_meta', 'collection_meta_nonce' );

		?>

		<div class="fields">
			<h3>Add Group</h3>
			<div class="add-group">
				<button onclick="addGroupRow(this)" class="button">Add Group</button>
			</div>

			<div class="collection-content-container">
				<ul class="sortable">
					<?php
						$collection_activities = $collection->activities;
						foreach( $collection_activities as $k => $group) {
							$group_label = $group['group_label'];
							$activities = $group['activities'];
							$group_id = rand(1,9999);
							$random_id = rand(1,9999);

							$activity_markup = '<ul class="group-activities sortable">';

							foreach( $activities as $id => $a ) {
								$canonical_title = get_the_title( $id );
								$label = $a['label'];
								$pre_heading = $a['pre_heading'];
								$activity_markup .= '<li class="grabbable">
									<span class="dashicons dashicons-move"></span>
									<input type="hidden" name="' . $group_id . '_activity_id[]" value="' . $id . '">
									<input placeholder="Activity Label" type="text" name="' . $group_id . '_activity_label[]" value="' . $label . '">
									<input placeholder="Pre-heading" type="text" name="' . $group_id . '_activity_pre_heading[]" value="' . $pre_heading . '">
									<a target="_blank" href="/wp-admin/post.php?post=' . $id . '&amp;action=edit">
										<small>Edit activity</small>
									</a>
									<span class="original-title">canonical: ' . $canonical_title . '</span>
									<span onclick="deleteParentRow(this)" class="dashicons dashicons-no-alt delete-activity-row"></span>
									</li>';
							}

							$activity_markup .= '</ul>';
							?>
							<li data-type="group">
								<span data-direction="up" onclick="moveGroup(this)" class="dashicons dashicons-arrow-up-alt"></span>
								<span data-direction="down" onclick="moveGroup(this)" class="dashicons dashicons-arrow-down-alt"></span>
								<h5 class="group-label">Label</h5>
								<input type="text" name="group_label[]" placeholder="<?php echo $group_label; ?>" value="<?php echo $group_label; ?>">
								<input type="hidden" name="group_id[]" value="<?php echo $group_id; ?>">
								<span onclick="deleteParentRow(this)" class="dashicons dashicons-trash delete-group-row"></span>
								<div class="autocomplete-search">
									<h5>Activities</h5>
									<input oninput="autoSearchResults(this)" placeholder="Search for and add an activity" class="widefat autocomplete" type="text" name="<?php echo $random_id; ?>" value="">
									<div class="search-results"></div>
								</div>
								<?php echo $activity_markup; ?>
							</li>
							<?php
						}
					?>
				</ul>
			</div>
		</div>

		<?php
	}

	public function collection_meta_save( $post_id ) {

		if ( ! isset( $_POST['collection_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['collection_meta_nonce'], 'collection_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'collection' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$data = array();

		$group_ids = $_POST['group_id'];

		$collection_data = array();

		foreach( $_POST['group_label'] as $k => $v ) {

			$group_label = $v;
			$group_id = $group_ids[$k];

			$activities = array();

			$activity_ids = $_POST[$group_id . '_activity_id'];
			$activity_labels = $_POST[$group_id . '_activity_label'];
			$activity_pre_headings = $_POST[$group_id . '_activity_pre_heading'];

			foreach( $activity_ids as $k => $id ) {
				$activities[$id] = array(
					'label' => $activity_labels[$k],
					'pre_heading' => $activity_pre_headings[$k],
				);
			}

			$group = array(
				'group_label' => $group_label,
				'activities' =>  $activities,
			);

			$collection_data[] = $group;

		}

		$data = array(
			'activities' => $collection_data,
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}


	public function hotel_admin_columns( $columns ) {

		$new = array();
		foreach($columns as $key => $title) {
			if ( $key == 'taxonomy-neighborhood' ) {
				$new['from_price'] = 'Starting Price';
			}

			$new[$key] = $title;

		}
		return $new;
	}

	public function hotel_admin_column_data( $column, $post_id ) {
		switch ( $column ) {
			case 'from_price':
				$hotel = new VisitWeho_Hotel( $post_id );
				if( get_post_meta( $post_id , 'from_price', true ) ) {
					echo '$' . $hotel->from_price;
				} else {
					echo 'N/A';
				}
				break;
		}
	}

	public function hotel_sortable_columns( $columns ) {
		$columns['from_price'] = 'from_price';

		return $columns;

	}


	public function hotel_admin_sort( $query ) {

		if( ! is_admin() ) {
			return;
		}

		$post_type = $query->get('post_type');

		if( $post_type != 'hotel' ) {
			return;
		}

		$orderby = $query->get( 'orderby');

		if( 'from_price' == $orderby ) {
			$query->set('meta_key','from_price');
			$query->set('orderby','meta_value_num');
		}

	}

	public function event_admin_columns( $columns ) {

		$new = array();
		foreach($columns as $key => $title) {
			if ( $key == 'taxonomy-venue' ) {
				$new['begin_time'] = 'Begin Time';
			}
			if ( $key == 'taxonomy-venue' ) {
				$new['dola_event_id'] = 'DOLA ID';
			}

			$new[$key] = $title;

		}
		return $new;
	}

	public function event_admin_column_data( $column, $post_id ) {
		switch ( $column ) {
			case 'begin_time':
				$event = new VisitWeho_Event( $post_id );
				if( $event->begin_time ) {
					echo date('F d, Y - h:i A', $event->begin_time );
				} else {
					echo 'N/A';
				}
				break;
			case 'dola_event_id':
				$event = new VisitWeho_Event( $post_id );
				if( $event->dola_id ) {
					echo $event->dola_id;
				} else {
					echo 'N/A';
				}
				break;
		}
	}

	public function event_sortable_columns( $columns ) {
		$columns['begin_time'] = 'begin_time';

		return $columns;

	}


	public function event_admin_sort( $query ) {

		if( ! is_admin() ) {
			return;
		}

		$post_type = $query->get('post_type');

		if( $post_type != 'event' ) {
			return;
		}

		$orderby = $query->get( 'orderby');

		if( 'begin_time' == $orderby ) {
			$query->set('meta_key','begin_time');
			$query->set('orderby','meta_value_num');
		}

	}


	public function offer_admin_columns( $columns ) {

		$new = array();
		foreach($columns as $key => $title) {
			if ( $key == 'date' ) {
				$new['expiration'] = 'Expiration';
			}
			if ( $key == 'date' ) {
				$new['hotel'] = 'Hotel(s)';
			}

			$new[$key] = $title;

		}
		return $new;
	}

	public function offer_admin_column_data( $column, $post_id ) {
		switch ( $column ) {
			case 'expiration':
				$offer = new VisitWeho_Offer( $post_id );
				if( $offer->expiration != '' ) {
					$exp = date( 'm/d/Y h:i A', $offer->expiration );
					echo $exp;
				} else {
					echo 'No Expiration';
				}
				break;
			case 'hotel':
				$offer = new VisitWeho_Offer( $post_id );
				if( $offer->hotel_ids ) {
					$count = count( $offer->hotel_ids );
					$i = 0;
					foreach( $offer->hotel_ids as $k => $v ) {
						$i++;
						echo '<a href="' . get_edit_post_link( $k ) . '">' . get_the_title( $k ) . '</a>';
						if( $i < $count ) {
							echo ', ';
						}
					}
				}
				break;
		}
	}


	public function visitweho_settings_init() {
		add_settings_section(
			'visitweho_settings_section',
			'VisitWeho Settings',
			array( $this, 'visitweho_settings_section_callback_function' ),
			'general'
		);

		add_settings_field(
			'google_api_key',
			'Google API Key',
			array( $this, 'google_api_key_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'ares_key',
			'ARES Key',
			array( $this, 'ares_key_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'mailchimp_api_key',
			'MailChimp API Key',
			array( $this, 'mailchimp_api_key_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'yelp_access_token',
			'Yelp Access Token',
			array( $this, 'yelp_access_token_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'instagram_access_token',
			'Instagram Access Token',
			array( $this, 'instagram_access_token_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'opentable_partner_id',
			'OpenTable Partner ID',
			array( $this, 'opentable_partner_id_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'opentable_referral_id',
			'OpenTable Referral ID',
			array( $this, 'opentable_referral_id_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'opentable_client_id',
			'OpenTable Client ID',
			array( $this, 'opentable_client_id_callback' ),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'opentable_secret',
			'OpenTable Client Secret',
			array( $this, 'opentable_client_secret_callback' ),
			'general',
			'visitweho_settings_section'
		);

		/**
		 * Feature On Hold
		 */

		// add_settings_field(
		// 	'crowdriff_api_key',
		// 	'CrowdRiff API Key',
		// 	array( $this, 'crowdriff_api_key_callback'),
		// 	'general',
		// 	'visitweho_settings_section'
		// );

		add_settings_field(
			'visit_weho_api_key',
			'VisitWeho API Key',
			array( $this, 'visit_weho_api_key_callback'),
			'general',
			'visitweho_settings_section'
		);

		add_settings_field(
			'system_api_encryption_key',
			'System API Encryption Key',
			array( $this, 'system_api_encryption_key_callback'),
			'general',
			'visitweho_settings_section'
		);

		register_setting( 'general', 'google_api_key' );
		register_setting( 'general', 'ares_key' );
		register_setting( 'general', 'mailchimp_api_key' );
		register_setting( 'general', 'instagram_access_token' );
		register_setting( 'general', 'yelp_access_token' );
		register_setting( 'general', 'opentable_referral_id' );
		register_setting( 'general', 'opentable_partner_id' );
		register_setting( 'general', 'opentable_client_id' );
		register_setting( 'general', 'opentable_client_secret' );

		/**
		 * Feature On Hold
		 */
		// register_setting( 'general', 'crowdriff_api_key' );
		register_setting( 'general', 'system_api_encryption_key' );
		register_setting( 'general', 'visit_weho_api_key' );
	}

	public function visitweho_settings_section_callback_function() {
		echo '';
	}

	public function google_api_key_callback() {
		echo '<input size="60" name="google_api_key" id="google_api_key" type="text" value="' . get_option( 'google_api_key' ) . '" />';
	}

	public function ares_key_callback() {
		echo '<input size="60" name="ares_key" id="ares_key" type="text" value="' . get_option( 'ares_key' ) . '" />';
	}

	public function mailchimp_api_key_callback() {
		echo '<input size="60" name="mailchimp_api_key" id="mailchimp_api_key" type="text" value="' . get_option( 'mailchimp_api_key' ) . '" />';
	}
	public function instagram_access_token_callback() {
		echo '<input size="60" name="instagram_access_token" id="instagram_access_token" type="text" value="' . get_option( 'instagram_access_token' ) . '" />';
	}

	public function yelp_access_token_callback() {
		echo '<input size="60" name="yelp_access_token" id="yelp_access_token" type="text" value="' . get_option( 'yelp_access_token' ) . '" />';
	}

	public function opentable_partner_id_callback() {
		echo '<input size="60" name="opentable_partner_id" id="opentable_partner_id" type="text" value="' . get_option( 'opentable_partner_id' ) . '" />';
	}

	public function opentable_referral_id_callback() {
		echo '<input size="60" name="opentable_referral_id" id="opentable_referral_id" type="text" value="' . get_option( 'opentable_referral_id' ) . '" />';
	}

	public function opentable_client_id_callback() {
		echo '<input size="60" name="opentable_client_id" id="opentable_client_id" type="text" value="' . get_option( 'opentable_client_id' ) . '" />';
	}

	public function opentable_client_secret_callback() {
		echo '<input size="60" name="opentable_client_secret" id="opentable_client_secret" type="text" value="' . get_option( 'opentable_client_secret' ) . '" />';
	}

	public function visit_weho_api_key_callback() {
		echo '<input size="60" name="visit_weho_api_key" id="visit_weho_api_key" type="text" value="' . get_option( 'visit_weho_api_key' ) . '" />';
	}

	/**
	 * Feature On Hold
	 */

	// public function crowdriff_api_key_callback() {
	// 	echo '<input size="60" name="crowdriff_api_key" id="crowdriff_api_key" type="text" value="' . get_option( 'crowdriff_api_key' ) . '" />';
	// }

	public function system_api_encryption_key_callback() {
		echo '<span style="font-weight:bold; color: red;">Changing the encryption token will invalidate all existing user short-term access tokens. Proceed with caution</span><br>';
		echo '<input size="60" name="system_api_encryption_key" readonly="readonly" id="system_api_encryption_key" type="text" value="' . get_option( 'system_api_encryption_key' ) . '" />';
		echo '<button class="button generate-encryption-key">Generate New Encryption Key</button>';

		?>
		<script>
			jQuery(document).ready( function($) {
				$('.generate-encryption-key').on('click', function(e) {
					e.preventDefault();
					var col = $(this).parent();
					$.ajax({
						type: 'post',
						url: ajaxurl + '?action=generate_new_encryption_key',
					})
					.done( function( response ) {
						var json = JSON.parse(response);
						var encryption_key = json.encryption_key;
						col.find('input[name="system_api_encryption_key"]').val(encryption_key);
					});
				});
			});
		</script>

		<?php
	}

	public function register_tinymce_buttons( $buttons ) {
		array_push( $buttons, 'attach-post-location' );
		array_push( $buttons, 'insert-video-shortcode' );

		return $buttons;
	}

	public function register_tinymce_script( $plugin_array ) {
		$plugin_array['attach_post_location'] = plugins_url( '/js/attach-post-location.js',__FILE__ );
		$plugin_array['insert_video_shortcode'] = plugins_url( '/js/insert-video-shortcode.js',__FILE__ );
		return $plugin_array;
	}

	public function get_attachable_locations() {
		$search = $_GET['term'];
		$visit_weho = new VisitWeho();
		$output = array();

		$args = array(
			's' => $search,
			'post_type' => array('location','hotel'),
			'posts_per_page' => 20,
		);

		$locations = get_posts( $args );

		foreach( $locations as $location ) {
			$suggestion = array();
			$suggestion['label'] = $location->post_title;
			$suggestion['value'] = $location->ID;

			$output[] = $suggestion;
		}

		echo json_encode( $output );
		wp_die();
	}

	public function extend_user_profile( $profileuser ) {

		$existing_photo = get_user_meta( $profileuser->ID, 'author_photo', true );
		$author_link_slug = get_user_meta( $profileuser->ID, 'author_link_slug', true );

		$img_output = '';

		if( $existing_photo ) {
			$img = wp_get_attachment_image_src( $existing_photo, 'thumbnail' );
			$img_output = '<img src="' . $img[0] . '" />';
		}

		echo '<table class="form-table">
			<tr class="author_link_slug">
				<th>
					<label for="author_link_slug">Author Link Slug</label>
				</th>
				<td>
					<input type="text" name="author_link_slug" id="author_link_slug" value="' . $author_link_slug . '" class="regular-text" required />
				</td>
			</tr>
		</table>';

		echo '<table class="form-table">
			<tr class="author_photo">
				<th>
					<label for="author_photo">Author Photo</label>
				</th>
				<td>
					<input type="file" name="author_photo" id="author_photo" value="" class="regular-text" />
					<br><br>' . $img_output . '
				</td>
			</tr>
		</table>';
		?>
		<script>
			document.getElementById("your-profile").enctype = "multipart/form-data";
			jQuery(document).ready( function($) {
				var authorPhoto = $('.author_photo');
				$('.user-profile-picture').after(authorPhoto);
				$('.user-profile-picture').hide();

				var authorSlug = $('.author_link_slug');
				$('.user-profile-picture').after(authorSlug);
				$('.user-profile-picture').hide();
			});
		</script>
		<?php
	}

	public function user_profile_edit($user_id) {

		// echo '<pre>';
		// print_r( $_FILES );
		// echo '</pre>';

		// exit;

		$data = array();
		$author_link_slug = esc_attr( $_POST['author_link_slug'] );

		if( empty( $author_link_slug ) ) {
			$fname = get_user_meta( $user_id, 'first_name', true );
			$lname = get_user_meta( $user_id, 'last_name', true );

			$author_link_slug = sanitize_title_with_dashes( $fname . ' ' . $lname );
			if( empty( $fname || $lname ) ) {
				$author_link_slug = get_user_meta( $user_id, 'user_nicename', true );
			}
		}

		if( !empty( $_FILES['author_photo']['tmp_name'] ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			$attachment_id = media_handle_upload( 'author_photo', $post_id );

			if ( is_wp_error( $attachment_id ) ) {
				echo 'Something went wrong with your file upload<br><br>';
				echo $attachment_id->get_error_message();
				wp_die();
			}

			$data['author_photo'] = $attachment_id;

		}

		$data['author_link_slug'] = $author_link_slug;

		foreach( $data as $k => $v ) {
			update_user_meta( $user_id, $k, $v );
		}

	}

	public function custom_image_size_select( $sizes ) {
		return array_merge( $sizes, array(
			'hero' => __('Hero'),
			'widescreen-crop' => __('Widescreen Crop'),
			'square' => __('Square'),
		) );
	}

	public function add_tools_menu() {
		add_submenu_page( 'tools.php', 'DOLA Tools', 'DOLA Tools', 'manage_options', '?page=vw_tools', array( $this, 'tools_page' ) );
	}

	public function tools_page() { ?>

		<div class="wrap">
			<h1>DOLA Tools</h1>

			<ul>
				<li><a class="btn button trigger-events-fetch">Trigger Events Fetch</a></li>
				<li><a class="btn button trigger-venue-fetch">Trigger Venue Fetch</a></li>
			</ul>
		</div>

	<?php }


	public function vw_video_block_init() {
		// Skip block registration if Gutenberg is not enabled/merged.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$vw = new VisitWeho();

		//$version = '1.0';

		//$dir = $vw->plugin_path . 'blocks';
		$dir = plugins_url() . '/visitweho/blocks/vw-video/';

		$index_js = $dir . 'index.js';
		wp_register_script(
			'vw-video-block-editor',
			$index_js,
			array(
				'wp-blocks',
				'wp-i18n',
				'wp-element',
			)
		);

		$editor_css = $dir .  'editor.css';
		wp_register_style(
			'vw-video-block-editor',
			$editor_css,
			array()
		);

		$style_css = $dir .  'style.css';
		wp_register_style(
			'vw-video-block',
			$style_css,
			array()
		);

		register_block_type( 'visitweho/vw-video', array(
			'attributes'      => array(
				'video_url'      => array(
					'type' => 'string',
				),
				'video_id'       => array(
					'type' => 'string',
				),
				'autoplay'       => array(
					'type' => 'boolean',
				),
				'auto_caption'   => array(
					'type' => 'boolean',
				),
				'auto_mute' => array(
					'type' => 'boolean',
				),
			),
			'editor_script' => 'vw-video-block-editor',
			'editor_style'  => 'vw-video-block-editor',
			'style'         => 'vw-video-block',
		) );

	}

	/**
	 * Adds the menu page
	 */
	public function admin_menu_pages() {
		add_menu_page( 'API Access', 'API Access', 'manage_options', 'api-access', array($this, 'api_management_content'), 'dashicons-lock', 6  );
		// add_submenu_page( 'dam', 'Credentials', 'Credentials',
	//   'manage_options', 'dam/creds', array($this, 'asset_management_creds_content'));
		// add_submenu_page( 'dam', 'Access Control', 'Access Control',
	//   'manage_options', 'dam/access', array($this, 'asset_management_control_content'));
	}

	public function api_management_content() {
		?>
		<?php add_thickbox(); ?>
		<style>
			.wrap .button.add-org {
				margin-top: 1em;
				margin-bottom: 1em;
				display: inline-block;
			}
			.api-table td {
				vertical-align: middle;
			}

			#add-org-form h4,
			#edit-org-form h4 {
				margin: 0.5em 0;
			}

			#add-org-form button[type="submit"],
			#edit-org-form button[type="submit"] {
				position: absolute;
				bottom: 10px;
				right: 10px;
			}

			button.generate-secret {
				margin: 0.75em 0!important;
			}

			#requests-list {
				height: 400px;
				overflow-y: auto;
			}

		</style>
		<script>
			jQuery(document).ready( function($) {
				$(document).on('click', '.delete-org', function(e) {
					var row,
							id;

					row = $(this).parent().parent();
					id = row.attr('data-id');
					if (window.confirm('Are you sure you want to delete this?')) {
					 	row.remove();

						$.ajax({
							type: 'post',
							url: ajaxurl + '?action=delete_organization',
							data: {
								'id' : id
							}
						})
						.done( function( response ) {
						});
					}
				});

				$('#add-org-form').on('submit', function(e) {
					e.preventDefault();
					var formData,
							name,
							email;
					formData = new FormData(e.target);

					$.ajax({
						type: 'post',
						url: '/wp-admin/admin-post.php',
						processData: false,
						contentType: false,
						data: formData
					})
					.done( function( response ) {
						console.log(response);
						console.log(JSON.parse(response));
						if( response !== false && response !== 'false' ) {
							$('#add-org-form input[name="org_name"]').val('');
							$('#add-org-form input[name="email_address"]').val('');
							var user = JSON.parse(response);
							$('.no-active').remove();
							var markup = '';
							markup += '<tr data-name="' + user.name + '" data-email="' + user.email + '" data-secret="' + user.client_secret + '" data-client-id="' + user.client_id + '" data-id="' + user.id + '">';
							markup += ' data-name="' + formData.get('org_name') + '"';
							markup += ' data-secret="' + formData.get('client_secret') + '">';
							markup += ' data-client-id="' + formData.get('client_id') + '">';
							markup += '<td>' + formData.get('org_name') + '</td>';
							markup += '<td>' + formData.get('email_address') + '</td>';
							markup += '<td>' + user.client_id + '</td>';
							markup += '<td>' + user.client_secret + '</td>';
							markup += '<td><a href="#TB_inline?&width=700&height=800&inlineId=edit-org-panel" class="button edit-org">Edit</a><button class="button delete-org">Delete</button></td>';
							markup += '<td>0</td>';
							markup += '</tr>';
							$('.tb-close-icon').trigger('click');
							$('table.api-table tbody').append(markup);
						}
					});

				});

				$(document).on('click','.edit-org',function(e) {
					var row,
							name,
							id,
							client_secret,
							client_id,
							email;

					row = $(this).parent().parent();
					name = row.attr('data-name');
					id = row.attr('data-id');
					client_secret = row.attr('data-secret');
					client_id = row.attr('data-client-id');
					email = row.attr('data-email');

					$('#edit-org-form input[name="org_name"]').val(name);
					$('#edit-org-form input[name="email_address"]').val(email);
					$('#edit-org-form input[name="client_id"]').val(client_id);
					$('#edit-org-form input[name="client_secret"]').val(client_secret);
					$('#edit-org-form input[name="org_id"]').val(id);

					tb_show('Edit Organization', '#TB_inline?&width=700&height=800&inlineId=edit-org-panel');

					$('#edit-org-form div#requests-list').html('');
					$.ajax({
						type: 'post',
						url: ajaxurl + '?action=fetch_client_requests',
						data: {
							'id' : id
						}
					})
					.done( function( response ) {
						var json = JSON.parse(response);
						var str = '';
						$.each(json, function(i, item) {
							str += item + '<br><br>';
						});

						$('#edit-org-form div#requests-list').html(str);


					});
				});

				$('#edit-org-form').on('submit', function(e) {
					e.preventDefault();
					var formData,
							name,
							email,
							id,
							token;
					formData = new FormData(e.target);

					$.ajax({
						type: 'post',
						url: '/wp-admin/admin-post.php',
						processData: false,
						contentType: false,
						data: formData
					})
					.done( function( response ) {
						console.log(response);
						if( response !== false && response !== 'false' ) {
							$('.tb-close-icon').trigger('click');
							setTimeout( function(e) {
								window.location.reload();
							}, 750 );

						}
					});
				});

				$('.generate-secret').on('click', function(e) {
					e.preventDefault();
					var form,
							id;

					form = $(this).parent();
					id = form.find('input[name="org_id"]').val();

						$.ajax({
							type: 'post',
							url: ajaxurl + '?action=generate_new_secret',
							data: {
								'id' : id
							}
						})
						.done( function( response ) {
							var json = JSON.parse(response);
							var token = json.client_secret;
							form.find('input[name="client_secret"]').val(token);
						});
				});

			});
		</script>

		<div id="add-org-panel" style="display:none;">
			<h2>Add Organization</h2>
			<form id="add-org-form" method="post">
				<h4>Organization Name</h4>
				<input type="text" class="widefat" name="org_name" />
				<input type="hidden" name="action" value="add_organization">
				<h4>Email Address</h4>
				<input type="email" class="widefat" name="email_address" />
				<button class="button" type="submit">Create</button>
			</form>
		</div>

		<div id="edit-org-panel" style="display:none;">
			<h2>Edit Organization</h2>
			<form id="edit-org-form">
				<input type="hidden" name="action" value="edit_organization">
				<input type="hidden" name="org_id" value="">
				<h4>Organization Name</h4>
				<input type="text" class="widefat" name="org_name" />

				<h4>Email Address</h4>
				<input type="email" class="widefat" name="email_address" />

				<h4>Client ID</h4>
				<input type="text" readonly="readonly" class="widefat" name="client_id" />

				<h4>Client Secret</h4>
				<input type="text" readonly="readonly" class="widefat" name="client_secret" />

				<button class="button generate-secret">Generate New Secret</button>

				<h5>Requests</h5>
				<div class="widefat" id="requests-list"></div>
				<button class="button" type="submit">Save</button>
			</form>
		</div>

		<div class="wrap">
			<h1>API Access Management</h1>
			<a href="#TB_inline?&width=700&height=800&inlineId=add-org-panel" class="button add-org thickbox">
				Add Organization
			</a>
			<br>
			<table class="widefat fixed api-table" cellspacing="0">
			<thead>
			<tr class="alternate">
				<td>Organization</td>
				<td>Email</td>
				<td>Client ID</td>
				<td>Client Secret</td>
				<td>Actions</td>
				<td>Requests</td>
			</tr>
			</thead>

			<?php
				$args = array(
					'post_type' => 'api-client',
					'posts_per_page' => -1,
				);

				$clients = get_posts( $args );
				$count = 0;

				if( $clients ) { ?>
					<tbody>

						<?php foreach( $clients as $client ) {
							$count++;
							if( $number % 2 == 0 ) {
								$tr_class = '';
							} else {
								$tr_class = '';
							}

							if( empty( get_post_meta( $client->ID, 'requests', true )) ) {
								$request_count = 0;
							} else {
								$request_count = count( get_post_meta( $client->ID, 'requests', true ) );
							}

							$name = get_post_meta($client->ID, 'organization_name', true );
							$email = get_post_meta($client->ID, 'email_address', true );
							$secret = get_post_meta($client->ID, '_client_secret', true );
							$client_id = get_post_meta($client->ID, '_client_id', true );

							echo '<tr data-name="' . $name . '" data-email="' . $email . '" data-secret="' . $secret . '" data-client-id="' . $client_id . '" data-id="' . $client->ID . '" class="' . $tr_class . '">';
							echo '<td>' . get_post_meta($client->ID, 'organization_name', true ) . '</td>';
							echo '<td>' . get_post_meta($client->ID, 'email_address', true ) . '</td>';
							echo '<td>' . get_post_meta($client->ID, '_client_id', true ) . '</td>';
							echo '<td>' . get_post_meta($client->ID, '_client_secret', true ) . '</td>';
							echo '<td>
											<a href="#TB_inline?&width=700&height=800&inlineId=edit-org-panel" class="button edit-org">
												Edit
											</a>
											<button class="button delete-org">Delete</button>
										</td>';
							echo '<td>' . $request_count . '</td>';
							echo '</tr>';
						} ?>

					</tbody>

				<?php } else { ?>
					<tbody><tr class="no-active"><td><h3>No active API clients</h3></td></tr></tbody>
				<?php } ?>

			</table>
		</div>
		<?php
	}

	public function process_add_organization() {
		$org_name = esc_attr( $_POST['org_name'] );
		$email = esc_attr( $_POST['email_address'] );

		$client_id = wp_generate_password( 28, false ) . strtotime('now');
		$client_secret = bin2hex( random_bytes(32) );
		$r = array(
			'post_title' => $org_name . '-' . strtotime('now'),
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => 'api-client'
		);

		$id = wp_insert_post( $r );

		if( is_wp_error( $id ) ) {
			echo json_encode(false);
			die();
		}

		update_post_meta( $id, 'email_address', $email );
		update_post_meta( $id, 'organization_name', $org_name );
		update_post_meta( $id, '_client_secret', $client_secret );
		update_post_meta( $id, '_client_id', $client_id );

		$a = array(
			'id' => $id,
			'client_secret' => $client_secret,
			'client_id' => $client_id,
			'name' => $org_name,
			'email' => $email,
		);

		echo json_encode( $a );
		die();

	}

	public function process_edit_organization() {
		$org_name = esc_attr( $_POST['org_name'] );
		$org_id = esc_attr( $_POST['org_id'] );
		$email = esc_attr( $_POST['email_address'] );
		$org_name = esc_attr( $_POST['org_name'] );
		$client_secret = esc_attr( $_POST['client_secret'] );

		$r = array(
			'ID' => $org_id,
			'post_title' => $org_name . '-' . strtotime('now'),
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => 'api-client'
		);

		$id = wp_update_post( $r );

		if( is_wp_error( $id ) ) {
			echo json_encode(false);
			die();
		}

		update_post_meta( $id, 'email_address', $email );
		update_post_meta( $id, 'organization_name', $org_name );
		update_post_meta( $id, '_client_secret', $client_secret );

		echo json_encode( true );
		die();

	}

	public function delete_organization() {
		$id = esc_attr( $_POST['id'] );
		$client = get_post( $id );
		if( 'api-client' == get_post_type( $id ) ) {

			if( current_user_can('manage_options' ) ) {
				wp_delete_post( $id, true);
			} else {
				echo json_encode( 'poop' );
				wp_die();
			}
		}

		echo json_encode( 'happy' );
		wp_die();
	}

	public function generate_new_secret() {
		$client_secret = bin2hex( random_bytes(32) );
		echo json_encode( array('client_secret' => $client_secret ) );
		wp_die();
	}

	public function generate_new_encryption_key() {

		if( !current_user_can( 'manage_options' ) ) {
			return false;
			wp_die();
		}

		$encryption_key = bin2hex( random_bytes(32) );
		echo json_encode( array('encryption_key' => $encryption_key ) );
		wp_die();
	}

	public function fetch_client_requests() {
		$id = esc_attr( $_POST['id'] );

		$requests = get_post_meta( $id, 'requests', true );

		if( empty( $requests ) ) {
			$requests = array();
		}

		echo json_encode( $requests );
		wp_die();
	}

	public function enable_nav_description_html( $menu_item ) {
		if ( isset( $menu_item->post_type ) ) {
			if ( 'nav_menu_item' == $menu_item->post_type ) {
				$menu_item->description = $menu_item->post_content;
			}
		}

		return $menu_item;
	}

	public function mega_menu_fields( $item_id, $item ) {
		wp_nonce_field( 'mega_menu_fields_nonce', 'mega_menu_fields_nonce_name' );
		$offer_id = get_post_meta( $item_id, 'offer_id', true );
		?>

		<?php if( $item->title == 'Special Offer' ): ?>
			<div class="field-custom_menu_meta description-wide" style="margin: 5px 0;">
				<span class="description"><strong>Offer ID</strong></span>
				<br>
				<input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />
				<div class="logged-input-holder">
					<input type="text" name="offer_id[<?php echo $item_id ;?>]" id="custom-menu-meta-for-<?php echo $item_id ;?>" value="<?php echo esc_attr( $offer_id ); ?>" />
				</div>
			</div>
		<?php endif; ?>

		<?php
	}

	function mega_menu_fields_update( $menu_id, $menu_item_db_id ) {
		// Verify this came from our screen and with proper authorization.
		if ( ! isset( $_POST['mega_menu_fields_nonce_name'] ) || ! wp_verify_nonce( $_POST['mega_menu_fields_nonce_name'], 'mega_menu_fields_nonce' ) ) {
			return $menu_id;
		}

		if ( isset( $_POST['offer_id'][$menu_item_db_id]  ) ) {
			$sanitized_data = sanitize_text_field( $_POST['offer_id'][$menu_item_db_id] );
			update_post_meta( $menu_item_db_id, 'offer_id', $sanitized_data );
		} else {
			delete_post_meta( $menu_item_db_id, 'offer_id' );
		}
	}

	// public function asset_management_creds_content() {
	// 	echo '<div id="dam-creds"></div>';
	// }

	// public function asset_management_control_content() {
	// 	echo '<div id="dam-access"></div>';
	// }

}
