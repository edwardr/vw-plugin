
/**
 * Functions
 */

 function enableDragSort(listClass) {
   const sortableLists = document.getElementsByClassName(listClass);
   Array.prototype.map.call(sortableLists, list => {
     enableDragList(list);
   });
 }

 function enableDragList(list) {
   Array.prototype.map.call(list.children, item => {
     enableDragItem(item);
   });
 }

 function enableDragItem(item) {
   item.setAttribute("draggable", true);
   item.ondrag = handleDrag;
   item.ondragend = handleDrop;
 }

 function handleDrag(item) {
   const selectedItem = item.target,
     list = selectedItem.parentNode,
     x = event.clientX,
     y = event.clientY;

   selectedItem.classList.add("drag-sort-active");
   let swapItem =
     document.elementFromPoint(x, y) === null
       ? selectedItem
       : document.elementFromPoint(x, y);

   if (list === swapItem.parentNode) {
     swapItem =
       swapItem !== selectedItem.nextSibling ? swapItem : swapItem.nextSibling;
     list.insertBefore(selectedItem, swapItem);
   }
 }

 function handleDrop(item) {
   item.target.classList.remove("drag-sort-active");
 }

 (() => {
   enableDragSort("drag-sort-enable");
 })();

function autoSearchResults(el) {
	var val,
			container,
			endpoint;

	val = el.value;

	container = el.nextElementSibling;

	if( val.length >= 3 ) {

		endpoint = '/json/visitweho/activities-autocomplete-search';

		let xhr = new XMLHttpRequest();
		var params = 'search=' + val;
		xhr.open('GET', endpoint + '?' + params);
		xhr.send();
		xhr.onload = function() {
			if (xhr.status != 200) {
				console.log('Error:');
				console.log(xhr);
			} else {
				var response = JSON.parse(xhr.response);
				if( response.data.length < 1 ) {
					container.innerHTML = '';
					container.classList.remove('active');
				} else {
					container.classList.add('active');
					container.innerHTML = response.data;
				}
			}
		};

	} else {
		container.innerHTML = '';
		container.classList.remove('active');
	}
}

function deleteParentRow(el) {
	el.parentNode.remove();
}

function moveGroup(el) {
	var li = el.parentNode;
	if( el.dataset.direction == 'up' ) {
		if(li.previousElementSibling) {
			li.parentNode.insertBefore(li, li.previousElementSibling);
		}
	} else if( el.dataset.direction == 'down' ) {
		if(li.nextElementSibling) {
			li.parentNode.insertBefore(li.nextElementSibling, li)
		}
	}
}

function addActivityRow(el) {
	var type = el.dataset.type;
	var name = el.dataset.name;
	var id = el.dataset.id;
	var container = el.parentNode.parentNode.parentNode.parentNode.querySelector('.group-activities');
	var groupLi = container.parentNode;
	var groupID = groupLi.querySelector('input[name="group_id[]"]').value;
	var results = el.parentNode.parentNode;

	var searchField = el.parentNode.parentNode.parentNode.querySelector('input.autocomplete');
	searchField.value = '';

	results.innerHTML = '';
	results.classList.remove('active');
	var li = document.createElement('li');
	li.classList.add('grabbable');
	var inner = '<span class="dashicons dashicons-move"></span>';
	inner += '<input type="hidden" name="' + groupID + '_activity_id[]" value="' + id + '" /> ';
	inner += '<input placeholder="Activity Label" type="text" name="' + groupID + '_activity_label[]" value="' + name + '"/> ';
	inner += '<input placeholder="Pre-heading" type="text" name="' + groupID + '_activity_pre_heading[]" value="">';
	inner += '<a target="_blank" href="/wp-admin/post.php?post=' + id + '&action=edit">';
	inner += '<small>Edit ' + type + '</small></a>';
	inner += '<span class="original-title">canonical: ' + name + '</span>';
	inner += '<span onclick="deleteParentRow(this)"';
	inner += ' class="dashicons dashicons-no-alt delete-activity-row"></span>';
	li.innerHTML = inner;
	container.appendChild(li);

	sortableRefresh('group-activities');
}

