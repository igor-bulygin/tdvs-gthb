<?php
namespace app\helpers;

use Yii;
use yii\helpers\Url;

class InstagramHelper
{
	public static function getAuthorizeUrl() {
		Yii::info('Instagram getAuthorizeUrl', __METHOD__);

		$authorize_request_body = [
			'response_type' => 'code',
			'client_id' => \Yii::$app->params['instagram_client_id'],
			'redirect_uri' =>  Url::base(true).'/instagram/connect-back',
		];

		$url = 'https://api.instagram.com/oauth/authorize' . '?' . http_build_query($authorize_request_body);

		Yii::info('Instagram getAuthorizeUrl response: '.$url, __METHOD__);
		return $url;
	}

	public static function validateAuthorizationToken($code) {

		$token_request_body = [
			'client_secret' => Yii::$app->params['instagram_secret_key'],
			'grant_type' => 'authorization_code',
			'client_id' => Yii::$app->params['instagram_client_id'],
			'code' => $code,
			'redirect_uri' =>  Url::base(true).'/instagram/connect-back',
		];

		Yii::info('Instagram validateAuthorizationToken: '.print_r($token_request_body, true), __METHOD__);

		$req = curl_init('https://api.instagram.com/oauth/access_token');
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_POST, true);
		curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

		// TODO: Additional error handling
		$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
		$resp = json_decode(curl_exec($req), true);
		curl_close($req);

		Yii::info('Instagram validateAuthorizationToken response: '.json_encode($resp), __METHOD__);
		return $resp;
	}

	public static function getUserSelfMedia($accessToken)
	{
		$token_request_body = [
			'access_token' => $accessToken,
		];

		Yii::info('Instagram getUserSelfMedia: '.print_r($token_request_body, true), __METHOD__);

		$req = curl_init('https://api.instagram.com/v1/users/self/media/recent/');
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_POST, true);
		curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

		// TODO: Additional error handling
		$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
		$resp = json_decode(curl_exec($req), true);
		curl_close($req);

		Yii::info('Instagram getUserSelfMedia response: '.json_encode($resp), __METHOD__);
		return $resp;
	}

//	public static function disconnectUserId($instagramUserId) {
//		Yii::info('Instagram disconnectUserId: '.$instagramUserId, __METHOD__);
//
//		$apiKey = \Yii::$app->params['instagram_secret_key'];
//		$clientId = \Yii::$app->params['instagram_client_id'];
//
//		$curl = curl_init();
//		curl_setopt_array($curl, [
//			CURLOPT_RETURNTRANSFER => 1,
//			CURLOPT_URL => 'https://connect.instagram.com/oauth/deauthorize',
//			CURLOPT_POST => 1,
//			CURLOPT_USERPWD => $apiKey . ':',
//			CURLOPT_POSTFIELDS => http_build_query([
//				'client_id' => $clientId,
//				'instagram_user_id' => $instagramUserId,
//			])
//		]);
//		$resp = curl_exec($curl);
//		curl_close($curl);
//
//		Yii::info('Instagram disconnectUserId response: '.json_encode($resp), __METHOD__);
//		return $resp;
//	}
}
