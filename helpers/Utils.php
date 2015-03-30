<?php
namespace yii\helpers;
use Yii;

class Utils
{
	public static function shortID($length)
	{
		$l = floor($length / 2);
		$e = $length % 2;
		$random = bin2hex(openssl_random_pseudo_bytes($l));
		if($e === 1){
			$random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
		}
		return $random;
	}
}