function sortableRefresh(c) {
	jQuery('.' + c).sortable({
		axis: 'y',
		tolerance: 'pointer',
		containment: 'parent'
	});
}

function addGroupRow(el) {

	event.preventDefault();

	var type = 'group';
	var name = el.dataset.name;
	var id = el.dataset.id;
	var count = document.querySelectorAll('[data-type="group"]').length;
	var i = count;
	var displayIndex = count;
	if( displayIndex < 1 ) {
		displayIndex = 1;
	} else {
		displayIndex++;
	}
	var name = 'Group ' + displayIndex + ' Label';
	var ul = document.querySelector('.collection-content-container ul');
	var li = document.createElement('li');
	//li.classList.add('grabbable');
	li.setAttribute('data-type', type);
	// var inner = '<h4>Group</h4><ul class="group">';
	// inner += '<ul>';

	var randomName = Math.floor(Math.random() * Math.floor(100));
	var randomGroupId = Math.floor(Math.random() * Math.floor(1000));
	var autoBlock = `<div class="autocomplete-search">
				<h5>Activities</h5>
				<input oninput="autoSearchResults(this)"
					placeholder="Search for and add an activity"
					class="widefat autocomplete"
					type="text"
					name="${randomName}"
					value=""
				/>
				<div class="search-results">
					<div>No results</div>
				</div>
			</div>
			<ul class="group-activities sortable"></ul>`;

	var inner = '<span data-direction="up" onclick="moveGroup(this)" class="dashicons dashicons-arrow-up-alt"></span>';
	inner += '<span data-direction="down" onclick="moveGroup(this)" class="dashicons dashicons-arrow-down-alt"></span>';
	inner += '<h5 class="group-label">Label</h5>';
	inner += '<input type="text" name="group_label[]" placeholder="' + name + '" value="' + name + '" /> ';
	inner += '<input type="hidden" name="group_id[]" value="' + randomGroupId + '" />';
	inner += '<span onclick="deleteParentRow(this)"';
	inner += ' class="dashicons dashicons-trash delete-group-row"></span>';
	inner += autoBlock;

	li.innerHTML = inner;
	ul.appendChild(li);

	li.querySelector('input').focus();

	sortableRefresh('group-activities');

}

function wehoAutocomplete() {
	var inputEl, inputType, options;
	inputEl = document.getElementById('place_search');

	if( !inputEl ) {
		return false;
	}

	inputType = inputEl.getAttribute('data-input-type');

	if( inputType != 'hotel' && inputType != 'location' ) {
		return false;
	}

	addressEl = document.getElementById('address');
	cityEl = document.getElementById('city');
	stateEl = document.getElementById('state');
	phoneEl = document.getElementById('phone');
	zipEl = document.getElementById('zip');
	websiteEl = document.getElementById('website_url');

	options = {
		types: ['establishment']
	};

	autocomplete = new google.maps.places.Autocomplete(inputEl, options);

	var streetNumber, route, unit,
	city, state, zip, addressArray,
	addressString = '', website = '';

	google.maps.event.addListener(autocomplete, 'place_changed', function(e) {
		var p = autocomplete.getPlace();

		for (var i = 0; i < p.address_components.length; i++) {
			var addressType = p.address_components[i].types[0];
			if( addressType == 'street_number') {
				streetNumber = p.address_components[i].long_name;
			} else if ( addressType == 'route' ) {
				route = p.address_components[i].long_name;
			} else if ( addressType == 'unit' ) {
				unit = p.address_components[i].long_name;
			} else if ( addressType == 'locality' ) {
				city = p.address_components[i].long_name;
			} else if ( addressType == 'administrative_area_level_1' ) {
				state = p.address_components[i].short_name;
			} else if( addressType == 'postal_code' ) {
				zip = p.address_components[i].long_name;
			}
		}

		addressArray = [streetNumber,route,unit];
		addressArray = addressArray.filter(function(n){ return n != undefined });

		for (var i = 0; i < addressArray.length; i++) {
			if (i == 0 ) {
				addressString += addressArray[i];
			} else {
				addressString += ' ' + addressArray[i];
			}

		}

		if( p.formatted_phone_number ) {
			phone = p.formatted_phone_number;
			phoneEl.value = phone;
		}

		if( p.website ) {
			website = p.website;
			websiteEl.value = website;
		}

		inputEl.value = p.id;
		addressEl.value = addressString;
		cityEl.value = city;
		stateEl.value = state;
		zipEl.value = zip;

		// Reset to empty string
		addressString = '';

	});
}

