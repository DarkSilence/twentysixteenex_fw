/* global screenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

( function( $ ) {
	var body, masthead, menuToggle, siteNavigation, socialNavigation, siteHeaderMenu, resizeTimer, scripts = scripts || [], functions = functions || [];

	function initMainNavigation( container ) {

		// Add dropdown toggle that displays child menu items.
		var dropdownToggle = $( '<button />', {
			'class': 'dropdown-toggle',
			'aria-expanded': false
		} ).append( $( '<span />', {
			'class': 'screen-reader-text',
			text: screenReaderText.expand
		} ) );

		container.find( '.menu-item-has-children > a' ).after( dropdownToggle );

		// Toggle buttons and submenu items with active children menu items.
		container.find( '.current-menu-ancestor > button' ).addClass( 'toggled-on' );
		container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );

		// Add menu items with submenus to aria-haspopup="true".
		container.find( '.menu-item-has-children' ).attr( 'aria-haspopup', 'true' );

		container.find( '.dropdown-toggle' ).click( function( e ) {
			var _this            = $( this ),
				screenReaderSpan = _this.find( '.screen-reader-text' );

			e.preventDefault();
			_this.toggleClass( 'toggled-on' );
			_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

			// jscs:disable
			_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
			// jscs:enable
			screenReaderSpan.text( screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand );
		} );
	}
	initMainNavigation( $( '.main-navigation' ) );

	masthead         = $( '#masthead' );
	menuToggle       = masthead.find( '#menu-toggle' );
	siteHeaderMenu   = masthead.find( '#site-header-menu' );
	siteNavigation   = masthead.find( '#site-navigation' );
	socialNavigation = masthead.find( '#social-navigation' );

	// Enable menuToggle.
	( function() {

		// Return early if menuToggle is missing.
		if ( ! menuToggle.length ) {
			return;
		}

		// Add an initial values for the attribute.
		menuToggle.add( siteNavigation ).add( socialNavigation ).attr( 'aria-expanded', 'false' );

		menuToggle.on( 'click.twentysixteenex', function() {
			$( this ).add( siteHeaderMenu ).toggleClass( 'toggled-on' );

			// jscs:disable
			$( this ).add( siteNavigation ).add( socialNavigation ).attr( 'aria-expanded', $( this ).add( siteNavigation ).add( socialNavigation ).attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
			// jscs:enable
		} );
	} )();

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	( function() {
		if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleFocusClassTouchScreen() {
			if ( window.innerWidth >= 910 ) {
				$( document.body ).on( 'touchstart.twentysixteenex', function( e ) {
					if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
						$( '.main-navigation li' ).removeClass( 'focus' );
					}
				} );
				siteNavigation.find( '.menu-item-has-children > a' ).on( 'touchstart.twentysixteenex', function( e ) {
					var el = $( this ).parent( 'li' );

					if ( ! el.hasClass( 'focus' ) ) {
						e.preventDefault();
						el.toggleClass( 'focus' );
						el.siblings( '.focus' ).removeClass( 'focus' );
					}
				} );
			} else {
				siteNavigation.find( '.menu-item-has-children > a' ).unbind( 'touchstart.twentysixteenex' );
			}
		}

		if ( 'ontouchstart' in window ) {
			$( window ).on( 'resize.twentysixteenex', toggleFocusClassTouchScreen );
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find( 'a' ).on( 'focus.twentysixteenex blur.twentysixteenex', function() {
			$( this ).parents( '.menu-item' ).toggleClass( 'focus' );
		} );
	} )();

	// Add the default ARIA attributes for the menu toggle and the navigations.
	function onResizeARIA() {
		if ( window.innerWidth < 910 ) {
			if ( menuToggle.hasClass( 'toggled-on' ) ) {
				menuToggle.attr( 'aria-expanded', 'true' );
			} else {
				menuToggle.attr( 'aria-expanded', 'false' );
			}

			if ( siteHeaderMenu.hasClass( 'toggled-on' ) ) {
				siteNavigation.attr( 'aria-expanded', 'true' );
				socialNavigation.attr( 'aria-expanded', 'true' );
			} else {
				siteNavigation.attr( 'aria-expanded', 'false' );
				socialNavigation.attr( 'aria-expanded', 'false' );
			}

			menuToggle.attr( 'aria-controls', 'site-navigation social-navigation' );
		} else {
			menuToggle.removeAttr( 'aria-expanded' );
			siteNavigation.removeAttr( 'aria-expanded' );
			socialNavigation.removeAttr( 'aria-expanded' );
			menuToggle.removeAttr( 'aria-controls' );
		}
	}

	function loadScript( script ) {

		var doc = window.document;
		var head = doc.head || doc.getElementsByTagName("head")[0];

		var s = doc.createElement('script');
		s.async = false;
		if ( typeof script.src !== 'undefined' && typeof script.src.type !== 'undefined' )
			s.type = 'text/' + script.src.type;
		else
			s.type = 'text/javascript';
		if ( typeof script.src !== 'undefined' ) s.src = script.src.src || script.src;
		if ( typeof script.id !== 'undefined' ) s.id = script.id;

		s.onreadystatechange = s.onload = function () {

			var state = s.readyState;

			if ( typeof script.callback !== 'undefined' ) {

				if ( !script.callback.done && ( !state || /loaded|complete/.test( state ) ) ) {
				
					script.callback.done = true;
					script.callback();
				}			
			}
		};

		// use body if available. more safe in IE
		(doc.body || head).appendChild(s);
	}

	function initSocials() {

		if ( $('.fb-like').length > 0 ) {

			scripts.push( { src: '//connect.facebook.net/ru_RU/all.js#xfbml=1', id: 'facebook-jssdk' } );
			// facebook init routine
			window.fbAsyncInit = window.fbAsyncInit || function() {
				FB.init({appId:fbAPPID,xfbml:true});

				if ( FB && FB.Event && FB.Event.subscribe) {
					
					FB.Event.subscribe('edge.create',function(u){if (ga && typeof ga === "function") ga('send','social','facebook','like',u);});
					FB.Event.subscribe('edge.remove',function(u){if (ga && typeof ga === "function") ga('send','social','facebook','unlike',u);});
					FB.Event.subscribe('message.send',function(u){if (ga && typeof ga === "function") ga('send','social','facebook','send',u);});
				}
			};		
		}

		if ( $('#vk_like').length > 0 ) {

			scripts.push( { src: '//vk.com/js/api/openapi.js?121', id: 'vk-openapi' } );

			// VK async init
			window.vkAsyncInit = window.vkAsyncInit || function() {
				VK.init({apiId:vkAPPID,onlyWidgets:true});
				VK.Widgets.Like("vk_like",{type: "button"});

				if (VK && VK.Observer && VK.Observer.subscribe) {

					var u = window.location.href.toString();
					VK.Observer.subscribe('widgets.like.liked',function(cnt){if (ga && typeof ga === "function") ga('send','social','vk','like',u);});
					VK.Observer.subscribe('widgets.like.unliked',function(cnt){if (ga && typeof ga === "function")ga('send','social','vk','unlike',u);});
				}
			};	
		}

		if ( $('g\\:plusone').length > 0 ) {

			scripts.push( { src: 'https://apis.google.com/js/plusone.js' } );

		}

		if ( $('.twitter-share-button').length > 0 ) {

			scripts.push( { src: '//platform.twitter.com/widgets.js', id: 'twitter-wjs' } );

			window.twttr = window.twttr || ( t = { _e: [], ready: function(f) { t._e.push(f); } } );
			window.twttr.ready(function(twttr) {
				try {
					if (twttr.events && twttr.events.bind) {
						twttr.events.bind('tweet', function(event) {
							if (event) {

								var u = window.location.href.toString();
								if (ga && typeof ga === "function") ga('send', 'social', 'twitter', 'tweet', u );
							}
						});
					}
				} catch (e) {}
			});
		}

		if ( $('.pin-it-button').length > 0 ) {

			scripts.push( { src: '//assets.pinterest.com/js/pinit.js', callback: function() {

				$('div.pinterest-main').click(function() {
					var u = window.location.href.toString();
					if (ga && typeof ga === "function") ga('send', 'social', 'pinterest', 'pin', u);
				});
			} } );
		}

		if ( $('#ok_shareWidget').length > 0 ) {

			scripts.push( { src: '//connect.ok.ru/connect.js', callback: function() {

				var u = window.location.href.toString();
				OK.CONNECT.insertShareWidget("ok_shareWidget",u,"{width:165,height:35,st:'rounded',sz:20,ck:1}");

			} } );
		}

		// if ( $('.mrc__plugin_like_button').length > 0 ) {

		// 	scripts.push( { src: '//cdn.connect.mail.ru/js/loader.js', callback: function() {

		// 		$('.mrc__plugin_like_button').click(function() {
		// 			var u = window.location.href.toString();
		// 			if (ga && typeof ga === "function") ga('send', 'social', 'pinterest', 'pin', u);
		// 		});
		// 	} } );
		// }
	}

	// Add 'below-entry-meta' class to elements.
	function belowEntryMetaClass( param ) {
		if ( body.hasClass( 'page' ) || body.hasClass( 'search' ) || body.hasClass( 'single-attachment' ) || body.hasClass( 'error404' ) ) {
			return;
		}

		$( '.entry-content' ).find( param ).each( function() {
			var element              = $( this ),
				elementPos           = element.offset(),
				elementPosTop        = elementPos.top,
				entryFooter          = element.closest( 'article' ).find( '.entry-footer' ),
				entryFooterPos       = entryFooter.offset(),
				entryFooterPosBottom = entryFooterPos.top + ( entryFooter.height() + 28 ),
				caption              = element.closest( 'figure' ),
				newImg;

			// Add 'below-entry-meta' to elements below the entry meta.
			if ( elementPosTop > entryFooterPosBottom ) {

				// Check if full-size images and captions are larger than or equal to 840px.
				if ( 'img.size-full' === param ) {

					// Create an image to find native image width of resized images (i.e. max-width: 100%).
					newImg = new Image();
					newImg.src = element.attr( 'src' );

					$( newImg ).load( function() {
						if ( newImg.width >= 840  ) {
							element.addClass( 'below-entry-meta' );

							if ( caption.hasClass( 'wp-caption' ) ) {
								caption.addClass( 'below-entry-meta' );
								caption.removeAttr( 'style' );
							}
						}
					} );
				} else {
					element.addClass( 'below-entry-meta' );
				}
			} else {
				element.removeClass( 'below-entry-meta' );
				caption.removeClass( 'below-entry-meta' );
			}
		} );
	}

	$( document ).ready( function() {
		body = $( document.body );

		initSocials();

		$( window )
			.on( 'load.twentysixteenex', onResizeARIA )
			.on( 'resize.twentysixteenex', function() {
				clearTimeout( resizeTimer );
				resizeTimer = setTimeout( function() {
					belowEntryMetaClass( 'img.size-full' );
					belowEntryMetaClass( 'blockquote.alignleft, blockquote.alignright' );
				}, 300 );
				onResizeARIA();
			} );

		belowEntryMetaClass( 'img.size-full' );
		belowEntryMetaClass( 'blockquote.alignleft, blockquote.alignright' );

		scripts.forEach( function( v ) {

			loadScript( v );

		});
		functions.forEach( function( v ) {

			v();

		});
	} );
} )( jQuery );
