<?php
namespace app\controllers;

use Yii;
use app\models\Lang;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Person;
use app\models\Country;
use app\models\SizeChart;
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
			$countries_lookup[$country["country_code"]] = Utils::getValue($country["country_name"], Yii::$app->language, array_keys(Lang::EN_US)[0]);
		}

		$deviser = $this->api->actionDevisers(Json::encode(["slug" => $slug]))->asArray()->one();

		return $this->render("edit-info", [
			"deviser" => $deviser,
			"slug" => $slug,
			'categories' => $this->api->actionCategories()->asArray()->all(),
			"countries" => $countries,
			"countries_lookup" => $countries_lookup
		]);
	}

	public function actionEditWork($short_id) {
		$countries = $this->api->actionCountries()->asArray()->all();
		$countries_lookup = [];
		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::getValue($country["country_name"], Yii::$app->language, array_keys(Lang::EN_US)[0]);
		}
		foreach(Country::CONTINENTS as $code => $continent) {
			$countries_lookup[$code] = Yii::t("app/admin", $continent);
		}

		$product = $this->api->actionProducts(Json::encode(["short_id" => $short_id]))->asArray()->all();
		$deviser = $this->api->actionDevisers(Json::encode(["short_id" => $product[0]["deviser_id"]]))->asArray()->one();
		return $this->render("edit-work", [
			"deviser" => $deviser,
			"product" => $product[0],
			"tags" => $this->api->actionTags()->asArray()->all(),
			'categories' => $this->api->actionCategories()->asArray()->all(),
			"countries" => $countries,
			"countries_lookup" => $countries_lookup,
			"deviser_sizecharts" => $this->api->actionSizeCharts(Json::encode(["type" => SizeChart::DEVISER, "deviser_id" => $deviser["short_id"]]))->asArray()->all()
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

	public function actionUploadProductPhoto($short_id) {

	}
}
