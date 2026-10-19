/*
* Locations Filtering functionality
* */

(function($) {

	if( !$('body').hasClass('page-template-template-indulge') && !$('body').hasClass('page-template-template-taste') ) {
		return false;
	}

	var itemSelector = '.locations-item';
	var itemsPerPage = 10;

	function fetchItems(types, neighborhoods, page, sort = 'asc' ) {
		var offset = ( parseInt(page) -1 ) * itemsPerPage;

		if( types.length < 1 ) {
			if( $('body').hasClass('page-template-template-indulge') ) {
				types = 'beauty-wellness,shopping,art';
			} else if ( $('body').hasClass('page-template-template-taste') ) {
				types = 'food-drink, nightlife';
			}
		}

		$.ajax({
			type: 'get',
			url: '/json/visitweho/locations',
			data: {
				'items':itemsPerPage,
				'types':types,
				'offset':offset,
				'order' : sort,
				'neighborhoods':neighborhoods,
			}
		})
		.done( function( response ) {
			var elOutput, output;
			$('.locations-list').empty();

			if( response.length < 1 ) {
				$('.locations-list').html('<h6 class="text-center">No locations meet that criteria</h6>');
				$('.locations-list').attr('data-total-count', 0 );
				$('.locations-list').trigger('count-change');
				setPagination();
				return false;
			} else {
				$('.isotope-pager').css('width', '100%');
				$('.isotope-pager').html('<div class="text-center"><img src="/wp-content/plugins/visitweho/public/assets/eclipse.svg" /></div>');
				// query the total for this filter
				$.ajax({
					types: 'get',
					url: '/json/visitweho/locations',
					data: {
						'items':-1,
						'types':types,
						'neighborhoods':neighborhoods,
					}
				})
				.done( function( response ) {
					$('.locations-list').attr('data-total-count', response.length );
					$('.locations-list').trigger('count-change');
					setPagination();
					$('.isotope-pager').css('width', 'auto');
				});		
			}

			$.each(response, function(i, item) {

				elOutput = '';

				elOutput += '<div class="locations-item hotels-item" data-lat="' + item.latitude + '"';
				elOutput += 'data-long="' + item.longitude + '"';
				elOutput += 'data-types=""';
				elOutput += 'data-neighborhood="'  + item.neighborhood.slug + '"';
				elOutput += 'data-name="' + item.name + '"';
				elOutput += 'data-id="' + item.id + '">';
				elOutput += '<div class="img"><a href="' + item.permalink + '">';
				elOutput += '<img src="' + item.banner['img_url'] + '" alt="' + item.name + '" />';
				elOutput += '</a></div>';
				elOutput += '<div class="content"><span class="neighborhood-name">' + item.neighborhood.name + '</span>';
				elOutput += '<h2><a href="' + item.permalink + '">' + item.name + '</a></h2>';
				elOutput += '<div class="item-content"><p><span class="phone">' + item.phone + '</span></p>';
				elOutput += '<p>' + item.address + '<br>' + item.city + ',' + item.state + ' ' + item.zip + '';
				elOutput += ' <a rel="noreferrer noopener" target="_blank" href="https://www.google.com/maps/search/?api=1&query=' + item.name + '&query_place_id=';
				elOutput += item.google_place_id + '">(Get Directions)</a>';
				elOutput += '</p></div><a href="' + item.permalink + '" class="link">Learn more</a></div></div>';

				output = $(elOutput);
				$('.locations-list').append( output );
				//$container.isotope('insert', output );
			});

		});
		
	}

	function goToPage(n, sort = 'asc') {
		currentPage = n;

		var types = (getQuery('types') != null ? getQuery('types') : '' );
		var hoods = (getQuery('neighborhoods') != null ? getQuery('neighborhoods') : '' );
		var sort  = (getQuery('sort') != null ? getQuery('sort') : '' );
		fetchItems( types, hoods, currentPage, sort );

		// $('html, body').animate({
		// 	scrollTop: locationContainer.offset().top
		// }, 500);

	}

	$(document).on("click", ".js-filter-neighborhoods", function(e) {

		e.preventDefault();
		var activeArray = [];
		$('.js-filter-neighborhoods[data-slug="'+$(this).data('slug')+'"]').toggleClass('active');
		$('.js-filter-neighborhoods.active').each(function(){
			activeArray.push($(this).data('slug'));
		});

		setQuery('neighborhoods', removeDuplicates(activeArray).join(","));
		setQuery('pg', 1 );
		goToPage(1);
	});

	$(document).on("click", ".js-filter-types", function(e) {

		e.preventDefault();
		var activeArray = [];
		$('.js-filter-types[data-slug="'+$(this).data('slug')+'"]').toggleClass('active');
		$('.js-filter-types.active').each(function(){
			activeArray.push($(this).data('slug'));
		});

		setQuery('types', removeDuplicates(activeArray).join(","));
		setQuery('pg', 1 );
		goToPage(1);
	});

	$(document).on("click", ".js-clear-filters", function(e){
		e.preventDefault();
		$(".js-filter-neighborhoods.active").removeClass("active");
		$(".js-filter-types.active").removeClass("active");
		$(".js-filter-count").text("");
		setQuery('neighborhoods', "");
		setQuery('types', "");
		setQuery('sort', "");
		setQuery('pg', 1 );
		goToPage(1);
	});

	$(document).on("click", ".js-sort-alpha-asc", function (e) {
		e.preventDefault();
		setQuery('sort', 'asc');
		$(".js-sort-label").text($(this).text());
		setQuery('pg', 1 );
		goToPage(1, 'asc');
	});

	$(document).on("click", ".js-sort-alpha-desc", function (e) {
		e.preventDefault();
		setQuery('sort', 'desc');
		$(".js-sort-label").text($(this).text());
		setQuery('pg', 1 );
		goToPage(1, 'desc');
	});

	$('.locations-list').on('count-change', function(){
		$('.js-filter-count').text( $(this).attr('data-total-count') );
	});

	function setPagination() {
		var SettingsPagesOnItems = function(){
			var itemsLength = $('.locations-list').attr('data-total-count');

			var pages = Math.ceil(itemsLength / itemsPerPage);
			var item = 1;
			var page = 1;

			currentNumberPages = pages;

			console.log(currentNumberPages);

		}();

		var $isotopePager = $('.isotope-pager');

		$isotopePager.html('');

		for( var i = 0; i < currentNumberPages; i++ ) {
			var $pager = $('<a href="javascript:void(0);" class="pager" data-page="'+(i+1)+'"></a>');
			$pager.html(i+1);
			if(currentPage == i+1){
				$pager.addClass('active');
			}
			$pager.data('page', i+1);

			$pager.appendTo($isotopePager);
		}
	}

	$(document).on('click', '.pager', function(e) {
		var page = $(this).attr('data-page');
		setQuery('pg', $(this).data('page'));
		goToPage(page);
		$(".pager.active").removeClass('active');
		$(this).addClass('active');
		$('html, body').animate({
			scrollTop: $('.locations-list').offset().top
		}, 500);
	});

  /*
   * Google Maps Func
   * */

  var map;
  var markers = [];
  var bounds = new google.maps.LatLngBounds();
  var icon = window.themeUrl + '/img/icon-map-pin.svg';
  var iconActive = window.themeUrl + '/img/icon-map-pin-active.svg';
  var styles = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#8f8f8f"}]},{"featureType":"poi.government","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.place_of_worship","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.school","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway.controlled_access","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit.station","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#b4d4e1"},{"visibility":"on"}]}];

  $(document).on('click', ".js-toggle-map-view", function(e){
    e.preventDefault();
    var clonedContainer = $('.map-view-wrap').clone(true).addClass('cloned');
    $('#map-view-fixed').append(clonedContainer).toggleClass("active");
    $('.map-view-wrap').not('.cloned').find('.locations-list').remove();
    $(".js-view-label").text('Map View').prev().attr('src', '/wp-content/themes/visitweho/img/icon-map.svg');

    setQuery('view','map');

    window.setTimeout(function(){
      initMapView();
    }, 500);
  });


  if(getQuery('view')=="map"){
    $(".js-toggle-map-view").click();
  }

  $(document).on('click', '.js-close-map-view', function(e){
    e.preventDefault();
    if($('#map-view-fixed').hasClass('active')){
      $(".js-view-label").text('List View').prev().attr('src', '/wp-content/themes/visitweho/img/icon-list-hotel.svg');
      var cloned = $('#map-view-fixed .locations-list').clone(true);
      $('#map-view-fixed').removeClass('active').html('');
      $('.map-view-wrap').find(".locations-list").remove();
      $('.map-view-wrap .filter-list').after(cloned);
      setQuery('view','');
    }

    return true;
  });

  function initMapView() {

    var locations = [];

    $('.locations-item').each(function(el, i){
      var arr = [];
      arr.push($(this).data('lat'));
      arr.push($(this).data('long'));
      arr.push($(this).data('id'));
      locations.push(arr);
    });


    map = new google.maps.Map($("#map-view-fixed .js-map-view")[0], {
      zoom: 10,
      center: new google.maps.LatLng(locations[0][0], locations[0][1]),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var num_markers = locations.length;
    for (var i = 0; i < num_markers; i++) {
      markers[i] = new google.maps.Marker({
        position: {lat: locations[i][0], lng: locations[i][1]},
        map: map,
        html: locations[i][0],
        id: i,
        hotelId: locations[i][2],
        icon: icon
      });

      bounds.extend(markers[i].position);

      google.maps.event.addListener(markers[i], 'click', function(){


      //clear previous active icon
      markers.forEach(function(element, index, array){
        element.setIcon(icon);
      });

      var id = markers[this.id].hotelId;
      var currentEl =$("#map-view-fixed .locations-item[data-id='"+id+"']");
      var elOffset = currentEl.get(0).offsetTop;
      $("#map-view-fixed .locations-list").animate({
        scrollTop: elOffset - 82 + 'px'
      });
      google.maps.event.trigger(map, 'resize');
      markers[this.id].setIcon(iconActive);
      currentEl.addClass("active");
      window.setTimeout(function(){
        currentEl.removeClass("active");
      }, 1000);
    });
   }

    map.fitBounds(bounds);
    map.setOptions({styles: styles});
  }

})(jQuery);