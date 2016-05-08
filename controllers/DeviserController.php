<?php
namespace app\controllers;

use Yii;
use app\models\Tag;
use app\models\Lang;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Person;
use app\models\Country;
use app\models\Product;
use app\models\Category;
use app\models\SizeChart;
use app\models\MetricType;
use yii\filters\VerbFilter;
use app\helpers\CController;
use yii\filters\AccessControl;

class DeviserController extends CController {
	public $defaultAction = "index";

	public function actionIndex() {
		return $this->render("index");
	}

	public function actionEditInfo($slug) {
		$countries = Country::find()->asArray()->all();
		$deviser = Person::find()->where(["slug" => $slug, "type" => ['$in' => [Person::DEVISER]]])->asArray()->one();

		return $this->render("edit-info", [
			"deviser" => $deviser,
			"slug" => $slug,
			'categories' => Category::find()->asArray()->all(),
			"countries" => $countries
		]);
	}

	public function actionEditWork($short_id) {
		$countries = Country::find()->select(["_id" => 0, "country_name.$this->lang", "country_name.$this->lang_en", "country_code", "continent"])->asArray()->all();
		$countries_lookup = [];
		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::getValue($country["country_name"], $this->lang, $this->lang_en);
		}
		foreach(Country::CONTINENTS as $code => $continent) {
			$countries_lookup[$code] = Yii::t("app/admin", $continent);
		}

		$product = Product::find()->where(["short_id" => $short_id])->asArray()->all();
		$deviser = Person::find()->where(["short_id" => $product[0]["deviser_id"]])->asArray()->one();
		return $this->render("edit-work", [
			"deviser" => $deviser,
			"product" => $product[0],
			"tags" => Tag::find()->select(["_id" => 0, "short_id", "enabled", "n_options", "required", "stock_and_price", "type", "name.$this->lang", "name.$this->lang_en", "description.$this->lang", "description.$this->lang_en", "categories", "options"])->asArray()->all(),
			'categories' => Category::find()->select(["_id" => 0, "short_id", "path", "name.$this->lang", "name.$this->lang_en", "sizecharts", "prints"])->asArray()->all(),
			"countries" => $countries,
			"countries_lookup" => $countries_lookup,
			"sizecharts" => SizeChart::find()->select(["type" => SizeChart::TODEVISE])->asArray()->all(),
			"deviser_sizecharts" => SizeChart::find()->select(["type" => SizeChart::DEVISER, "deviser_id" => $deviser["short_id"]])->asArray()->all(),
			"mus" => [
				[
					"text" => Yii::t("app/admin", MetricType::TXT[MetricType::NONE]),
					"sub" => []
				],
				[
					"text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE]),
					"sub" => array_map(function($x) { return ["text" => $x, "value" => $x]; }, MetricType::UNITS[MetricType::SIZE])
				],
				[
					"text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT]),
					"sub" => array_map(function($x) { return ["text" => $x, "value" => $x]; }, MetricType::UNITS[MetricType::WEIGHT])
				]
			]
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

	public function actionUploadProductPhoto($slug, $short_id) {
		/* @var $product \app\models\Product */
		$product = Product::findOne(["short_id" => $short_id]);

		$data = Yii::$app->request->getBodyParam("data", null);
		if($data === null) return;

		$data = Json::decode($data);
		$product_path = Utils::join_paths(Yii::getAlias("@product"), $short_id);

		//If we passed a name, make sure to override the existing file
		$data["name"] = $data["name"] === "" ? null : $data["name"];
		$res = $this->savePostedFile($product_path, $data["name"]);

		if($res !== false) {
			$media = $product->media;
			$media["photos"][] = [
				"name" => $res,
				"tags" => $data["tags"]
			];
			$product->media = $media;
			$product->save();

			return $product;
		}
	}

	public function actionDeleteProductPhoto($slug, $short_id) {
		/* @var $product \app\models\Product */
		$product = Product::findOne(["short_id" => $short_id]);
		$product_path = Utils::join_paths(Yii::getAlias("@product"), $product->short_id);
		$photo_name = $this->getJsonFromRequest("photo_name");

		@unlink(Utils::join_paths($product_path, $photo_name));

		$media = $product->media;
		$media["photos"] = array_values(array_filter($media["photos"], function($photo) use ($photo_name) {
			return $photo["name"] !== $photo_name;
		}));
		$product->media = $media;
		$product->save();

		return $product;
	}
}
