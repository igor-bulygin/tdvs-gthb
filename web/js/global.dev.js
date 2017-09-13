function currentHost() {
	return window.location.origin;
}

function currentURL() {
	return currentHost() + window.location.pathname;
}

function getStripeApiKey() {
	return 'pk_test_p1DPyiicE2IerEV676oj5t89';
}