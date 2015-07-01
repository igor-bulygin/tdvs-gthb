<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class Person extends CActiveRecord implements IdentityInterface {

	const ADMIN = 0;
	const CLIENT = 1;
	const DEVISER = 2;
	const COLLABORATOR = 3;

	//public $accessToken;

	public static function collectionName() {
		return 'person';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'slug',
			'type',
			'personal_info',
			'media',
			'credentials',
			'preferences'
		];
	}

	public static function findIdentity($id) {
		return Person::findOne(['short_id' => $id]);
	}

	public static function findIdentityByAccessToken($token, $type = null) {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	public static function findByEmail($username) {
		return Person::findOne(['credentials.email' => $username]);
	}

	public function getId() {
		return $this->short_id;
	}

	public function getAuthKey() {
		return $this->credentials["auth_key"];
	}

	public function validateAuthKey($auth_key) {
		return $this->getAuthKey() === $auth_key;
	}

	public function validatePassword($password) {
		return $this->credentials["password"] === bin2hex(Yii::$app->Scrypt->calc($password, $this->credentials["salt"], 8, 8, 16, 32));
	}

	public function beforeSave($insert) {
		if (!array_key_exists("auth_key", $this->credentials) || $this->credentials["auth_key"] === null) {
			$this->credentials = array_merge_recursive($this->credentials, [
				"auth_key" => Yii::$app->getSecurity()->generateRandomString(128)
			]);
		}

		if($this->media === null) {
			$this->media = [];
		}

		return parent::beforeSave($insert);
	}

	public function setPassword($password) {
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$password = bin2hex(Yii::$app->Scrypt->calc($password, $salt, 8, 8, 16, 32));
		$this->credentials = array_merge_recursive($this->credentials, [
			"salt" => $salt,
			"password" => $password
		]);
	}

	public function setLanguage($lang) {
		$this->preferences = array_merge_recursive($this->preferences, [
			"lang" => $lang
		]);
	}
}