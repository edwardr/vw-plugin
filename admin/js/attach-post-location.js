(function( $ ) {
	'use strict';

	$(document).ready( function(e) {
		var title, editor_title, label, insert, close;
		title = 'Insert a Location';
		editor_title = 'Insert a Location';
		label = 'Select Location';
		insert = 'Insert';
		close = 'Close';
		tinymce.create('tinymce.plugins.attachPostLocation', {
			init : function(editor, url) {
				editor.addButton('attach-post-location', {
						title : title,
						cmd : 'insert-attach-post-location',
				});

				editor.addCommand('insert-attach-post-location', function() {
				var postID = $('#post_ID').val();
						editor.windowManager.open({
							title: editor_title,
						  body: [{
						    type: 'container',
						    layout: 'stack',
						    items: [
						    	{type: 'label', text: 'Location', classes: 'attach-location-label'},
						      {type: 'textbox', name: 'location_id', value: '', placeholder: 'search for a location', classes: 'attach-location-input tiny-location-autocomplete'},
									{type: 'label', text: 'Title Override', classes: 'attach-location-label'},
									{type: 'textbox', placeholder: 'optional', name: 'location_title', value: '', classes: 'attach-location-input'},
									{type: 'label', text: 'Image Override', classes: 'attach-location-label'},
									{type: 'textbox', subtype: 'url', placeholder: 'override image url', name: 'location_img', value: '', classes: 'attach-location-input'},
									{type: 'label', text: 'Blurb (optional)', classes: 'attach-location-label'},
						      {type: 'textbox',multiline: true, placeholder: 'optional', name: 'location_blurb', value: '', classes: 'attach-location-input'},
						    ]
						  }],
							buttons: [{
									text: insert,
									onclick: "submit"
							}, {
									text: close,
									onclick: "close"
							}],
							width: 500,
							height: 500,
							onsubmit: function(e) {

								if( e.data.location_id == 0 ) {
									return false;
								}

								var selection = '[story-location id="' + e.data.location_id + '"';

								// if( e.data.location_blurb.length > 1 ) {
								// 	selection += ' blurb="' + e.data.location_blurb + '"]';
								// }
								
								if( e.data.location_img.length > 1 ) {
									selection += ' img="' + e.data.location_img + '"';
								}

								if( e.data.location_title.length > 1 ) {
									selection += ' title="' + e.data.location_title + '"]';
								} else {
									selection += ']';
								}

								selection += e.data.location_blurb;

								selection += '[/story-location]';
								
								window.tinymce.execCommand('mceInsertContent', false ,selection);
							}
						});
						$( ".mce-tiny-location-autocomplete" ).autocomplete({
							source: ajaxurl + '?action=get_attachable_locations',
							delay: 500,
							minLength: 3
						});
				});
			},
		});
		
		tinymce.PluginManager.add( 'attach_post_location', tinymce.plugins.attachPostLocation );
	});

})( jQuery );