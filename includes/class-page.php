<?php

/**
 * Generic page class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */

 class VisitWeho_Page {
 	public $id;
 	public $title;
 	public $content;
 	public $excerpt;
 	public $subtitle;
 	public $featured_media;
 	public $permalink;
 	public $date;
 	public $modified;
	public $fields;

 	public function __construct( $id ) {
 		$l = get_post( $id );

 		$type = get_post_type( $id );

 		if ('page' != $type) {
 			throw new Exception('ID ' . $id . ' is not a page');
 		}

 		$base_url = get_site_url();

 		$this->id = $id;
 		$this->title = get_the_title( $id );
 		$this->content = apply_filters('the_content', $l->post_content );

 		if( has_excerpt( $this->id ) ) {
 			$this->excerpt = $l->post_excerpt;
 		} else {
 			$this->excerpt = wp_trim_words( $l->post_content, 55, null );
 		}

 		$this->permalink = get_the_permalink( $this->id );
 		$this->date = get_the_time('U', $this->id );
 		$this->modified = get_the_modified_time('U', $this->id );

		$exclude_fields = array(
			'subtitle',
		);

		$page_meta = get_post_meta( $id );

		foreach( $page_meta as $k => $v ) {
			// Exclude hidden fields
			if( strpos( $k, '_' ) === 0 ) {
				unset( $page_meta[$k] );
			}
		}

		$this->fields = $page_meta;

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
