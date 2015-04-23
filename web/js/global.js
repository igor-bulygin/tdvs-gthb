/**
 * This function will add (or replace it's value if it exists)
 * all the query parameters in the current URL and it will return an object.
 * @param params Object
 * @returns {object}
 */
function addQueryParams(params) {
	var query = window.location.search.substr(1);
	var params = $.merge(params, $.deparam(query));
	delete params['length'];
	return params;
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
	return window.location.origin + window.location.pathname;
}

/**
 * This function will return a URL suitable for goToQuery().
 * It will contain the output of currentURL() appended with
 * all the parameters from $params. This function won't append
 * any existing parameters. If you want that, pass 'true' as a
 * second parameter to this function.
 * @param params Object of parameters to append to the current URL.
 * @param append Boolean, true will append all the existing parameters too.
 * @returns {string}
 */
function urlToQuery(params, append) {
	params = append === true ? addQueryParams(params) : params;
	return currentURL() + "?" + $.param(params);
}

/**
 * This function will get the current URL (excluding GET parameters)
 * and then it will append all the parameters from $params object
 * @param params Object
 */
function goToQuery(params) {
	window.location.href = urlToQuery(params);
}