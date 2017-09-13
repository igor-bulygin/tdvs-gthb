<?php

return [
	'angular_datepicker' => "yyyy-MM-dd",
	'php_fmt_date' => "Y-m-d",
	'php_fmt_datatime' => "Y-m-d H:i:s",

	// Stripe
	'stripe_client_id' => !YII_ENV_PROD ? 'ca_9z47cPhqGcOPdRgTMEOXnF3hc7Cwf59g' : 'ca_9z47mImRRJvWUMSbMpJGpQzh7aUCyjgd',
	'stripe_publishable_key' => !YII_ENV_PROD  ? 'pk_test_p1DPyiicE2IerEV676oj5t89' : 'pk_live_gzc7Ew2CBNsTw0bdeZhvSpj7',
	'stripe_secret_key' => !YII_ENV_PROD  ? 'sk_test_eLdJxVmKSGQxGPhX2bqpoRk4' : 'sk_live_1iZUiFADhvbdRitBZQTFSsCG',

	// Instagram
	'instagram_client_id' => '7cdaa91e4b27429a867e9e142d114b65',
	'instagram_secret_key' => !YII_ENV_PROD  ? 'f25dcb37b4594b61a5c50de7c42c3906' : 'secret_prod',

	'default_todevise_fee' => 0.04,
	'admin_email' => 'info@todevise.com',
	'from_email' => 'info@todevise.com',
];
