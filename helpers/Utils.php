<?php
namespace app\helpers;

use Yii;
use yii\helpers\Url;

class Utils
{
	/**
	 * Generate a random ID (YouTube-like) useful for slugs.
	 * @param int $length Desired length of ID
	 * @return string
	 */
	public static function shortID($length = 6)
	{
		$l = floor($length / 2);
		$e = $length % 2;
		$random = bin2hex(openssl_random_pseudo_bytes($l));
		if($e === 1){
			$random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
		}
		return $random;
	}

	/**
	 * Returns true if the current controller/action is equal to the passed URL.
	 * @param string URL to compare to. Example: 'admin/deviser', 'deviser/product', etc...
	 * @return bool
	 */
	public static function compareURL($url)
	{
		$queryParams = Yii::$app->request->queryParams;
		array_walk($queryParams, function(&$v, $k){
			$v = null;
		});

		return Url::current($queryParams) === Url::toRoute($url);
	}
}
