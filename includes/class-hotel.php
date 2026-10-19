<?php

/**
 * Main hotel class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Hotel {

	public $id;
	public $name;
	public $description;
	public $excerpt;
	public $permalink;
	public $banner;
	// TODO deprecate $gallery_items;
	public $gallery_items;
	public $media;
	public $featured_media;
	public $neighborhood;
	public $amenities;
	public $features;
	public $address;
	public $city;
	public $state;
	public $zip;
	public $phone;
	public $secondary_phone;
	public $subtitle;
	public $latitude;
	public $longitude;
	public $from_price;
	public $website_url;
	public $twitter_url;
	public $facebook_url;
	public $instagram_url;
	public $yelp_url;
	public $google_place_id;
	public $social_embed;
	public $ares_id;
	public $related_stories;

	public function __construct( $id ) {
		$visit_weho = new VisitWeho();
		$h = get_post( $id );

		$type = get_post_type( $id );

		if ('hotel' != $type) {
			throw new Exception('ID ' . $id . ' is not a hotel');
		}

		$media_id = get_post_thumbnail_id( $id );
		$r = wp_get_attachment_image_src( $media_id, 'hero' );
		$banner = empty( $r ) ? false : $r[0];
		$this->id = $id;
		$this->name = get_the_title( $id );
		$this->description = $h->post_content;
		$base_url = get_site_url();

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $h->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $h->post_content, 55, null );
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

		$amenities = wp_get_post_terms( $id, 'amenity' );

		if( is_wp_error( $amenities ) || empty( $amenities ) ) {
			$this->amenities = false;
		} else {
			$this->amenities = $amenities;
		}

		$features = empty( get_post_meta( $id, 'hotel_features', true ) ) ? array() : get_post_meta( $id, 'hotel_features', true );

		$this->address = get_post_meta( $id, 'address', true  );
		$this->city = get_post_meta( $id, 'city', true  );
		$this->state = get_post_meta( $id, 'state', true  );
		$this->zip = get_post_meta( $id, 'zip', true  );
		$this->features = $features;
		$this->latitude = get_post_meta( $id, 'latitude', true  );
		$this->longitude = get_post_meta( $id, 'longitude', true  );
		$this->subtitle = get_post_meta( $id, 'subtitle', true  );
		$this->ares_id = get_post_meta( $id, 'ares_id', true  );

		$this->permalink = get_the_permalink( $id );

		if( get_post_meta( $id, 'phone', true ) ) {
			$formatted_phone = $visit_weho->format_phone_number( get_post_meta( $id, 'phone', true ), 'US' );
			$this->phone = $formatted_phone;
		}

		if( get_post_meta( $id, 'secondary_phone', true ) ) {
			$formatted_phone = $visit_weho->format_phone_number( get_post_meta( $id, 'secondary_phone', true ), 'US' );
			$this->secondary_phone = $formatted_phone;
		}


		// $att_args = array(
		// 		'posts_per_page' => -1,
		// 		'post_parent' => $this->id,
		// 		'post_type' => 'attachment',
		// 		'post_mime_type' => 'image',
		// 		'order' => 'ASC',
		// 	);
		//
		// $attachments = get_posts( $att_args );

		/**
		 * Moving from pure attachments to admin-managed gallery-items
		 * @feature/attached-media-management
		 * Doing this in a backwards compatible manner for now
		 * @2.7.0
		 */
		$hotel_gallery_items = array();
		$media_gallery = get_post_meta( $id, 'gallery_images', true );

		foreach( $media_gallery as $media_gallery_id ) {
			$img_url = wp_get_attachment_url( $media_gallery_id );
			$hotel_gallery_items[$media_gallery_id] = $img_url;
		}

		$this->gallery_items = $hotel_gallery_items;

		$this->from_price = get_post_meta( $id, 'from_price', true  );
		$this->website_url = get_post_meta( $id, 'website_url', true  );
		$this->facebook_url = get_post_meta( $id, 'facebook_url', true  );
		$this->twitter_url = get_post_meta( $id, 'twitter_url', true  );
		$this->instagram_url = get_post_meta( $id, 'instagram_url', true  );
		$this->yelp_url = get_post_meta( $id, 'yelp_url', true  );
		$this->google_place_id = get_post_meta( $id, 'google_place_id', true );
		$this->social_embed = get_post_meta( $id, 'social_embed', true );
		$this->related_stories = get_post_meta( $id, 'related_stories', true );
	}
}
