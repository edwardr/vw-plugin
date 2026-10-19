<?php

/**
 * Home-slide class
 *
 * @link       https://visitwesthollywood.com
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */

 class VisitWeho_Home_Slide {
 	public $id;
 	public $title;
	public $cta;
 	public $type;
 	public $featured_media;
 	public $permalink;
 	public $date;
 	public $modified;

 	public function __construct( $id ) {
 		$l = get_post( $id );

 		$post_type = get_post_type( $id );

 		if ('home-slide' != $post_type) {
 			throw new Exception('ID ' . $id . ' is not a home slide');
 		}

 		$base_url = get_site_url();

 		$this->id = $id;
 		$this->title = get_the_title( $id );
		$url = get_post_meta( $id, 'slide_url', true );
		$cta = get_post_meta( $id, 'slide_cta', true );
		$type = get_post_meta( $id, 'slide_type', true );
 		$this->permalink = $url;
		$this->cta = $cta;
		$this->type = $type;
 		$this->date = get_the_time('U', $this->id );
 		$this->modified = get_the_modified_time('U', $this->id );

		$media_id = get_post_thumbnail_id($id);
		$meta = wp_get_attachment_metadata( $media_id );
		$meta['media_id'] = $media_id;
		$meta['file'] = $base_url . '/wp-content/uploads/' . $meta['file'];
		$base = basename( $meta['file'] );
		$path = str_replace( $base, '', $meta['file'] );

		foreach( $meta['sizes'] as $k => $v ) {
			$meta['sizes'][$k]['file'] = $path . $meta['sizes'][$k]['file'];
		}

		$this->featured_media = $meta;

 	}

 }
