/**
 * This function will add (or replace the value if the key exists)
 * all the query parameters from the current URL to an object, merge it
 * with the passed object and return the result.
 * @param params Object
 * @returns {object}
 */
function addQueryParams(params) {
	var query = window.location.search.substr(1);
	var new_params = {};
	$.extend(true, new_params, $.deparam(query), params);
	return new_params;
}

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
 * This function will take an object (can be nested too) and convert it
 * to a suitable string for a parameter in a GET petition.
 * @param obj
 * @returns {string}
 */
function objectToQueryParam(obj) {
	var json = JSON.stringify(obj) || JSON.stringify({});
	return encodeURIComponent(json);
}

/**
 * This function will get the current URL (excluding GET parameters)
 * and then it will append all the parameters from $params object
 * @param params Object of parameters to append to the current URL.
 * @param append Boolean, true will append all the existing parameters too.
 */
function goToQuery(params, append) {
	window.location.href = urlToQuery(params, append);
}