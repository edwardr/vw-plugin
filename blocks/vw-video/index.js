( function( wp ) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	var Editable          = wp.blocks.Editable;
	var RichText = wp.editor.RichText;
	var SelectControl = wp.blocks.SelectControl;
	var PlainText = wp.editor.PlainText;
	var InspectorControls = wp.blocks.InspectorControls;
	var BlockDescription  = wp.blocks.BlockDescription;
	var ToggleControl     = wp.blocks.ToggleControl;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 * @see https://github.com/WordPress/gutenberg/tree/master/element#element
	 */
	var el = wp.element.createElement;
	/**
	 * Retrieves the translation of text.
	 * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
	 */
	var __ = wp.i18n.__;

	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/block-api/
	 */
	registerBlockType( 'visitweho/vw-video', {
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'VisitWeho Video' ),

		/**
		 * An icon property should be specified to make it easier to identify a block.
		 * These can be any of WordPress’ Dashicons, or a custom svg element.
		 */
		icon: 'video-alt2',

		/**
		 * Blocks are grouped into categories to help users browse and discover them.
		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
		 */
		category: 'common',

		/**
		 * Optional block extended support features.
		 */
		supports: {
			// Removes support for an HTML mode.
			html: false,
		},

		attributes: {
			video_id: {
				type: 'string',
				default: '',
			},
			video_url: {
				type: 'string',
				default: '',
			},
			auto_cc: {
				type: 'boolean',
				default: false,
			},
			auto_mute: {
				type: 'boolean',
				default: false,
			},
			auto_play: {
				type: 'boolean',
				default: false,
			}
		},

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		// edit: function( props ) {
		// 	var video_id = props.video_id;
		// 	var video_url = props.video_url;
		// 	var auto_cc = props.auto_cc;
		// 	var auto_mute = props.auto_mute;
		// 	var auto_play = props.auto_play;

		// 	console.log(video_id);
		// 	console.log(video_url);
		// 	console.log(auto_cc);
		// 	return el(
		// 		'select',
		// 		{ className: props.className },
		// 		__( 'video thing' )
		// 	);
		// },
	
			edit: function( props ) {
				var video_url = props.attributes.video_url;
				var focus = props.focus;

				function onChangeContent( updatedContent ) {
					props.setAttributes( { video_url: updatedContent } );
				}

				return [
					el(
						PlainText,
						{
							tagName: 'input',
							label: 'React is an overengineered pile of garbage.',
							className: props.className,
							value: video_url,
							onChange: onChangeContent,
							focus: focus,
							onFocus: props.setFocus,
							placeholder: 'Dammit',
						},
						InspectorControls,
					),
					el(
						PlainText,
						{
							tagName: 'input',
							label: 'React is an overengineered pile of garbage.',
							className: props.className,
							value: video_url,
							onChange: onChangeContent,
							focus: focus,
							onFocus: props.setFocus
						},
						InspectorControls,
					),
				]
			},

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
		save: function() {
			return el(
				'p',
				{},
				__( 'Hello from the saved content!' )
			);
		}
	} );
} )(
	window.wp
);
