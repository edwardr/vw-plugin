
/*
* Event Filtering functionality
* */

(function($) {

	if( !$('body').hasClass('single-event') && !$('body').hasClass('page-template-template-events') ) {
		return false;
	}

	var weekDays = getWeekdays();
	var monthNames = getMonthNames();

	var itemSelector = '.filter-item';

	var $container = $('.events-grid').isotope({
		itemSelector: itemSelector,
		layoutMode: 'fitRows',
		masonry: {
			columnWidth: itemSelector,
			isFitWidth: true
		}
	});

	var $container = $('.events-grid');

	var responsiveIsotope = [
		[480, 4],
		[720, 6]
	];

	var itemsPerPageDefault = 9;
	var itemsPerPage = defineItemsPerPage();
	var currentNumberPages = 1;
	var currentPage = (getQuery('pg') != null ? getQuery('pg') : 1 );
	var currentFilter = (getQuery('type') != null ? getQuery('type') : '' );
	var filterAtribute = 'data-filter';
	var pageAtribute = 'data-page';
	var pagerClass = 'isotope-pager';
	
	function fetchItems(filter, page, dateRange = '' ) {

		var offset = ( parseInt(page) -1 ) * itemsPerPage;

		$.ajax({
			type: 'get',
			url: '/json/visitweho/events',
			data: {
				'items':itemsPerPage,
				'categories':filter,
				'offset':offset,
				'date_between': dateRange,
			}
		})
		.done( function( response ) {
			var elOutput, output;
			$('.events-grid').empty();

			if( response.length < 1 ) {
				$('.events-grid').html('<h6 class="text-center">No events meet that criteria</h6>');
				$('.events-grid').attr('data-total-count', 0 );
				setPagination();
				return false;
			} else {
				$('.' + pagerClass + '').css('width', '100%');
				$('.' + pagerClass + '').html('<div class="text-center"><img src="/wp-content/plugins/visitweho/public/assets/eclipse.svg" /></div>');
				// query the total for this filter
				$.ajax({
					type: 'get',
					url: '/json/visitweho/events',
					data: {
						'items':-1,
						'categories':filter,
						'date_between': dateRange,
					}
				})
				.done( function( response ) {
					$('.events-grid').attr('data-total-count', response.length );
					setPagination();
					$('.' + pagerClass + '').css('width', 'auto');
				});		
			}

			$.each(response, function(i, item) {
				var date, dayName, day, month, year, hour, minutes, ampm;

				date = new Date( parseInt(item.begin_time)*1000 );
				dayName = weekDays[date.getDay()];
				month = monthNames[date.getMonth()];
				day = date.getDate();
				year = date.getFullYear();
				hour = date.getHours();
				minutes = date.getMinutes();

				if( minutes < 10 ) {
					minutes = '0' + minutes;
				}

				ampm = hour < 12 ? 'AM' : 'PM';
				hour = (hour % 12) || 12;

				var dateStr = dayName + ', ' + month + ' ' + day + ', ' + year + ' at ' + hour + ':' + minutes + ampm;

				elOutput = '';
				elOutput += '<div class="filter-item" data-filter=""><div class="boxed-item">';
				elOutput += '<a class="img" href="' + item.permalink + '"';
				elOutput += ' style="background-image: url(' + item.banner['img_url'] + ');"></a>';
				elOutput += '<div class="text"><h3><a href="' + item.permalink + '">' + item.name + '</a></h3>';
				elOutput += '<p class="event-details"><span class="venue-name">' + item.venue.name + '</span><br>';
				elOutput += '<span class="event-date-time">' + dateStr + '</span></p>';
				elOutput += '<a href="' + item.permalink + '" class="link color-pink">Details</a>';
				elOutput += '</div></div></div>';
				output = $(elOutput);
				$container.isotope('insert', output );
				equalheight('.js-equal > *');
			});

		});
		
	}


	function goToPage(n) {
		currentPage = n;

		var selector = itemSelector;
		selector += ( currentFilter != '' ) ? '['+filterAtribute+'="'+currentFilter+'"]' : '';
		selector += '['+pageAtribute+'="'+currentPage+'"]';

		var dateRange = getQuery('date-range');

		fetchItems( currentFilter, currentPage, dateRange );

	}

	function defineItemsPerPage() {
		var pages = itemsPerPageDefault;

		for( var i = 0; i < responsiveIsotope.length; i++ ) {
			if( $(window).width() <= responsiveIsotope[i][0] ) {
				pages = responsiveIsotope[i][1];
				break;
			}

		}

		return pages;
	}

	function setPagination() {

		var SettingsPagesOnItems = function(){

			var itemsLength = $('.events-grid').attr('data-total-count');

			var pages = Math.ceil(itemsLength / itemsPerPage);
			var item = 1;
			var page = 1;
			var selector = itemSelector;
			selector += ( currentFilter != '' ) ? '['+filterAtribute+'="'+currentFilter+'"]' : '';

			currentNumberPages = pages;

		}();

		var $isotopePager = $('.' + pagerClass + '');

		$isotopePager.html('');

		for( var i = 0; i < currentNumberPages; i++ ) {
			var $pager = $('<a href="javascript:void(0);" class="pager" '+pageAtribute+'="'+(i+1)+'"></a>');
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
			scrollTop: $( '#events-filter-container' ).offset().top
		}, 500);
	});


	$('.event-categories a').click(function(e) {
		var startRange, endRange, dateRange, filter;
		e.preventDefault();
		filter = $(this).attr(filterAtribute);
		$(".event-categories .active").removeClass('active');
		$(this).addClass('active');
		currentFilter = filter;

		startRange = $('.start-range').val();
		endRange = $('.end-range').val();

		if( startRange && endRange ) {
			startRange = new Date(startRange);
			startRange = startRange.getMonth() + 1 + '/' +  startRange.getDate() + '/' +  startRange.getFullYear();
			endRange = new Date(endRange);
			endRange = endRange.getMonth() + 1 + '/' +  endRange.getDate() + '/' +  endRange.getFullYear();
			dateRange = startRange + ',' + endRange;
		}

		setQuery('type', $(this).data('filter'));

		if( dateRange ) {
			setQuery('date-range', dateRange );
		}

		setQuery('pg', 1);

		setPagination();
		goToPage(1);

	});

	$('.event-dates input.input-group-field').on('change', function(e) {
		if( $(this).hasClass('is-invalid-input') ) {
			$(this).removeClass('is-invalid-input');
		}

	});

	$('.event-dates .event-button').on('click', function(e) {
		var startRange, endRange, dateRange;
		e.preventDefault();

		startRange = $('.start-range').val();
		endRange = $('.end-range').val();

		if( startRange.length < 1 ) {
			$('.start-range').addClass('is-invalid-input');
			return false;
		}

		if( endRange.length < 1 ) {
			$('.end-range').addClass('is-invalid-input');
			return false;
		}

		if( startRange && endRange ) {
			startRange = new Date(startRange);
			startRange = startRange.getMonth() + 1 + '/' +  startRange.getDate() + '/' +  startRange.getFullYear();
			endRange = new Date(endRange);
			endRange = endRange.getMonth() + 1 + '/' +  endRange.getDate() + '/' +  endRange.getFullYear();
			dateRange = startRange + ',' + endRange;
		}

		if( dateRange ) {
			setQuery('date-range', dateRange );
		}

		setQuery('pg', 1);

		setPagination();
		goToPage(1);

		$('.js-clear-dates').addClass('active');

	});

	$('.js-clear-dates').on('click', function(e) {
		e.preventDefault();
		$('.event-date-wrap input').val('');
		setQuery('date-range', '' );
		setPagination();
		goToPage(1);
		$('.js-clear-dates').removeClass('active');
	});

	$(window).resize(function(){
		itemsPerPage = defineItemsPerPage();
		setPagination();
	});

})(jQuery);