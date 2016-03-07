
var gatracksocial = gatracksocial || {};

gatracksocial.trackSocial = function( opt_pageUrl ) {
	
	gatracksocial.trackVkontakte( opt_pageUrl );
	gatracksocial.trackFacebook( opt_pageUrl );
	gatracksocial.trackTwitter( opt_pageUrl );

};

/**
 * Tracks Facebook likes, unlikes and sends by suscribing to the Facebook
 * JSAPI event model. Note: This will not track facebook buttons using the
 * iFrame method.
 * @param {string} opt_pageUrl An optional URL to associate the social
 *     tracking with a particular page.
 */
gatracksocial.trackFacebook = function( opt_pageUrl ) {

	try {

		if (FB && FB.Event && FB.Event.subscribe) {

			FB.Event.subscribe('edge.create', function(targetUrl) {

				if ( ga && typeof ga === "function" )
					ga('send', 'social', 'facebook', 'like', targetUrl );

			});
			FB.Event.subscribe('edge.remove', function(targetUrl) {

				if ( ga && typeof ga === "function" )
					ga('send', 'social', 'facebook', 'unlike', targetUrl );

			});
			FB.Event.subscribe('message.send', function(targetUrl) {

				if ( ga && typeof ga === "function" )
					ga('send', 'social', 'facebook', 'send', targetUrl );

			});
		}
	} catch ( e ) {

		console.log( e );

	}
};

/**
 * Tracks everytime a user clicks on a tweet button from Twitter.
 * This subscribes to the Twitter JS API event mechanism to listen for
 * clicks coming from this page. Details here:
 * http://dev.twitter.com/pages/intents-events#click
 * This method should be called once the twitter API has loaded.
 * @param {string} opt_pageUrl An optional URL to associate the social
 *     tracking with a particular page.
 * @param {string} opt_trackerName An optional name for the tracker object.
 */
gatracksocial.trackTwitter = function(opt_pageUrl) {

	try {
		if (twttr && twttr.events && twttr.events.bind) {
			twttr.events.bind('tweet', function(event) {
				if (event) {
					var targetUrl; // Default value is undefined.
					if (event.target && event.target.nodeName == 'IFRAME') {
						targetUrl = gatracksocial.extractParamFromUri_(event.target.src, 'url');
					}

					if ( ga && typeof ga === "function" )
						ga('send', 'social', 'twitter', 'tweet', targetUrl );
					
				}
			});
		}
	} catch ( e ) {

		console.log( e );

	}
};


gatracksocial.trackVkontakte = function(opt_pageUrl) {

	try {

		if (VK && VK.Observer && VK.Observer.subscribe) {

			VK.Observer.subscribe('widgets.like.liked', function() {

				if ( ga && typeof ga === "function" )
					ga('send', 'social', 'vk', 'like', opt_pageUrl );

			});
			VK.Observer.subscribe('widgets.like.unliked', function() {

				if ( ga && typeof ga === "function" )
					ga('send', 'social', 'vk', 'unlike', opt_pageUrl );

			});
		}
	} catch ( e ) {

		console.log( e );

	}
};

/**
 * Extracts a query parameter value from a URI.
 * @param {string} uri The URI from which to extract the parameter.
 * @param {string} paramName The name of the query paramater to extract.
 * @return {string} The un-encoded value of the query paramater. underfined
 *     if there is no URI parameter.
 * @private
 */
gatracksocial.extractParamFromUri_ = function(uri, paramName) {
	if (!uri) {
		return;
	}
	uri = uri.split('#')[0];  // Remove anchor.
	var parts = uri.split('?');  // Check for query params.
	if (parts.length === 1) {
		return;
	}
	var query = decodeURI(parts[1]);

	// Find url param.
	paramName += '=';
	var params = query.split('&');
	for (var i = 0; i < params.length; ++i) {
		if (param[i].indexOf(paramName) === 0) {
			return unescape(param[i].split('=')[1]);
		}
	}
	return;
};