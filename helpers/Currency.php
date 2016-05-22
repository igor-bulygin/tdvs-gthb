<?php
namespace app\helpers;

use Yii;
use app\models\Currency as Cur;

class Currency {
	const _EUR = "EUR"; //Euros

	const _USD = "USD";
	const _JPY = "JPY";
	const _BGN = "BGN";
	const _CZK = "CZK";
	const _DKK = "DKK";
	const _GBP = "GBP";
	const _HUF = "HUF";
	const _PLN = "PLN";
	const _RON = "RON";
	const _SEK = "SEK";
	const _CHF = "CHF";
	const _NOK = "NOK";
	const _HRK = "HRK";
	const _RUB = "RUB";
	const _TRY = "TRY";
	const _AUD = "AUD";
	const _BRL = "BRL";
	const _CAD = "CAD";
	const _CNY = "CNY";
	const _HKD = "HKD";
	const _IDR = "IDR";
	const _ILS = "ILS";
	const _INR = "INR";
	const _KRW = "KRW";
	const _MXN = "MXN";
	const _MYR = "MYR";
	const _NZD = "NZD";
	const _PHP = "PHP";
	const _SGD = "SGD";
	const _THB = "THB";
	const _ZAR = "ZAR";

	private $currency;
	private $amount;

	/**
	 * @param $currency
	 * @param $amount
	 */
	function __construct ($currency, $amount) {
		$cache = Yii::$app->cache;
		$yesterday = date(Yii::$app->params["php_fmt_date"], time() - (24 * 60 * 60));

		if ($cache->get($yesterday) === false) {
			$exchange = $this->initCurrencyExchangeData();
			if ($exchange !== false) {
				$cache->set($exchange["time"], $exchange["exchange"]);
			}
		}

		$this->currency = $currency;
		$this->amount = $amount;
	}

	private function initCurrencyExchangeData () {
		$xml_result = simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
		if ($xml_result === false) {
			$exchange = $xml_result;
		} else {
			$exchange = $xml_result->Cube->Cube;

			$values = [];
			foreach($exchange->Cube as $n) {
				$values[(string)$n->attributes()->currency] = (float)$n->attributes()->rate;
			}

			$exchange = [
				"time" => (string)$exchange->attributes()->time,
				"exchange" => $values
			];
		}
		return $exchange;
	}

	private function isValidCurrency ($currency) {
		$cache = Yii::$app->cache;
		$yesterday = date(Yii::$app->params["php_fmt_date"], time() - (24 * 60 * 60));
		$exchange = $cache->get($yesterday);
		$exchange = $exchange === false ? [] : $exchange;
		return in_array($currency, array_keys($exchange));
	}

	/**
	 * Convert given amount of $from_currency to $to_currency, returning the result
	 * @param $to_currency
	 * @returns float
	 */
	public function convert($to_currency) {
		if (!$this->isValidCurrency($to_currency) && $to_currency !== Currency::_EUR) {
			return [
				"amount" => $this->amount,
				"currency" => $this->currency
			];
		}

		$cache = Yii::$app->cache;
		$yesterday = date(Yii::$app->params["php_fmt_date"], time() - (24 * 60 * 60));
		$exchange = $cache->get($yesterday);

		if ($this->currency === $to_currency) {
			$amount = round($this->amount, 2);
		} else if ($to_currency === Currency::_EUR) {
			$amount = round($this->amount / $exchange[$this->currency], 2);
		} else if ($this->currency === Currency::_EUR) {
			$amount = round($this->amount * $exchange[$to_currency], 2);
		} else {
			$amount_in_eur = $this->amount / $exchange[$this->currency];
			$amount = round($amount_in_eur * $exchange[$to_currency], 2);
		}

		return [
			"amount" => $amount,
			"currency" => $to_currency
		];
	}

	public static function getSymbol($text) {
		foreach (Cur::CURRENCIES as $key => $value) {
			if ($value['text'] === $text) {
				return $value['symbol'];
			}
		}
	}
}
