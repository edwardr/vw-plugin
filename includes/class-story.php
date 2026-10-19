<?php

/**
 * Story/blog post class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Story {

	public $id;
	public $title;
	public $content;
	public $excerpt;
	public $subtitle;
	public $banner;
	public $permalink;
	public $neighborhoods;
	public $categories;
	public $tags;
	public $date;
	public $modified;
	public $reading_time;
	public $word_count;

	public function __construct( $id ) {
		$l = get_post( $id );

		$type = get_post_type( $id );

		if ('post' != $type) {
			throw new Exception('ID ' . $id . ' is not a story');
		}

		$base_url = get_site_url();

		$visit_weho = new VisitWeho();

		$media_id = get_post_thumbnail_id( $id );
		$r = wp_get_attachment_image_src( $media_id, 'hero' );
		$banner = empty( $r ) ? false : $r[0];

		if( $banner ) {
			$this->banner = array('img_url' => $banner, 'media_id' => $media_id );
		} else {
			$this->banner = array('img_url' => $base_url . '/wp-content/plugins/visitweho/public/assets/content-fallback.jpg', 'media_id' => false );
		}

		$this->id = $id;
		$this->title = get_the_title( $id );
		$this->content = apply_filters('the_content', $l->post_content );

		$word_count = str_word_count( $l->post_content );
		$this->word_count = $word_count;

		$base_reading_time = $word_count / 265 * 60;
		$imgs = explode( '<img', $l->post_content );
		$img_count = count($imgs);

		$instas = explode( 'instagram.com', $l->post_content );
		$insta_count = count($instas);

		$extra_time = $img_count * 12;
		$insta_time = $insta_count * 12;

		$extra_time = $extra_time + $insta_time;

		$total_reading_time = $base_reading_time + $extra_time;
		$total_reading_time = $total_reading_time / 60;
		$total_reading_time = round( $total_reading_time );

		$this->reading_time = $total_reading_time;

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $l->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $l->post_content, 55, null );
		}

		$this->subtitle = get_post_meta( $id, 'subtitle', true );

		$neighborhoods = wp_get_post_terms( $id, 'neighborhood' );

		if( is_wp_error( $neighborhoods ) || empty( $neighborhoods ) ) {
			$this->neighborhoods = false;
		} else {
			$neighborhoods = $neighborhoods;
			$this->neighborhoods = $neighborhoods;
		}

		$categories = wp_get_post_terms( $id, 'category' );

		if( is_wp_error( $categories ) || empty( $categories ) ) {
			$this->categories = false;
		} else {
			$this->categories = $categories;
		}

		$tags = wp_get_post_terms( $id, 'post_tag' );

		if( is_wp_error( $tags ) || empty( $tags ) ) {
			$this->tags = false;
		} else {
			$this->tags = $tags;
		}

		$this->permalink = get_the_permalink( $this->id );

		$this->date = get_the_time('U', $this->id );
		$this->modified = get_the_modified_time('U', $this->id );

	}

	public function get_related_stories($id) {

		$neighborhood_ids = array();

		if( $this->neighborhoods ) {
			foreach( $this->neighborhoods as $neighborhood ) {
				$neighborhood_ids[] = $neighborhood->term_id;
			}			
		}

		$category_ids = array();

		if( $this->categories ) {
			foreach( $this->categories as $category ) {
				$category_ids[] = $category->term_id;
			}			
		}

		$tag_ids = array();

		if( $this->tags ) {
			foreach( $this->tags as $tag ) {
				$tag_ids[] = $tag->term_id;
			}			
		}

		$manual_related_stories = get_post_meta( $id, 'related_stories', true );
		$manual_story_count = 0;

		$related_stories_arr = array();

		if( $manual_related_stories ) {

			foreach( $manual_related_stories as $story ) {
				$related_stories_arr[] = $story;
			}

			$manual_story_count = count( $manual_related_stories );

			if( $manual_story_count < 3 ) {

				$number_to_query = 3 - (int)$manual_story_count;

				$args = array(
					'post_type' => 'post',
					'post__not_in' => array( $id ),
					'posts_per_page' => $number_to_query,
					'orderby' => 'rand',
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'post_tag',
							'field'    => 'term_id',
							'terms'    => $tag_ids,
						),
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => $category_ids,
						),
					),
				);

				$related_stories = get_posts( $args );

				foreach( $related_stories as $story ) {
					$related_stories_arr[] = $story->ID;
				}
			}

		} else {
			// query for related stories

			$args = array(
				'post_type' => 'post',
				'post__not_in' => array( $id ),
				'posts_per_page' => 3,
				'orderby' => 'rand',
				'tax_query' => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => $tag_ids,
					),
					array(
						'taxonomy' => 'neighborhood',
						'field'    => 'term_id',
						'terms'    => $neighborhood_ids,
					),
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $category_ids,
					),
				),
			);

			$related_stories = get_posts( $args );

			foreach( $related_stories as $story ) {
				$related_stories_arr[] = $story->ID;
			}

		}

		return $related_stories_arr;
	}
}