<?php

/**
 * Video post class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Video {

	public $id;
	public $name;
	public $description;
	public $excerpt;
	public $banner;
	public $neighborhoods;
	public $video_url;
	public $video_embed;
	public $location_ids;
	public $date;

	public function __construct( $id ) {
		$l = get_post( $id );

		$type = get_post_type( $id );

		if ('video' != $type) {
			throw new Exception('ID ' . $id . ' is not a video');
		}

		$this->id = $id;
		$this->name = get_the_title( $id );
		$this->description = apply_filters('the_content', $l->post_content );
		$this->video_url = get_post_meta( $id, 'video_url', true );
		$this->video_embed = wp_oembed_get( $this->video_url );

		$media_id = get_post_thumbnail_id( $id );
		$r = wp_get_attachment_image_src( $media_id, 'widescreen-crop' );
		$banner = empty( $r ) ? false : $r[0];

		if( $banner ) {
			$this->banner = array('img_url' => $banner, 'media_id' => $media_id );
		} else {
			$this->banner = array('img_url' => $base_url . '/wp-content/plugins/visitweho/public/assets/content-fallback.jpg', 'media_id' => false );
		}

		$this->date = get_the_time('U', $this->id );

		$this->location_ids = get_post_meta( $id, 'location_ids', true );
		$base_url = get_site_url();

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $l->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $l->post_content, 55, null );
		}

		$neighborhoods = wp_get_post_terms( $id, 'neighborhood' );

		if( is_wp_error( $neighborhoods ) || empty( $neighborhoods ) ) {
			$this->neighborhoods = false;
		} else {
			$neighborhoods = $neighborhoods;
			$this->neighborhoods = $neighborhoods;
		}


	}
}