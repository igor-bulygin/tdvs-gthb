<?php
namespace app\models;

use app\helpers\Currency as C;

class Currency {
	const CURRENCIES = [
		[
			"symbol" => "€",
			"text" => "EUR",
			"value" => C::_EUR
		],
		[
			"symbol" => "$",
			"text" => "USD",
			"value" => C::_USD
		],
		[
			"symbol" => "¥",
			"text" => "JPY",
			"value" => C::_JPY
		],
		[
			"symbol" => "лв",
			"text" => "BGN",
			"value" => C::_BGN
		],
		[
			"symbol" => "Kč",
			"text" => "CZK",
			"value" => C::_CZK
		],
		[
			"symbol" => "kr",
			"text" => "DKK",
			"value" => C::_DKK
		],
		[
			"symbol" => "£",
			"text" => "GBP",
			"value" => C::_GBP
		],
		[
			"symbol" => "Ft",
			"text" => "HUF",
			"value" => C::_HUF
		],
		[
			"symbol" => "zł",
			"text" => "PLN",
			"value" => C::_PLN
		],
		[
			"symbol" => "lei",
			"text" => "RON",
			"value" => C::_RON
		],
		[
			"symbol" => "kr",
			"text" => "SEK",
			"value" => C::_SEK
		],
		[
			"symbol" => "CHF",
			"text" => "CHF",
			"value" => C::_CHF
		],
		[
			"symbol" => "kr",
			"text" => "NOK",
			"value" => C::_NOK
		],
		[
			"symbol" => "kn",
			"text" => "HRK",
			"value" => C::_HRK
		],
		[
			"symbol" => "руб",
			"text" => "RUB",
			"value" => C::_RUB
		],
		[
			"symbol" => "YTL",
			"text" => "TRY",
			"value" => C::_TRY
		],
		[
			"symbol" => "$",
			"text" => "AUD",
			"value" => C::_AUD
		],
		[
			"symbol" => "R$",
			"text" => "BRL",
			"value" => C::_BRL
		],
		[
			"symbol" => "$",
			"text" => "CAD",
			"value" => C::_CAD
		],
		[
			"symbol" => "元",
			"text" => "CNY",
			"value" => C::_CNY
		],
		[
			"symbol" => "元",
			"text" => "HKD",
			"value" => C::_HKD
		],
		[
			"symbol" => "Rp",
			"text" => "IDR",
			"value" => C::_IDR
		],
		[
			"symbol" => "₪",
			"text" => "ILS",
			"value" => C::_ILS
		],
		[
			"symbol" => "₹",
			"text" => "INR",
			"value" => C::_INR
		],
		[
			"symbol" => "₩",
			"text" => "KRW",
			"value" => C::_KRW
		],
		[
			"symbol" => "$",
			"text" => "MXN",
			"value" => C::_MXN
		],
		[
			"symbol" => "RM",
			"text" => "MYR",
			"value" => C::_MYR
		],
		[
			"symbol" => "$",
			"text" => "NZD",
			"value" => C::_NZD
		],
		[
			"symbol" => "Ph",
			"text" => "PHP",
			"value" => C::_PHP
		],
		[
			"symbol" => "$",
			"text" => "SGD",
			"value" => C::_SGD
		],
		[
			"symbol" => "฿",
			"text" => "THB",
			"value" => C::_THB
		],
		[
			"symbol" => "R",
			"text" => "ZAR",
			"value" => C::_ZAR
		]
	];

	public static function getSerialized()
	{
		return static::CURRENCIES;
	}
}
