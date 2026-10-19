<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/public
 */

	use Firebase\JWT\JWT;
	use Firebase\JWT\ExpiredException;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/public
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function register_post_types() {


		register_post_type(
			'activity',
			array(
				'labels'             => array(
					'name'               => _x( 'Activities', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Activity', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Activities', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Activity', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'activity', 'visitweho' ),
					'add_new_item'       => __( 'Add New Activity', 'visitweho' ),
					'new_item'           => __( 'New Activity', 'visitweho' ),
					'edit_item'          => __( 'Edit Activity', 'visitweho' ),
					'view_item'          => __( 'View Activity', 'visitweho' ),
					'all_items'          => __( 'All Activities', 'visitweho' ),
					'search_items'       => __( 'Search Activities', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Activities:', 'visitweho' ),
					'not_found'          => __( 'No Activities Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Activities Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-tickets-alt',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				//'rewrite'            => array( 'slug' => 'activity', 'with_front' => false ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array(
						'title',
						'editor',
						'thumbnail',
						'excerpt',
					),
			)
		);

		register_post_type(
			'collection',
			array(
				'labels'             => array(
					'name'               => _x( 'Collections', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Collection', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Collections', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Collection', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'collection', 'visitweho' ),
					'add_new_item'       => __( 'Add New Collection', 'visitweho' ),
					'new_item'           => __( 'New Collection', 'visitweho' ),
					'edit_item'          => __( 'Edit Collection', 'visitweho' ),
					'view_item'          => __( 'View Collection', 'visitweho' ),
					'all_items'          => __( 'All Collections', 'visitweho' ),
					'search_items'       => __( 'Search Collections', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Collections:', 'visitweho' ),
					'not_found'          => __( 'No Collections Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Collections Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-portfolio',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				//'rewrite'            => array( 'slug' => 'collection', 'with_front' => false ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array(
						'title',
						'editor',
						'thumbnail',
						'excerpt',
					),
			)
		);

		register_post_type(
			'home-slide',
			array(
				'labels'             => array(
					'name'               => _x( 'Home Slider', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Slide', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Home Slider', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Slide', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'home-slide', 'visitweho' ),
					'add_new_item'       => __( 'Add New Slide', 'visitweho' ),
					'new_item'           => __( 'New Slide', 'visitweho' ),
					'edit_item'          => __( 'Edit Slide', 'visitweho' ),
					'view_item'          => __( 'View Video', 'visitweho' ),
					'all_items'          => __( 'All Slides', 'visitweho' ),
					'search_items'       => __( 'Search Slides', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Slide:', 'visitweho' ),
					'not_found'          => __( 'No Slides Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Slides Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-media-interactive',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => false,
				'query_var'          => true,
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'show_in_rest'       => true,
				'menu_position'      => 4,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);

		register_post_type(
			'hotel',
			array(
				'labels'             => array(
					'name'               => _x( 'Hotels', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Hotel', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Hotels', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Hotel', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'hotel', 'visitweho' ),
					'add_new_item'       => __( 'Add New Hotel', 'visitweho' ),
					'new_item'           => __( 'New Hotel', 'visitweho' ),
					'edit_item'          => __( 'Edit Hotel', 'visitweho' ),
					'view_item'          => __( 'View Hotel', 'visitweho' ),
					'all_items'          => __( 'All Hotels', 'visitweho' ),
					'search_items'       => __( 'Search Hotels', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Hotels:', 'visitweho' ),
					'not_found'          => __( 'No Hotels Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Hotels Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-building',
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'hotels', 'with_front' => false ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);

		register_post_type(
			'event',
			array(
				'labels'             => array(
					'name'               => _x( 'Events', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Event', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Events', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'event', 'visitweho' ),
					'add_new_item'       => __( 'Add New Event', 'visitweho' ),
					'new_item'           => __( 'New Event', 'visitweho' ),
					'edit_item'          => __( 'Edit Event', 'visitweho' ),
					'view_item'          => __( 'View Event', 'visitweho' ),
					'all_items'          => __( 'All Events', 'visitweho' ),
					'search_items'       => __( 'Search Events', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Events:', 'visitweho' ),
					'not_found'          => __( 'No Events Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Events Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-calendar-alt',
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'events', 'with_front' => false ),
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 6,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);

		register_post_type(
			'location',
			array(
				'labels'             => array(
					'name'               => _x( 'Locations', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Location', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Locations', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Location', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'location', 'visitweho' ),
					'add_new_item'       => __( 'Add New Location', 'visitweho' ),
					'new_item'           => __( 'New Location', 'visitweho' ),
					'edit_item'          => __( 'Edit Location', 'visitweho' ),
					'view_item'          => __( 'View Location', 'visitweho' ),
					'all_items'          => __( 'All Locations', 'visitweho' ),
					'search_items'       => __( 'Search Locations', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Location:', 'visitweho' ),
					'not_found'          => __( 'No Locations Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Locations Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-location-alt',
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_rest'       => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'locations', 'with_front' => false ),
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 8,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);

		register_post_type(
			'offer',
			array(
				'labels'             => array(
					'name'               => _x( 'Special Offers', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Offer', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Special Offers', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Offer', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'offer', 'visitweho' ),
					'add_new_item'       => __( 'Add New Offer', 'visitweho' ),
					'new_item'           => __( 'New Offer', 'visitweho' ),
					'edit_item'          => __( 'Edit Offer', 'visitweho' ),
					'view_item'          => __( 'View Offer', 'visitweho' ),
					'all_items'          => __( 'All Offers', 'visitweho' ),
					'search_items'       => __( 'Search Offers', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Offer:', 'visitweho' ),
					'not_found'          => __( 'No Offers Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Offers Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-megaphone',
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_rest'       => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => false,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'offer', 'with_front' => false ),
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 8,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						//'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);

		register_post_type(
			'video',
			array(
				'labels'             => array(
					'name'               => _x( 'Videos', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Video', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Videos', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Video', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'video', 'visitweho' ),
					'add_new_item'       => __( 'Add New Video', 'visitweho' ),
					'new_item'           => __( 'New Video', 'visitweho' ),
					'edit_item'          => __( 'Edit Video', 'visitweho' ),
					'view_item'          => __( 'View Video', 'visitweho' ),
					'all_items'          => __( 'All Videos', 'visitweho' ),
					'search_items'       => __( 'Search Videos', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Video:', 'visitweho' ),
					'not_found'          => __( 'No Videos Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Videos Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-video-alt2',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_rest'       => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'query_var'          => true,
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 8,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);

		register_post_type(
			'crowdriff-app',
			array(
				'labels'             => array(
					'name'               => _x( 'CrowdRiff Apps', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'CrowdRiff App', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'CrowdRiff Apps', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'CrowdRiff App', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'crowdriff-app', 'visitweho' ),
					'add_new_item'       => __( 'Add New CrowdRiff App', 'visitweho' ),
					'new_item'           => __( 'New CrowdRiff App', 'visitweho' ),
					'edit_item'          => __( 'Edit CrowdRiff App', 'visitweho' ),
					'view_item'          => __( 'View CrowdRiff App', 'visitweho' ),
					'all_items'          => __( 'All CrowdRiff Apps', 'visitweho' ),
					'search_items'       => __( 'Search CrowdRiff Apps', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent CrowdRiff App:', 'visitweho' ),
					'not_found'          => __( 'No CrowdRiff Apps Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No CrowdRiff Apps Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-format-gallery',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_rest'       => false,
				'show_in_menu'       => false,
				'show_in_nav_menus'  => false,
				'query_var'          => false,
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 8,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						'custom-fields',
					),
			)
		);

		register_post_type(
			'menu-widget',
			array(
				'labels'             => array(
					'name'               => _x( 'Menu Widgets', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'Menu Widget', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'Menu Widgets', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'Menu Widget', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'menu-widget', 'visitweho' ),
					'add_new_item'       => __( 'Add New Menu Widget', 'visitweho' ),
					'new_item'           => __( 'New Menu Widget', 'visitweho' ),
					'edit_item'          => __( 'Edit Menu Widget', 'visitweho' ),
					'view_item'          => __( 'View Menu Widget', 'visitweho' ),
					'all_items'          => __( 'All Menu Widget', 'visitweho' ),
					'search_items'       => __( 'Search Menu Widgets', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent Menu Widget:', 'visitweho' ),
					'not_found'          => __( 'No Menu Widgets Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No Menu Widgets Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-video-alt2',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_rest'       => false,
				'show_in_menu'       => false,
				'show_in_nav_menus'  => true,
				'query_var'          => false,
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 8,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);

		register_post_type(
			'api-client',
			array(
				'labels'             => array(
					'name'               => _x( 'API Clients', 'post type general name', 'visitweho' ),
					'singular_name'      => _x( 'API Client', 'post type singular name', 'visitweho' ),
					'menu_name'          => _x( 'API Clients', 'admin menu', 'visitweho' ),
					'name_admin_bar'     => _x( 'API Client', 'add new on admin bar', 'visitweho' ),
					'add_new'            => _x( 'Add New', 'api-client', 'visitweho' ),
					'add_new_item'       => __( 'Add New API Client', 'visitweho' ),
					'new_item'           => __( 'New API Client', 'visitweho' ),
					'edit_item'          => __( 'Edit API Client', 'visitweho' ),
					'view_item'          => __( 'View API Client', 'visitweho' ),
					'all_items'          => __( 'All API Client', 'visitweho' ),
					'search_items'       => __( 'Search API Clients', 'visitweho' ),
					'parent_item_colon'  => __( 'Parent API Client:', 'visitweho' ),
					'not_found'          => __( 'No API Clients Found.', 'visitweho' ),
					'not_found_in_trash' => __( 'No API Clients Found in Trash.', 'visitweho' )
					),
				'description'        => __( 'Description.', 'visitweho' ),
				'menu_icon'          => 'dashicons-video-alt2',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => false,
				'show_in_rest'       => false,
				'show_in_menu'       => false,
				'show_in_nav_menus'  => true,
				'query_var'          => false,
				'with_front'         => false,
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 8,
				'supports'           => array(
						'title',
						'editor',
						//'author',
						'thumbnail',
						'excerpt',
						//'comments',
						//'custom-fields',
					),
			)
		);
	}

	public function register_taxonomies() {

		$neighborhood_post_types = array('post','hotel','location','video');
		register_taxonomy(
			'neighborhood',
			$neighborhood_post_types,
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Neighborhoods', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Neighborhood', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Neighborhoods', 'visitweho' ),
					'all_items'         => __( 'All Neighborhoods', 'visitweho' ),
					'parent_item'       => __( 'Parent Neighborhood', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Neighborhood:', 'visitweho' ),
					'edit_item'         => __( 'Edit Neighborhood', 'visitweho' ),
					'update_item'       => __( 'Update Neighborhood', 'visitweho' ),
					'add_new_item'      => __( 'Add New Neighborhood', 'visitweho' ),
					'new_item_name'     => __( 'New Neighborhood Name', 'visitweho' ),
					'menu_name'         => __( 'Neighborhoods', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'neighborhood' ),
				)
		);

		register_taxonomy(
			'location-type',
			'location',
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Location Type', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Location Type', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Location Types', 'visitweho' ),
					'all_items'         => __( 'All Location Types', 'visitweho' ),
					'parent_item'       => __( 'Parent Location Type', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Location Type:', 'visitweho' ),
					'edit_item'         => __( 'Edit Location Type', 'visitweho' ),
					'update_item'       => __( 'Update Location Type', 'visitweho' ),
					'add_new_item'      => __( 'Add New Location Type', 'visitweho' ),
					'new_item_name'     => __( 'New Location Type Name', 'visitweho' ),
					'menu_name'         => __( 'Location Types', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'location-type' ),
				)
		);

		register_taxonomy(
			'location-tag',
			'location',
			array(
				'hierarchical'      => false,
				'labels'            => array(
					'name'              => _x( 'Location Tag', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Location Tag', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Location Tags', 'visitweho' ),
					'all_items'         => __( 'All Location Tags', 'visitweho' ),
					'parent_item'       => __( 'Parent Location Tag', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Location Tag:', 'visitweho' ),
					'edit_item'         => __( 'Edit Location Tag', 'visitweho' ),
					'update_item'       => __( 'Update Location Tag', 'visitweho' ),
					'add_new_item'      => __( 'Add New Location Tag', 'visitweho' ),
					'new_item_name'     => __( 'New Location Tag Name', 'visitweho' ),
					'menu_name'         => __( 'Location Tags', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'location-tag' ),
				)
		);

		register_taxonomy(
			'amenity',
			'hotel',
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Amenities', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Amenity', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Amenities', 'visitweho' ),
					'all_items'         => __( 'All Amenities', 'visitweho' ),
					'parent_item'       => __( 'Parent Amenity', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Amenity:', 'visitweho' ),
					'edit_item'         => __( 'Edit Amenity', 'visitweho' ),
					'update_item'       => __( 'Update Amenity', 'visitweho' ),
					'add_new_item'      => __( 'Add New Amenity', 'visitweho' ),
					'new_item_name'     => __( 'New Amenity Name', 'visitweho' ),
					'menu_name'         => __( 'Amenities', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'amenity' ),
				)
		);

		register_taxonomy(
			'venue',
			'event',
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Venues', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Venue', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Venues', 'visitweho' ),
					'all_items'         => __( 'All Venues', 'visitweho' ),
					'parent_item'       => __( 'Parent Venue', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Venue:', 'visitweho' ),
					'edit_item'         => __( 'Edit Venue', 'visitweho' ),
					'update_item'       => __( 'Update Venue', 'visitweho' ),
					'add_new_item'      => __( 'Add New Venue', 'visitweho' ),
					'new_item_name'     => __( 'New Venue Name', 'visitweho' ),
					'menu_name'         => __( 'Venues', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'venue' ),
			)
			);

		register_taxonomy(
			'event-category',
			'event',
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Categories', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Category', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Categories', 'visitweho' ),
					'all_items'         => __( 'All Categories', 'visitweho' ),
					'parent_item'       => __( 'Parent Category', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Category:', 'visitweho' ),
					'edit_item'         => __( 'Edit Category', 'visitweho' ),
					'update_item'       => __( 'Update Category', 'visitweho' ),
					'add_new_item'      => __( 'Add New Category', 'visitweho' ),
					'new_item_name'     => __( 'New Venue Category', 'visitweho' ),
					'menu_name'         => __( 'Categories', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'event-category' ),
				)
		);

		register_taxonomy(
			'campaign',
			'offer',
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Campaign', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Campaign', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Campaigns', 'visitweho' ),
					'all_items'         => __( 'All Campaigns', 'visitweho' ),
					'parent_item'       => __( 'Parent Campaign', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Campaign:', 'visitweho' ),
					'edit_item'         => __( 'Edit Campaign', 'visitweho' ),
					'update_item'       => __( 'Update Campaign', 'visitweho' ),
					'add_new_item'      => __( 'Add New Campaign', 'visitweho' ),
					'new_item_name'     => __( 'New Campaign Name', 'visitweho' ),
					'menu_name'         => __( 'Campaigns', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'campaign' ),
				)
		);

		register_taxonomy(
			'offer-tag',
			'offer',
			array(
				'hierarchical'      => false,
				'labels'            => array(
					'name'              => _x( 'Offer Tag', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Offer Tag', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Offer Tags', 'visitweho' ),
					'all_items'         => __( 'All Offer Tags', 'visitweho' ),
					'parent_item'       => __( 'Parent Offer Tag', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Offer Tag:', 'visitweho' ),
					'edit_item'         => __( 'Edit Offer Tag', 'visitweho' ),
					'update_item'       => __( 'Update Offer Tag', 'visitweho' ),
					'add_new_item'      => __( 'Add New Offer Tag', 'visitweho' ),
					'new_item_name'     => __( 'New Offer Tag Name', 'visitweho' ),
					'menu_name'         => __( 'Offer Tags', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'offer-tag' ),
				)
		);

		register_taxonomy(
			'activity-tag',
			'activity',
			array(
				'hierarchical'      => false,
				'labels'            => array(
					'name'              => _x( 'Activity Tag', 'taxonomy general name', 'visitweho' ),
					'singular_name'     => _x( 'Activity Tag', 'taxonomy singular name', 'visitweho' ),
					'search_items'      => __( 'Search Activity Tags', 'visitweho' ),
					'all_items'         => __( 'All Activity Tags', 'visitweho' ),
					'parent_item'       => __( 'Parent Activity Tag', 'visitweho' ),
					'parent_item_colon' => __( 'Parent Activity Tag:', 'visitweho' ),
					'edit_item'         => __( 'Edit Activity Tag', 'visitweho' ),
					'update_item'       => __( 'Update Activity Tag', 'visitweho' ),
					'add_new_item'      => __( 'Add New Activity Tag', 'visitweho' ),
					'new_item_name'     => __( 'New Activity Tag Name', 'visitweho' ),
					'menu_name'         => __( 'Activity Tags', 'visitweho' ),
					),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'activity-tag' ),
				)
		);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/css/visitweho-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-visit-weho-icons', plugin_dir_url( __FILE__ ) . '/icons/style.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-cookieconsent', plugin_dir_url( __FILE__ ) . '/css/cookieconsent.min.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {

		$vw = new VisitWeho();

		if( VW_ENVIRONMENT == 'production' ) {
			wp_enqueue_script( $this->plugin_name . '-gtm', plugin_dir_url( __FILE__ ) . 'js/gtm.js', $this->version, true );
		}

		wp_enqueue_script( $this->plugin_name . '-cookieconsent', plugin_dir_url( __FILE__ ) . 'js/cookieconsent.min.js', array(), $this->version, true );
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dragscroll.js', array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/visitweho-public.js', array('jquery'), $this->version, true );
	}

	public function schedule_cron_tasks() {
		if (!wp_next_scheduled('dola_events_fetch')) {
			wp_schedule_event( time(), 'daily', 'dola_events_fetch' );
		}

		if (!wp_next_scheduled('dola_available_venue_fetch')) {
			wp_schedule_event( time(), 'daily', 'dola_available_venue_fetch' );
		}

		if( !wp_next_scheduled('expire_hotel_offers') ) {
			wp_schedule_event( time(), 'hourly', 'expire_hotel_offers' );
		}

		if( !wp_next_scheduled('expire_events') ) {
			wp_schedule_event( time(), 'fourfold-daily', 'expire_events' );
		}

		if( !wp_next_scheduled('fetch_yelp_access_token') ) {
			wp_schedule_event( time(), 'monthly', 'fetch_yelp_access_token' );
		}

		if( !wp_next_scheduled('fetch_yelp_ratings') ) {
			wp_schedule_event( time(), 'daily', 'fetch_yelp_ratings' );
		}

		if( !wp_next_scheduled( 'fetch_hotel_prices' ) ) {
			wp_schedule_event( time(), 'daily', 'fetch_hotel_prices' );
		}

		if( !wp_next_scheduled( 'fetch_opentable_listings' ) ) {
			wp_schedule_event( time(), 'weekly', 'fetch_opentable_listings' );
		}

		if( !wp_next_scheduled( 'delete_wp_oembed_cache' ) ) {
			wp_schedule_event( time(), 'hourly', 'delete_wp_oembed_cache' );
		}

		if( !wp_next_scheduled( 'save_location_stories' ) ) {
			wp_schedule_event( time(), 'daily', 'save_location_stories' );
		}

		if( !wp_next_scheduled( 'save_hotel_stories' ) ) {
			wp_schedule_event( time(), 'daily', 'save_hotel_stories' );
		}

		if( !wp_next_scheduled( 'generate_mega_menu_markup_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'generate_mega_menu_markup_cron' );
		}

		if( !wp_next_scheduled( 'register_menu_widgets' ) ) {
			wp_schedule_event( time(), 'hourly', 'register_menu_widgets' );
		}

		if( !wp_next_scheduled( 'clear_cache_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'clear_cache_cron' );
		}

	}

	public function available_cron_schedules( $schedules ) {

		if( !isset( $schedules['quarter-hour'] ) ) {
			$schedules['quarter-hour'] = array(
				'interval' => 15*60,
				'display' => __('Once every 15 minutes', 'visitweho'),
			);
		}

		if( !isset( $schedules['fourfold-daily'] ) ) {
			$schedules['fourfold-daily'] = array(
				'interval' => 60*60*6,
				'display' => __('Once every 6 hours', 'visitweho'),
			);
		}

		if( !isset( $schedules['monthly'] ) ) {
			$schedules['monthly'] = array(
				'interval' => 60*60*24*30,
				'display' => __('Once every 30 days', 'visitweho'),
			);
		}

		if( !isset( $schedules['weekly'] ) ) {
			$schedules['weekly'] = array(
				'interval' => 604800,
				'display' => __('Once Weekly', 'visitweho'),
			);
		}

		return $schedules;
	}

	public function rest_api_base_prefix() {
		return 'json';
	}

	public function rest_api_cors() {
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		add_filter( 'rest_pre_serve_request', function( $value ) {
			header( 'Access-Control-Allow-Origin: *' );
			header( 'Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT' );
			header( 'Access-Control-Allow-Credentials: true' );
			header( 'Access-Control-Expose-Headers: Link', false );
			header( 'Access-Control-Allow-Headers: X-WP-Nonce, Authorization, Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
			return $value;
		} );
	}

	public function register_weho_rest_routes() {
		register_rest_route( 'visitweho', '/hotels', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_hotels'),
		));

		register_rest_route( 'visitweho', "/hotels/(?P<id>[\d]+)", array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_single_hotel'),
		));

		register_rest_route( 'visitweho', '/locations', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_locations'),
		));

		register_rest_route( 'visitweho', "/locations/(?P<id>[\d]+)", array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_single_location'),
		));

		register_rest_route( 'visitweho', '/collections', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_collections'),
		));

		register_rest_route( 'visitweho', "/collections/(?P<id>[\d]+)", array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_single_collection'),
		));

		register_rest_route( 'visitweho', '/events', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_events'),
		));

		register_rest_route( 'visitweho', '/dola-venues', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_dola_venues'),
		));

		register_rest_route( 'visitweho', '/events/(?P<id>[\d]+)', array(
			'methods' => 'GET',
			'callback' => array( $this, 'api_fetch_single_event' ),
			'args' => array(
						'id' => array(
							'validate_callback' => function($param, $request, $key) {
								return is_numeric( $param );
							}
						),
					),
			)
		);

		register_rest_route( 'visitweho', '/activities-autocomplete-search', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_activities_search_autocomplete'),
		));

		register_rest_route( 'visitweho', '/admin-events-fetch', array(
			'methods' => 'POST',
			'callback' => array($this, 'api_admin_events_fetch'),
		));

		register_rest_route( 'visitweho', '/admin-venue-fetch', array(
			'methods' => 'POST',
			'callback' => array($this, 'api_admin_venue_fetch'),
		));
		register_rest_route( 'visitweho', '/request-token', array(
			'methods' => 'POST',
			'callback' => array($this, 'api_token_fetch'),
		));

		register_rest_route( 'visitweho', '/request-access-token', array(
			'methods' => 'POST',
			'callback' => array($this, 'api_access_token_fetch'),
		));

		register_rest_route( 'visitweho', '/validate-access-token', array(
			'methods' => 'POST',
			'callback' => array($this, 'api_access_token_check'),
		));

		register_rest_route( 'visitweho', '/pages', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_pages'),
		));

		register_rest_route( 'visitweho', "/pages/(?P<slug>[a-zA-Z0-9-]+)", array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_single_page'),
		));

		register_rest_route( 'visitweho', '/stories', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_posts'),
		));

		register_rest_route( 'visitweho', "/stories/(?P<slug>[a-zA-Z0-9-]+)", array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_single_post'),
		));

		register_rest_route( 'visitweho', '/home-slides', array(
			'methods' => 'GET',
			'callback' => array($this, 'api_fetch_home_slides'),
		));

	}

	public function api_activities_search_autocomplete( $request ) {
		$args = $request->get_params();
		$r = array();

		foreach( $args as $k => $v ) {
			$a = explode(',', $v );

			if( count( $a ) > 1 ) {
				$v = $a;
			}

			$r[$k] = $v;
		}

		$search = $args['search'];

		if( empty( $search ) ) {
			wp_send_json_success();
			wp_die();
		}

		$args = array(
			'post_type' => array('activity'),
			'posts_per_page' => 20,
			's' => $search
		);

		$items = get_posts( $args );

		$content_return = '';

		$tab_index_count = 0;

		if( $items ) {
			$content_return .= '<ul>';
			foreach( $items as $item ) {
				$tab_index_count++;
				$type = get_post_type( $item->ID );
				$content_return .= '<li tabindex="' . $tab_index_count . '" data-type="' . $type . '" data-id="' . $item->ID . '" data-name="' . $item->post_title . '" onclick="addActivityRow(this)" href="">' . $item->post_title . ', <strong>' . ucfirst($type) . '</strong>';
				$content_return .= '</li>';
			}
			$content_return .= '</ul>';
		}

		if( empty( $content_return ) ) {
			$content_return = '<ul><li class="no-results">Nothing matches your search</li></ul>';
		}

		wp_send_json_success($content_return);
		wp_die();
	}

	public function api_access_token_check( $request ) {

		$visit_weho = new VisitWeho();

		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		try {
			$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
		} catch (\Exception $e) {
			return new WP_Error(
				'error',
				__( 'Access token invalid', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		} catch(\Firebase\JWT\ExpiredException $e){
			return new WP_Error(
				'error',
				__( 'Access token expired', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
    }

		$now = strtotime('now');

		if( $decode->exp < $now ) {
			return new WP_Error(
				'error',
				__( 'Access token expired', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		return array('code' => 200, 'message' => 'valid', 'data' => 'Access token valid');
	}

	public function api_access_token_fetch( $request ) {

		$visit_weho = new VisitWeho();

		$args = json_decode( $request->get_body() );

		if( !isset( $args->client_id ) || !isset( $args->client_secret ) ) {
			return new WP_Error(
				'error',
				__( 'Missing credentials', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( empty( $args->client_id ) || empty( $args->client_secret ) ) {
			return new WP_Error(
				'error',
				__( 'Missing credentials', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$r = array(
			'post_type' => 'api-client',
			'posts_per_page' => 1,
			'meta_query' => array(
				array(
					'key' => '_client_id',
					'value' => esc_attr( $args->client_id ),
					'compare' => '=',
				),
			)
		);

		$clients = get_posts( $r );

		if( empty( $clients ) ) {
			return new WP_Error(
				'error',
				__( 'Invalid client', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$client = $clients[0];

		$user_client_secret = get_post_meta( $client->ID, '_client_secret', true );
		$org_name = get_post_meta( $client->ID, 'org_name', true );

		if( $user_client_secret !== $args->client_secret ) {
			return new WP_Error(
				'error',
				__( 'Invalid credentials', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$visit_weho = new VisitWeho();

		$token = array(
			"iss" => get_site_url(),
			"aud" => $org_name,
			'iat' => time(),
			'exp' => time() + 60*60*24*30,
			'sub' => $client->ID
		);

		// Default algorithm HS256
		$jwt = JWT::encode($token, $visit_weho->system_api_encryption_key);

		$response = array(
			'access_token' => $jwt,
			'expiration' => $token['exp'],
		);

		return $response;

	}

	public function api_token_fetch( $request ) {

		$visit_weho = new VisitWeho();

		$nonce = $request->get_header('X-WP-Nonce');

		if( !wp_verify_nonce( $nonce, 'wp_rest') ) {
			return new WP_Error( 'error', __( 'Invalid nonce.', 'visitweho' ) );
			die();
		} else {
			return $visit_weho->visit_weho_api_key;
		}
	}

	public function api_fetch_dola_venues( $request ) {

		$visit_weho = new VisitWeho();

		return $visit_weho->available_dola_venues();
	}

	public function api_fetch_hotels( $request ) {

		$visit_weho = new VisitWeho();

		$args = $request->get_params();
		$r = array();
		foreach( $args as $k => $v ) {
			$a = explode(',', $v );
			if( count( $a ) > 1 ) {
				$v = $a;
			}
			$r[$k] = $v;
		}

		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token
			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'ASC',
			'orderby'       => 'name',
			'neighborhoods' => array(),
			'types'         => array(),
			'tags'          => array(),
		);

		$args = wp_parse_args( $r, $defaults );

		return $visit_weho->get_hotels($args);
	}

	public function api_fetch_single_hotel( $request ) {
		$r = $request->get_params();
		$visit_weho = new VisitWeho();

		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token

			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}

		try {
			$e = new VisitWeho_Hotel( $r['id'] );
		} catch (Exception $e) {
			$e = $e->getMessage();
			return new WP_Error( 'error', __( 'Not a hotel', 'visitweho' ) );
			die();
		}

		return $e;
	}

	public function api_fetch_locations( $request ) {
		$args = $request->get_params();
		$r = array();
		foreach( $args as $k => $v ) {
			$a = explode(',', $v );
			if( count( $a ) > 1 ) {
				$v = $a;
			}
			$r[$k] = $v;
		}

		$visit_weho = new VisitWeho();

		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token

			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'ASC',
			'orderby'       => 'name',
			'neighborhoods' => array(),
			'types'         => array(),
			'tags'          => array(),
		);

		$args = wp_parse_args( $r, $defaults );

		return $visit_weho->get_locations($args);
	}

	public function api_fetch_single_location( $request ) {
		$r = $request->get_params();
		$visit_weho = new VisitWeho();
		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token

			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}

		try {
			$e = new VisitWeho_Location( $r['id'] );
		} catch (Exception $e) {
			$e = $e->getMessage();
			return new WP_Error( 'error', __( 'Not a location', 'visitweho' ) );
			die();
		}

		return $e;
	}

	public function api_fetch_collections( $request ) {
		$args = $request->get_params();
		$r = array();
		foreach( $args as $k => $v ) {
			$a = explode(',', $v );
			if( count( $a ) > 1 ) {
				$v = $a;
			}
			$r[$k] = $v;
		}

		$visit_weho = new VisitWeho();

		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token

			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}

		$defaults = array(
			'items'         => -1,
			'offset'        => 0,
			'order'         => 'ASC',
			'orderby'       => 'name',
		);

		$args = wp_parse_args( $r, $defaults );

		return $visit_weho->get_collections($args);
	}

	public function api_fetch_single_collection( $request ) {
		$r = $request->get_params();
		$visit_weho = new VisitWeho();
		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token

			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}

		try {
			$e = new VisitWeho_Collection( $r['id'] );
		} catch (Exception $e) {
			$e = $e->getMessage();
			return new WP_Error( 'error', __( 'Not a collection', 'visitweho' ) );
			die();
		}

		return $e;
	}

	public function api_fetch_events( $request ) {
		$args = $request->get_params();
		$r = array();
		foreach( $args as $k => $v ) {
			$a = explode(',', $v );
			if( count( $a ) > 1 ) {
				$v = $a;
			}
			$r[$k] = $v;
		}

		$visit_weho = new VisitWeho();

		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token

			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}

		$defaults = array(
			'items'        => -1,
			'offset'       => 0,
			'order'        => 'ASC',
			'orderby'      => 'begin_time',
			'venues'       => array(),
			'categories'   => array(),
			'neighborhoods' => array(),
			'is_free'      => '',
			'is_featured' => '',
			'is_ongoing'   => '',
			'rsvp'         => '',
			'month'        => '',
			'day'          => '',
			'year'         => '',
			'date_between' => array(),
			'not_in' => array(),
		);

		$args = wp_parse_args( $r, $defaults );

		return $visit_weho->get_events($args);
	}

	public function api_fetch_single_event( $request ) {
		$r = $request->get_params();

		$visit_weho = new VisitWeho();

		if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		$token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		if( empty( $token ) ) {
			return new WP_Error(
				'error',
				__( 'Access token required', 'visitweho' ),
				array(
					'status' => 401
				)
			);
			die();
		}

		if( $token !== $visit_weho->visit_weho_api_key ) {
			// Check validity of client token

			try {
				$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
			} catch (\Exception $e) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			} catch(\Firebase\JWT\ExpiredException $e){
				return new WP_Error(
					'error',
					__( 'Access token expired', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
	    }

			$now = strtotime('now');

			if( $decode->exp < $now ) {
				return new WP_Error(
					'error',
					__( 'Access token invalid', 'visitweho' ),
					array(
						'status' => 401
					)
				);
				die();
			}

			$requests = get_post_meta( $decode->sub, 'requests', true );

			if( empty( $requests ) ) {
				$requests = array();
			}

			$info = $_SERVER['REMOTE_ADDR'] . ' - ';
			$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
			$info .= $_SERVER['REDIRECT_URL'] . ' - ';
			$info .= date('r');

			$requests[] = $info;

			update_post_meta( $client_id, 'requests', $requests );
		}
		try {
			$e = new VisitWeho_Event( $r['id'] );
		} catch (Exception $e) {
			$e = $e->getMessage();
			return new WP_Error( 'error', __( 'Not an event', 'visitweho' ) );
			die();
		}

		return $e;
	}

	public function api_admin_events_fetch( $request ) {
		$this->dola_events_fetch();
	}

	public function api_admin_venue_fetch( $request ) {
		$this->dola_available_venue_fetch();
	}

	public function api_fetch_pages( $request ) {
		$args = $request->get_params();
		$r = array();
		foreach( $args as $k => $v ) {
			$a = explode(',', $v );
			if( count( $a ) > 1 ) {
				$v = $a;
			}
			$r[$k] = $v;
		}

		$visit_weho = new VisitWeho();

		// if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
		// 	return new WP_Error(
		// 		'error',
		// 		__( 'Access token required', 'visitweho' ),
		// 		array(
		// 			'status' => 401
		// 		)
		// 	);
		// 	die();
		// }

		// $token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		// if( empty( $token ) ) {
		// 	return new WP_Error(
		// 		'error',
		// 		__( 'Access token required', 'visitweho' ),
		// 		array(
		// 			'status' => 401
		// 		)
		// 	);
		// 	die();
		// }

		// if( $token !== $visit_weho->visit_weho_api_key ) {
		// 	// Check validity of client token
		// 	try {
		// 		$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
		// 	} catch (\Exception $e) {
		// 		return new WP_Error(
		// 			'error',
		// 			__( 'Access token invalid', 'visitweho' ),
		// 			array(
		// 				'status' => 401
		// 			)
		// 		);
		// 		die();
		// 	} catch(\Firebase\JWT\ExpiredException $e){
		// 		return new WP_Error(
		// 			'error',
		// 			__( 'Access token expired', 'visitweho' ),
		// 			array(
		// 				'status' => 401
		// 			)
		// 		);
		// 		die();
	  //   }
		//
		// 	$now = strtotime('now');
		//
		// 	if( $decode->exp < $now ) {
		// 		return new WP_Error(
		// 			'error',
		// 			__( 'Access token invalid', 'visitweho' ),
		// 			array(
		// 				'status' => 401
		// 			)
		// 		);
		// 		die();
		// 	}
		//
		// 	$requests = get_post_meta( $decode->sub, 'requests', true );
		//
		// 	if( empty( $requests ) ) {
		// 		$requests = array();
		// 	}
		//
		// 	$info = $_SERVER['REMOTE_ADDR'] . ' - ';
		// 	$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
		// 	$info .= $_SERVER['REDIRECT_URL'] . ' - ';
		// 	$info .= date('r');
		//
		// 	$requests[] = $info;
		//
		// 	update_post_meta( $client_id, 'requests', $requests );
		// }

		$defaults = array(
			// 'items'        => -1,
			// 'offset'       => 0,
			// 'order'        => 'ASC',
			// 'orderby'      => 'begin_time',
			// 'venues'       => array(),
			// 'categories'   => array(),
			// 'neighborhoods' => array(),
			// 'is_free'      => '',
			// 'is_featured' => '',
			// 'is_ongoing'   => '',
			// 'rsvp'         => '',
			// 'month'        => '',
			// 'day'          => '',
			// 'year'         => '',
			// 'date_between' => array(),
			// 'not_in' => array(),
		);

		//$args = wp_parse_args( $r, $defaults );

		return $visit_weho->get_pages();
	}

	public function api_fetch_home_slides( $request ) {
		$args = $request->get_params();
		$r = array();
		foreach( $args as $k => $v ) {
			$a = explode(',', $v );
			if( count( $a ) > 1 ) {
				$v = $a;
			}
			$r[$k] = $v;
		}

		$defaults = array(
			'items' => -1,
		);

		$args = wp_parse_args( $r, $defaults );

		global $wpdb;
		$result = $wpdb->get_results(
			$wpdb->prepare("
				SELECT ID
				FROM  $wpdb->posts
				WHERE post_type = 'home-slide'
				LIMIT %d
				",
				$args['items']
			)
		);

		$return = array();

		foreach ( $result as $item ) {
			$slide = new VisitWeho_Home_Slide( $item->ID );
			$return[] = $slide;
		}

		return $return;
	}

	public function api_fetch_single_page( $request ) {
		$r = $request->get_params();

		if( is_numeric( $r['slug'] ) ) {
			$field = 'id';
		} else {
			$field = 'slug';
		}

		$visit_weho = new VisitWeho();

		try {
			if( $field == 'id' ) {
				$p = new VisitWeho_Page( $r['slug'] );
			} else {
				$page_obj = get_page_by_path($r['slug']);
				$p = new VisitWeho_Page( $page_obj->ID );
			}

		} catch (Exception $e) {
			//$e = $e->getMessage();
			return new WP_Error( 'error', __( 'Not a page', 'visitweho' ) );
			die();
		}

		return $p;
	}

	public function api_fetch_posts( $request ) {
		$args = $request->get_params();
		$r = array();
		foreach( $args as $k => $v ) {
			$a = explode(',', $v );
			if( count( $a ) > 1 ) {
				$v = $a;
			}
			$r[$k] = $v;
		}

		$visit_weho = new VisitWeho();

		// if( !isset( $_SERVER["HTTP_AUTHORIZATION"] ) ) {
		// 	return new WP_Error(
		// 		'error',
		// 		__( 'Access token required', 'visitweho' ),
		// 		array(
		// 			'status' => 401
		// 		)
		// 	);
		// 	die();
		// }

		// $token = str_replace('Bearer ', '', $_SERVER["HTTP_AUTHORIZATION"] );

		// if( empty( $token ) ) {
		// 	return new WP_Error(
		// 		'error',
		// 		__( 'Access token required', 'visitweho' ),
		// 		array(
		// 			'status' => 401
		// 		)
		// 	);
		// 	die();
		// }

		// if( $token !== $visit_weho->visit_weho_api_key ) {
		// 	// Check validity of client token
		// 	try {
		// 		$decode = JWT::decode($token, $visit_weho->system_api_encryption_key, array('HS256'));
		// 	} catch (\Exception $e) {
		// 		return new WP_Error(
		// 			'error',
		// 			__( 'Access token invalid', 'visitweho' ),
		// 			array(
		// 				'status' => 401
		// 			)
		// 		);
		// 		die();
		// 	} catch(\Firebase\JWT\ExpiredException $e){
		// 		return new WP_Error(
		// 			'error',
		// 			__( 'Access token expired', 'visitweho' ),
		// 			array(
		// 				'status' => 401
		// 			)
		// 		);
		// 		die();
		//   }
		//
		// 	$now = strtotime('now');
		//
		// 	if( $decode->exp < $now ) {
		// 		return new WP_Error(
		// 			'error',
		// 			__( 'Access token invalid', 'visitweho' ),
		// 			array(
		// 				'status' => 401
		// 			)
		// 		);
		// 		die();
		// 	}
		//
		// 	$requests = get_post_meta( $decode->sub, 'requests', true );
		//
		// 	if( empty( $requests ) ) {
		// 		$requests = array();
		// 	}
		//
		// 	$info = $_SERVER['REMOTE_ADDR'] . ' - ';
		// 	$info .= $_SERVER['HTTP_USER_AGENT'] . ' - ';
		// 	$info .= $_SERVER['REDIRECT_URL'] . ' - ';
		// 	$info .= date('r');
		//
		// 	$requests[] = $info;
		//
		// 	update_post_meta( $client_id, 'requests', $requests );
		// }

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

		$args = wp_parse_args( $r, $defaults );

		return $visit_weho->get_stories( $args );
	}

	public function api_fetch_single_post( $request ) {
		$r = $request->get_params();

		if( is_numeric( $r['slug'] ) ) {
			$field = 'id';
		} else {
			$field = 'slug';
		}

		$visit_weho = new VisitWeho();

		try {
			if( $field == 'id' ) {
				$p = new VisitWeho_Story( $r['slug'] );
			} else {
				$page_obj = get_page_by_path($r['slug'], 'OBJECT', 'post');
				$p = new VisitWeho_Story( $page_obj->ID );
			}

		} catch (Exception $e) {
			//$e = $e->getMessage();
			return new WP_Error( 'error', __( 'Not a story', 'visitweho' ) );
			die();
		}

		return $p;
	}

	public function dola_available_venue_fetch() {
		set_time_limit(0);

		$visit_weho = new VisitWeho();

		$dola_venue_feed = 'http://dola.com/visitwesthollywood/venues.json';

		$dola_venues = array();

		$ch   = curl_init($dola_venue_feed);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36');
		curl_setopt($ch, CURLOPT_MAXREDIRS,      4);
		curl_setopt($ch, CURLOPT_TIMEOUT,        30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$url  = $dola_venue_feed;
		$ch   = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36');
		curl_setopt($ch, CURLOPT_MAXREDIRS,      4);
		curl_setopt($ch, CURLOPT_TIMEOUT,        30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$root = curl_exec($ch);
		$root = json_decode($root, true);

		if( isset( $root['featured_venues'] ) ) {
			foreach( $root['featured_venues'] as $venue ) {
				$dola_venues[] = $venue;
			}
		}

		$pages = $root['paging']['total_pages'];

		for ($i = 1; $i <= $pages; $i++) {
			$url  = $dola_venue_feed . '?page=' . $i;
			$ch   = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_USERAGENT,      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36');
			curl_setopt($ch, CURLOPT_MAXREDIRS,      4);
			curl_setopt($ch, CURLOPT_TIMEOUT,        30);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$dola = curl_exec($ch);
			$dola = json_decode($dola, true);

			foreach( $dola['venues'] as $venue ) {
				$dola_venues[] = $venue;
			}

		}

		$process_venues = array();

		foreach( $dola_venues as $venue ) {
			if( isset($venue['latitude'] ) && !empty( $venue['latitude'] ) ):
				$latlng = $venue['latitude'] . ',' . $venue['longitude'];
				$location = $visit_weho->get_geolocation( $latlng, true );

				$address_components = $location['address_components'];

				$city = '';
				$state = '';
				$zip = '';

				$place_id = isset( $location[0]['place_id'] ) ? $location[0]['place_id'] : '';

				if( $address_components ):

					foreach( $address_components as $component ) {
						$types = $component['types'];

						if( in_array('locality', $types) ) {
							$city = $component['long_name'];
						}

						if( in_array('administrative_area_level_1', $types) ) {
							$state = $component['short_name'];
						}

						if( in_array('postal_code', $types) ) {
							$zip = $component['long_name'];
						}

					}

				endif;

				$process_venues[] = array(
					'id' => $venue['id'],
					'name' => $venue['title'],
					'permalink' => str_replace( '/venues/', '', $venue['permalink'] ),
					'latitude' => $venue['latitude'],
					'longitude' => $venue['longitude'],
					'address' => $venue['address'],
					'city' => $city,
					'state' => $state,
					'zip' => $zip,
					'description' => strip_tags( $venue['description'] ),
					'image' => $venue['imagery']['default'],
					'google_place_id' => $place_id,
				);
			endif;

		}

		$venues_json = json_encode( $process_venues, true );

		update_option('dola_venues', $venues_json );

	}

	public function dola_events_fetch() {

		set_time_limit(0);

		$system_default_user = 10;

		$terms = get_terms( array(
			'taxonomy' => 'venue',
			'hide_empty' => false,
		) );

		foreach( $terms as $term ) {

			$dola_permalink = get_term_meta( $term->term_id, 'dola_permalink', true );
			$venue_media_id = get_term_meta( $term->term_id, 'media_id', true );

			if( $dola_permalink ):
				$dola_venue_feed = 'http://dola.com/venues/' . $dola_permalink . '.json';
				$photo_base = 'https://res.cloudinary.com/dostuff-media/image/upload/';

				$ch   = curl_init($dola_venue_feed);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_USERAGENT,      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36');
				curl_setopt($ch, CURLOPT_MAXREDIRS,      4);
				curl_setopt($ch, CURLOPT_TIMEOUT,        30);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				$root  = curl_exec($ch);
				$root  = json_decode($root, true);
				$pages = $root['paging']['total_pages'];

				if( $pages == 0 ) {
					$pages = 1;
				}

				for ($i = 1; $i <= $pages; $i++) {
					$url  = $dola_venue_feed . '?page=' . $i;
					$ch   = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_USERAGENT,      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36');
					curl_setopt($ch, CURLOPT_MAXREDIRS,      4);
					curl_setopt($ch, CURLOPT_TIMEOUT,        30);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

					$dola = curl_exec($ch);
					$dola = json_decode($dola, true);

					if( isset( $dola['event_groups'] ) && !empty( $dola['event_groups'] ) ) {
						foreach ($dola['event_groups'] as $e) {
							$events = $e['events'];
							foreach( $events as $event ) {
								$dola_event_id = $event['id'];
								$cat_slug = $event['category_param'];
								$cat_label = $event['category'];
								$title = $event['title'];
								$description = $event['description'] ? strip_tags( $event['description'], '<p><br>') : '';
								$presented_by = $event['presented_by'];
								$permalink = $event['permalink'];
								$begin_time = $event['begin_time'];
								$process_begin_time = substr($begin_time, 0, strrpos($begin_time, '-'));
								$phpdate = strtotime( $process_begin_time );
								$begin_time = DateTime::createFromFormat('U', $phpdate);
								$begin_timestamp = $begin_time->getTimestamp();
								$day = $begin_time->format('j');
								$year = $begin_time->format('Y');
								$month = $begin_time->format('n');
								$end_time = $event['end_time'];
								$end_timestamp = '';
								if( !empty( $end_time ) ) {
									$process_end_time = substr($end_time, 0, strrpos($end_time, '-'));
									$phpdate = strtotime( $process_end_time );
									$end_time = DateTime::createFromFormat('U', $phpdate);
									$end_timestamp = $begin_time->getTimestamp();
								}
								$is_free = empty( $event['is_free'] ) ? 0 : 1;
								$is_ongoing = empty( $event['is_ongoing'] ) ? 0 : 1;
								$sold_out = empty( $event['sold_out'] ) ? 0 : 1;
								$rsvp = empty( $event['rsvp'] ) ? 0 : 1;
								$buy_url = isset( $event['buy_url'] ) ? $event['buy_url'] : '';
								$event_photo = $event['imagery']['photo'];
								$venue = $event['venue'];

								if( isset( $event['artists'] ) && !empty( $event['artists'] ) ) {
									$artists = $event['artists'];
								} else {
									$artists = false;
								}

								$cat_term = get_term_by('slug', $cat_slug, 'event-category');

								$current_time = strtotime('now');

								if( $begin_timestamp > $current_time ):

									$args = array(
										'post_type' => 'event',
										'meta_query' => array(
												array(
													'key' => 'dola_event_id',
													'value' => $dola_event_id,
													'compare' => '=',
													),
												),
										'posts_per_page' => 1,
										);

									$e = get_posts( $args );
									if( empty( $e ) ) {

										$date_append = date('m-d-Y', $begin_timestamp );

										$post_name = sanitize_title_with_dashes( $title ) . '-' . $date_append;

										// new event
										$args = array(
											'post_title' => $title,
											'post_content' => $description,
											'post_type' => 'event',
											'post_status' => 'publish',
											'post_author' => $system_default_user,
											'post_name' => $post_name,
											);

										$event_post_id = wp_insert_post( $args );

										$data = array(
											'presented_by' => $presented_by,
											'is_free' => (bool)$is_free,
											'is_ongoing' => (bool)$is_ongoing,
											'sold_out' => (bool)$sold_out,
											'buy_url' => $buy_url,
											'begin_time' => $begin_timestamp,
											'end_time' => $end_timestamp,
											'rsvp' => (bool)$rsvp,
											'dola_event_id' => $dola_event_id,
											'artists' => $artists,
											);

										foreach( $data as $k => $v ) {
											update_post_meta( $event_post_id, $k, $v );
										}

										if( $cat_term == false ) {
											// new term
											$new_cat = wp_insert_term(
												$cat_label,
												'event-category',
												array(
													'slug' => $cat_slug,
												)
											);

											if( !is_wp_error( $new_cat ) ) {
												wp_set_object_terms( $event_post_id, (int)$new_cat['term_id'], 'event-category', false );
											}

										} else {
											wp_set_object_terms( $event_post_id, (int)$cat_term->term_id, 'event-category', false );
										}

										wp_set_object_terms( $event_post_id, (int)$term->term_id, 'venue', false );

										if( $event_photo ) {
											require_once(ABSPATH . 'wp-admin/includes/media.php');
											require_once(ABSPATH . 'wp-admin/includes/file.php');
											require_once(ABSPATH . 'wp-admin/includes/image.php');
											$image = media_sideload_image($photo_base . $event_photo, $event_post_id, $title );

											$att_args = array(
													'numberposts' => '1',
													'post_parent' => $event_post_id,
													'post_type' => 'attachment',
													'post_mime_type' => 'image',
													'order' => 'ASC',
													);

											$attachments = get_posts( $att_args );

											if( sizeof( $attachments ) > 0 ) {
												$attachment_id = $attachments[0]->ID;
												$meta = wp_get_attachment_metadata( $attachment_id, false );

												if( $meta['width'] < 200 ) {
													if( $venue_media_id ) {
														set_post_thumbnail($event_post_id, $venue_media_id);
													} else {
														set_post_thumbnail($event_post_id, $attachments[0]->ID);
													}
												} else {
													set_post_thumbnail($event_post_id, $attachments[0]->ID);
												}
											}
										}
									} else {
										// update the old event
										$e = $e[0];
										$system_event_id = $e->ID;

										$date_append = date('m-d-Y', $begin_timestamp );

										$post_name = sanitize_title_with_dashes( $title ) . '-' . $date_append;

										$event_args = array(
											'ID' => $system_event_id,
											'post_type' => 'event',
											'post_title' => $title,
											'post_status' => 'publish',
											'post_name' => $post_name,
											);

										$event_p = wp_update_post( $event_args );

										$data = array(
											'presented_by' => $presented_by,
											'is_free' => $is_free,
											'is_ongoing' => $is_ongoing,
											'sold_out' => $sold_out,
											'buy_url' => $buy_url,
											'begin_time' => $begin_timestamp,
											'end_time' => $end_timestamp,
											'rsvp' => $rsvp,
											'day' => $day,
											'month' => $month,
											'year' => $year,
											);

										foreach( $data as $k => $v ) {
											update_post_meta( $system_event_id, $k, $v );
										}
									}
								endif;
							}
						}
					}
				}
			endif; // dola venue
		}
	}

	public function expire_hotel_offers() {

		$args = array(
			'post_type' => 'offer',
			'posts_per_page' => -1,
			);

		$offers = get_posts( $args );

		foreach( $offers as $offer ) {
			$offer_obj = new VisitWeho_Offer( $offer->ID );
			if( !empty( $offer_obj->expiration ) ) {
				if( $offer_obj->expiration <= strtotime('now') ) {
					wp_delete_post( $offer->ID, false );
				}
			}
		}
	}

	public function expire_events() {

		$args = array(
			'post_type' => 'event',
			'posts_per_page' => -1,
			);

		$events = get_posts( $args );

		foreach( $events as $event ) {
			$event_obj = new VisitWeho_Event( $event->ID );
			if( !empty( $event_obj->end_time ) ) {
				if( $event_obj->end_time <= strtotime('now') ) {

					$args = array(
						'post_type'   => 'attachment',
						'numberposts' => -1,
						'post_status' => 'any',
						'post_parent' => $event->ID,
					);

					$attachments = get_posts( $args );

					if ( $attachments ) {
						foreach ( $attachments as $attachment ) {
							wp_delete_attachment( $attachment->ID , true );
						}
					}

					wp_delete_post( $event->ID );
				}
			}
		}
	}

	public function delete_wp_oembed_cache() {
		global $wpdb;

		$wpdb->show_errors();

		$match = '_oembed';

		$output = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->postmeta
				WHERE meta_key LIKE %s;",
					'%' . $wpdb->esc_like($match) . '%'
				)
		);
	}

	public function fetch_opentable_listings() {
		set_time_limit(0);
		$visit_weho = new VisitWeho();

		$ch = curl_init('https://oauth.opentable.com/api/v2/oauth/token?grant_type=client_credentials');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'VisitWeho/0.1 (edward@codewrangler.io)');

		$token = $visit_weho->opentable_client_id . ':' . $visit_weho->opentable_client_secret;
		$a = base64_encode( $token );

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Basic ' . $a
				));

		$data = curl_exec($ch);
		$info = curl_getinfo($ch);

		$json = json_decode( $data );
		$access_token = $json->access_token;

		curl_close($ch);

		$return_restaurants = array();

		$ch = curl_init('https://platform.opentable.com/sync/directory?country=US');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'VisitWeho/0.1 (edward@codewrangler.io)');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $access_token
				));

		$data = curl_exec($ch);

		$json = json_decode( $data );

		$total_count = $json->total_items;
		$loop_count = 0;
		$items = $json->items;

		$last_page = ceil($total_count / 1000);
		$last_page = $last_page + 1;

		$fetch_intervals = range(1, $last_page );

		curl_close($ch);

		foreach( $fetch_intervals as $interval ) {

			$offset = $loop_count;

			$ch = curl_init('https://platform.opentable.com/sync/directory?country=US&offset=' . $offset );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'VisitWeho/0.1 (edward@codewrangler.io)');

			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $access_token
			));

			$data = curl_exec($ch);
			$json = json_decode( $data );

			$items = $json->items;

			foreach( $items as $item ) {
				$loop_count++;

				if( $item->metro_name == 'Los Angeles' ) {
					$otid = $item->rid;
					$return_restaurants[$otid] = $item;
				}

			}

			curl_close($ch);

		}

		usort($return_restaurants, function($a, $b) {
			return strcmp($a->name, $b->name);
		});

		update_option('opentable_listings', $return_restaurants );
	}

	public function fetch_hotel_prices() {
		$vw = new VisitWeho();
		$key = $vw->ares_key;

		$url = 'https://hotelgeneral.com/datafeeds/download?key=' . $key . '&format=xml&report=hotel';

		$xml = simplexml_load_file( $url );

		foreach( $xml as $xml ) {
			$ares_id = (string)$xml->id[0];
			$name = (string)$xml->name[0];
			$rates = $xml->rates;
			$args = array(
				'post_type' => 'hotel',
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => 'ares_id',
						'value' => $ares_id,
						'compare' => '=',
					)
				)
			);

			$posts = get_posts( $args );

			if( $posts ) {
				$hotel = $posts[0];
				if( !empty( $rates ) ) {
					$min_price = (string)round($rates->minPrice);
					if( $min_price != '0' ) {
						update_post_meta( $hotel->ID, 'from_price', $min_price );
					}
				}
			}
		}

	}

	public function fetch_yelp_ratings() {
		set_time_limit(0);
		$visit_weho = new VisitWeho();
		$locations = $visit_weho->get_locations();
		$yelp_access_token = $visit_weho->yelp_access_token;
		$count = 0;
		foreach( $locations as $location ) {
			$count++;
			$yelp_url = $location->yelp_url;
			$yelp_id = basename( $yelp_url );
			$endpoint = 'https://api.yelp.com/v3/businesses/' . $yelp_id;

			if( $yelp_id ) {
				$context = stream_context_create(array(
						'http' => array(
								'header'  => "Authorization: Bearer " . $yelp_access_token,
						)
				));
				$data = json_decode( file_get_contents($endpoint, false, $context) );
				$review_count = $data->review_count;
				$rating = $data->rating;
				if( $rating ) {
					$r = array('rating' => $rating, 'review_count' => $review_count );
					update_post_meta( $location->id, 'yelp_data', $r );
				}
			}
		}
	}

	public function fetch_yelp_access_token() {
		$ch = curl_init();
		$client_id  = "YELP_CLIENT_ID";
		$client_secret = "YELP_CLIENT_SECRET";
		$data_str = 'client_id=' . $client_id . '&client_secret=' . $client_secret;
		curl_setopt($ch, CURLOPT_URL,"https://api.yelp.com/oauth2/token");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = json_decode( curl_exec ($ch) );

		if( $server_output->access_token ) {
			update_option('yelp_access_token', $server_output->access_token );
		}

		curl_close ($ch);
	}

	public function story_location_shortcode( $atts, $content = null ) {
		ob_start();

		extract( shortcode_atts( array (
			'id' => '',
			'title' => '',
			'blurb' => '',
			'img' => '',
		), $atts ) );

		if( empty( $atts['id'] ) ) {
			return '';
		}

		if( get_post_type( $atts['id'] ) != 'hotel' && get_post_type( $atts['id'] ) != 'location' ) {
			return '';
		}

		if( get_post_type( $atts['id'] ) == 'location' ) {
			$location = new VisitWeho_Location( $atts['id'] );
		} else {
			$location = new VisitWeho_Hotel( $atts['id'] );
		}

		if( empty( $atts['title'] ) ) {
			$atts['title'] = $location->name;
		}

		$o = get_post($atts['id']);
		$slug = $o->post_name;

		echo '<div class="story-location"
				data-lat="' . $location->latitude . '"
				data-lng="' . $location->longitude . '"
				data-id="' . $location->id . '"
				data-hotel-name="' . $slug . '"
				data-ares-id="' . get_post_meta( $atts['id'], 'ares_id', true ) . '"
				data-type="' . get_post_type( $atts['id'] ) . '"
				data-place-id="' . $location->google_place_id . '">';

		if( empty( $atts['img'] ) ) {
			if( $location->featured_media['sizes']['widescreen-crop']['file'] ) {
				echo '<img src="' . $location->featured_media['sizes']['widescreen-crop']['file'] . '" alt="' . $atts['title'] . '" />';
			}
		} else {
			echo '<img src="' . $atts['img'] . '" alt="' . $atts['title'] . '" />';
		}

		if( $location->neighborhood ) {
			echo '<h6 class="location-neighborhood">' . $location->neighborhood->name . '</h6>';
		}

		echo '<h2 class="location-title">' . $atts['title'] . '</h2>';
		echo '<div class="location-content">';
		echo wpautop( $content );
		echo '</div>';
		echo '<p class="location-address">' . $location->address . '<br>' . $location->city . ', ' . $location->state . ' ' . $location->zip . '<br>' . $location->phone . '</p>';

		echo '<div class="location-sub">';

		// if( get_post_type( $atts['id'] ) == 'hotel' ) {
		// 	echo '<span class="location-price">' . $location->from_price . '</span>';
		// }

		if( 'hotel' == get_post_type( $atts['id'] ) ) {
			echo '<span class="location-link"><a class="check-rates-link">Check Rates</a></span> | ';
		}

		if( $location->website_url ) {
			echo '<span class="location-link"><a rel="noreferrer noopener" target="_blank" href="' . $location->website_url . '">Website</a></span> | ';
		}

		$url = urlencode( $location->name );
		$url = str_replace('%26%23038%3B', ' ', $url);
		echo '<span class="location-link"><a rel="noreferrer noopener" target="_blank" href="https://www.google.com/maps/search/?api=1&query=' . $url . '&query_place_id=' . $location->google_place_id . '">Get Directions</a></span>';

		echo '</div>';

		echo '</div>';


		$output = ob_get_clean();

		return $output;

	}

	public function video_shortcode( $atts, $content = null ) {
		ob_start();

		$atts = shortcode_atts( array (
			'url' => '',
			'id' => 'yt-player',
			'mute' => 0,
			'cc' => 0,
			'autoplay' => 0,
		), $atts );

		if( empty( $atts['url'] ) ) {
			return '';
		}

		/**
		 * Adds AMP conditional
		 * @since   1.2.0
		 */

		if( function_exists('is_amp_endpoint') ) {
			if( is_amp_endpoint() ) {
				$amp = true;
			} else {
				$amp = false;
			}
		} else {
			$amp = false;
		}

		if( $amp ) {

			$url = $atts['url'];

			if( strpos($atts['url'], 'youtu.be') !== false ) {
				$video_id = basename( $url );
			} else {
				parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
				$video_id = $vars['v'];
			}

			$div = '<amp-youtube data-videoid="' . $video_id . '"
								layout="responsive"
								width="560"
								height="315">
							</amp-youtube>';
		} else {
			$div = '<div class="flex-video">';
			$div .= '<div
									data-url="' . $atts['url'] . '"
									data-mute="' . $atts['mute'] . '"
									data-cc="' . $atts['cc'] . '"
									data-autoplay="' . $atts['autoplay'] . '"
									id="' . $atts['id'] . '"></div>';
			$div .= '</div>';
		}

		// Shows the actual embed output in admin
		// Otherwise YouTube API handles rendering
		if( is_admin() ) {
			// Render the raw shortcode
			//remove_filter('the_content', 'do_shortcode', 11);
			//add_filter('the_content', 'do_shortcode', 100);
			$div = '[video]';
			echo $div;
		} else {
			echo $div;
		}

		$output = ob_get_clean();

		return $output;

	}

	public function crowdriff_shortcode( $atts, $content = null ) {
		$visit_weho = new VisitWeho();
		ob_start();

		$atts = shortcode_atts( array (
			'id' => '',
			'count' => 100,
		), $atts );

		if( empty( $atts['id'] ) ) {
			return '';
		}

		$matching = get_page_by_title( $atts['id'], OBJECT, 'crowdriff-app' );

		if( $matching == null ) {
			return;
		}

		$args = array(
			'post_type'   => 'attachment',
			'numberposts' => -1,
			'post_status' => 'any',
			'post_parent' => $matching->ID,
		);

		$attachments = get_posts( $args );

		$image_layer = '';

		$i = 0;

		?>

	<div class="crowdriff-lightbox">
		<div class="crowdriff-lightbox__controls">
			<button class="crowdriff-lightbox__close">
				<svg fill="#000000" height="32" viewBox="0 0 24 24" width="32" xmlns="http://www.w3.org/2000/svg">
						<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
						<path d="M0 0h24v24H0z" fill="none"/>
				</svg>
			</button>
			<button data-direction="left" class="crowdriff-lightbox__nextPrev crowdriff-lightbox__nextPrev--prev">
				<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
						<path d="M0 0h24v24H0z" fill="none"/>
						<path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
				</svg>
			</button>
			<button data-direction="right" class="crowdriff-lightbox__nextPrev crowdriff-lightbox__nextPrev--next">
				<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
						<path d="M0 0h24v24H0z" fill="none"/>
						<path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
				</svg>
			</button>
		</div>

		<div
			data-active-slide="0"
			data-max-items="<?php echo min( array(count($attachments), $atts['count'] ) ); ?>"
			id="crowdriff-slider" class="crowdriff-lightbox__slider">

			<?php
				foreach( $attachments as $attachment ):
					$full_img = wp_get_attachment_image_src( $attachment->ID, 'full');
					$full_img = $full_img[0];
					$username = get_post_meta( $attachment->ID, 'username', true );
					$full_name = get_post_meta( $attachment->ID, 'user_full_name', true );
					$location_name = get_post_meta( $attachment->ID, 'location_name', true );
					$date = strtotime(get_post_meta( $attachment->ID, 'date', true ));
					$desc = get_post_meta( $attachment->ID, 'desc', true );
					$hashtags = get_post_meta( $attachment->ID, 'hashtags', true );
					$native_url = get_post_meta( $attachment->ID, 'native_url', true );
					$source = get_post_meta( $attachment->ID, 'source', true );

					$cta_url = get_post_meta( $attachment->ID, 'cta_url', true );
					$cta_label = get_post_meta( $attachment->ID, 'cta_label', true );

					if( empty( $username ) ) {
						$username = $full_name;
					}

					if ( $i >= $atts['count'] ) {
						break;
					}

					?>
					<div data-index="<?php echo $i; ?>" class="crowdriff-lightbox__slider__wrapper">
						<img class="crowdriff-lightbox-image" src="<?php echo $full_img; ?>" />
						<div class="crowdriff-lightbox__slider__info">
							<div class="crowdriff-lightbox__slider__info__name">
								<a target="_blank" rel="noopener noreferrer" href="<?php echo $native_url; ?>">
									<?php echo $username; ?>
								</a>
							</div>
							<div class="crowdriff-lightbox__slider__info__source">
								<?php if( $source == 'facebook' ){ ?>
									<div class="facebook-svg">
										<?php echo $visit_weho->fetch_svg('facebook-black'); ?>
									</div>
								<?php } else { ?>
									<div class="instagram-svg">
										<?php echo $visit_weho->fetch_svg('instagram-black'); ?>
									</div>
								<?php } ?>
							</div>
							<div class="crowdriff-lightbox__slider__info__date">
								<?php echo date('F j', $date ); ?>
							</div>
							<div class="crowdriff-lightbox__slider__info__location">
								<?php if( $location_name ) { ?>
									<div class="location-svg">
										<?php echo $visit_weho->fetch_svg('location-pin-black'); ?>
									</div>
									<?php echo $location_name; ?>
								<?php } ?>
							</div>
							<div class="crowdriff-lightbox__slider__info__desc">
								<?php echo $desc; ?>
							</div>
							<div class="crowdriff-lightbox__slider__info__hashtags">

							</div>
						</div>
					</div>
			<?php
					$i++;
					endforeach;
				?>
		</div>

		<div class="crowdriff-lightbox__counter">
		</div>
	</div>

		<?php

		$i = 0;

		foreach( $attachments as $attachment ) {

			$quality = get_post_meta( $attachment->ID, 'score', true );
			$source = get_post_meta( $attachment->ID, 'source', true );
			$username = get_post_meta( $attachment->ID, 'username', true );
			$full_name = get_post_meta( $attachment->ID, 'user_full_name', true );
			$location_name = get_post_meta( $attachment->ID, 'location_name', true );
			// $date = get_post_meta( $attachment->ID, 'date', true );
			// $desc = get_post_meta( $attachment->ID, 'desc', true );
			// $hashtags = get_post_meta( $attachment->ID, 'hashtags', true );
			// $native_url = get_post_meta( $attachment->ID, 'native_url', true );

			$cta_url = get_post_meta( $attachment->ID, 'cta_url', true );
			$cta_label = get_post_meta( $attachment->ID, 'cta_label', true );

			if( empty( $username ) ) {
				$username = $full_name;
			}

			if ( $i >= $atts['count'] ) {
				break;
			}

			if( $location_name ) {
				$pin = '<span class="source location">';
				$pin .= $visit_weho->fetch_svg('location-pin') . $location_name;
				$pin .= '</span>';
			} else {
				$pin = '';
			}

			if( $cta_url != '' && $cta_label != '' ) {
				$cta = '<div class="cta"><a class="clearing" href="' . $cta_url . '">' . $cta_label . '</a></div>';
			} else {
				$cta = '';
			}

			$full_img = wp_get_attachment_image_src( $attachment->ID, 'full');
			$full_img = $full_img[0];
			$large_img = wp_get_attachment_image_src( $attachment->ID, 'large');
			$large_img = $large_img[0];

			$icon = $visit_weho->fetch_svg($source);
			$image_layer .= '<div class="brick">
												<img
													data-index="' . $i . '"
													data-user-name="@' . $username . '"
													src="' . $large_img . '"
													/>
												' . $pin . $cta . '
												<span class="source">' . $icon . '</span>
												<span class="source name">' . $username . '</span>
												<div class="brick-overlay"></div>
											</div>';

			$i++;
		}

		$div = '<div class="vw-bricks-slides crowdriff-gallery">';
		$div .= '<div data-left="0" class="vw-bricks-slides__inner">';
		$div .= $image_layer;
		$div .= '</div>';
		$div .= '</div>';

		echo $div;

		$output = ob_get_clean();

		return $output;

	}

	public function customize_embed( $html, $url, $args ) {
		// $html = str_replace( '?feature=oembed', '?feature=oembed&showinfo=0&rel=0&controls=1&autoplay=1enablejsapi=1', $html );
		// $t = '<div class="flex-video">' . $html . '</div>';
		// $html = $t;

		return $html;
	}

	public function gtm_no_script() {
		echo '<!-- Google Tag Manager (noscript) -->
					<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WGT6T3D"
					height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
					<!-- End Google Tag Manager (noscript) -->';
	}

	public function custom_image_sizes() {
		add_image_size( 'hero', 1920, 1920 );
		add_image_size( 'widescreen-crop', 600, 338, array( 'center', 'center' ) );
		add_image_size( 'square', 600, 600, array( 'center', 'center' ) );
		add_image_size( 'skyscraper', 321, 580, array( 'center', 'center' ) );
		add_image_size( 'medium-large', 600, 0, array( 'center', 'center' ) );
	}

	public function page_script() {
		global $post;

		if( $post ) {
			$page_script = get_post_meta( $post->ID, 'page_script', true );
			if( $page_script ) {
				echo $page_script;
			}
		}

	}

	public function mc_subscription() {

		$visit_weho = new VisitWeho();

		$email = esc_attr( $_POST['email'] );
		$apikey = $visit_weho->mailchimp_api_key;

		$auth = base64_encode( 'user:'.$apikey );
		$data = array(
			'apikey'        => $apikey,
			'email_address' => $email,
			'status'        => 'subscribed',
			'merge_fields'  => array('MMERGE4' => 'visitwesthollywood.com' ),
		);

		$json_data = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://us4.api.mailchimp.com/3.0/lists/d768b545f4/members/');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
			'Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		$result = curl_exec($ch);
		$resObj = json_decode($result);

		if($resObj->status == "subscribed") {
			echo 'success';
		} else if ($resObj->title == 'Member Exists'){
			echo 'exists';
		} else {
			echo 'error';
		}

		wp_die();
	}


	/**
	 * AJAX navigation skeleton
	 */

	public function ajax_data_navigation() {
		$page = isset( $_POST['page'] ) ? esc_attr( $_POST['page'] ) : 1;
		$page_arr = range( 1, (int)$_POST['total'] );

		$visit_weho = new VisitWeho();

		echo $visit_weho->data_navigation($page, $page_arr);

		wp_die();
	}


	public function offer_slug_mod( $post_link, $post ) {

		if ( is_object( $post ) && $post->post_type == 'offer' ) {
			return str_replace( '%placeholder%/', '', $post_link );
		} else {
			return $post_link;
		}
	}

	public function page_filtering() {
		global $post;

		if( is_admin() ) {
			return;
		}

		if( isset( $post ) && $post->post_type == 'offer' ) {
			// check whether this offer has an LP or not
			$offer = new VisitWeho_Offer( $post->ID );
			if( !$offer->has_landing_page ) {
				wp_safe_redirect( '/', 301 );
			}
		}
	}

	/**
	 * Extends Yoast Schema Data
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function yoast_json($data) {

		if( !isset( $data['@type'] ) ) {
			return $data;
		}

		if( $data['@type'] !== 'Organization' ) {
			return $data;
		}

		$phone_local = array(
			'@type' => 'ContactPoint',
			'telephone' => '+1-310-289-2525',
			'contactType' => 'customer service',
		);

		$toll_free = array(
			'@type' => 'ContactPoint',
			'telephone' => '+1-800-368-6020',
			'contactType' => 'customer service',
			'contactOption' => 'TollFree',
		);

		$data['contactPoint'] = array(
			$phone_local,
			$toll_free,
		);

		return $data;
	}

	function author_url_request_mod( $query_vars ) {

		if ( array_key_exists( 'author_name', $query_vars ) ) {
			global $wpdb;
			$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='author_link_slug' AND meta_value = %s", $query_vars['author_name'] ) );

			if ( $author_id ) {
				$query_vars['author'] = $author_id;
			}

			unset( $query_vars['author_name'] );
		}

		return $query_vars;

	}

	function author_url_profile_mod( $link, $author_id, $author_nicename ) {

		$user = get_user_by('ID', $author_id );

		if( $user ) {
			$new_slug = get_user_meta( $user->ID, 'author_link_slug', true );

			if( !empty( $new_slug ) ) {
				$link = str_replace( $author_nicename, $new_slug, $link );
			}
		}

		return $link;
	}


	/**
	 * Appends slideshow content to the slideshow template output
	 * @since     1.0.0
	 * @return  string post content
	 */

	public function slideshow_page_content( $content ) {

		global $post;

		$vw = new VisitWeho();


		if ( isset( $post->post_type ) ) {
			if( $post->post_type == 'post' ) {

				if( !is_admin() && is_singular() && is_main_query() ) {
					$slideshow = $vw->output_slideshow_markup( $post->ID );
					$index = $vw->get_slide_index();
					$wrapper_class = $index ? 'cw-slide-overview' : 'cw-slide-overview';
					$content = '<div class="' . $wrapper_class . '">' . $content . '</div>' . $slideshow;
				}

			}
		}

		return $content;

	}

	/**
	 * Modifies the query if slideshow posts are included in blog feeds
	 * @since  1.0.0
	 */

	public function slideshow_query_mod( $query ) {

		// $plugin = new VisitWeho();
		// $options = $plugin->get_options();

		// if( $options['show_in_blog'] == true ) {
		//   if ( !is_admin() && $query->is_main_query() ) {
		//   	if( $query->is_feed() || $query->is_archive() || $query->is_search() || $query->is_posts_page() || $query->is_home() ) {
		//   		if( !is_post_type_archive() ) {
		//   			$query->set('post_type', array( 'cw-slideshow', 'post' ) );
		//   		}
		//   	}
		//   }
		// }

	}

	public function yoast_og_filter( $url ) {

		if( is_single() ) {
			global $post;

			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			if( $thumbnail_id ) {
				$r = wp_get_attachment_image_src( $thumbnail_id, 'hero' );
				$banner = empty( $r ) ? false : $r[0];
				return str_replace($url, $banner, $url);
			} else {
				return $url;
			}

		} else {
			return $url;
		}
	}

	public function templates_redirect() {
		if( is_tax('venue') ) {
			$term_id = get_queried_object()->term_id;
			$term = get_term_by('id', $term_id, 'venue' );
			if( $term ) {
				wp_redirect( home_url( '/events?venues=' . $term->slug ), 301 );
				exit();
			}
		} elseif( is_tax('event-category') ) {
			$term_id = get_queried_object()->term_id;
			$term = get_term_by('id', $term_id, 'event-category' );
			if( $term ) {
				wp_redirect( home_url( '/events?type=' . $term->slug ), 301 );
				exit();
			}
		} elseif( is_tax('neighborhood') ) {
			$term_id = get_queried_object()->term_id;
			$term = get_term_by('id', $term_id, 'neighborhood' );
			if( $term ) {
				wp_redirect( home_url( '/hotels?neighborhoods=' . $term->slug ), 301 );
				exit();
			}
		} elseif( is_tax('amenity') ) {
			$term_id = get_queried_object()->term_id;
			$term = get_term_by('id', $term_id, 'amenity' );
			if( $term ) {
				wp_redirect( home_url( '/hotels?amenities=' . $term->slug ), 301 );
				exit();
			}
		} elseif( is_tax('location-tag') ) {
			wp_redirect( home_url( '/taste' ), 301 );
			exit();
		} elseif( is_tax('offer-tag') ) {
			wp_redirect( home_url(), 301 );
			exit();
		} elseif( is_tax('campaign') ) {
			wp_redirect( home_url(), 301 );
			exit();
		} elseif( is_tax('location-type') ) {
			wp_redirect( home_url(), 301 );
			exit();
		}
	}

	public function handle_event_404() {
		global $wp_query;
		if( $wp_query->is_404 ) {
			if(strpos($_SERVER['REQUEST_URI'], 'events') !== false) {
				setcookie('event-expired-query', true, time() + 10, '/');
				wp_redirect( home_url('/events'), 301 );
				exit();
			}

			if(strpos($_SERVER['REQUEST_URI'], 'offer') !== false) {
				setcookie('offer-expired-query', true, time() + 10, '/');
				wp_redirect( home_url('/play'), 301 );
				exit();
			}
		}
	}


	public function crowdriff_api_app_fetch() {

		set_time_limit(0);
		$visit_weho = new VisitWeho();

		$token = $visit_weho->crowdriff_api_key;

		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		/**
		 * Grabs any newly added apps
		 */

		$ch = curl_init('https://api.crowdriff.com/v2/apps?pretty=true');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'VisitWeho/0.1 (edward@codewrangler.io)');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $token
				));

		$data = curl_exec($ch);

		$json = json_decode( $data );

		if( isset( $json->data ) ) {
			foreach( $json->data as $app ) {
				$app_id = $app->id;
				$app_label = $app->label;

				$matching = get_page_by_title( $app_id, OBJECT, 'crowdriff-app' );

				if( $matching == null ) {


					$args = array(
						'post_type' => 'crowdriff-app',
						'post_status' => 'publish',
						'post_content' => '',
						'post_title' => $app_id,
					);

					$insert = wp_insert_post( $args );

					update_post_meta( $insert, 'label', $app_label );
				}
			}
		}

		/**
		 * Fetch assets by local apps
		 */

		$args = array(
			'post_type' => 'crowdriff-app',
			'posts_per_page' => -1,
		);

		$apps = get_posts( $args );

		foreach( $apps as $app ) {
			$app_id = $app->post_title;
			$app_post_id = $app->ID;
			try {
				$ch = curl_init();

				if ($ch === false) {
					throw new Exception('DOH!');
				}

				$data = array(
					'count' => 100,
					'search_filter' => array('apps' => array(array('id' => $app_id ))),
				);

				$json_data = json_encode($data);

				curl_setopt($ch, CURLOPT_USERAGENT, 'VisitWeho/0.1 (edward@codewrangler.io)');
				curl_setopt($ch, CURLOPT_URL, 'https://api.crowdriff.com/v2/search?pretty=false');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Authorization: Bearer ' . $token
						));
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

				$content = curl_exec($ch);

				if ($content === false) {
					throw new Exception(curl_error($ch), curl_errno($ch));
				}

				$content = json_decode($content);

				$args = array(
					'post_type'   => 'attachment',
					'numberposts' => -1,
					'post_status' => 'any',
					'post_parent' => $app_post_id,
				);

				$attachments = get_posts( $args );

				if ( $attachments ) {
					foreach ( $attachments as $attachment ) {
						wp_delete_attachment( $attachment->ID , true );
					}
				}

				$collection = array();

				foreach( $content->data->assets as $asset ) {
					$hashtags = $asset->hashtags;
					$cta_ids = $asset->ctas;
					$source = $asset->source;
					$likes = $asset->native_likes;
					$native_url = $asset->native_url;
					$desc = $asset->text;
					$user_full_name = $asset->user->full_name;
					$username = $asset->user->screen_name;
					$img_url = $asset->image_fullscreen->url;
					$location_name = $asset->location_label;
					$date = $asset->created_at;
					$score = $asset->quality_score;

					// Fields
					$r = array(
						'hashtags'       => $hashtags,
						'source'         => $source,
						'likes'          => $likes,
						'native_url'     => $native_url,
						'desc'           => $desc,
						'user_full_name' => $user_full_name,
						'username'       => $username,
						'img_url'        => $img_url,
						'location_name'  => $location_name,
						'date'           => $date,
						'cta_ids'        => $cta_ids,
						'score'          => $score,
					);

					$collection[] = $r;
				}

				/**
				 * Reorder based on CR quality score
				 */

				usort($collection, function($a, $b) {
					return $a['score'] <=> $b['score'];
				});

				foreach( $collection as $asset ) {
					$load_img_id = media_sideload_image( $asset['img_url'], $app_post_id, $app_id, 'id' );

					if( !is_wp_error( $load_img_id ) ) {
						foreach( $asset as $k => $v ) {
							update_post_meta( $load_img_id, $k, $v );
						}
					}
				}

				curl_close($ch);
			} catch(Exception $e) {
				continue;
			}

		}

	}

	public function default_script_mods( $scripts ) {
			if (!is_admin() ) {
				$scripts->remove( 'jquery');
				$scripts->add( 'jquery', false, array( 'jquery-core' ) );
		}
	}

	public function crowdriff_api_cta_fetch() {
		$visit_weho = new VisitWeho();
		$token = $visit_weho->crowdriff_key;

		$args = array(
			'post_type' => 'crowdriff-app',
			'posts_per_page' => -1,
		);

		$apps = get_posts( $args );

		foreach( $apps as $app ) {
			$app_id = $app->post_title;
			$app_post_id = $app->ID;

			$args = array(
				'post_type'   => 'attachment',
				'numberposts' => -1,
				'post_status' => 'any',
				'post_parent' => $app_post_id,
			);

			$attachments = get_posts( $args );

			foreach( $attachments as $attachment ) {
				$cta_ids = get_post_meta( $attachment->ID, 'cta_ids', true );

				if( !empty( $cta_ids ) ) {
					// Multiple CTAs??
					$cta_id = implode(',', $cta_ids);
					try {
						$ch = curl_init();

						if ($ch === false) {
							throw new Exception('DOH!');
						}

						curl_setopt($ch, CURLOPT_USERAGENT, 'VisitWeho/0.1 (edward@codewrangler.io)');
						curl_setopt($ch, CURLOPT_URL, 'https://api.crowdriff.com/v2/ctas/' . $cta_id );
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(
								'Content-Type: application/json',
								'Authorization: Bearer ' . $token
								));

						$content = curl_exec($ch);

						if ($content === false) {
							throw new Exception(curl_error($ch), curl_errno($ch));
						}

						$content = json_decode($content);

						$cta = current($content->data);
						$label = $cta->label;
						$url = $cta->url;

						update_post_meta( $attachment->ID, 'cta_url', $url );
						update_post_meta( $attachment->ID, 'cta_label', $label );

					} catch(Exception $e) {
						continue;
					}
				}
			}
		}
	}


	public function disable_autoptimize_amp() {

		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return true;
		} else {
			return false;
		}

	}

	public function register_menu_widgets() {
		$args = array(
			'post_type' => 'menu-widget',
			'posts_per_page' => -1,
		);

		$widgets = get_posts( $args );

		if( empty( $widgets ) ) {

			$defaults = array(
				'Menu Column',
				'Categories',
				'Recent Articles',
				'Heading',
				'Text Block',
				'Featured Events',
				'Event Categories',
				'Special Offer',
			);

			foreach( $defaults as $item ) {
				$r = array(
					'post_title' => $item,
					'post_status' => 'publish',
					'post_type' => 'menu-widget',
					'post_content' => '',
				);

				$p = wp_insert_post( $r );

			}
		}
	}

	public function generate_mega_menu_markup($nav_menu_selected_id) {
		$markup = $this->output_mega_menu_markup($nav_menu_selected_id);

		$theme_locations = get_nav_menu_locations();

		foreach ($theme_locations as $k => $v) {
			if( $v == $nav_menu_selected_id ) {
				update_option($k . '_menu_markup', $markup);
			}
		}
	}

	public function generate_mega_menu_markup_cron() {

		$theme_locations = get_nav_menu_locations();

		foreach( $theme_locations as $k => $v ) {
			$markup = $this->output_mega_menu_markup($v);
			update_option($k . '_menu_markup', $markup);
		}
	}

	public function mega_menu_save($nav_menu_selected_id) {
    $this->generate_mega_menu_markup($nav_menu_selected_id);
	}

	public function output_mega_menu_markup($nav_menu_selected_id = '') {
		$visit_weho = new VisitWeho();

		if( empty( $nav_menu_selected_id ) ) {
			return false;
		}

		$markup = '';
		$menu_objects = wp_get_nav_menu_items( $nav_menu_selected_id );
		$top_level_items = array();

		foreach( $menu_objects as $menu_item ) {
			if( $menu_item->menu_item_parent == 0 ) {
				$top_level_items[] = $menu_item;
			}
		}

		$count = 0;

		foreach( $top_level_items  as $item ) {
			$item->children = array();
			foreach( $menu_objects as $menu_item ) {
				if( $menu_item->menu_item_parent == $item->ID ) {
					$item->children[] = $menu_item;
				}
			}
			$top_level_items[$count] = $item;
			$count++;
		}

		$markup .= '<ul id="header-menu" class="menu">';
		foreach( $top_level_items as $menu_item ) {

			$desc = $menu_item->description;
			$classes = $menu_item->classes;

			$class_str = '';

			foreach( $classes as $class ) {
				$class_str = $class;
			}

			$children = $menu_item->children;

			if( !empty( $children ) ) {
				$class_str = $class_str .= ' has-submenu';
			}

			$markup .= '<li id="li-drop-' . $menu_item->ID . '" on="tap:drop-' . $menu_item->ID . '.toggleVisibility,li-drop-' . $menu_item->ID . '.toggleClass(class=' . "open" . ')" class="' . $class_str . '">';

			if( empty( $menu_item->post_title ) ) {
				$main_title = $menu_item->title;
			} else {
				$main_title = $menu_item->post_title;
			}

			$markup .= '<a href="' . $menu_item->url . '">' . $main_title . '</a>';

			$is_mega_menu = false;

			foreach( $children as $t ) {
				if( $t->title == 'Menu Column' ) {
					$is_mega_menu = true;
				}
			}

			if( $is_mega_menu ) {
				$markup .= '<div id="drop-' . $menu_item->ID . '" class="mega-dropdown-nav" hidden>
										<div class="dropdown-inner ' . $class_str . '">';

				foreach( $children as $t ) {
					if( $t->title == 'Menu Column' ) {

						$classes = $t->classes;
						$class_str = '';

						foreach( $classes as $class ) {
							$class_str = $class;
						}

						$markup .= '<div class="dropdown-column ' . $class_str . '">';

						foreach( $menu_objects as $object ) {
							if( $object->menu_item_parent == $t->ID ) {

								$classes = $object->classes;
								$class_str = '';

								foreach( $classes as $class ) {
									$class_str = $class;
								}

								$markup .= '<div class="column-item ' . $class_str . '">';
								if( $object->type_label == 'Post' ) {
									try {
										$story = new VisitWeho_Story( $object->object_id );

										$media_id = get_post_thumbnail_id( $object->object_id );
										$r = wp_get_attachment_image_src( $media_id, 'widescreen-crop' );
										$image = empty( $r ) ? false : $r[0];

										$markup .= '<a href="' . $object->url . '">';
										if( $image ) {
											$markup .= '<img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="' . $image . '" alt="' . $object->title . '" />';
										}
										$markup .=  '<h6 class="merriweather">' . $object->title . '</h6></a>';
									} catch (Exception $e) {
										continue;
									}
								} elseif( $object->type_label == 'Page' ) {
									$markup .= '<a href="' . $object->url . '">' . $object->title . '</a>';
								} elseif( $object->type_label == 'Custom Link' ) {
									$markup .= '<a href="' . $object->url . '">' . $object->title . '</a>';
								} elseif( $object->type_label == 'Category' ) {
									$markup .= '<a href="' . $object->url . '">' . $object->title . '</a>';
								} elseif( $object->type_label == 'Menu Widget' ) {

									if( $object->title == 'Recent Articles' ) {
										$markup .= '<div class="menu-widget menu-widget__recent-articles">
																<h4 class="menu-widget__recent-articles__heading merriweather">Recent Articles</h4>';

										$qargs = array(
											'post_type' => 'post',
											'posts_per_page' => 2,
										);

										$recent_articles = get_posts( $qargs );

										$markup .= '<ul>';

										foreach( $recent_articles as $article ) {
											$markup .= '<li><a href="' . get_the_permalink( $article->ID ) . '">' . $article->post_title . '</a></li>';
										}

										$markup .= '</ul>';

										$markup .= '<h4 class="merriweather no-border"><a href="/stories">See All Articles</a></h4>';

										$markup .= '</div>';
									}

									if( $object->title == 'Categories' ) {
										$markup .= '<div class="menu-widget menu-widget__categories">
																<h4 class="menu-widget__categories__heading  merriweather">Categories</h4>';

										$cats = get_categories();

										$markup .= '<ul>';

										foreach( $cats as $cat ) {
											$markup .= '<li><a href="' . get_term_link( $cat->term_id) . '">' . $cat->name . '</a></li>';
										}

										$markup .= '</ul>';

										$markup .= '</div>';
									}

									if( $object->title == 'Event Categories' ) {
										$markup .= '<div class="menu-widget menu-widget__event-categories">
																<h4 class="menu-widget__event-categories__heading merriweather">Events by Category</h4>';

										$cats = get_terms( array(
											'taxonomy' => 'event-category',
											'hide_empty' => true,
										) );

										$markup .= '<ul>';

										foreach( $cats as $cat ) {
											$markup .= '<li><a href="' . get_term_link( $cat->term_id) . '">' . $cat->name . '</a></li>';
										}

										$markup .= '</ul>';

										//$markup .= '<h4 class="merriweather no-border"><a href="/stories">See All Events</a></h4>';

										$markup .= '</div>';
									}

									if( $object->title == 'Featured Events' ) {

										$featured_events = $visit_weho->get_events( array('items' => 3, 'is_featured' => true ) );

										if( !empty( $featured_events ) ) {
											$markup .= '<div class="menu-widget menu-widget__featured-events">
											<h4 class="menu-widget__featured-events__heading merriweather">Featured Events</h4>';

											$markup .= '<ul>';

											foreach( $featured_events as $event ) {
												$date = date('F j, Y', $event->begin_time );
												$markup .= '<li>';
												$markup .= '<a class="menu-widget__featured-events__title" href="' . $event->permalink . '">' . $event->name . '</a>';
												//$markup .= '<a class="menu-widget__featured-events__date" href="' . $event->permalink . '">' . $date . '</a>';
												$markup .= '<span class="menu-widget__featured-events__date">' . $date . '</span>';
												$markup .= '</li>';
											}

											$markup .= '</ul>';

											$markup .= '</div>';
										}

									}

									if( $object->title == 'Heading' ) {
											$markup .= '<div class="menu-widget menu-widget__heading">';
											$markup .= '<h4 class=" merriweather">' . $object->description . '</h4>';
											$markup .= '</div>';
									}

									if( $object->title == 'Text Block' ) {
											$markup .= '<div class="menu-widget menu-widget__text-block">';
											$markup .= '<p>' . $object->description . '</p>';
											$markup .= '</div>';
									}

									if( $object->title == 'Special Offer' ) {
										$offer_id = get_post_meta( $object->ID, 'offer_id', true );
										if( !empty( $offer_id ) ) {
											$o = new VisitWeho_Offer( $offer_id );
											if( $o->expiration >= strtotime('now') ) {
												if( !empty( $o->menu_image ) ) {
													$markup .= '<div style="margin-bottom: 1rem;" class="menu-widget menu-widget__special-offer">';
													$markup .= '<a href="' . get_the_permalink($offer_id) . '">
													<img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="' . $o->menu_image . '" /></a>';
													$markup .= '</div>';
												}
											}
										}
									}

								} else {
									$markup .= '<a class="else" href="' . $object->url . '">' . $object->title . '</a>';
								}

								$markup .= '</div>';
							}
						}

						$markup .= '</div>';
					}
				}

				$markup .= '</div>';
				$markup .= '</div>';
			} else {

				if( $children ) {
					$markup .= '<ul class="dropdown-nav">';

					foreach( $children as $t ) {

						$markup .= '<li class="">';
						$markup .= '<a href="' . $t->url . '">' . $t->title . '</a>';
						$markup .= '</li>';

					}

					$markup .= '</ul>';
				}

			}

		}

		$markup .= '</ul>';

		return $markup;
	}

	// Sets nonce expiration to 72 hours to exceed cache timeout
	public function extend_nonce() {
		return 259200;
	}

	/**
		 * Clears cache action
		 * @since 2.8.0
	 */
	public function clear_cache_cron() {
		$vw = new VisitWeho();
		$vw->clear_all_cache();
		//$url = get_site_url();
		exec('wget --spider --recursive --level=2 https://www.visitwesthollywood.com');
	}

	/**
		 * Unsets Filter Images
		 * TODO: not currently running
		 * @since 2.8.1
	 */
	public function filter_wp_images( $sizes) {
		unset( $sizes['1536x1536']);
		unset( $sizes['2048x2048']);
		return $sizes;
	}

	/**
		 * Saves location stories datafeeds
		 * @since 2.9.0
	 */

	public function save_location_stories() {
		$visit_weho = new VisitWeho();

		$largs = array(
			'post_type' => 'location',
			'posts_per_page' => -1,
		);

		$locations = get_posts( $largs );

		foreach( $locations as $location ) {
			$store_ids = array();
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 3,
				'meta_query' => array(
					array(
						'key' => 'attached_locations',
						'value' => $location->ID,
						'compare' => 'LIKE',
					),
				)
			);

			$stories = get_posts( $args );

			foreach( $stories as $story ) {
				$store_ids[] = $story->ID;
			}

			$count = count( $stories );
			if( $count < 3 ) {
				$diff = 3 - $count;

				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $diff,
					'orderby' => 'rand',
				);

				$supp_stories = get_posts( $args );

				$count = count( $supp_stories );

				foreach( $supp_stories as $story ) {
					$store_ids[] = $story->ID;
				}
			}

			update_post_meta( $location->ID, 'related_stories', $store_ids );
		}
	}

	/**
		 * Saves hotel stories datafeeds
		 * @since 2.9.0
	 */

	public function save_hotel_stories() {
		$visit_weho = new VisitWeho();

		$hargs = array(
			'post_type' => 'hotel',
			'posts_per_page' => -1,
		);

		$hotels = get_posts( $hargs );

		foreach( $hotels as $hotel ) {
			$store_ids = array();
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 3,
				'meta_query' => array(
					array(
						'key' => 'attached_locations',
						'value' => $hotel->ID,
						'compare' => 'LIKE',
					),
				)
			);

			$stories = get_posts( $args );

			foreach( $stories as $story ) {
				$store_ids[] = $story->ID;
			}

			$count = count( $stories );
			if( $count < 3 ) {
				$diff = 3 - $count;

				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $diff,
					'orderby' => 'rand',
				);

				$supp_stories = get_posts( $args );

				$count = count( $supp_stories );

				foreach( $supp_stories as $story ) {
					$store_ids[] = $story->ID;
				}
			}

			update_post_meta( $hotel->ID, 'related_stories', $store_ids );
		}
	}



	public function register_yoast_variables() {
		if( function_exists('wpseo_register_var_replacement') ) {
			wpseo_register_var_replacement(
				'%%event_date%%',
				array($this, 'yoast_event_date' ),
				'advanced',
				'Help text'
			);

			wpseo_register_var_replacement(
				'%%event_category%%',
				array($this, 'yoast_event_category' ),
				'advanced',
				'Help text'
			);
		}
	}

	public function yoast_event_date() {
		global $post;
		$ts = get_post_meta( $post->ID, 'begin_time', true );
		return date('F d', $ts);
	}

	public function yoast_event_category() {
		global $post;
		$event = new VisitWeho_Event( $post->ID );
		if( $event->categories ) {
			$cat = $event->categories[0]->name;
			return $cat;
		}
	}
}
