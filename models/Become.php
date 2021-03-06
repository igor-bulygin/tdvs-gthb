<?php
namespace app\models;

use app\helpers\CActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * Class Become
 * @deprecated unused collection
 * @package app\models
 */
class Become extends CActiveRecord implements IdentityInterface
{

	const ADMIN = 0;
	const CLIENT = 1;
	const DEVISER = 2;
	const COLLABORATOR = 3;

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];

	//public $accessToken;

	public static function collectionName() {
		return 'become';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'name',
			'email',
			'brand',
			'phone',
			'create',
			'portfolio',
			'video',
			'observations'
		];
	}

	public function rules()
	{
		return [
			[['name', 'email', 'create', 'portfolio', 'video'], 'required']
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
		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->categories == null) {
			$this["categories"] = [];
		}

		if($this->collections == null) {
			$this["collections"] = [];
		}

		if($this->type == null) {
			$this["type"] = [];
		}

		if($this->personal_info == null) {
			$this["personal_info"] = [
				"country" => "",
				"city" => ""
			];
		}

		if($this->media == null) {
			$this["media"] = [
				"videos_links" => [],
				"photos" => []
			];
		}

		if($this->credentials == null) {
			$this["credentials"] = [];
		}

		if($this->preferences == null) {
			$this["preferences"] = [];
		}

		if (!array_key_exists("auth_key", $this->credentials) || $this->credentials["auth_key"] === null) {
			$this->credentials = array_merge_recursive($this->credentials, [
				"auth_key" => Yii::$app->getSecurity()->generateRandomString(128)
			]);
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
