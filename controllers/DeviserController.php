<?php
namespace app\controllers;

use Yii;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Person;
use yii\filters\VerbFilter;
use app\helpers\CController;
use yii\filters\AccessControl;

class DeviserController extends CController {
	public $defaultAction = "index";

	public function actionIndex() {
		return $this->render("index");
	}

	public function actionEditInfo($slug) {
		$countries = $this->api->actionCountries()->asArray()->all();
		$countries_lookup = [];
		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = $country["country_name"][Yii::$app->language];
		}

		$deviser = $this->api->actionDevisers(Json::encode(["slug" => $slug]))->asArray()->all();
		return $this->render("edit-info", [
			"deviser" => $deviser[0],
			"slug" => $slug,
			'categories' => $this->api->actionCategories()->asArray()->all(),
			"countries" => $countries,
			"countries_lookup" => $countries_lookup
		]);
	}

	public function actionUploadHeaderPhoto($slug) {
		/* @var $deviser \app\models\Person */
		$deviser = Person::findOne(["slug" => $slug]);
		$deviser_path = Utils::join_paths(Yii::getAlias("@deviser"), $deviser->short_id);

		$res = $this->savePostedFile($deviser_path, "header");
		if($res !== false) {
			//Delete the old header picture if it didn't get overridden by the new one
			if(isset($deviser["media"]["header"]) && strcmp($deviser["media"]["header"], $res) !== 0) {
				@unlink(Utils::join_paths($deviser_path, $deviser["media"]["header"]));
			}

			$deviser->media = array_replace_recursive($deviser->media, [
				"header" => $res
			]);
			$deviser->save();

			return $deviser;
		}
	}

	public function actionUploadProfilePhoto($slug) {
		/* @var $deviser \app\models\Person */
		$deviser = Person::findOne(["slug" => $slug]);
		$deviser_path = Utils::join_paths(Yii::getAlias("@deviser"), $deviser->short_id);

		$res = $this->savePostedFile($deviser_path, "profile");
		if($res !== false) {
			//Delete the old profile picture if it didn't get overridden by the new one
			if(isset($deviser["media"]["profile"]) && strcmp($deviser["media"]["profile"], $res) !== 0) {
				@unlink(Utils::join_paths($deviser_path, $deviser["media"]["profile"]));
			}

			$deviser->media = array_replace_recursive($deviser->media, [
				"profile" => $res
			]);
			$deviser->save();

			return $deviser;
		}
	}
}
