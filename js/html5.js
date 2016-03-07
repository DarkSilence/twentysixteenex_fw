// IE5.5+ PNG Alpha Fix v2.0 Alpha: Background Tiling Support
// (c) 2008-2009 Angus Turnbull http://www.twinhelix.com

// This is licensed under the GNU LGPL, version 2.1 or later.
// For details, see: http://creativecommons.org/licenses/LGPL/2.1/

var IEPNGFix = window.IEPNGFix || {};

IEPNGFix.tileBG = function(elm, pngSrc, ready) {
	// Params: A reference to a DOM element, the PNG src file pathname, and a
	// hidden "ready-to-run" passed when called back after image preloading.

	var data = this.data[elm.uniqueID],
		elmW = Math.max(elm.clientWidth, elm.scrollWidth),
		elmH = Math.max(elm.clientHeight, elm.scrollHeight),
		bgX = elm.currentStyle.backgroundPositionX,
		bgY = elm.currentStyle.backgroundPositionY,
		bgR = elm.currentStyle.backgroundRepeat;

	// Cache of DIVs created per element, and image preloader/data.
	if (!data.tiles) {
		data.tiles = {
			elm: elm,
			src: '',
			cache: [],
			img: new Image(),
			old: {}
		};
	}
	var tiles = data.tiles,
		pngW = tiles.img.width,
		pngH = tiles.img.height;

	if (pngSrc) {
		if (!ready && pngSrc != tiles.src) {
			// New image? Preload it with a callback to detect dimensions.
			tiles.img.onload = function() {
				this.onload = null;
				IEPNGFix.tileBG(elm, pngSrc, 1);
			};
			return tiles.img.src = pngSrc;
		}
	} else {
		// No image?
		if (tiles.src) ready = 1;
		pngW = pngH = 0;
	}
	tiles.src = pngSrc;

	if (!ready && elmW == tiles.old.w && elmH == tiles.old.h &&
		bgX == tiles.old.x && bgY == tiles.old.y && bgR == tiles.old.r) {
		return;
	}

	// Convert English and percentage positions to pixels.
	var pos = {
			top: '0%',
			left: '0%',
			center: '50%',
			bottom: '100%',
			right: '100%'
		},
		x,
		y,
		pc;
	x = pos[bgX] || bgX;
	y = pos[bgY] || bgY;
	if (pc = x.match(/(\d+)%/)) {
		x = Math.round((elmW - pngW) * (parseInt(pc[1]) / 100));
	}
	if (pc = y.match(/(\d+)%/)) {
		y = Math.round((elmH - pngH) * (parseInt(pc[1]) / 100));
	}
	x = parseInt(x);
	y = parseInt(y);

	// Handle backgroundRepeat.
	var repeatX = { 'repeat': 1, 'repeat-x': 1 }[bgR],
		repeatY = { 'repeat': 1, 'repeat-y': 1 }[bgR];
	if (repeatX) {
		x %= pngW;
		if (x > 0) x -= pngW;
	}
	if (repeatY) {
		y %= pngH;
		if (y > 0) y -= pngH;
	}

	// Go!
	this.hook.enabled = 0;
	if (!({ relative: 1, absolute: 1 }[elm.currentStyle.position])) {
		elm.style.position = 'relative';
	}
	var count = 0,
		xPos,
		maxX = repeatX ? elmW : x + 0.1,
		yPos,
		maxY = repeatY ? elmH : y + 0.1,
		d,
		s,
		isNew;
	if (pngW && pngH) {
		for (xPos = x; xPos < maxX; xPos += pngW) {
			for (yPos = y; yPos < maxY; yPos += pngH) {
				isNew = 0;
				if (!tiles.cache[count]) {
					tiles.cache[count] = document.createElement('div');
					isNew = 1;
				}
				var clipR = Math.max(0, xPos + pngW > elmW ? elmW - xPos : pngW),
					clipB = Math.max(0, yPos + pngH > elmH ? elmH - yPos : pngH);
				d = tiles.cache[count];
				s = d.style;
				s.behavior = 'none';
				s.left = (xPos - parseInt(elm.currentStyle.paddingLeft)) + 'px';
				s.top = yPos + 'px';
				s.width = clipR + 'px';
				s.height = clipB + 'px';
				s.clip = 'rect(' +
					(yPos < 0 ? 0 - yPos : 0) + 'px,' +
					clipR + 'px,' +
					clipB + 'px,' +
					(xPos < 0 ? 0 - xPos : 0) + 'px)';
				s.display = 'block';
				if (isNew) {
					s.position = 'absolute';
					s.zIndex = -999;
					if (elm.firstChild) {
						elm.insertBefore(d, elm.firstChild);
					} else {
						elm.appendChild(d);
					}
				}
				this.fix(d, pngSrc, 0);
				count++;
			}
		}
	}
	while (count < tiles.cache.length) {
		this.fix(tiles.cache[count], '', 0);
		tiles.cache[count++].style.display = 'none';
	}

	this.hook.enabled = 1;

	// Cache so updates are infrequent.
	tiles.old = {
		w: elmW,
		h: elmH,
		x: bgX,
		y: bgY,
		r: bgR
	};
};


