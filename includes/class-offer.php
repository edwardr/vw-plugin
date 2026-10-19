<?php

/**
 * Main offer class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Offer {

	public $id;
	public $name;
	public $display_name;
	public $short_description;
	public $long_description;
	public $fine_print;
	public $excerpt;
	public $expiration;
	public $has_landing_page;
	public $landing_page_description;
	public $landing_page_title;
	public $landing_page_subtitle;
	public $hotel_ids;
	public $location_ids;
	public $media_id;
	public $media_url;
	public $featured_media;
	public $banner_url;
	public $campaign_slug;
	public $menu_image;
	public $hide_events_cta;

	public function __construct( $id ) {
		$o = get_post( $id );
		$type = get_post_type( $id );

		if ('offer' != $type) {
			throw new Exception('ID ' . $id . ' is not an offer');
		}

		$base_url = get_site_url();


		$media_id = get_post_thumbnail_id( $id );
		$this->media_id = $media_id;
		$r = wp_get_attachment_image_src( $media_id, 'square' );
		$media_url = empty( $r ) ? false : $r[0];

		$r = wp_get_attachment_image_src( $media_id, 'hero' );
		$banner_url = empty( $r ) ? false : $r[0];

		$this->id = $id;
		$this->name = get_the_title( $id );

		$this->display_name = get_post_meta( $id, 'display_name', true );
		$this->long_description = apply_filters('the_content', $o->post_content );
		$this->short_description = get_post_meta( $id, 'short_description', true );
		$this->fine_print = get_post_meta( $id, 'fine_print', true );
		$this->hotel_ids = get_post_meta( $id, 'hotel_ids', true );
		$this->location_ids = get_post_meta( $id, 'location_ids', true );
		$this->expiration = get_post_meta( $id, 'expiration', true );

		$this->campaign_slug = get_post_meta( $id, 'campaign_slug', true );

		if( $media_url ) {
			$this->media_url = array('img_url' => $banner_url, 'media_id' => $media_id );
		} else {
			$this->media_url = array('img_url' => $base_url . '/wp-content/plugins/visitweho/public/assets/content-fallback.jpg', 'media_id' => false );
		}

		if( $banner_url ) {
			$this->banner_url = array('img_url' => $banner_url, 'media_id' => $media_id );
		} else {
			$this->banner_url = array('img_url' => $base_url . '/wp-content/plugins/visitweho/public/assets/content-fallback.jpg', 'media_id' => false );
		}

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $o->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $o->post_content, 55, null );
		}

		$media = get_attached_media( 'image', $id );

		if( $media_id ) {
			$meta = wp_get_attachment_metadata( $media_id );
			$meta['media_id'] = $media_id;
			$meta['file'] = $base_url . '/wp-content/uploads/' . $meta['file'];
			$base = basename( $meta['file'] );
			$path = str_replace( $base, '', $meta['file'] );

			foreach( $meta['sizes'] as $k => $v ) {
				$meta['sizes'][$k]['file'] = $path . $meta['sizes'][$k]['file'];
			}

			$sizes = array('thumbnail', 'medium', 'medium_large', 'large', 'widescreen-crop', 'square', 'hero' );

			foreach( $sizes as $size ) {
				if( !isset( $meta['sizes'][$size] ) ) {
					$meta['sizes'][$size]['file'] = $meta['file'];
				}
			}

			$this->featured_media = $meta;
		} else {
			$this->featured_media = false;
		}

		$landing = get_post_meta( $id, 'has_landing_page', true );

		$this->has_landing_page = $landing;
		$this->landing_page_title = get_post_meta( $id, 'landing_page_title', true );
		$this->landing_page_subtitle = get_post_meta( $id, 'landing_page_subtitle', true );
		$this->terms = get_post_meta( $id, 'terms', true );
		$this->menu_image = get_post_meta( $id, 'menu_image', true );
		$this->related_content = get_post_meta( $id, 'related_content', true );

		$this->landing_page_description = get_post_meta( $id, 'landing_page_description', true );

		$hide_events_cta = get_post_meta( $id, 'hide_events_cta', true );
		$this->hide_events_cta = $hide_events_cta == '' ? false : true;
	}
}
