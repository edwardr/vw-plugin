<?php

/**
 * Venue class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class VisitWeho_Venue {

	public $id;
	public $name;
	public $slug;
	public $latitude;
	public $longitude;
	public $description;
	public $excerpt;
	public $banner;
	public $address;
	public $city;
	public $state;
	public $zip;
	public $phone;
	public $facebook_url;
	public $twitter_url;
	public $website_url;
	public $dola_permalink;
	public $google_place_id;
	public $venue_data_type;
	public $dola_id;
	public $neighborhood;
	public $media_id;

	public function __construct( $id ) {
		$visit_weho = new VisitWeho();
		$e = get_term( $id, 'venue' );
		
		if ( is_wp_error( $e ) ) {
			throw new Exception('ID ' . $id . ' is not a venue');
		}

		$this->id = $id;
		$this->name = $e->name;
		$this->slug = $e->slug;
		$this->latitude = get_term_meta( $id, 'latitude', true );
		$this->longitude = get_term_meta( $id, 'longitude', true );
		$this->description = $e->description;
		$this->banner = '';
		$this->address = get_term_meta( $id, 'address', true );
		$this->city = get_term_meta( $id, 'city', true );
		$this->state = get_term_meta( $id, 'state', true );
		$this->zip = get_term_meta( $id, 'zip', true );

		if( has_excerpt( $this->id ) ) {
			$this->excerpt = $e->post_excerpt;
		} else {
			$this->excerpt = wp_trim_words( $e->post_content, 55, null );
		}

		if( get_term_meta( $id, 'phone', true ) ) {
			$formatted_phone = $visit_weho->format_phone_number( get_term_meta( $id, 'phone', true ), 'US' );
			$this->phone = $formatted_phone;
		}

		$this->facebook_url = get_term_meta( $id, 'facebook_url', true );
		$this->twitter_url = get_term_meta( $id, 'twitter_url', true );
		$this->website_url = get_term_meta( $id, 'website_url', true );
		$this->dola_permalink = get_term_meta( $id, 'dola_permalink', true );
		$this->dola_id = get_term_meta( $id, 'dola_id', true );
		$this->venue_data_type = get_term_meta( $id, 'venue_data_type', true );
		$this->google_place_id = get_term_meta( $id, 'google_place_id', true );

		$this->neighborhood_id = get_term_meta( $id, 'neighborhood_id', true );
		$this->media_id = get_term_meta( $id, 'media_id', true );
		
	}

}