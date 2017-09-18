<?php

defined('STRIPE_LIVE_MODE') or define('STRIPE_LIVE_MODE', false);

return [
	'angular_datepicker' => "yyyy-MM-dd",
	'php_fmt_date' => "Y-m-d",
	'php_fmt_datatime' => "Y-m-d H:i:s",

	// Stripe
	// stripe in live mode in production and beta
	'stripe_client_id' => STRIPE_LIVE_MODE ? 'ca_9z47mImRRJvWUMSbMpJGpQzh7aUCyjgd' : 'ca_9z47cPhqGcOPdRgTMEOXnF3hc7Cwf59g',
	'stripe_publishable_key' => STRIPE_LIVE_MODE ? 'pk_live_gzc7Ew2CBNsTw0bdeZhvSpj7' : 'pk_test_p1DPyiicE2IerEV676oj5t89',
	'stripe_secret_key' => STRIPE_LIVE_MODE ? 'sk_live_1iZUiFADhvbdRitBZQTFSsCG' : 'sk_test_eLdJxVmKSGQxGPhX2bqpoRk4',

	// Instagram
	'instagram_client_id' => '7cdaa91e4b27429a867e9e142d114b65',
	'instagram_secret_key' => !YII_ENV_PROD  ? 'f25dcb37b4594b61a5c50de7c42c3906' : 'secret_prod',

	'default_todevise_fee' => 0.04,
	'admin_email' => 'info@todevise.com',
	'from_email' => 'info@todevise.com',
];
