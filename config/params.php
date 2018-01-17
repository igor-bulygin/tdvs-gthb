<?php

return [
	'angular_datepicker' => "yyyy-MM-dd",
	'php_fmt_date' => "Y-m-d",
	'php_fmt_datatime' => "Y-m-d H:i:s",

	// Stripe
	'stripe_client_id' => YII_ENV_PROD ? 'ca_9z47mImRRJvWUMSbMpJGpQzh7aUCyjgd' : 'ca_9z47cPhqGcOPdRgTMEOXnF3hc7Cwf59g',
	'stripe_publishable_key' => YII_ENV_PROD ? 'pk_live_gzc7Ew2CBNsTw0bdeZhvSpj7' : 'pk_test_p1DPyiicE2IerEV676oj5t89',
	'stripe_secret_key' => YII_ENV_PROD ? 'sk_live_1iZUiFADhvbdRitBZQTFSsCG' : 'sk_test_eLdJxVmKSGQxGPhX2bqpoRk4',

	// Instagram
	'instagram_client_id' => '7cdaa91e4b27429a867e9e142d114b65',
	'instagram_secret_key' => YII_ENV_PROD  ? 'f25dcb37b4594b61a5c50de7c42c3906' : 'f25dcb37b4594b61a5c50de7c42c3906',

	// Mailchimp & Mandrill
	'mailchimp_api_key' => 'e0c28ae6c8351fc0736d0c352aad5a3f-us9',
	'mandrill_api_key' => YII_ENV_PROD ? 'L2ETh5H39FL7Is_cvLmV1A' : 'mbo3FMDEVLDbxXYpZomrng',

	'default_todevise_fee' => 0.145,
	'default_spain_vat' => 0.21,
	'admin_email' => 'info@todevise.com',
	'from_email' => 'info@todevise.com',

	'index_banners' => [
		['img' => '/imgs/banner-1.jpg', 'url' => '/deviser/isabel-de-pedro/80226c0/store', 'alt' => 'Isabel De Pedro', 'active' => true],
		['img' => '/imgs/banner-2.jpg', 'url' => '/deviser/vontrueba/329504s/store', 'alt' => 'Vontrueba', 'active' => false],
		['img' => '/imgs/banner-3.jpg', 'url' => '/deviser/retrospective-jewellery/facd773/store', 'alt' => 'Retrospective Jewellery', 'active' => false],
		['img' => '/imgs/banner-4.jpg', 'url' => '/deviser/acurrator/5c7020p/store', 'alt' => 'Acurrator', 'active' => false],
		['img' => '/imgs/banner-5.jpg', 'url' => '/deviser/vols-and-original/e23e0bv/store', 'alt' => 'Vols And Original', 'active' => false],
	],
];
