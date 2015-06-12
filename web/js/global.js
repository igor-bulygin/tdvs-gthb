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