/* global colorScheme, Color */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api ) {
	var cssTemplate = wp.template( 'twentysixteenex-color-scheme' ),
		colorSchemeKeys = [
			'background_color',
			'link_color',
			'main_text_color',
			'secondary_text_color',
			'buttons_background_color',
			'buttons_text_color',
			'buttons_active_background_color',
			'buttons_active_text_color',
			'buttons_disabled_background_color',
			'buttons_disabled_text_color'
		],
		colorSettings = [
			'background_color',
			'link_color',
			'main_text_color',
			'secondary_text_color',
			'buttons_background_color',
			'buttons_text_color',
			'buttons_active_background_color',
			'buttons_active_text_color',
			'buttons_disabled_background_color',
			'buttons_disabled_text_color'
		];

	api.controlConstructor.select = api.Control.extend( {
		ready: function() {
			if ( 'color_scheme' === this.id ) {
				this.setting.bind( 'change', function( value ) {
					var colors = colorScheme[value].colors;

					// Update Background Color.
					var color = colors[0];
					api( 'background_color' ).set( color );
					api.control( 'background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Link Color.
					color = colors[2];
					api( 'link_color' ).set( color );
					api.control( 'link_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Main Text Color.
					color = colors[3];
					api( 'main_text_color' ).set( color );
					api.control( 'main_text_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Secondary Text Color.
					color = colors[4];
					api( 'secondary_text_color' ).set( color );
					api.control( 'secondary_text_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Button Background Color.
					color = colors[5];
					api( 'buttons_background_color' ).set( color );
					api.control( 'buttons_background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Button Text Color.
					color = colors[6];
					api( 'buttons_text_color' ).set( color );
					api.control( 'buttons_text_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Active Button Background Color.
					color = colors[7];
					api( 'buttons_active_background_color' ).set( color );
					api.control( 'buttons_active_background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Active Button Text Color.
					color = colors[8];
					api( 'buttons_active_text_color' ).set( color );
					api.control( 'buttons_active_text_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Disabled Button Background Color.
					color = colors[9];
					api( 'buttons_disabled_background_color' ).set( color );
					api.control( 'buttons_disabled_background_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );

					// Update Disabled Button Text Color.
					color = colors[10];
					api( 'buttons_disabled_text_color' ).set( color );
					api.control( 'buttons_disabled_text_color' ).container.find( '.color-picker-hex' )
						.data( 'data-default-color', color )
						.wpColorPicker( 'defaultColor', color );
				} );
			}
		}
	} );

	// Generate the CSS for the current Color Scheme.
	function updateCSS() {
		var scheme = api( 'color_scheme' )(),
			css,
			colors = _.object( colorSchemeKeys, colorScheme[ scheme ].colors );

		// Merge in color scheme overrides.
		_.each( colorSettings, function( setting ) {
			colors[ setting ] = api( setting )();
		} );

		// Add additional color.
		// jscs:disable
		colors.border_color = Color( colors.main_text_color ).toCSS( 'rgba', 0.2 );
		colors.transparent_bg_color = Color( colors.background_color ).toCSS( 'rgba', 0.75 );
		// jscs:enable

		css = cssTemplate( colors );

		api.previewer.send( 'update-color-scheme-css', css );
	}

	// Update the CSS whenever a color setting is changed.
	_.each( colorSettings, function( setting ) {
		api( setting, function( setting ) {
			setting.bind( updateCSS );
		} );
	} );
} )( wp.customize );
