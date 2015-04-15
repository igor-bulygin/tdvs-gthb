<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class PersonCredentials extends Model {
	public $email, $password, $salt, $auth_key;
}

class PersonPersonal_Info extends Model {
	public $name, $surnames, $bdate;
}

class PersonPreferences extends Model {
	public $language, $currency;
}

class Person extends CActiveRecord implements IdentityInterface {

	const TYPE_ADMIN = 'admin';
	const TYPE_CLIENT = 'client';
	const TYPE_DEVISER = 'deviser';

	//public $accessToken;

	public function embedCredentialsModel() {
		return $this->mapEmbedded("credentials", PersonCredentials::className());
	}

	public function embedPersonal_InfoModel() {
		return $this->mapEmbedded("personal_info", PersonPersonal_Info::className());
	}

	public function embedPreferencesModel() {
		return $this->mapEmbedded("preferences", PersonPreferences::className());
	}

	public static function collectionName() {
		return 'person';
	}


	public function attributes() {
		return [
			'_id',
			'short_id',
			'type',
			'personal_info',
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
		return $this->credentialsModel->auth_key;
	}

	public function validateAuthKey($auth_key) {
		return $this->getAuthKey() === $auth_key;
	}

	public function validatePassword($password) {
		return $this->credentialsModel->password === bin2hex(Yii::$app->Scrypt->calc($password, $this->credentialsModel->salt, 8, 8, 16, 32));
	}

	public function beforeSave($insert) {
		if ($this->credentialsModel->auth_key === null) {
			$this->credentialsModel->auth_key = Yii::$app->getSecurity()->generateRandomString(128);
		}

		return parent::beforeSave($insert);
	}

	public function setPassword($password) {
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$password = bin2hex(Yii::$app->Scrypt->calc($password, $salt, 8, 8, 16, 32));
		$this->credentialsModel->salt = $salt;
		$this->credentialsModel->password = $password;
	}

	public function setLanguage($lang) {
		$this->preferencesModel->lang = $lang;
	}
}