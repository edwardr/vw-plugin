(function( $ ) {
	'use strict';

	var dateHelper = [
		'Jan',
		'Feb',
		'Mar',
		'Apr',
		'May',
		'Jun',
		'Jul',
		'Aug',
		'Sep',
		'Oct',
		'Nov',
		'Dec'
	];

	var facebookSVG = document.getElementById('facebook-svg');
	var instagramSVG = document.getElementById('instagram-svg');
	var	locationSVG = document.getElementById('location-svg');

	var crImg,
			crClose,
			crLightbox,
			crLightboxSlide,
			crLightboxImg,
			crLightboxName,
			crLightboxDate,
			crLightboxSource,
			crLightboxLocation,
			crLightboxDesc,
			crLightboxHashtags,
			crLightboxActiveIndex,
			crLightboxMaxItems,
			crLightBoxRightBtn,
			crLightBoxLeftBtn,
			crGalleryListTrack,
			crInnerSlideTrack,
			crSlideMoving,
			crSlider;

	crSlideMoving = false;

	crGalleryListTrack = document.querySelector('.crowdriff-gallery');

	crLightbox = document.querySelector('.crowdriff-lightbox');
	crLightboxSlide = document.querySelector('.crowdriff-lightBox__slider');
	crLightboxImg = document.querySelector('.crowdriff-lightbox-image');

	crLightboxName = document.querySelector('.crowdriff-lightbox__slider__info__name');
	crLightboxDate = document.querySelector('.crowdriff-lightbox__slider__info__date');
	crLightboxSource = document.querySelector('.crowdriff-lightbox__slider__info__source');
	crLightboxLocation = document.querySelector('.crowdriff-lightbox__slider__info__location');
	crLightboxDesc = document.querySelector('.crowdriff-lightbox__slider__info__desc');
	crLightboxHashtags = document.querySelector('.crowdriff-lightbox__slider__info__hashtags');

	crSlider = document.querySelector('#crowdriff-slider');

	if( crSlider ) {
		crLightboxActiveIndex = crSlider.dataset.activeSlide;
		crLightboxMaxItems = crSlider.dataset.maxItems;
	}

	crLightBoxRightBtn = document.querySelector('.crowdriff-lightbox__nextPrev--next');
	crLightBoxLeftBtn = document.querySelector('.crowdriff-lightbox__nextPrev--prev');

	crInnerSlideTrack = document.querySelector('.vw-bricks-slides__inner');

	crImg = document.querySelectorAll('.crowdriff-gallery img');
	crClose = document.querySelector('.crowdriff-lightbox__close');

	function Swipe(elem, callback) {
		var self = this;
		this.callback = callback;

		function handleEvent(e) {
			self.touchHandler(e);
		}

		elem.addEventListener('touchstart', handleEvent, false);
		elem.addEventListener('touchmove', handleEvent, false);
		elem.addEventListener('touchend', handleEvent, false);
	}
	Swipe.prototype.touches = {
		"touchstart": {"x":-1, "y":-1},
		"touchmove" : {"x":-1, "y":-1},
		"touchend"  : false,
		"direction" : "undetermined"
	};
	Swipe.prototype.touchHandler = function (event) {
		var touch;
		if (typeof event !== 'undefined'){
			if (typeof event.touches !== 'undefined') {
				touch = event.touches[0];
				switch (event.type) {
					case 'touchstart':
					case 'touchmove':
						this.touches[event.type].x = touch.pageX;
						this.touches[event.type].y = touch.pageY;
						break;
					case 'touchend':
						this.touches[event.type] = true;
						var x = (this.touches.touchstart.x - this.touches.touchmove.x),
							y = (this.touches.touchstart.y - this.touches.touchmove.y);
						if (x < 0) x /= -1;
						if (y < 0) y /= -1;
						if (x > y)
							this.touches.direction = this.touches.touchstart.x < this.touches.touchmove.x ? "right" : "left";
						else
							this.touches.direction = this.touches.touchstart.y < this.touches.touchmove.y ? "down" : "up";
						this.callback(event, this.touches.direction);
						break;
				}
			}
		}
	};

	if( crSlider ) {
		new Swipe(crSlider, function(event, direction) {
			event.preventDefault();
			switch (direction) {
				case "up":
					// Handle Swipe Up
					break;
				case "down":
					// Handle Swipe Down
					break;
				case "left":
					crLightBoxRightBtn.click();
					break;
				case "right":
					crLightBoxLeftBtn.click();
					break;
			}
		});
	}

	var transitionCrowdRiffSlideshow = function(event) {
		var index = crSlider.dataset.activeSlide;
		var direction = this.dataset.direction;

		var slide = document.querySelector('.crowdriff-lightbox__slider__wrapper[data-index="' + crLightboxActiveIndex +'"');
		
		slide.classList.remove('active');

		var checkIndex = crLightboxActiveIndex;

		if( direction == 'right') {
			checkIndex++
			if( parseInt(checkIndex) >= parseInt(crLightboxMaxItems) ) {
				crLightboxActiveIndex = 0;
			} else {
				crLightboxActiveIndex++;
			}
		} else {
			checkIndex--;
			var lastIndex = crLightboxMaxItems;
			lastIndex--;
			if( parseInt(checkIndex) < 0 ) {
				crLightboxActiveIndex = lastIndex;
			} else {
				crLightboxActiveIndex--;
			}
		}

		slide = document.querySelector('.crowdriff-lightbox__slider__wrapper[data-index="' + crLightboxActiveIndex +'"');
		slide.classList.add('active');

		crSlider.dataset.activeSlide = crLightboxActiveIndex;
		var at = parseInt(crLightboxActiveIndex) * 100;
		crSlider.style.webkitTransform = 'translate(-' + at + 'vw, 0)';
	}

	var triggerCrowdRiffSlideshow = function(event) {

		if( crSlideMoving == true ) {
			return false;
		}

		document.querySelector('body').classList.add('crowdriff-lightbox-active');
		var index = this.dataset.index;

		crLightboxActiveIndex = index;

		crSlider.dataset.activeSlide = index;
		var at = parseInt(index) * 100;
		crSlider.style.webkitTransition = 'none';
		crSlider.style.mozTransition = 'none';
		crSlider.style.transition = 'none';
		crSlider.style.webkitTransform = 'translate(-' + at + 'vw, 0)';

		crLightbox.classList.add('is-active');
		var slide = document.querySelector('.crowdriff-lightbox__slider__wrapper[data-index="' + index +'"');
		slide.classList.add('active');

		setTimeout( function(e) {
			crSlider.style.webkitTransition = '';
			crSlider.style.mozTransition = '';
			crSlider.style.transition = '';
		}, 800 );
	}

	var dragScroll = function(event) {
		var x = event.screenX;

		var max_distance;

		var dragScrollMove = function(event) {

			crSlideMoving = true;

			var move,
					left,
					maxDistance,
					totalDistance,
					scrollDistance;

			maxDistance = crInnerSlideTrack.offsetWidth - screen.width + 50;
			left = crInnerSlideTrack.dataset.left;
			move = event.screenX;

			if( move > x ) {
				scrollDistance = 20;
			} else {
				scrollDistance = -20;
			}

			totalDistance = parseInt(left) + parseInt(scrollDistance);

			if( Math.abs(totalDistance) >= maxDistance ) {
				totalDistance = -maxDistance;
			}

			if( totalDistance > 0 ) {
				totalDistance = 0;
			}

			crInnerSlideTrack.dataset.left = totalDistance;
			crGalleryListTrack.width;

			crInnerSlideTrack.style.webkitTransform = 'translateX(' + totalDistance + 'px)';

		}

		var dragScrollEnd = function(event) {
		  document.removeEventListener('mousemove', dragScrollMove);

		  setTimeout( function(e) {
		  	crSlideMoving = false;
		  }, 300 );
		}

		document.addEventListener('mousemove', dragScrollMove);
		document.addEventListener('mouseup', dragScrollEnd);
	}

	var wheelScroll = function(event) {
		// var x = event.screenX;

		// var max_distance;

		// console.log(event);

		// var dragScrollMove = function(event) {
		// 	var move,
		// 			distance;
		// 	move = event.screenX;
		// 	if( move > x ) {
		// 		distance = 20;
		// 	} else {
		// 		distance = -20;
		// 	}

		// 	var left = crInnerSlideTrack.dataset.left;

		// 	distance = parseInt(left) + parseInt(distance);

		// 	// console.log('instance');
		// 	// console.log(crInnerSlideTrack.offsetWidth);
		// 	// console.log(distance);
		// 	// console.log(screen.width);
		// 	// console.log(Math.abs(distance) + Math.abs(crInnerSlideTrack.dataset.left) - crInnerSlideTrack.offsetWidth);

		// 	if( Math.abs(distance) + Math.abs(crInnerSlideTrack.dataset.left) - crInnerSlideTrack.offsetWidth > (screen.width + 20) ) {
		// 		return false;
		// 	}

		// 	crInnerSlideTrack.dataset.left = distance;
		// 	crGalleryListTrack.width;

		// 	crInnerSlideTrack.style.webkitTransform = 'translateX(' + distance + 'px)';			


		// 	//console.log(move);
		// 	//move = Math.easeInOutQuad(currentTime, start, change, duration);
		// 	// var scroll = crInnerSlideTrack.scrollLeft;
		// 	// var max = scroll + move;
		// 	// crInnerSlideTrack.scrollLeft = scroll + move;
		// 	// 			console.log(crInnerSlideTrack.scrollLeft);

		// }

		// var dragScrollEnd = function(event) {
		// 	//console.log('scroll ends');
		//   document.removeEventListener('mousemove', dragScrollMove);	
		// }

		// document.addEventListener('mousemove', dragScrollMove);
		// document.addEventListener('mouseup', dragScrollEnd);
	}

	if( crInnerSlideTrack ) {
		crInnerSlideTrack.addEventListener('mousedown', dragScroll);
		crInnerSlideTrack.addEventListener('mousewheel', wheelScroll);
	}

	for (var i = 0; i < crImg.length; i++) {
		//crImg[i].addEventListener('mousedown', dragScroll);
		crImg[i].ondragstart = function() { return false; }
	}

	var triggerCrowdRiffClose = function(event) {
		document.querySelector('body').classList.remove('crowdriff-lightbox-active');
		document.querySelector('.crowdriff-lightbox__slider__wrapper.active').classList.remove('active');
		crLightbox.classList.remove('is-active');
		// var slide = document.querySelector('.crowdriff-lightbox__slider__wrapper[data-index="' + index +'"');
		// slide.classList.remove('active');

		//crLightboxImg.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
	}

	for (var i = 0; i < crImg.length; i++) {
		crImg[i].addEventListener('click', triggerCrowdRiffSlideshow);
	}

	if( crClose ) {
		crClose.addEventListener('click', triggerCrowdRiffClose);
	}

	if( crLightBoxRightBtn ) {
		crLightBoxRightBtn.addEventListener('click', transitionCrowdRiffSlideshow);
	}

	if( crLightBoxLeftBtn ) {
		crLightBoxLeftBtn.addEventListener('click', transitionCrowdRiffSlideshow);
	}

	// Cookie consent init
	window.addEventListener("load", function(){
	window.cookieconsent.initialise({
	  "palette": {
	    "popup": {
	      "background": "#000"
	    },
	    "button": {
	      "background": "#244879",
	      "text": "#ffffff"
	    }
	  },
	  "content": {
	    "href": "https://www.visitwesthollywood.com/privacy-policy"
	  },
	  "position": "bottom-right",
	})});

	$(document).ready( function(e) {

		$('.check-rates-link').on('click', function(e) {
			e.preventDefault();

			var aresID, page, hotelID, urlBase, url, promo, hotelName;

			hotelName = $(this).parent().parent().parent().attr('data-hotel-name');

			aresID = 10337;

			promo = $(this).attr('data-promo');

			if( !promo ) {
				promo = '';
			}

			hotelID = $(this).parent().parent().parent().attr('data-ares-id');

			if( hotelName == 'alta-cienega-motel' || hotelName == 'holloway-motel' ) {
				if( hotelName == 'alta-cienega-motel' ) {
					urlBase = 'http://www.booking.com/hotel/us/alta-cienega-motel.html?ref=VisitWestHollywood';
				} else {
					urlBase = 'https://www.hollowaymotel.com/rooms?ref=VisitWestHollywood';
				}
			} else {
				urlBase = 'https://reservations.visitwesthollywood.com/hotel/single/' + aresID + '/' + hotelID;

				// urlBase += '?Search[checkInDate]=' + startDate;
				// urlBase += '&Search[checkOutDate]=' + endDate;
				// urlBase += '?Search[numberOfRooms]=1';
				// urlBase += '?Search[numberOfAdults]=1';
				// urlBase += '?Search[numberOfChildren]=0';
			}

			console.log(urlBase);

			url = encodeURI( urlBase );
			window.open(url);	
		});

		$('.book-room-wrap .booking-button').on('click', function(e) {
			e.preventDefault();

			var aresID, page, hotelID, startDate, endDate,
			adults, children, urlBase, url, promo, hotelName;

			hotelName = $(this).parent().parent().attr('data-hotel-name');

			startDate = $('.booking-date.check-in').val();
			endDate = $('.booking-date.check-out').val();

			startDate = Date.parse(startDate);
			endDate = Date.parse(endDate);

			startDate = new Date(startDate);
			endDate = new Date(endDate);

			startDate = startDate.getMonth()+1 + '/' + startDate.getDate() + '/' + startDate.getFullYear();
			endDate = endDate.getMonth()+1 + '/' + endDate.getDate() + '/' + endDate.getFullYear();

			aresID = 10337;

			promo = $(this).attr('data-promo');

			if( !promo ) {
				promo = '';
			}

			hotelID = $(this).attr('data-ares-id');

			if( hotelID == 'all' ) {
				page = 'main';
			} else {
				page = 'single';
			}

			if( page == 'main' ) {
				urlBase = 'https://reservations.visitwesthollywood.com/hotel/list/' + aresID;
				urlBase += '?Search[hotelRegion]=m3454';
				urlBase += '&Search[checkInDate]=' + startDate;
				urlBase += '&Search[checkOutDate]=' + endDate;
				urlBase += '&Search[numberOfRooms]=1';
				urlBase += '&Search[numberOfAdults]=1';
				urlBase += '&Search[numberOfChildren]=0';
				url = encodeURI( urlBase );
				window.open(url);
			} else if( page == 'single' ) {
				if( hotelName == 'alta-cienega-motel' || hotelName == 'holloway-motel' ) {
					if( hotelName == 'alta-cienega-motel' ) {
						urlBase = 'http://www.booking.com/hotel/us/alta-cienega-motel.html?ref=VisitWestHollywood';
					} else {
						urlBase = 'https://www.hollowaymotel.com/rooms?ref=VisitWestHollywood';
					}
				} else {
					urlBase = 'https://reservations.visitwesthollywood.com/hotel/single/' + aresID + '/' + hotelID;

					urlBase += '?Search[checkInDate]=' + startDate;
					urlBase += '&Search[checkOutDate]=' + endDate;
					urlBase += '&Search[numberOfRooms]=1';
					urlBase += '&Search[numberOfAdults]=1';
					urlBase += '&Search[numberOfChildren]=0';
				}

				url = encodeURI( urlBase );
				window.open(url);	
			}

		});


		var wideImage = $(document).find('.superwide');

		if( wideImage.length >= 1 ) {
			var parent = wideImage.parent();
			if( parent.hasClass('wp-caption') ) {
				parent.addClass('superwide-margin');
			} else {
				//alert();
				wideImage.addClass('superwide-margin');
			}
		}

		var changeSlide = function(index) {
			var scrollEl;
			$('.cw-slide').addClass('cw-hide');
			$('.cw-slide[data-index="' + index + '"]').removeClass('cw-hide');

			// if( index == 0 ) {
			// 	scrollEl = $('.cw-slide-overview');
			// } else {
			// 	scrollEl = $('.cw-content-slides');
			// }
			
			scrollEl = $('.cw-content-slides');

			$('html,body').animate({
				scrollTop: scrollEl.offset().top - 50
			}, 600 );
		}

		$('.cw-slide-nav').on('click', function(e) {
			var index, state;
			index = $(this).attr('data-open');

			if( index == 0 ) {
				index = 1;
			}
			
			state = { 'slide': index };
			history.pushState(state, '', '?cw-slide=' + index );

			if( index == 1 ) {
				$('.prev-slide').addClass('hide');
			} else {
				$('.prev-slide').removeClass('hide');
			}

			changeSlide(index);

			// if( cwSlideshow.options.force_reload == true ) {
			// 	window.location.href = window.location.href;
			// } else {
			// 	changeSlide(index);
			// }

		});

		// $('.cw-begin-slideshow').on('click', function(e) {
		// 	var index, state;
		// 	index = $(this).attr('data-open');
		// 	state = { 'slide': index };
		// 	history.pushState(state, '', '?cw-slide=' + index );

		// 	$(this).addClass('cw-hide');

		// 	if( index == 1 ) {
		// 		$('.prev-slide').addClass('hide');
		// 	} else {
		// 		$('.prev-slide').removeClass('hide');
		// 	}

			//$('.cw-slide-overview').addClass('cw-hide');

			// if( cwSlideshow.options.force_reload == true ) {
			// 	window.location.href = window.location.href;
			// } else {
			// 	changeSlide(index);
			// }
			
		// 	changeSlide(index);
		// });

	});

	$(window).ready( function(e) {
		var urlParams,
				slideIndex,
				scrollEl;

		urlParams = new URLSearchParams(window.location.search);
		slideIndex = urlParams.get('cw-slide');

		if( slideIndex !== null ) {
			scrollEl = $('.cw-content-slides');

			$('html,body').animate({
				scrollTop: scrollEl.offset().top - 50
			}, 100 );
		}
	});

})( jQuery );
