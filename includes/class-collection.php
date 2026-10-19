<?php

/**
 * Collection class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Collection {

	public $id;
	public $name;
	public $description;
	public $excerpt;
	public $media_id;
	public $activities;
	//public $tags;

	public function __construct( $id ) {
		$l = get_post( $id );

		$type = get_post_type( $id );

		if ('collection' != $type) {
			throw new Exception('ID ' . $id . ' is not a collection');
		}

		$this->id = $id;
		$this->name = get_the_title( $id );
		$this->description = apply_filters('the_content', $l->post_content );

		$media_id = get_post_thumbnail_id( $id );
		$this->media_id = $media_id;

		$this->date = get_the_time('U', $this->id );

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $l->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $l->post_content, 55, null );
		}

		$tags = wp_get_post_terms( $id, 'activity-tag' );

		$this->activities = get_post_meta( $id, 'activities', true );

		// if( is_wp_error( $tags ) || empty( $tags ) ) {
		// 	$this->tags = false;
		// } else {
		// 	$tags = $tags;
		// 	$this->tags = $tags;
		// }


	}
}
