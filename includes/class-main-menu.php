<?php

/**
 * Main Menu walker class
 *
 * @link       http://edwardr.me
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */

class VisitWeho_Main_Menu extends Walker_Nav_Menu {

		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= '</a><ul class="dropdown-nav">';
		}

		public function end_lvl( &$output, $depth = 0, $args = array() ) {
				$output .= '</ul>';
		}

		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

				$classes = array();

				$obj_id = $item->object_id;

				$post_type = get_post_type( $obj_id );

				$special_class = 'offer' == $post_type && $depth === 1 ? 'special' : '';

				if( !empty( $item->classes ) ) {
						$classes = (array) $item->classes;
				}

				$explore_link = false;

				foreach( $item->classes as $class ) {
					if( $class == 'special-blue' ) {
						$explore_link = true;
					}
				}

				$search_link = false;

				foreach( $item->classes as $class ) {
					if( $class == 'search-link' ) {
						$search_link = true;
					}
				}

				$subscribe_link = false;

				foreach( $item->classes as $class ) {
					if( $class == 'subscribe-link' ) {
						$subscribe_link = true;
					}
				}

				$active_class = '';

				if( in_array('current-menu-item', $classes) ) {
						$active_class = 'active';
				}

				$li_class = empty( $active_class ) ? $special_class : $active_class . ' ' . $special_class;

				$url = '';

				if( !empty( $item->url ) ) {
						$url = $item->url;
				}

				if( 'offer' == $post_type && $depth === 1 ) {
					$offer = new VisitWeho_Offer( $obj_id );
					$url = get_the_permalink( $offer->hotel_id );
					$output .= '<li class="' . $li_class . '"><a href="' . $url . '">';
					$thumb = wp_get_attachment_image_url( $offer->media_id, 'thumbnail' );

					if( $thumb ) {
						$output .= '<img src="' . $thumb . '" alt="' . $offer->display_name . '" />';
					}

					$output .= '<span class="label">' . $offer->display_name . ' <span>' . $offer->short_description . '</span></span>';

				} else if( $explore_link ) {
					$output .= '<li class="link"><a href="' . $url . '">' . $item->title;
				} else if( $search_link ) {
					// $output .= '
					// 	<li>
					// 		<form role="search" method="get" class="search-form" action="/">
	  		// 				<input type="search" class="search-field" placeholder="Search …" value="" name="s" title="Search for:">
	  		// 				<button type="submit"><img src="/wp-content/themes/visitweho/img/icon-search.svg" alt=""></button>
					// 		</form>
					// 	</li>';
				} else if( $subscribe_link ) {
					$output .= '
						<li>
							<a class="subscribe-btn fancybox" href="#subscribe-modal">Subscribe</a>
						</li>';
				} else {
					$output .= '<li class="' . $li_class . '"><a href="' . $url . '">' . $item->title;
				}

		}

		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
				$output .= '</a></li>';
		}

}
