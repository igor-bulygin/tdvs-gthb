console.log("js/global.js loaded");
var global = angular.module('global', []);
var todevise = angular.module('todevise', ['header']);

/**
 * Iterate over a list of properties of an object and if any of those is an empty array, convert it to an object.
 * @param obj
 * @param props
 */
global.arrayToObject = function(obj, props) {
	angular.forEach(props, function(prop) {
		if(obj.hasOwnProperty(prop) && angular.isArray(obj[prop]) && obj[prop].length === 0) obj[prop] = {};
	});
};

/**
 * Get a value of an object by a key, or get another value using a default key.
 * @param obj
 * @param key
 * @param default_key
 * @returns {*}
 */
global.getValue = function(obj, key, default_key) {
	return obj.hasOwnProperty(key) ? obj[key] : obj[default_key];
};

/**
 * Return the current host.
 */
function currentHost() {
	return window.location.origin;
}

/**
 * Return the current URL without query parameters.
 * This will return the current protocol, followed by the hostname
 * and the pathname.
 * If you are at 'http://www.example.com/foo/bar/?q=1&p=2', this
 * function will return 'http://www.example.com/foo/bar/'
 * @returns {string}
 */
function currentURL() {
	return currentHost() + window.location.pathname;
}

var aus = {
	/**
	 * Internal objects that hold the current state (url)
	 */
	_state: {},
	_shadowURL: "",

	/**
	 * Get the value of the parameter "key". NULL will be returned
	 * if the parameter doesn't exist or if it can't be parsed.
	 * If "decode" is true, the value will be JSON parsed.
	 * @param key
	 * @param decode
	 * @returns {*}
	 */
	get: function(key, decode) {
		if(!this._state.hasOwnProperty(key)) {
			return null;
		}

		var _v = decodeURIComponent(this._state[key]);

		// Parse the value as this is a JSON encoded object
		if(decode === true) {
			try {
				_v = JSON.parse(_v)
			} catch(e) {
				_v = null;
			}
		}

		return _v;

	},

	_objToStr: function(value) {
		var _v = JSON.stringify(value);
		return encodeURIComponent(_v);
	},

	/**
	 * Set the value of the parameter "key". If "encode" is true,
	 * the value will be JSON stringified. If "extend" is true,
	 * the passed value will be appended to the existing value
	 * via $.extend(); (recursive extend is called).
	 * @param key
	 * @param value
	 * @param encode
	 * @param extend
	 */
	set: function(key, value, encode, extend) {
		var _v;

		if(encode === true) {
			if(extend === true) {
				_v = this.get(key, true) || {};
				$.extend(true, _v, value);
			} else {
				_v = value;
			}

			_v = JSON.stringify(_v);
		} else {
			_v = value;
		}

		this._state[key] = encodeURIComponent(_v);

		return this;
	},

	/**
	 * Remove the parameter "key".
	 * @param key
	 */
	remove: function(key) {
		if(this._state.hasOwnProperty(key)) {
			delete this._state[key];
		}

		return this;
	},

	/**
	 * Read the current URL and fill the internal state objects with it.
	 */
	syncFromURL: function() {
		this._path = window.location.pathname;
		this._query = window.location.search.substr(1);
		this._state = {};

		var _params = this._query.split("&");
		for(var i=0; i < _params.length; i++) {
			var _obj = _params[i].split("=");
			if(_obj.length === 2) {
				this._state[_obj[0]] = _obj[1];
			}
		}

		return this;
	},

	/**
	 * Transform the current internal state to a URL form and apply it
	 * via pushState (HTML5 browsers). This will return the applied URL.
	 * @returns {string}
	 */
	syncToURL: function() {
		var _url = this._path + "?";

		for (var key in this._state) {
			if(this._state.hasOwnProperty(key)) {
				_url += key + "=" + this._state[key] + "&";
			}
		}
		_url = _url.substr(0, _url.length - 1);

		if(this._shadowURL !== _url) {
			history.pushState(null, null, _url);
			this._shadowURL = _url;
		}

		return _url;
	}
};
aus.syncFromURL();

/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 */
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean")
{i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k()
{h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid)
{g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);
