<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * @property mixed _id
 * @property string slug
 * @property mixed type
 * @property array categories
 * @property array collections
 * @property array personal_info
 * @property array media
 * @property array credentials
 * @property array preferences
 */
class Person extends CActiveRecord implements IdentityInterface {

	const ADMIN = 0;
	const CLIENT = 1;
	const DEVISER = 2;
	const COLLABORATOR = 3;

    const SCENARIO_DEVISER_PROFILE_UPDATE = 'deviser-profile-update';
    const SCENARIO_USER_PROFILE_UPDATE = 'user-profile-update';
    const SCENARIO_TREND_SETTER_PROFILE_UPDATE = 'trend-setter-profile-update';

	//public $accessToken;

	public static function collectionName() {
		return 'person';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'text_short_description',
			'slug',
			'type',
			'categories',
			'collections',
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

    public function rules()
    {
        return [
            // the name, email, subject and body attributes are required
            [['slug'], 'required'],
            [['text_short_description'], 'required', 'on' => [self::SCENARIO_DEVISER_PROFILE_UPDATE]],

            // the email attribute should be a valid email address
//            ['email', 'email'],
        ];
    }
}