IEPNGFix.update = function() {
	// Update all PNG backgrounds.
	for (var i in IEPNGFix.data) {
		var t = IEPNGFix.data[i].tiles;
		if (t && t.elm && t.src) {
			IEPNGFix.tileBG(t.elm, t.src);
		}
	}
};
IEPNGFix.update.timer = 0;

if (window.attachEvent && !window.opera) {
	window.attachEvent('onresize', function() {
		clearTimeout(IEPNGFix.update.timer);
		IEPNGFix.update.timer = setTimeout(IEPNGFix.update, 100);
	});
}

// Add ECMA262-5 method binding if not supported natively
//
if (!('bind' in Function.prototype)) {
	Function.prototype.bind= function(owner) {
		var that= this;
		if (arguments.length<=1) {
			return function() {
				return that.apply(owner, arguments);
			};
		} else {
			var args= Array.prototype.slice.call(arguments, 1);
			return function() {
				return that.apply(owner, arguments.length===0? args : args.concat(Array.prototype.slice.call(arguments)));
			};
		}
	};
}

// Add ECMA262-5 string trim if not supported natively
//
if (!('trim' in String.prototype)) {
	String.prototype.trim= function() {
		return this.replace(/^\s+/, '').replace(/\s+$/, '');
	};
}

// Add ECMA262-5 Array methods if not supported natively
//
if (!('indexOf' in Array.prototype)) {
	Array.prototype.indexOf= function(find, i /*opt*/) {
		if (i===undefined) i= 0;
		if (i<0) i+= this.length;
		if (i<0) i= 0;
		for (var n= this.length; i<n; i++)
			if (i in this && this[i]===find)
				return i;
		return -1;
	};
}
if (!('lastIndexOf' in Array.prototype)) {
	Array.prototype.lastIndexOf= function(find, i /*opt*/) {
		if (i===undefined) i= this.length-1;
		if (i<0) i+= this.length;
		if (i>this.length-1) i= this.length-1;
		for (i++; i-->0;) /* i++ because from-argument is sadly inclusive */
			if (i in this && this[i]===find)
				return i;
		return -1;
	};
}
if (!('forEach' in Array.prototype)) {
	Array.prototype.forEach= function(action, that /*opt*/) {
		for (var i= 0, n= this.length; i<n; i++)
			if (i in this)
				action.call(that, this[i], i, this);
	};
}
if (!('map' in Array.prototype)) {
	Array.prototype.map= function(mapper, that /*opt*/) {
		var other= new Array(this.length);
		for (var i= 0, n= this.length; i<n; i++)
			if (i in this)
				other[i]= mapper.call(that, this[i], i, this);
		return other;
	};
}
if (!('filter' in Array.prototype)) {
	Array.prototype.filter= function(filter, that /*opt*/) {
		var other= [], v;
		for (var i=0, n= this.length; i<n; i++)
			if (i in this && filter.call(that, v= this[i], i, this))
				other.push(v);
		return other;
	};
}
if (!('every' in Array.prototype)) {
	Array.prototype.every= function(tester, that /*opt*/) {
		for (var i= 0, n= this.length; i<n; i++)
			if (i in this && !tester.call(that, this[i], i, this))
				return false;
		return true;
	};
}
if (!('some' in Array.prototype)) {
	Array.prototype.some= function(tester, that /*opt*/) {
		for (var i= 0, n= this.length; i<n; i++)
			if (i in this && tester.call(that, this[i], i, this))
				return true;
		return false;
	};
}

