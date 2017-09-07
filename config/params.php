<?php

return [
	'angular_datepicker' => "yyyy-MM-dd",
	'php_fmt_date' => "Y-m-d",
	'php_fmt_datatime' => "Y-m-d H:i:s",

	// Stripe
	'stripe_client_id' => 'ca_9z47cPhqGcOPdRgTMEOXnF3hc7Cwf59g',
	'stripe_secret_key' => !YII_ENV_PROD  ? 'sk_test_eLdJxVmKSGQxGPhX2bqpoRk4' : '',
	'stripe_publishable_key' => !YII_ENV_PROD  ? 'pk_test_p1DPyiicE2IerEV676oj5t89' : '',

	// Instagram
	'instagram_client_id' => 'client_id',
	'instagram_secret_key' => !YII_ENV_PROD  ? 'secret_dev' : 'secret_prod',

	'default_todevise_fee' => 0.04,
	'admin_email' => 'info@todevise.com',
	'from_email' => 'info@todevise.com',
];
