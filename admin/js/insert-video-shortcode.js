(function( $ ) {
	'use strict';

	$(document).ready( function(e) {
		var title, editor_title, label, insert, close;
		title = 'Insert a Video';
		editor_title = 'Insert a Video';
		label = 'URL';
		insert = 'Insert';
		close = 'Close';
		tinymce.create('tinymce.plugins.insertVideoShortcode', {
			init : function(editor, url) {
				editor.addButton('insert-video-shortcode', {
						title : title,
						cmd : 'insert-insert-video-shortcode',
				});

				editor.addCommand('insert-insert-video-shortcode', function() {
				var postID = $('#post_ID').val();
						editor.windowManager.open({
							title: editor_title,
						  body: [{
						    type: 'container',
						    layout: 'stack',
						    items: [
						    	{type: 'label', text: 'Video URL', classes: 'attach-location-label'},
						      {type: 'textbox', name: 'video_url', value: '', placeholder: 'video url', classes: 'attach-location-input'},
						      {type: 'label', text: 'Video ID', classes: 'attach-location-label'},
						      {type: 'textbox', name: 'id', value: '', placeholder: '(optional unless multiple videos are on the same page)', classes: 'attach-location-input'},
						      {type: 'label', text: 'Auto-Enable Captions', classes: 'attach-location-label'},
						      {type: 'checkbox', name: 'cc', value: '', classes: 'attach-location-input'},
						      {type: 'label', text: 'Autoplay', classes: 'attach-location-label'},
						      {type: 'checkbox', name: 'autoplay', value: '', classes: 'attach-location-input'},
						      {type: 'label', text: 'Auto Mute', classes: 'attach-location-label'},
									{type: 'checkbox', name: 'mute', value: '', classes: 'attach-location-input'},
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

								if( e.data.video_url == 0 ) {
									return false;
								}

								var selection = '[vw-youtube url="' + e.data.video_url + '"';

								if( e.data.id != 0 ) {
									e.data.id = e.data.id.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
									selection += ' id="' + e.data.id + '"';
								}

								if( e.data.cc == true ) {
									selection += ' cc="1"';
								}

								if( e.data.autoplay == true ) {
									selection += ' autoplay="1"';
								}

								if( e.data.mute == true ) {
									selection += ' mute="1"';
								}

								selection += ']';
								
								window.tinymce.execCommand('mceInsertContent', false ,selection);
							}
						});
				});
			},
		});
		
		tinymce.PluginManager.add( 'insert_video_shortcode', tinymce.plugins.insertVideoShortcode );
	});

})( jQuery );