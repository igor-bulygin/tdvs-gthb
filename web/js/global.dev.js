function currentHost() {
	return window.location.origin;
}

function currentURL() {
	return currentHost() + window.location.pathname;
}