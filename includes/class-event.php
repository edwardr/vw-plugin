<?php

/**
 * Main event class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Event {

	public $id;
	public $permalink;
	public $name;
	public $description;
	public $schema_description;
	public $excerpt;
	public $banner;
	public $venue;
	public $categories;
	public $dola_id;
	public $begin_time;
	public $end_time;
	public $is_free;
	public $is_featured;
	public $is_ongoing;
	public $presented_by;
	public $rsvp;
	public $sold_out;
	public $day;
	public $month;
	public $year;
	public $buy_url;
	public $artists;

	public function __construct( $id ) {
		$e = get_post( $id );
		$type = get_post_type( $id );

		if ('event' != $type) {
			throw new Exception('ID ' . $id . ' is not an event');
		}
		
		$media_id = get_post_thumbnail_id( $id );
		$r = wp_get_attachment_image_src( $media_id, 'hero' );
		$banner = empty( $r ) ? false : $r[0];
		$this->id = $id;
		$this->name = get_the_title( $id );
		$this->description = $e->post_content;
		$this->schema_description = str_replace('"', '', $e->post_content);
		$this->permalink = get_the_permalink( $id );
		$this->artists = get_post_meta( $id, 'artists', true );
		$base_url = get_site_url();

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $e->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $e->post_content, 55, null );
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

				if( $meta['sizes'] ) {
					foreach( $meta['sizes'] as $k => $v ) {
						$meta['sizes'][$k]['file'] = $path . $meta['sizes'][$k]['file'];
					}
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

		$venues = wp_get_post_terms( $id, 'venue' );

		if( is_wp_error( $venues ) || empty( $venues ) ) {
			$this->venue = false;
		} else {
			$venue = $venues[0];
			$term_id = $venue->term_id;
			$this->venue = new VisitWeho_Venue( $term_id );
		}

		$categories = wp_get_post_terms( $id, 'event-category' );

		if( is_wp_error( $categories ) || empty( $categories ) ) {
			$this->categories = false;
		} else {
			$this->categories = $categories;
		}

		$this->dola_id = get_post_meta( $id, 'dola_event_id', true  );
		$this->buy_url = get_post_meta( $id, 'buy_url', true  );
		$this->begin_time = get_post_meta( $id, 'begin_time', true  );
		$this->month = get_post_meta( $id, 'month', true  );
		$this->day = get_post_meta( $id, 'day', true  );
		$this->year = get_post_meta( $id, 'year', true  );
		$this->end_time = get_post_meta( $id, 'end_time', true  );

		if( get_post_meta( $id, 'end_time', true  ) == '' ) {
			$this->end_time = strtotime( '+4 hours', (int)$this->begin_time );
		}

		$this->is_free = get_post_meta( $id, 'is_free', true  );
		$this->is_featured = get_post_meta( $id, 'is_featured', true  );
		$this->is_ongoing = get_post_meta( $id, 'is_ongoing', true  );
		$this->presented_by = get_post_meta( $id, 'presented_by', true  );
		$this->rsvp = get_post_meta( $id, 'rsvp', true  );
		$this->sold_out = get_post_meta( $id, 'sold_out', true  );
	}
}