<?php

/**
 * Main location class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Location {

	public $id;
	public $name;
	public $description;
	public $excerpt;
	public $banner;
	public $media;
	public $featured_media;
	public $neighborhood;
	public $types;
	public $tags;
	public $address;
	public $city;
	public $state;
	public $zip;
	public $phone;
	public $latitude;
	public $longitude;
	public $distance;
	public $twitter_url;
	public $facebook_url;
	public $instagram_url;
	public $website_url;
	public $yelp_url;
	public $yelp_data;
	public $google_place_id;
	public $venue;
	public $permalink;
	public $story_ids;
	public $opentable_id;
	public $opentable_data;
	public $related_stories;

	public function __construct( $id ) {
		$visit_weho = new VisitWeho();
		$l = get_post( $id );

		$type = get_post_type( $id );

		if ('location' != $type) {
			throw new Exception('ID ' . $id . ' is not a location');
		}

		$media_id = get_post_thumbnail_id( $id );
		$r = wp_get_attachment_image_src( $media_id, 'hero' );
		$banner = empty( $r ) ? false : $r[0];
		$this->id = $id;
		$this->name = get_the_title( $id );
		$this->google_place_id = get_post_meta( $id, 'google_place_id', true  );
		$this->description = $l->post_content;
		$base_url = get_site_url();

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $l->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $l->post_content, 55, null );
		}

		if( $banner ) {
			$this->banner = array('img_url' => $banner, 'media_id' => $media_id );
		} else {
			$this->banner = array('img_url' => $base_url . '/wp-content/plugins/visitweho/public/assets/content-fallback.jpg', 'media_id' => false );
		}

		$media = get_attached_media( 'image', $id );

		$r = array();

		if( $media ) {
			foreach( $media as $media ) {
				$meta = wp_get_attachment_metadata( $media->ID );
				$meta['media_id'] = $media->ID;
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

				$r[] = $meta;
			}
		}

		$this->media = $r;


		$r = array();

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

		$neighborhoods = wp_get_post_terms( $id, 'neighborhood' );

		if( is_wp_error( $neighborhoods ) || empty( $neighborhoods ) ) {
			$this->neighborhood = false;
		} else {
			$neighborhood = $neighborhoods[0];
			$this->neighborhood = $neighborhood;
		}

		$types = wp_get_post_terms( $id, 'location-type' );

		if( is_wp_error( $types ) || empty( $types ) ) {
			$this->types = false;
		} else {
			$this->types = $types;
		}

		$tags = wp_get_post_terms( $id, 'location-tag' );

		if( is_wp_error( $tags ) || empty( $tags ) ) {
			$this->tags = false;
		} else {
			$this->tags = $tags;
		}

		$this->address = get_post_meta( $id, 'address', true  );
		$this->city = get_post_meta( $id, 'city', true  );
		$this->state = get_post_meta( $id, 'state', true  );
		$this->zip = get_post_meta( $id, 'zip', true  );
		$this->latitude = get_post_meta( $id, 'latitude', true  );
		$this->longitude = get_post_meta( $id, 'longitude', true  );
		$this->distance = get_post_meta( $id, 'distance', true  );

		if( get_post_meta( $id, 'phone', true ) ) {
			$formatted_phone = $visit_weho->format_phone_number( get_post_meta( $id, 'phone', true ), 'US' );
			$this->phone = $formatted_phone;
		}

		if( get_post_meta( $id, 'secondary_phone', true ) ) {
			$formatted_phone = $visit_weho->format_phone_number( get_post_meta( $id, 'secondary_phone', true ), 'US' );
			$this->secondary_phone = $formatted_phone;
		}

		$this->yelp_data = get_post_meta( $id, 'yelp_data', true );

		$this->permalink = get_the_permalink( $id );
		$this->website_url = get_post_meta( $id, 'website_url', true  );
		$this->facebook_url = get_post_meta( $id, 'facebook_url', true  );
		$this->twitter_url = get_post_meta( $id, 'twitter_url', true  );
		$this->instagram_url = get_post_meta( $id, 'instagram_url', true  );
		$this->yelp_url = get_post_meta( $id, 'yelp_url', true  );
		$this->google_place_id = get_post_meta( $id, 'google_place_id', true );
		$this->venue = get_post_meta( $id, 'venue_id', true );

		$this->opentable_id = get_post_meta( $id, 'opentable_id', true );
		$this->opentable_data = get_post_meta( $id, 'opentable_data', true );

		if( $this->venue ) {
			$this->venue = new VisitWeho_Venue( $this->venue );
		}

		$this->related_stories = get_post_meta( $id, 'related_stories', true );
	}
}
