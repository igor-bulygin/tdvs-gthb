<?php
namespace app\helpers;

use Yii;
use yii\helpers\Url;

class StripeHelper
{
	public static function getAuthorizeUrl() {
		Yii::info('Stripe getAuthorizeUrl', __METHOD__);

		$authorize_request_body = [
			'response_type' => 'code',
			'scope' => 'read_write',
			'client_id' => \Yii::$app->params['stripe_client_id'],
			'redirect_uri' =>  Url::base(true).'/stripe/connect-back',
		];

		$url = 'https://connect.stripe.com/oauth/authorize' . '?' . http_build_query($authorize_request_body);

		Yii::info('Stripe getAuthorizeUrl response: '.$url, __METHOD__);
		return $url;
	}

	public static function disconnectUserId($stripeUserId) {
		Yii::info('Stripe disconnectUserId: '.$stripeUserId, __METHOD__);

		$apiKey = \Yii::$app->params['stripe_secret_key'];
		$clientId = \Yii::$app->params['stripe_client_id'];

		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://connect.stripe.com/oauth/deauthorize',
			CURLOPT_POST => 1,
			CURLOPT_USERPWD => $apiKey . ':',
			CURLOPT_POSTFIELDS => http_build_query([
				'client_id' => $clientId,
				'stripe_user_id' => $stripeUserId,
			])
		]);
		$resp = curl_exec($curl);
		curl_close($curl);

		Yii::info('Stripe disconnectUserId response: '.json_encode($resp), __METHOD__);
		return $resp;
	}

	public static function validateAuthorizationToken($code) {

		$token_request_body = [
			'grant_type' => 'authorization_code',
			'client_id' => Yii::$app->params['stripe_client_id'],
			'code' => $code,
			'client_secret' => Yii::$app->params['stripe_secret_key'],
		];

		Yii::info('Stripe validateAuthorizationToken: '.print_r($token_request_body, true), __METHOD__);

		$req = curl_init('https://connect.stripe.com/oauth/token');
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_POST, true);
		curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

		// TODO: Additional error handling
		$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
		$resp = json_decode(curl_exec($req), true);
		curl_close($req);

		Yii::info('Stripe validateAuthorizationToken response: '.json_encode($resp), __METHOD__);
		return $resp;
	}
}