/**
* @preserve HTML5 Shiv 3.7.3 | @afarkas @jdalton @jon_neal @rem | MIT/GPL2 Licensed
*/
;(function(window, document) {
/*jshint evil:true */
	/** version */
	var version = '3.7.3';

	/** Preset options */
	var options = window.html5 || {};

	/** Used to skip problem elements */
	var reSkip = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i;

	/** Not all elements can be cloned in IE **/
	var saveClones = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i;

	/** Detect whether the browser supports default html5 styles */
	var supportsHtml5Styles;

	/** Name of the expando, to work with multiple documents or to re-shiv one document */
	var expando = '_html5shiv';

	/** The id for the the documents expando */
	var expanID = 0;

	/** Cached data for each document */
	var expandoData = {};

	/** Detect whether the browser supports unknown elements */
	var supportsUnknownElements;

	(function() {
		try {
				var a = document.createElement('a');
				a.innerHTML = '<xyz></xyz>';
				//if the hidden property is implemented we can assume, that the browser supports basic HTML5 Styles
				supportsHtml5Styles = ('hidden' in a);

				supportsUnknownElements = a.childNodes.length == 1 || (function() {
					// assign a false positive if unable to shiv
					(document.createElement)('a');
					var frag = document.createDocumentFragment();
					return (
						typeof frag.cloneNode == 'undefined' ||
						typeof frag.createDocumentFragment == 'undefined' ||
						typeof frag.createElement == 'undefined'
					);
				}());
		} catch(e) {
			// assign a false positive if detection fails => unable to shiv
			supportsHtml5Styles = true;
			supportsUnknownElements = true;
		}

	}());

	/*--------------------------------------------------------------------------*/

	/**
	 * Creates a style sheet with the given CSS text and adds it to the document.
	 * @private
	 * @param {Document} ownerDocument The document.
	 * @param {String} cssText The CSS text.
	 * @returns {StyleSheet} The style element.
	 */
	function addStyleSheet(ownerDocument, cssText) {
		var p = ownerDocument.createElement('p'),
				parent = ownerDocument.getElementsByTagName('head')[0] || ownerDocument.documentElement;

		p.innerHTML = 'x<style>' + cssText + '</style>';
		return parent.insertBefore(p.lastChild, parent.firstChild);
	}

	/**
	 * Returns the value of `html5.elements` as an array.
	 * @private
	 * @returns {Array} An array of shived element node names.
	 */
	function getElements() {
		var elements = html5.elements;
		return typeof elements == 'string' ? elements.split(' ') : elements;
	}

	/**
	 * Extends the built-in list of html5 elements
	 * @memberOf html5
	 * @param {String|Array} newElements whitespace separated list or array of new element names to shiv
	 * @param {Document} ownerDocument The context document.
	 */
	function addElements(newElements, ownerDocument) {
		var elements = html5.elements;
		if(typeof elements != 'string'){
			elements = elements.join(' ');
		}
		if(typeof newElements != 'string'){
			newElements = newElements.join(' ');
		}
		html5.elements = elements +' '+ newElements;
		shivDocument(ownerDocument);
	}

	 /**
	 * Returns the data associated to the given document
	 * @private
	 * @param {Document} ownerDocument The document.
	 * @returns {Object} An object of data.
	 */
	function getExpandoData(ownerDocument) {
		var data = expandoData[ownerDocument[expando]];
		if (!data) {
				data = {};
				expanID++;
				ownerDocument[expando] = expanID;
				expandoData[expanID] = data;
		}
		return data;
	}

	/**
	 * returns a shived element for the given nodeName and document
	 * @memberOf html5
	 * @param {String} nodeName name of the element
	 * @param {Document|DocumentFragment} ownerDocument The context document.
	 * @returns {Object} The shived element.
	 */
	function createElement(nodeName, ownerDocument, data){
		if (!ownerDocument) {
				ownerDocument = document;
		}
		if(supportsUnknownElements){
				return ownerDocument.createElement(nodeName);
		}
		if (!data) {
				data = getExpandoData(ownerDocument);
		}
		var node;

		if (data.cache[nodeName]) {
				node = data.cache[nodeName].cloneNode();
		} else if (saveClones.test(nodeName)) {
				node = (data.cache[nodeName] = data.createElem(nodeName)).cloneNode();
		} else {
				node = data.createElem(nodeName);
		}

		// Avoid adding some elements to fragments in IE < 9 because
		// * Attributes like `name` or `type` cannot be set/changed once an element
		//   is inserted into a document/fragment
		// * Link elements with `src` attributes that are inaccessible, as with
		//   a 403 response, will cause the tab/window to crash
		// * Script elements appended to fragments will execute when their `src`
		//   or `text` property is set
		return node.canHaveChildren && !reSkip.test(nodeName) && !node.tagUrn ? data.frag.appendChild(node) : node;
	}

	/**
	 * returns a shived DocumentFragment for the given document
	 * @memberOf html5
	 * @param {Document} ownerDocument The context document.
	 * @returns {Object} The shived DocumentFragment.
	 */
	function createDocumentFragment(ownerDocument, data){
		if (!ownerDocument) {
				ownerDocument = document;
		}
		if(supportsUnknownElements){
				return ownerDocument.createDocumentFragment();
		}
		data = data || getExpandoData(ownerDocument);
		var clone = data.frag.cloneNode(),
				i = 0,
				elems = getElements(),
				l = elems.length;
		for(;i<l;i++){
				clone.createElement(elems[i]);
		}
		return clone;
	}

	/**
	 * Shivs the `createElement` and `createDocumentFragment` methods of the document.
	 * @private
	 * @param {Document|DocumentFragment} ownerDocument The document.
	 * @param {Object} data of the document.
	 */
	function shivMethods(ownerDocument, data) {
		if (!data.cache) {
				data.cache = {};
				data.createElem = ownerDocument.createElement;
				data.createFrag = ownerDocument.createDocumentFragment;
				data.frag = data.createFrag();
		}


		ownerDocument.createElement = function(nodeName) {
			//abort shiv
			if (!html5.shivMethods) {
					return data.createElem(nodeName);
			}
			return createElement(nodeName, ownerDocument, data);
		};

		ownerDocument.createDocumentFragment = Function('h,f', 'return function(){' +
			'var n=f.cloneNode(),c=n.createElement;' +
			'h.shivMethods&&(' +
				// unroll the `createElement` calls
				getElements().join().replace(/[\w\-:]+/g, function(nodeName) {
					data.createElem(nodeName);
					data.frag.createElement(nodeName);
					return 'c("' + nodeName + '")';
				}) +
			');return n}'
		)(html5, data.frag);
	}

	/*--------------------------------------------------------------------------*/

	/**
	 * Shivs the given document.
	 * @memberOf html5
	 * @param {Document} ownerDocument The document to shiv.
	 * @returns {Document} The shived document.
	 */
	function shivDocument(ownerDocument) {
		if (!ownerDocument) {
				ownerDocument = document;
		}
		var data = getExpandoData(ownerDocument);

		if (html5.shivCSS && !supportsHtml5Styles && !data.hasCSS) {
			data.hasCSS = !!addStyleSheet(ownerDocument,
				// corrects block display not defined in IE6/7/8/9
				'article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}' +
				// adds styling not present in IE6/7/8/9
				'mark{background:#FF0;color:#000}' +
				// hides non-rendered elements
				'template{display:none}'
			);
		}
		if (!supportsUnknownElements) {
			shivMethods(ownerDocument, data);
		}
		return ownerDocument;
	}

	/*--------------------------------------------------------------------------*/

	/**
	 * The `html5` object is exposed so that more elements can be shived and
	 * existing shiving can be detected on iframes.
	 * @type Object
	 * @example
	 *
	 * // options can be changed before the script is included
	 * html5 = { 'elements': 'mark section', 'shivCSS': false, 'shivMethods': false };
	 */
	var html5 = {

		/**
		 * An array or space separated string of node names of the elements to shiv.
		 * @memberOf html5
		 * @type Array|String
		 */
		'elements': options.elements || 'abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video',

		/**
		 * current version of html5shiv
		 */
		'version': version,

		/**
		 * A flag to indicate that the HTML5 style sheet should be inserted.
		 * @memberOf html5
		 * @type Boolean
		 */
		'shivCSS': (options.shivCSS !== false),

		/**
		 * Is equal to true if a browser supports creating unknown/HTML5 elements
		 * @memberOf html5
		 * @type boolean
		 */
		'supportsUnknownElements': supportsUnknownElements,

		/**
		 * A flag to indicate that the document's `createElement` and `createDocumentFragment`
		 * methods should be overwritten.
		 * @memberOf html5
		 * @type Boolean
		 */
		'shivMethods': (options.shivMethods !== false),

		/**
		 * A string to describe the type of `html5` object ("default" or "default print").
		 * @memberOf html5
		 * @type String
		 */
		'type': 'default',

		// shivs the document according to the specified `html5` object options
		'shivDocument': shivDocument,

		//creates a shived element
		createElement: createElement,

		//creates a shived documentFragment
		createDocumentFragment: createDocumentFragment,

		//extends list of elements
		addElements: addElements
	};

	/*--------------------------------------------------------------------------*/

	// expose html5
	window.html5 = html5;

	// shiv the document
	shivDocument(document);

	if(typeof module == 'object' && module.exports){
		module.exports = html5;
	}

}(typeof window !== "undefined" ? window : this, document));