/**
 * jQuery Callback
 */

(function( $ ) {

	$(document).ready( function() {
		'use strict';

		// $('.collection-content-container .sortable').sortable({
		// 	axis: 'y',
		// 	tolerance: 'pointer',
		// 	containment: 'parent'
		// });

		$('.group-activities').sortable({
			axis: 'y',
			tolerance: 'pointer',
			containment: 'parent'
		});


		$.datetimepicker.setLocale('en');

		$('.color-picker').wpColorPicker();

		$('#upload_menu_image_button').on( 'click', function( event ) {

			// New media frame
			var frame = wp.media({
				title: 'Select or Upload Media',
				button: {
					text: 'Insert'
				},
				multiple: false,
				frame:    'post',
				state: 'insert',
			});

			frame.on( 'insert', function() {
				var state, selection, attachment, display, imgurl;

				state = frame.state();
				selection = state.get('selection');
				attachment = selection.first();
				display = state.display(attachment).toJSON();
				attachment = attachment.toJSON();
				imgurl = attachment.sizes[display.size].url;

				$('#upload_menu_image').val( imgurl );
				frame.close();
			});

			frame.open();

		 });

		$('#upload_image_button').on( 'click', function( event ) {

			// New media frame
			var frame = wp.media({
				title: 'Select or Upload Media',
				button: {
					text: 'Insert'
				},
				multiple: false,
				frame:    'post',
				state: 'insert',
			});

			frame.on( 'insert', function() {
				var state, selection, attachment, display, imgurl;

				state = frame.state();
				selection = state.get('selection');
				attachment = selection.first();
				display = state.display(attachment).toJSON();
				attachment = attachment.toJSON();
				imgurl = attachment.sizes[display.size].url;

				$('#upload_image').val( imgurl );
				frame.close();
			});

			frame.open();

		});

		$('#banner_link_image_upload_button').on( 'click', function( event ) {

			// New media frame
			var frame = wp.media({
				title: 'Select or Upload Media',
				button: {
					text: 'Insert'
				},
				multiple: false,
				frame:    'post',
				state: 'insert',
			});

			frame.on( 'insert', function() {
				var state, selection, attachment, display, imgurl;

				state = frame.state();
				selection = state.get('selection');
				attachment = selection.first();
				display = state.display(attachment).toJSON();
				attachment = attachment.toJSON();
				imgurl = attachment.sizes[display.size].url;

				$('#banner_link_image').val( imgurl );
				frame.close();
			});

			frame.open();

		});

		$('.datetime-select').datetimepicker({
			formatTime:'g:i A',
			format: 'm/d/Y h:i A',
		});

		$('span.add-hotel-feature').on('click', function(e) {
			var last, row, rowClass, rowIndex;
			last = $('#hotel-features tbody tr').last();

			if( last.hasClass('alternate') ) {
				rowClass = '';
			} else {
				rowClass = 'alternate';
			}

			if( last.hasClass('placeholder') ) {
				last.remove();
				rowIndex = 1;
			} else {
				rowIndex = last.find('td.select-icon a').attr('data-row');
				rowIndex++;
			}

			row = '<tr data-row="' + rowIndex + '" class="' + rowClass + '">';

			row += '<td class="select-icon">';
			row += '<input class="selected_icon" type="hidden" name="selected_icon[]" value="" />';
			row += '<a href="#" data-row="' + rowIndex + '">';
			row += '<div class="icon-placeholder"><span class="placeholder-select-text">Select Icon</div></a></td>';
			row += '<td><input name="feature_name[]" value="" type="text" class="widefat" /></td>';
			row += '<td colspan="2"><textarea name="feature_desc[]" rows="5" class="widefat"></textarea></td>';
			row += '<td class="check-column"><span class="dashicons dashicons-no-alt delete-hotel-feature"></span></td>';

			$('#hotel-features tbody').append(row);

		});

		$(document).on('click', 'span.delete-hotel-feature', function(e) {
			$(this).parent().parent().remove();
			if( $('#hotel-features tbody tr').length == 0 ) {
				$('#hotel-features tbody').append('<tr class="placeholder"><td colspan="4">This hotel has no features. Maybe you should add some... 🤔</td></tr>');
			}
		});

		$('span.add-offer-hotel').on('click', function(e) {
			var last, row, rowClass, rowIndex, optionStr = '';
			var token = '';

			$.ajax({
				type: 'post',
				url: '/json/visitweho/request-token',
				contentType: 'application/json',
				headers: {
					'X-WP-Nonce': wehoMain.nonce,
				},
				success: function (data, status) {
					token = data;

					$.ajax({
						type: 'get',
						contentType: 'application/json',
						headers: {
							'Authorization': 'Bearer ' + token,
						},
						url: '/json/visitweho/hotels',
					})
					.done( function( response ) {

						response.forEach(function(k,v) {
							optionStr += '<option value="' + k.id + '">' + k.name + '</option>';
						});

						last = $('#offer-hotels tbody tr').last();

						if( last.hasClass('alternate') ) {
							rowClass = '';
						} else {
							rowClass = 'alternate';
						}

						if( last.hasClass('placeholder') ) {
							last.remove();
							rowIndex = 1;
						} else {
							rowIndex = last.find('td.select-hotel a').attr('data-row');
							rowIndex++;
						}

						row = '<tr data-row="' + rowIndex + '" class="' + rowClass + '">';

						row += '<td class="hotel-name">';
						row += '<select name="selected_hotel[]">' + optionStr + '</select>';
						row += '</td>';
						row += '<td><input placeholder="Offer Name" name="hotel_offer_title[]" value="" type="text" class="widefat hotel-offer-title" />';
						row += '<input placeholder="Promo Code" name="hotel_offer_promo_code[]" value="" type="text" class="widefat promo-code" />';
						row += '<input placeholder="URL" name="hotel_offer_url[]" value="" type="url" class="widefat promo-code" />';
						row += '<textarea placeholder="Offer Description" name="hotel_offer_desc[]" rows="5" class="widefat"></textarea>';
						row += '</td>';
						row += '<td class="check-column"><span class="dashicons dashicons-no-alt delete-offer-hotel"></span></td>';

						$('#offer-hotels tbody').append(row);
					});
				},
				error: function (xhr, desc, err) {
					console.log('Invalid nonce value');
					return false;
				}
			});

		});

		$(document).on('click', 'span.delete-offer-hotel', function(e) {
			$(this).parent().parent().remove();
			if( $('#offer-hotels tbody tr').length == 0 ) {
				$('#offer-hotels tbody').append('<tr class="placeholder"><td colspan="4">This offer has no hotels. Maybe you should add some... 🤔</td></tr>');
			}
		});

		$('span.add-offer-location').on('click', function(e) {
			var last, row, rowClass, rowIndex, optionStr = '';

			var token = '';

			$.ajax({
				type: 'post',
				url: '/json/visitweho/request-token',
				contentType: 'application/json',
				headers: {
					'X-WP-Nonce': wehoMain.nonce,
				},
				success: function (data, status) {
					token = data;

					$.ajax({
						type: 'get',
						contentType: 'application/json',
						headers: {
							'Authorization': 'Bearer ' + token,
						},
						url: '/json/visitweho/locations',
					})
					.done( function( response ) {

						response.forEach(function(k,v) {
							optionStr += '<option value="' + k.id + '">' + k.name + '</option>';
						});

						last = $('#offer-locations tbody tr').last();

						if( last.hasClass('alternate') ) {
							rowClass = '';
						} else {
							rowClass = 'alternate';
						}

						if( last.hasClass('placeholder') ) {
							last.remove();
							rowIndex = 1;
						} else {
							rowIndex = last.find('td.select-location a').attr('data-row');
							rowIndex++;
						}

						row = '<tr data-row="' + rowIndex + '" class="' + rowClass + '">';

						row += '<td class="location-name">';
						row += '<select name="selected_location[]">' + optionStr + '</select>';
						row += '</td>';
						row += '<td class="check-column"><span class="dashicons dashicons-no-alt delete-offer-location"></span></td>';

						$('#offer-locations tbody').append(row);
					});
				},
				error: function (xhr, desc, err) {
					console.log('Invalid nonce value');
					return false;
				}
			});

		});

		$(document).on('click', 'span.delete-offer-location', function(e) {
			$(this).parent().parent().remove();
			if( $('#offer-locations tbody tr').length == 0 ) {
				$('#offer-locations tbody').append('<tr class="placeholder"><td colspan="4">This offer has no locations. Maybe you should add some... 🤔</td></tr>');
			}
		});


		$('.visitweho-icon-selector .insert-new-icon').on('click', function(e) {
			var row, iconClass;
			row = $('#icon-data-row').val();
			iconClass = $(this).attr('class');
			iconClass = iconClass.replace('insert-new-icon ', '');
			$('a[data-row="' + row + '"').find('.icon-placeholder').html('<span class="' + iconClass + '"></span>');
			$('tr[data-row="' + row + '"').find('.selected_icon').val(iconClass);
			$('#TB_closeWindowButton').trigger('click');
		});

		$(document).on('click', 'td.select-icon a', function(e) {
			e.preventDefault();
			var row = $(this).attr('data-row');
			$('#icon-data-row').val(row);
			$('#open-thickbox').trigger('click');
			return false;
		});

		var chosenElement = $('.chosen-select');

		if( chosenElement ) {

			if( chosenElement.hasClass('opentable') ) {
				$(chosenElement).chosen({ max_selected_options: 1 });
			} else {
				$(chosenElement).chosen({width: '100%'});
			}

		}

		// Moves the import row to the top of term edit/creation
		if( $('body').hasClass('edit-tags-php') && $('body').hasClass('taxonomy-venue') ) {
			$('.venue-import-row').prependTo('form#addtag');
			// if( $('.form-table').length ) {
			// 	$('tr.venue-import-row').insertBefore('table.form-table > tbody > tr:first');
			// } else {
			// 	$('.venue-import-row').prependTo('form#addtag');
			// }
		}

		$('select#dola_venue').on('change', function(e) {
			var selected, name, slug, dolaPermalink, dolaID,
			address, city, state, zip, placeID, lat, lng,
			description;
			selected = $(this).find('option:selected');
			name = selected.attr('data-name');
			slug = name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
			dolaPermalink = selected.attr('data-dola-permalink');
			dolaID = $(this).val();
			address = selected.attr('data-address');
			city = selected.attr('data-city');
			state = selected.attr('data-state');
			zip = selected.attr('data-zip');
			placeID = selected.attr('data-place-id');
			lat = selected.attr('data-latitude');
			lng = selected.attr('data-longitude');
			description = selected.attr('data-description');

			$('#tag-name').val( name );
			$('#tag-slug').val( slug );
			$('#tag-description').val( description );
			$('#dola_permalink').val( dolaPermalink );
			$('#dola_id').val( dolaID );

			$('#address').val( address );
			$('#city').val( city );
			$('#state').val( state );
			$('#zip').val( zip );
			//$('#google_place_id').val( placeID );

			$('#latitude').val( lat );
			$('#longitude').val( lng );
		});

		$('#has_landing_page').on('change', function(e) {
			if( $(this).is(':checked') ) {
				$('.offer-landing-page-fields').show();
			} else {
				$('.offer-landing-page-fields').hide();
			}
		});

		// Runs when the image button is clicked.
		$('#add-item-image').on('click', function(e) {
			var meta_gallery_frame;
			e.preventDefault();

			if ( meta_gallery_frame ) {
				meta_gallery_frame.open();
				return;
			}

			meta_gallery_frame = wp.media.frames.meta_gallery_frame = wp.media({
				title: '',
				button: { text:  'Select', },
				library: { type: 'image' },
				multiple: true
			});

			meta_gallery_frame.states.add([
				new wp.media.controller.Library({
					id:         'selectimg',
					title:      'Select Images for Gallery',
					priority:   20,
					toolbar:    'main-gallery',
					filterable: 'uploaded',
					library:    wp.media.query( meta_gallery_frame.options.library ),
					multiple:   false,
					editable:   true,
					allowLocalEdits: true,
					displaySettings: true,
					displayUserSettings: true
				}),
			]);

			meta_gallery_frame.on('ready', function() {
				jQuery( '.media-modal' ).addClass( 'no-sidebar' );
			});

			meta_gallery_frame.on('select', function() {
				var images;
				var imageIDArray = [];
				var imageHTML = '';
				var metadataString = '';
				var currentImages = '';
				images = meta_gallery_frame.state().get('selection');
				images.each(function(attachment) {
					$('#item-media').val(attachment.attributes.id);
					$('.item-img-container').html('<img src="'+attachment.attributes.sizes.thumbnail.url+'" /><span class="dashicons dashicons-no-alt img-delete"></span>');
				});
			});

			meta_gallery_frame.open();

		});

		$(document).on('click', '.item-img-container .img-delete', function(e) {
			$('#item-media').val('');
			$('.item-img-container').empty();
		});

		$('.trigger-events-fetch').on('click', function(e) {

			if (window.confirm('Click to confirm events fetch.')) {
				$.ajax({
					type: 'post',
					url: '/json/visitweho/admin-events-fetch',
				})
				.done( function( response ) {
				});
			}

		});

		$('.trigger-venue-fetch').on('click', function(e) {
			if (window.confirm('Click to confirm venue fetch.')) {
				$.ajax({
					type: 'post',
					url: '/json/visitweho/admin-venue-fetch',
				})
				.done( function( response ) {
				});
			}
		});

		/**
		 * Gutenberg rich text meta box patch
		 */

		if (wp.data && window.tinymce) {
			wp.data.subscribe(function () {
				if( wp.data.select( 'core/editor' ) ) {
					if (wp.data.select( 'core/editor' ).isSavingPost() && window.tinymce.editors) {
						for (var i = 0; i < tinymce.editors.length; i++) {
							tinymce.editors[i].save();
						}
					}
				}
			});
		}

		/**
		 * Disables label attribute for menu widgets
		 */
		if (window.location.href.indexOf("nav-menu") > -1) {
			 $('.item-controls .item-type').each(function( index ) {
				if( $(this).text() == 'Menu Widget' ) {
					var parent = $(this).parent().parent().parent().parent();
					var id = parent.attr('id');
					id = id.replace('menu-item-', '');
					$('#edit-menu-item-title-' + id ).addClass('disabled').attr('readonly', 'readonly');
					$('#edit-menu-item-attr-title-' + id ).hide();
				}
			 });
		}

    $('.add-to-menu input').on('click', function(e) {
    	setTimeout( function(e) {
	       $('.item-controls .item-type').each(function( index ) {
	       	if( $(this).text() == 'Menu Widget' ) {
	       		var parent = $(this).parent().parent().parent().parent();
	       		var id = parent.attr('id');
	       		id = id.replace('menu-item-', '');
	       		$('#edit-menu-item-title-' + id ).addClass('disabled').attr('readonly', 'readonly');
	       		$('#edit-menu-item-attr-title-' + id ).hide();
	       	}
	       });
    	}, 2500 );

    });

		$('.js-add-gallery-image').click(function(e) {
			 e.preventDefault();
			 var image_frame;
			 if(image_frame){
					 image_frame.open();
			 }

			 image_frame = wp.media({
										 title: 'Select Media',
										 multiple : false,
										 library : {
													type : 'image',
											}
								 });

			 image_frame.on('close',function() {
					var selection =  image_frame.state().get('selection');
					selection.each(function(attachment) {
						var img_id = attachment['id'];
						var img_url = attachment['attributes']['url'];
						var item = '<div style="background-image:url(' + img_url + ');" class="thumbnail">';
						item += '<input type="hidden" name="gallery_images[]" value="' + img_id + '" />';
						item += '<span class="dashicons dashicons-no-alt js-remove-gallery-image"></span>';
						item += '</div>';

						$('.thumbnail-manager').append(item);

						enableDragSort("drag-sort-enable");
					});
			 });

		image_frame.open();
	 });

		$(document).on('click', '.js-remove-gallery-image', function(e) {
		 $(this).parent().remove();
		});

	});

})( jQuery );
