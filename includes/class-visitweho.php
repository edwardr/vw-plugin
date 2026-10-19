<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      VisitWeho_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		date_default_timezone_set('America/Los_Angeles');
		$this->plugin_name = 'visitweho';
		$this->version = '2.15.0';
		// TODO remove this
		$key = get_option('visit_weho_api_key');
		$this->visit_weho_api_key = $key;
		$this->system_api_encryption_key = get_option('system_api_encryption_key');
		$this->google_api_key = get_option('google_api_key');
		$this->ares_key = get_option('ares_key');
		$this->yelp_access_token = get_option('yelp_access_token');
		$this->mailchimp_api_key = get_option('mailchimp_api_key');
		/**
		 * Feature On Hold
		 */
		//$this->crowdriff_api_key = get_option('crowdriff_api_key');
		$this->opentable_partner_id = get_option('opentable_partner_id');
		$this->opentable_referral_id = get_option('opentable_referral_id');
		$this->opentable_client_id = get_option('opentable_client_id');
		$this->opentable_client_secret = get_option('opentable_client_secret');
		$this->latitude = '34.0823377';
		$this->longitude = '-118.3823671';
		$this->available_dola_venues = get_option('dola_venues');
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->plugin_path = plugin_dir_path( dirname( __FILE__ ) );
		$this->fallback_image = '/wp-content/plugins/visitweho/public/assets/content-fallback.jpg';
		$this->cloudfront_base_url = 'https://d2pzzspvx6hqws.cloudfront.net/';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - VisitWeho_Loader. Orchestrates the hooks of the plugin.
	 * - VisitWeho_i18n. Defines internationalization functionality.
	 * - VisitWeho_Admin. Defines all hooks for the admin area.
	 * - VisitWeho_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-visitweho-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-visitweho-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-visitweho-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-visitweho-public.php';

		/**
		 * Composer autoloader
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';


		$this->loader = new VisitWeho_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the VisitWeho_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new VisitWeho_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new VisitWeho_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'weho_meta_boxes' );
		$this->loader->add_action( 'save_post_hotel', $plugin_admin, 'hotel_meta_save' );
		$this->loader->add_action( 'save_post_event', $plugin_admin, 'event_meta_save' );
		$this->loader->add_action( 'save_post_location', $plugin_admin, 'location_meta_save' );
		$this->loader->add_action( 'save_post_offer', $plugin_admin, 'offer_meta_save' );
		$this->loader->add_action( 'save_post_post', $plugin_admin, 'story_meta_save' );
		$this->loader->add_action( 'save_post_page', $plugin_admin, 'home_meta_save' );
		$this->loader->add_action( 'save_post_page', $plugin_admin, 'page_meta_save' );
		$this->loader->add_action( 'save_post_video', $plugin_admin, 'video_meta_save' );
		$this->loader->add_action( 'save_post_home-slide', $plugin_admin, 'slide_meta_save' );

		$this->loader->add_action( 'save_post_activity', $plugin_admin, 'activity_meta_save' );
		$this->loader->add_action( 'save_post_collection', $plugin_admin, 'collection_meta_save' );

		$this->loader->add_action( 'venue_add_form_fields', $plugin_admin, 'add_venue_meta' );
		$this->loader->add_action( 'created_venue', $plugin_admin, 'save_venue_meta', 10, 2 );
		$this->loader->add_action( 'venue_edit_form_fields', $plugin_admin, 'edit_venue_meta', 1, 2 );
		$this->loader->add_action( 'edited_venue', $plugin_admin, 'edit_save_venue_meta', 10, 2 );

		$this->loader->add_action( 'neighborhood_add_form_fields', $plugin_admin, 'add_neighborhood_meta' );
		$this->loader->add_action( 'created_neighborhood', $plugin_admin, 'save_neighborhood_meta', 10, 2 );
		$this->loader->add_action( 'neighborhood_edit_form_fields', $plugin_admin, 'edit_neighborhood_meta', 1, 2 );
		$this->loader->add_action( 'edited_neighborhood', $plugin_admin, 'edit_save_neighborhood_meta', 10, 2 );

		$this->loader->add_action( 'manage_hotel_posts_columns', $plugin_admin, 'hotel_admin_columns' );
		$this->loader->add_action( 'manage_hotel_posts_custom_column', $plugin_admin, 'hotel_admin_column_data', 10, 2);
		$this->loader->add_action( 'manage_edit-hotel_sortable_columns', $plugin_admin, 'hotel_sortable_columns' );
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'hotel_admin_sort' );
		$this->loader->add_action( 'manage_offer_posts_columns', $plugin_admin, 'offer_admin_columns' );
		$this->loader->add_action( 'manage_offer_posts_custom_column', $plugin_admin, 'offer_admin_column_data', 10, 2);
		$this->loader->add_action( 'admin_init', $plugin_admin, 'visitweho_settings_init' );

		$this->loader->add_action( 'manage_event_posts_columns', $plugin_admin, 'event_admin_columns' );
		$this->loader->add_action( 'manage_event_posts_custom_column', $plugin_admin, 'event_admin_column_data', 10, 2);
		$this->loader->add_action( 'manage_edit-event_sortable_columns', $plugin_admin, 'event_sortable_columns' );
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'event_admin_sort' );

		$this->loader->add_action( 'wp_ajax_get_attachable_locations', $plugin_admin, 'get_attachable_locations' );
		$this->loader->add_filter( 'mce_buttons', $plugin_admin, 'register_tinymce_buttons', 99 );
		$this->loader->add_filter( 'mce_external_plugins', $plugin_admin, 'register_tinymce_script', 99 );

		$this->loader->add_filter( 'image_size_names_choose', $plugin_admin, 'custom_image_size_select' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_tools_menu' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'wp_enqueue_editor' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu_pages' );

		$this->loader->add_action( 'admin_post_add_organization', $plugin_admin, 'process_add_organization' );
		$this->loader->add_action( 'admin_post_edit_organization', $plugin_admin, 'process_edit_organization' );
		$this->loader->add_action( 'wp_ajax_delete_organization', $plugin_admin, 'delete_organization' );
		$this->loader->add_action( 'wp_ajax_generate_new_secret', $plugin_admin, 'generate_new_secret' );
		$this->loader->add_action( 'wp_ajax_generate_new_encryption_key', $plugin_admin, 'generate_new_encryption_key' );
		$this->loader->add_action( 'wp_ajax_fetch_client_requests', $plugin_admin, 'fetch_client_requests' );

		$this->loader->add_action( 'wp_nav_menu_item_custom_fields', $plugin_admin, 'mega_menu_fields', 10, 2 );
		$this->loader->add_action( 'wp_update_nav_menu_item', $plugin_admin, 'mega_menu_fields_update', 10, 2 );

		/** Yoast Open Graph */

		add_filter( 'wpseo_opengraph_image_size', function( $string ) { return 'large'; } );

		/** Author Photo */
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'extend_user_profile', 5 );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'extend_user_profile', 5 );
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'user_profile_edit' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'user_profile_edit' );
		//$this->loader->add_action( 'init', $plugin_admin, 'vw_video_block_init' );

		$this->loader->add_filter( 'wp_setup_nav_menu_item', $plugin_admin, 'enable_nav_description_html' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function define_public_hooks() {

		$plugin_public = new VisitWeho_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'page_script' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'gtm_no_script', $plugin_public, 'gtm_no_script' );
		$this->loader->add_action( 'init', $plugin_public, 'register_post_types' );
		$this->loader->add_action( 'init', $plugin_public, 'register_taxonomies' );
		$this->loader->add_action( 'init', $plugin_public, 'schedule_cron_tasks' );
		$this->loader->add_action( 'init', $plugin_public, 'custom_image_sizes' );
		$this->loader->add_action( 'wp', $plugin_public, 'page_filtering' );

		/**
		 * Disabled - Feature on Hold
		 */
		// $this->loader->add_action( 'crowdriff_api_fetch', $plugin_public, 'crowdriff_api_app_fetch' );
		// $this->loader->add_action( 'crowdriff_api_fetch', $plugin_public, 'crowdriff_api_cta_fetch' );
		//
		$this->loader->add_action( 'dola_events_fetch', $plugin_public, 'dola_events_fetch' );

		$this->loader->add_action( 'register_menu_widgets', $plugin_public, 'register_menu_widgets' );

		$this->loader->add_action( 'dola_available_venue_fetch', $plugin_public, 'dola_available_venue_fetch' );

		$this->loader->add_action( 'expire_hotel_offers', $plugin_public, 'expire_hotel_offers' );
		$this->loader->add_action( 'expire_events', $plugin_public, 'expire_events' );
		$this->loader->add_action( 'fetch_hotel_prices', $plugin_public, 'fetch_hotel_prices' );
		$this->loader->add_action( 'fetch_opentable_listings', $plugin_public, 'fetch_opentable_listings' );
		$this->loader->add_action( 'delete_wp_oembed_cache', $plugin_public, 'delete_wp_oembed_cache' );
		$this->loader->add_action( 'fetch_yelp_ratings', $plugin_public, 'fetch_yelp_ratings' );
		$this->loader->add_action( 'fetch_yelp_access_token', $plugin_public, 'fetch_yelp_access_token' );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'register_weho_rest_routes' );
		$this->loader->add_filter( 'cron_schedules', $plugin_public, 'available_cron_schedules' );
		$this->loader->add_filter( 'rest_url_prefix', $plugin_public, 'rest_api_base_prefix' );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'rest_api_cors', 15);

		$this->loader->add_shortcode( 'story-location', $plugin_public, 'story_location_shortcode' );
		$this->loader->add_shortcode( 'vw-youtube', $plugin_public, 'video_shortcode' );
		$this->loader->add_shortcode( 'crowdriff-app', $plugin_public, 'crowdriff_shortcode' );
		$this->loader->add_filter( 'oembed_result', $plugin_public, 'customize_embed', 10, 3 );
		$this->loader->add_action( 'wp_ajax_mc_subscribe', $plugin_public, 'mc_subscription' );
		$this->loader->add_action( 'wp_ajax_nopriv_mc_subscribe', $plugin_public, 'mc_subscription' );

		$this->loader->add_action( 'wp_ajax_data_navigation', $plugin_public, 'ajax_data_navigation' );
		$this->loader->add_action( 'wp_ajax_nopriv_data_navigation', $plugin_public, 'ajax_data_navigation' );
		$this->loader->add_filter( 'wpseo_json_ld_output', $plugin_public, 'yoast_json', 10, 2 );

		// Author URL modification
		$this->loader->add_filter( 'author_link', $plugin_public, 'author_url_profile_mod', 10, 3 );
		$this->loader->add_filter( 'request', $plugin_public, 'author_url_request_mod', 10, 3 );

		$this->loader->add_filter( 'the_content', $plugin_public, 'slideshow_page_content' );
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'slideshow_query_mod', 100, 1 );

		$this->loader->add_filter( 'wpseo_opengraph_image', $plugin_public, 'yoast_og_filter' );

		//$this->loader->add_filter( 'post_type_link', $plugin_public, 'offer_slug_mod', 10, 2 );
		//$this->loader->add_action( 'pre_get_posts', $plugin_public, 'offer_slug_parse' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'templates_redirect', 9999);
		$this->loader->add_action( 'wp', $plugin_public, 'handle_event_404');
		$this->loader->add_filter( 'wp_default_scripts', $plugin_public, 'default_script_mods', 999);

		$this->loader->add_filter( 'autoptimize_filter_noptimize', $plugin_public, 'disable_autoptimize_amp', 999);

		$this->loader->add_action( 'generate_mega_menu_markup_cron', $plugin_public, 'generate_mega_menu_markup_cron');

		$this->loader->add_action( 'wp_update_nav_menu', $plugin_public, 'mega_menu_save' );

		$this->loader->add_filter( 'nonce_life', $plugin_public, 'extend_nonce' );

		$this->loader->add_action( 'clear_cache_cron', $plugin_public, 'clear_cache_cron' );
		$this->loader->add_action( 'save_location_stories', $plugin_public, 'save_location_stories' );
		$this->loader->add_action( 'save_hotel_stories', $plugin_public, 'save_hotel_stories' );
		//$this->loader->add_filter( 'intermediate_image_sizes_advanced', $plugin_public, 'filter_wp_images' );
		$this->loader->add_action( 'wpseo_register_extra_replacements', $plugin_public, 'register_yoast_variables', 10, 4);

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    VisitWeho_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_hotel_offer( $hotel_id ) {

		$meta_query = array();

		$meta_query[] = array(
			'key' => 'hotel_ids',
			'value' => $hotel_id,
			'compare' => 'LIKE',
		);

		$query_args = array(
			'post_type'      => 'offer',
			'posts_per_page' => -1,
			'meta_query'     => $meta_query,
		);

		$offers = get_posts( $query_args );

		$return_offer = array();

		foreach( $offers as $offer ) {
			$o = new VisitWeho_Offer( $offer->ID );
			if( empty( $o->expiration ) || $o->expiration >= strtotime('now') ) {
				$return_offer[] = $o;
			}
		}

		return $return_offer;
	}

	public function get_events( $args = array() ) {

		$defaults = array(
			'search'       => '',
			'items'        => -1,
			'offset'       => 0,
			'order'        => 'ASC',
			'orderby'      => 'begin_time',
			'venues'       => array(),
			'categories'   => array(),
			'neighborhoods' => array(),
			'tags' => array(),
			'is_free'      => '',
			'is_featured' => '',
			'is_ongoing'   => '',
			'rsvp'         => '',
			'month'        => '',
			'day'          => '',
			'year'         => '',
			'date_between' => array(),
			'not_in' => array(),
			'fill_items' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = array('relation' => 'AND');
		$tax_query = array();

		$month = empty( $args['month'] ) ? false : $args['month'];
		$day = empty( $args['day'] ) ? false : $args['day'];
		$year = empty( $args['year'] ) ? false : $args['year'];

		$process_date = false;

		// Taxonomies
		if(!empty( $args['venues'] ) ) {

			foreach( (array)$args['venues'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}
			$tax = array(
				'taxonomy' => 'venue',
				'field'    => $field,
				'terms'    => $args['venues'],
				'operator' => 'IN',
				);
			$tax_query[] = $tax;
		}

		if(!empty( $args['categories'] ) ) {

			$args['categories'] = array_filter( $args['categories'] );

			foreach( (array)$args['categories'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}

			$tax = array(
				'taxonomy' => 'event-category',
				'field'    => $field,
				'terms'    => $args['categories'],
				'operator' => 'IN',
				);

			$tax_query[] = $tax;
		}

		if(!empty( $args['neighborhoods'] ) ) {

			if( !is_array( $args['neighborhoods'] ) ) {
				$args['neighborhoods'] = explode(',', $args['neighborhoods'] );
			}

			$args['neighborhoods'] = array_filter( $args['neighborhoods'] );

			$nids = array();

			foreach( $args['neighborhoods'] as $k => $v ) {

				$t = get_term_by('slug', $v, 'neighborhood' );

				$nids[] = $t->term_id;

				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}

			// Query the venues in these neighborhoods

			$venue_args = array(
				'taxonomy'   => 'venue',
				'hide_empty' => false,
				'meta_query' => array(
					array(
						'key'       => 'neighborhood_id',
						'value'     => $nids,
						'compare'   => 'IN'
					)
				)
			);

			$t = get_terms( $venue_args );

			$tids = array();

			foreach( $t as $term ) {
				$tids[] = $term->term_id;
			}

			$tax = array(
				'taxonomy' => 'venue',
				'field'    => 'term_id',
				'terms'    => $tids,
				'operator' => 'IN',
				);

			$tax_query[] = $tax;
		}

		// Custom fields
		if( !empty( $args['is_featured'] ) ) {
				$metaq = array(
					'key'      => 'is_featured',
					'value'    => $args['is_featured'],
					'operator' => '=',
					);

				$meta_query[] = $metaq;
		}

		if( !empty( $args['is_free'] ) ) {
				$metaq = array(
					'key'      => 'is_free',
					'value'    => $args['is_free'],
					'operator' => '=',
					);

				$meta_query[] = $metaq;
		}

		if( !empty( $args['is_ongoing'] ) ) {
				$metaq = array(
					'key'      => 'is_ongoing',
					'value'    => $args['is_ongoing'],
					'operator' => '=',
					);

				$meta_query[] = $metaq;
		}

		if( !empty( $args['rsvp'] ) ) {
				$metaq = array(
					'key'      => 'rsvp',
					'value'    => $args['rsvp'],
					'operator' => '=',
					);

				$meta_query[] = $metaq;
		}

		if( $month || $day || $year ) {

			$date_args = array();

			if( $month ) {
				$date_args['month'] = $month;
			}

			if( $day ) {
				$date_args['day'] = $day;
			}

			if( $year ) {
				$date_args['year'] = $year;
			}

			foreach( $date_args as $k => $v ) {
				$metaq = array(
					'key' => $k,
					'value' => $v,
					'compare' => '=',
					);

				$meta_query[] = $metaq;
			}
		}

		if( !empty( $args['date_between'] ) ) {
				$first_date = strtotime($args['date_between'][0]);
				$last_date = strtotime($args['date_between'][1]);

				// Advance the last day to get later start times
				$last_date_mod = strtotime('+1 day', $last_date );

				$metaq = array(
					'key'      => 'begin_time',
					'value'    =>  array( $first_date, $last_date_mod ),
					'type' => 'numeric',
					'compare' => 'BETWEEN'
					);

				if( $first_date == $last_date ) {
					$plus_one_day = strtotime( '+1 day', $first_date );
					$metaq = array(
						'key'      => 'begin_time',
						'value'    =>  array( $first_date, $plus_one_day ),
						'type' => 'numeric',
						'compare' => 'BETWEEN'
						);
				}

				$meta_query[] = $metaq;
		}

		$query_args = array();

		if( $args['orderby'] == 'begin_time' ) {
			$orderby = 'meta_value_num';
		} else {
			$orderby = $args['orderby'];
		}

		$query_args['post_type'] = 'event';
		$query_args['posts_per_page'] = $args['items'];
		$query_args['offset'] = $args['offset'];

		if( $args['orderby'] == 'begin_time' ) {
			$query_args['meta_key'] = 'begin_time';
		}

		if( !empty( $args['not_in'] ) ) {
			$query_args['post__not_in'] = $args['not_in'];
		}

		$query_args['order'] = $args['order'];
		$query_args['orderby'] = $orderby;
		$query_args['meta_query'] = $meta_query;
		$query_args['tax_query'] = $tax_query;


		if( !empty( $args['search'] ) ) {
			$query_args['s'] = $args['search'];
		}

		$events = get_posts( $query_args );

		$count = count( $events );

		$return_events = array();

		$captured_ids = array();

		foreach( $events as $event ) {
			$e = new VisitWeho_Event( $event->ID );
			$captured_ids[] = $e->id;
			$return_events[] = $e;
		}

		/**
		 * Featured Fill Query
		 *
		 * Flag to fill featured-item queries with fallback events
		 * to equal the number of items requested in the fetch.
		 *
		 * TODO: Apply this fill setup to other types of Event queries
		 *
		 * TODO: Improve sorting handling. Featured fill flag overrides
		 * sorting to use begin_time
		 */

		if( $args['fill_items'] !== false ) {
			if( $count < $args['items'] ) {
				$number_to_query = $args['items'] - (int)$count;

				$fill_args = $query_args;
				$fill_args['posts_per_page'] = $number_to_query;
				$fill_args['post__not_in'] = $captured_ids;

				$fill_args['meta_query'] = array(
					array(
						'key' => 'is_featured',
						'compare' => 'NOT EXISTS',
					),
				);

				$fill_events = get_posts( $fill_args );

				foreach( $fill_events as $event ) {
					$e = new VisitWeho_Event( $event->ID );
					$return_events[] = $e;
				}

				usort($return_events, function($a, $b) {
					return strcmp($a->begin_time, $b->begin_time);
				});
			}
		}

		return $return_events;
	}

	public function get_max_events( $args ) {

		$defaults = array(
			'items'        => -1,
			'offset'       => 0,
			'order'        => 'ASC',
			'orderby'      => 'begin_time',
			'venues'       => array(),
			'categories'   => array(),
			'is_free'      => '',
			'is_ongoing'   => '',
			'rsvp'         => '',
			'month'        => '',
			'day'          => '',
			'year'         => '',
			'date_between' => array(),
			's'            => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = array();
		$tax_query = array();

		$month = empty( $args['month'] ) ? false : $args['month'];
		$day = empty( $args['day'] ) ? false : $args['day'];
		$year = empty( $args['year'] ) ? false : $args['year'];

		$process_date = false;

		// Taxonomies

		if(!empty( $args['venues'] ) ) {

			foreach( (array)$args['venues'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}
			$tax = array(
				'taxonomy' => 'venue',
				'field'    => $field,
				'terms'    => $args['venues'],
				'operator' => 'IN',
				);
			$tax_query[] = $tax;
		}

		if(!empty( $args['categories'] ) ) {
			foreach( (array)$args['categories'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}

			$tax = array(
				'taxonomy' => 'event-category',
				'field'    => $field,
				'terms'    => $args['categories'],
				'operator' => 'IN',
				);

			$tax_query[] = $tax;
		}

		// Custom fields

		if( !empty( $args['is_free'] ) ) {
				$metaq = array(
					'key'      => 'is_free',
					'value'    => $args['is_free'],
					'operator' => '=',
					);

				$meta_query[] = $metaq;
		}

		if( !empty( $args['is_ongoing'] ) ) {
				$metaq = array(
					'key'      => 'is_ongoing',
					'value'    => $args['is_ongoing'],
					'operator' => '=',
					);

				$meta_query[] = $metaq;
		}

		if( !empty( $args['rsvp'] ) ) {
				$metaq = array(
					'key'      => 'rsvp',
					'value'    => $args['rsvp'],
					'operator' => '=',
					);

				$meta_query[] = $metaq;
		}

		if( $month || $day || $year ) {

			$date_args = array();

			if( $month ) {
				$date_args['month'] = $month;
			}

			if( $day ) {
				$date_args['day'] = $day;
			}

			if( $year ) {
				$date_args['year'] = $year;
			}

			foreach( $date_args as $k => $v ) {
				$metaq = array(
					'key' => $k,
					'value' => $v,
					'compare' => '=',
					);

				$meta_query[] = $metaq;
			}
		}

		if( !empty( $args['date_between'] ) ) {
				$first_date = strtotime($args['date_between'][0]);
				$last_date = strtotime($args['date_between'][1]);

				// Advance the last day to get later start times
				$last_date_mod = strtotime('+1 day', $last_date );

				$metaq = array(
					'key'      => 'begin_time',
					'value'    =>  array( $first_date, $last_date_mod ),
					'type' => 'numeric',
					'compare' => 'BETWEEN'
					);

				if( $first_date == $last_date ) {
					$plus_one_day = strtotime( '+1 day', $first_date );
					$metaq = array(
						'key'      => 'begin_time',
						'value'    =>  array( $first_date, $plus_one_day ),
						'type' => 'numeric',
						'compare' => 'BETWEEN'
						);
				}

				$meta_query[] = $metaq;
		}

		if( $args['orderby'] == 'begin_time' ) {
			$orderby = 'meta_value_number';
		} else {
			$orderby = $args['orderby'];
		}

		$query_args = array();

		if( $args['orderby'] == 'begin_time' ) {
			$query_args['meta_key'] = 'begin_time';
		}

		$query_args['post_type'] = 'event';
		$query_args['posts_per_page'] = $args['items'];
		$query_args['offset'] = $args['offset'];
		$query_args['order'] = $args['order'];
		$query_args['orderby'] = $orderby;
		$query_args['meta_query'] = $meta_query;
		$query_args['tax_query'] = $tax_query;

		if( $args['s'] !== '' ) {
			$query_args['s'] = $args['s'];
		}

		$count = count( get_posts( $query_args ) );

		return $count;
	}

	public function get_locations( $args = array() ) {

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'ASC',
			'orderby'       => 'name',
			'neighborhoods' => array(),
			'types'         => array(),
			'tags'          => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = array();
		$tax_query = array();

		if(!empty( $args['neighborhoods'] ) ) {

			if( !is_array( $args['neighborhoods'] ) ) {
				$args['neighborhoods'] = (array)$args['neighborhoods'];
			}

			foreach( $args['neighborhoods'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}
			$tax = array(
				'taxonomy' => 'neighborhood',
				'field'    => $field,
				'terms'    => $args['neighborhoods'],
				'operator' => 'IN',
				);
			$tax_query[] = $tax;
		}

		if(!empty( $args['types'] ) ) {

			if( !is_array( $args['types'] ) ) {
				$args['types'] = (array)$args['types'];
			}

			foreach( $args['types'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}
			$tax = array(
				'taxonomy' => 'location-type',
				'field'    => $field,
				'terms'    => $args['types'],
				'operator' => 'IN',
				);
			$tax_query[] = $tax;
		}

		if(!empty( $args['tags'] ) ) {

			if( !is_array( $args['tags'] ) ) {
				$args['tags'] = (array)$args['tags'];
			}

			foreach( $args['tags'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
				$tax = array(
					'taxonomy' => 'location-tag',
					'field'    => $field,
					'terms'    => $v,
					'operator' => 'IN',
					);
				$tax_query[] = $tax;
			}
		}

		$query_args = array(
			'post_type'      => 'location',
			'posts_per_page' => $args['items'],
			'offset'         => $args['offset'],
			'order'          => $args['order'],
			'orderby'        => $args['orderby'],
			'meta_query'     => $meta_query,
			'tax_query'      => $tax_query,
		);

		$locations = get_posts( $query_args );
		$return_locations = array();

		foreach( $locations as $location ) {
			$l = new VisitWeho_Location( $location->ID );
			$return_locations[] = $l;
		}

		return $return_locations;
	}

	public function get_collections( $args = array() ) {

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'ASC',
			'orderby'       => 'name',
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = array();
		$tax_query = array();

		$query_args = array(
			'post_type'      => 'collection',
			'posts_per_page' => $args['items'],
			'offset'         => $args['offset'],
			'order'          => $args['order'],
			'orderby'        => $args['orderby'],
			'meta_query'     => $meta_query,
			'tax_query'      => $tax_query,
		);

		$locations = get_posts( $query_args );
		$return_collections = array();

		foreach( $collections as $collection ) {
			$l = new VisitWeho_Collection( $collection->ID );
			$return_collections[] = $l;
		}

		return $return_collections;
	}

	public function get_videos( $args = array() ) {

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'ASC',
			'orderby'       => 'name',
			'neighborhoods' => array(),
			'location_id'	  => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = array();
		$tax_query = array();

		if(!empty( $args['neighborhoods'] ) ) {

			if( !is_array( $args['neighborhoods'] ) ) {
				$args['neighborhoods'] = (array)$args['neighborhoods'];
			}

			foreach( $args['neighborhoods'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}
			$tax = array(
				'taxonomy' => 'neighborhood',
				'field'    => $field,
				'terms'    => $args['neighborhoods'],
				'operator' => 'IN',
				);
			$tax_query[] = $tax;
		}

		if( !empty( $args['location_id'] ) ) {
			$meta_query[] = array(
				'key' => 'location_ids',
				'value' => $args['location_id'],
				'compare' => 'LIKE',
			);
		}


		$query_args = array(
			'post_type'      => 'video',
			'posts_per_page' => $args['items'],
			'offset'         => $args['offset'],
			'order'          => $args['order'],
			'orderby'        => $args['orderby'],
			'meta_query'     => $meta_query,
			'tax_query'      => $tax_query,
		);

		$videos = get_posts( $query_args );
		$return_videos = array();

		foreach( $videos as $video ) {
			$v = new VisitWeho_Video( $video->ID );
			$return_videos[] = $v;
		}

		return $return_videos;
	}

	public function get_hotels( $args = array() ) {

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'ASC',
			'orderby'       => 'menu_order',
			'neighborhoods' => array(),
			'amenities'     => array(),
			'post__not_in'  => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = array();
		$tax_query = array();

		if(!empty( $args['neighborhoods'] ) ) {

			if( !is_array( $args['neighborhoods'] ) ) {
				$args['neighborhoods'] = (array)$args['neighborhoods'];
			}

			foreach( $args['neighborhoods'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}
			$tax = array(
				'taxonomy' => 'neighborhood',
				'field'    => $field,
				'terms'    => $args['neighborhoods'],
				'operator' => 'IN',
				);
			$tax_query[] = $tax;
		}

		if(!empty( $args['amenities'] ) ) {

			if( !is_array( $args['amenities'] ) ) {
				$args['amenities'] = (array)$args['amenities'];
			}

			foreach( $args['amenities'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
				$tax = array(
					'taxonomy' => 'amenity',
					'field'    => $field,
					'terms'    => $v,
					'operator' => 'IN',
					);
				$tax_query[] = $tax;
			}
		}

		$query_args = array(
			'post_type'      => 'hotel',
			'posts_per_page' => $args['items'],
			'offset'         => $args['offset'],
			'order'          => $args['order'],
			'orderby'        => $args['orderby'],
			'meta_query'     => $meta_query,
			'tax_query'      => $tax_query,
			'post__not_in'   => $args['post__not_in'],
		);

		$hotels = get_posts( $query_args );
		$return_hotels = array();

		foreach( $hotels as $hotel ) {
			$h = new VisitWeho_Hotel( $hotel->ID );
			$return_hotels[] = $h;
		}

		return $return_hotels;
	}

	public function get_stories( $args = array() ) {

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'DESC',
			'orderby'       => 'date',
			'neighborhoods' => array(),
			'tags'          => array(),
			'categories'    => array(),
			'post__in'      => array(),
			'location_id'   => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = array();
		$tax_query = array();

		if( !empty( $args['location_id'] ) ) {
			$meta_query[] = array(
				'key' => 'attached_locations',
				'value' => $args['location_id'],
				'compare' => 'LIKE',
			);
		}

		if(!empty( $args['neighborhoods'] ) ) {

			if( !is_array( $args['neighborhoods'] ) ) {
				$args['neighborhoods'] = (array)$args['neighborhoods'];
			}

			foreach( $args['neighborhoods'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
			}
			$tax = array(
				'taxonomy' => 'neighborhood',
				'field'    => $field,
				'terms'    => $args['neighborhoods'],
				'operator' => 'IN',
				);
			$tax_query[] = $tax;
		}

		if(!empty( $args['tags'] ) ) {

			if( !is_array( $args['tags'] ) ) {
				$args['tags'] = (array)$args['tags'];
			}

			foreach( $args['tags'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
				$tax = array(
					'taxonomy' => 'post_tag',
					'field'    => $field,
					'terms'    => $v,
					'operator' => 'IN',
					);
				$tax_query[] = $tax;
			}
		}

		if(!empty( $args['categories'] ) ) {

			if( !is_array( $args['categories'] ) ) {
				$args['categories'] = (array)$args['categories'];
			}

			foreach( $args['categories'] as $k => $v ) {
				if( is_numeric( $v ) ) {
					$field = 'id';
				} else {
					$field = 'slug';
				}
				$tax = array(
					'taxonomy' => 'category',
					'field'    => $field,
					'terms'    => $v,
					'operator' => 'IN',
					);
				$tax_query[] = $tax;
			}
		}

		$query_args = array(
			'post_type'      => 'post',
			'posts_per_page' => $args['items'],
			'offset'         => $args['offset'],
			'order'          => $args['order'],
			'orderby'        => $args['orderby'],
			'meta_query'     => $meta_query,
			'tax_query'      => $tax_query,
			'post__in'       => $args['post__in'],
		);

		$stories = get_posts( $query_args );
		$return_stories = array();

		foreach( $stories as $story ) {
			$s = new VisitWeho_Story( $story->ID );
			$return_stories[] = $s;
		}

		return $return_stories;
	}

	public function get_neighborhoods() {
		$terms = get_terms(
			array(
			'taxonomy' => 'neighborhood',
			'hide_empty' => false,
			)
		);

		return $terms;
	}

	public function get_venues( $args = array() ) {

		$defaults = array(
			'hide_empty' => false,
			'taxonomy'   => 'venue',
		);

		$args = wp_parse_args( $args, $defaults );

		$terms = get_terms( $args );

		$venue_arr = array();

		foreach( $terms as $term ) {
			$venue = new VisitWeho_Venue( $term->term_id );
			$venue_arr[] = $venue;
		}

		return $venue_arr;
	}

	public function get_event_categories() {

		$terms = get_terms(
			array(
			'taxonomy' => 'event-category',
			'hide_empty' => true,
			)
		);

		$event_cat_arr = array();

		foreach( $terms as $term ) {
			$event_cat = $term;
			$event_cat_arr[] = $event_cat;
		}

		return $event_cat_arr;
	}

	public function get_pages() {

		$query_args = array(
			'post_type' => 'page',
			'posts_per_page' => -1,
		);

		$pages = get_posts( $query_args );

		$return_pages = array();

		foreach( $pages as $page ) {
			$p = new VisitWeho_Page( $page->ID );
			$return_pages[] = $p;
		}

		return $return_pages;
	}

	public function get_states() {
		$state_list = array(
			'AL'=>"Alabama",
			'AK'=>"Alaska",
			'AZ'=>"Arizona",
			'AR'=>"Arkansas",
			'CA'=>"California",
			'CO'=>"Colorado",
			'CT'=>"Connecticut",
			'DE'=>"Delaware",
			'DC'=>"District Of Columbia",
			'FL'=>"Florida",
			'GA'=>"Georgia",
			'HI'=>"Hawaii",
			'ID'=>"Idaho",
			'IL'=>"Illinois",
			'IN'=>"Indiana",
			'IA'=>"Iowa",
			'KS'=>"Kansas",
			'KY'=>"Kentucky",
			'LA'=>"Louisiana",
			'ME'=>"Maine",
			'MD'=>"Maryland",
			'MA'=>"Massachusetts",
			'MI'=>"Michigan",
			'MN'=>"Minnesota",
			'MS'=>"Mississippi",
			'MO'=>"Missouri",
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",
			'OK'=>"Oklahoma",
			'OR'=>"Oregon",
			'PA'=>"Pennsylvania",
			'RI'=>"Rhode Island",
			'SC'=>"South Carolina",
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",
			'TX'=>"Texas",
			'UT'=>"Utah",
			'VT'=>"Vermont",
			'VA'=>"Virginia",
			'WA'=>"Washington",
			'WV'=>"West Virginia",
			'WI'=>"Wisconsin",
			'WY'=>"Wyoming"
		);

		return $state_list;
	}

	public function get_icons() {
		$r = maybe_unserialize( get_option('visitweho_icons') );

		return $r;
	}

	public function get_distance( $from, $to ) {

		$visit_weho = new VisitWeho();

		$url = 'https://maps.googleapis.com/maps/api/directions/json?origin=' . $from['lat'] . ',' . $from['lng'] . '&destination=' . $to['lat'] . ',' . $to['lng'] . '&sensor=true&key=' . $visit_weho->google_api_key;

		$file = json_decode( file_get_contents( $url, true ) );

		$distance = $file->routes[0]->legs[0]->distance->value;

		return $distance;

	}

	public function get_geolocation( $string, $use_latlng = false ) {
		$protocol = is_ssl() ? 'https:' : 'http:';

		if( $use_latlng ) {
			$url = $protocol . '//maps.google.com/maps/api/geocode/json?sensor=false&latlng=' . urlencode( $string ) . '&key=' . $this->google_api_key;
		} else {
			$url = $protocol . '//maps.google.com/maps/api/geocode/json?sensor=false&address=' . urlencode( $string ) . '&key=' . $this->google_api_key;
		}

		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);

		if($data['status'] == 'OK') {
			$return = $data['results'];
		}

		$return = $data['status'] == 'OK' ? $data['results'] : false;

		return $return;

	}

	public function format_phone_number( $number, $format = 'US' ) {
		$number_arr = str_split( $number );
		$return = '';

		if( $format == 'US' ) {
			foreach( $number_arr as $k => $v ) {
				if( $k == 3 ) {
					$return .= '-';
				}
				if( $k == 6 ) {
					$return .= '-';
				}
				$return .= $v;
			}
		}

		return $return;
	}

	public function available_dola_venues() {
		$venues = get_option('dola_venues');

		return json_decode( $venues );
	}

	public function sort_venues_by_name($a, $b) {
		return strcmp($a->name, $b->name);
	}

	/**
	 * Checks the VisitWeho content type
	 */

	public function check_content_type( $id ) {

		if( !$id ) {
			return false;
		}

		$post_type = get_post_type( $id );

		return $post_type;

	}

	/**
	 * Queries the correct class by generic content ID
	 */

	public function get_generic_content( $id ) {
		$type = get_post_type( $id );

		if( $type == 'post' ) {
			return new VisitWeho_Story( $id );
		} elseif( $type == 'location' ) {
			return new VisitWeho_Location( $id );
		} elseif( $type == 'hotel' ) {
			return new VisitWeho_Hotel( $id );
		} elseif( $type == 'event' ) {
			return new VisitWeho_Event( $id );
		} elseif( $type == 'offer' ) {
			return new VisitWeho_Offer( $id );
		} elseif( $type == 'venue' ) {
			return new VisitWeho_Venue( $id );
		} elseif( $type == 'video' ) {
			return new VisitWeho_Video( $id );
		} else {
			return false;
		}
	}

	/**
	 * Outputs events/location navigation markup
	 */

	public function data_navigation( $page, $page_arr ) {

		//$page = (int)$page;

		if( !is_numeric( $page ) ) {
			return false;
		}

		if( $page < 1 ) {
			$page = 1;
		}

		$output = '<div class="isotope-pager">';
		$output .= '<a href="javascript:void(0);" class="pager arrow" data-page="1">' . '<<' . '</a>';
		$output .= '<a href="javascript:void(0);" class="pager previous" data-page="' . ($page - 1) . '">' . 'Previous' . '</a>';

		$last = count( $page_arr );

		if( $last <= 10 ) {
			foreach( $page_arr as $page_num ) {
				$class = $page_num == $page ? 'pager active' : 'pager';
				$output .= '<a href="javascript:void(0);" class="' . $class . '" data-page="' . $page_num . '">' . $page_num . '</a>';
			}
		} else {

			$first_five = array_splice($page_arr, 0, 5);
			$last_five = array_splice( $page_arr, -5, $last );

			if( in_array( $page, $first_five ) || in_array( $page, $last_five ) ) {
				foreach( $first_five as $page_num ) {
					$class = $page_num == $page ? 'pager active' : 'pager';
					$output .= '<a href="javascript:void(0);" class="' . $class . '" data-page="' . $page_num . '">' . $page_num . '</a>';
				}

				$output .= '<span>...</span>';

				foreach( $last_five as $page_num ) {
					$class = $page_num == $page ? 'pager active' : 'pager';
					$output .= '<a href="javascript:void(0);" class="' . $class . '" data-page="' . $page_num . '">' . $page_num . '</a>';
				}
			} else {
				$i = 0;
				foreach( $first_five as $page_num ) {
					$i++;
					if( $page_num != ( $page-1 ) ) {
						$class = $page_num == $page ? 'pager active' : 'pager';
						$output .= '<a href="javascript:void(0);" class="' . $class . '" data-page="' . $page_num . '">' . $page_num . '</a>';
					}
				}

				$output .= '<span>...</span>';

				$left_page = ( $page -1 );
				$right_page = ( $page + 1 );

				$middle_r = array( $left_page, $page, $right_page );

				foreach( $middle_r as $page_num ) {
					$class = $page_num == $page ? 'pager active' : 'pager';
					$output .= '<a href="javascript:void(0);" class="' . $class . '" data-page="' . $page_num . '">' . $page_num . '</a>';
				}

				$output .= '<span>...</span>';

				foreach( $last_five as $page_num ) {
					$class = $page_num == $page ? 'pager active' : 'pager';
					$output .= '<a href="javascript:void(0);" class="' . $class . '" data-page="' . $page_num . '">' . $page_num . '</a>';
				}
			}

		}

		if( $page != $last ) {
			$output .= '<a href="javascript:void(0);" class="pager next" data-page="' . ($page + 1) . '">' . 'Next' . '</a>';
		}

		$output .= '<a href="javascript:void(0);" class="pager arrow" data-page="' . $last . '">' . '>>' . '</a>';
		$output .= '</div>';

		if( $last < 2 ) {
			$output = '<div class="isotope-pager"></div>';
		}

		return $output;
	}

	public function has_featured_image( $post_id ) {
		$media_id = get_post_thumbnail_id( $post_id );
		$r = wp_get_attachment_image_src( $media_id, 'full' );
		$return = empty( $r ) ? false : true;

		return $return;
	}

	public function get_slide_index() {
		$index = isset( $_GET['cw-slide'] ) && is_numeric( $_GET['cw-slide'] ) ? $_GET['cw-slide'] : 1;

		return $index;
	}

	/**
	 * Generates the front-end slideshow markup
	 * @since 1.0.0
	 * @return  string HTML
	 */

	public function output_slideshow_markup( $post_id ) {
		$slideshow = new CW_Slideshow_Post( $post_id );
		$index = $this->get_slide_index();

		$output = '';

		// $begin_class = $index ? 'cw-hide cw-begin-slideshow' : 'cw-begin-slideshow';

		// $output .= '<div class="cw-begin-wrap">';
		// $output .= '<button data-open="1" class="button ' . $begin_class . '">';
		// $output .= __('Begin Slideshow', 'cw-slideshow') . '</button>';
		// $output .= '</div>';

		if( $slideshow->slides ) {
			$slide_count = count( $slideshow->slides );
			$output .= '<div class="cw-content-slides">';
			$i = 0;
			foreach( $slideshow->slides as $slide ) {

				$i++;

				if( $i == $index ) {
					$hide = '';
				}

				$hide = $i == $index ? '' : 'cw-hide';
				$output .= '<div class="cw-slide ' . $hide . '" data-index="' . $i . '">';
				$output .= '<h4>' . $slide['title'] . '</h4>';
				$img_src = wp_get_attachment_image_src( $slide['image'], 'large', false );
				$img_srcset = wp_get_attachment_image_srcset( $slide['image'], 'large' );
				$alt = get_post_meta( $slide['image'], '_wp_attachment_image_alt', true);

				if( empty( $alt ) ) {
					$alt = $slide['title'];
				}

				if( $slide['image'] ) {
					$attachment = get_post( $slide['image'] );
					$image_caption = $attachment->post_excerpt;

					$output .= '<img src="' . esc_url( $img_src[0] ) . '" srcset="' . esc_attr( $img_srcset ) . '"
					sizes="(max-width: 50em) 87vw, 680px" alt="' . $alt . '" />';

					if( $image_caption ) {
						$output .= '<figcaption class="wp-caption-text">' . $image_caption . '</figcaption>';
					}
				}

				$output .= '<div class="cw-slide-description">' . $slide['desc'] . '</div>';
				$output .= '<div class="cw-slide-footer">';

				$output .= '<div class="cw-button-wrap">';

				$prev_class = $i == 1 ? 'hide' : '';

				$output .= '<button data-open="' . ($i - 1) . '" class="button cw-slide-nav cw-slide-prev ' . $prev_class . '">' . __('Previous', 'cw-slideshow') . '</button>';

				if( $slide_count > $i ) {
					$output .= '<button data-open="' . ($i + 1) . '" class="button cw-slide-nav cw-slide-next">' . __('Next', 'cw-slideshow') . '</button>';
				}

				$output .= '</div>';

				$output .= '<div class="cw-slide-legend"><span>' . $i . ' / ' . $slide_count . '</span></div>';

				$output .= '</div>';

				$output .= '</div>';

			}
			$output .= '</div>';
		}
		return $output;
	}

	public function fetch_svg( $name ) {
		$svg = file_get_contents( $this->plugin_path . '/public/assets/' . $name . '.svg');

		return $svg;
	}

	/**
	 * Adds OpenTable Widget Output
	 * @since 1.3.0
	 */

	public function output_opentable_widget( $opentable_id ) {
		$opentable_id = (int)$opentable_id;
		echo "<script type='text/javascript' src='//www.opentable.com/widget/reservation/loader?rid=$opentable_id&type=standard&theme=standard&iframe=false&overlay=false&domain=com&lang=en-US'></script>";
	}

	/**
		 * Adds cache clearing function
		 * @since 2.8.0
	 */
	public function clear_all_cache() {
		if (function_exists('wp_cache_clear_cache')) {
			wp_cache_clear_cache();
		}
	}

}
