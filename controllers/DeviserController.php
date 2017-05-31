<?php
namespace app\controllers;

use app\helpers\CController;
use app\helpers\Utils;
use app\models\Category;
use app\models\Country;
use app\models\MetricType;
use app\models\Person;
use app\models\Product;
use app\models\SizeChart;
use app\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * Class DeviserController
 * @package app\controllers
 * @deprecated
 */
class DeviserController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => [
								'about-edit', 'delete-product-photo', 'faq-edit', 'press-edit', 'store-edit', 'upload-header-photo', 'upload-product-photo', 'upload-profile-photo', 'videos-edit',
						],
						'rules' => [
								[
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
		];
	}


	/*********************************************************************************************************/
	/******************************* deprecated methods after this line **************************************/
	/*********************************************************************************************************/

	/**
	 * @param $slug
	 * @return string
	 * @deprecated
	 */
	public function actionEditInfo($slug)
	{
		$countries = Country::find()
			->select(["_id" => 0])
			->asArray()
			->all();
		Utils::l_collection($countries, "country_name");

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized(Yii::$app->request->get('deviser_id'))->toArray();
		$person["short_id"] = $person["id"];

		$categories = Category::find()
			->select(["_id" => 0])
			->where(["path" => "/"])
			->orderBy(["name.$this->lang" => -1])
			->asArray()
			->all();
		Utils::l_collection($categories, "name");

		return $this->render("edit-info", [
			"deviser" => $person,
			"slug" => $slug,
			'categories' => $categories,
			"countries" => $countries
		]);
	}

	/**
	 * @param $short_id
	 * @return string
	 * @deprecated
	 */
	public function actionEditWork($short_id)
	{
		$countries = Country::find()
			->select(["_id" => 0, "country_name.$this->lang", "country_name.$this->lang_en", "country_code", "continent"])
			->asArray()
			->all();

		$countries_lookup = [];
		foreach ($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::l($country["country_name"]);
		}
		foreach (Country::CONTINENTS as $code => $continent) {
			$countries_lookup[$code] = Yii::t("app/admin", $continent);
		}

		$product = Product::find()
			->select(["_id" => 0])
			->where(["short_id" => $short_id])
			->asArray()
			->all();

		$person = Person::find()
			->select(["_id" => 0])
			->where(["short_id" => $product[0]["deviser_id"]])
			->asArray()
			->one();

		$tags = Tag::find()
			->select(["_id" => 0, "short_id", "enabled", "n_options", "required", "stock_and_price", "type", "name.$this->lang", "name.$this->lang_en", "description.$this->lang", "description.$this->lang_en", "categories", "options"])
			->asArray()
			->all();
		Utils::l_collection($tags, "name");
		Utils::l_collection($tags, "description");
		foreach ($tags as $key => &$value) {
			Utils::l_collection($value['options'], "text");
		}

		$categories = Category::find()
			->select(["_id" => 0, "short_id", "path", "name.$this->lang", "name.$this->lang_en", "sizecharts", "prints"])
			->asArray()
			->all();
		Utils::l_collection($categories, "name");

		$sizechart = SizeChart::find()
			->select(["_id" => 0])
			->where(["type" => SizeChart::TODEVISE])
			->asArray()
			->all();
		Utils::l_collection($sizechart, "name");

		$deviser_sizecharts = SizeChart::find()
			->select(["_id" => 0])
			->where(["type" => SizeChart::DEVISER, "deviser_id" => $person["short_id"]])
			->asArray()
			->all();
		Utils::l_collection($deviser_sizecharts, "name");

		$mus = [
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::NONE]),
				"sub" => []
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE]),
				"sub" => array_map(function ($x) {
					return ["text" => $x, "value" => $x];
				}, MetricType::UNITS[MetricType::SIZE])
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT]),
				"sub" => array_map(function ($x) {
					return ["text" => $x, "value" => $x];
				}, MetricType::UNITS[MetricType::WEIGHT])
			]
		];

		return $this->render("edit-work", [
			"deviser" => $person,
			"product" => $product[0],
			"tags" => $tags,
			'categories' => $categories,
			"countries" => $countries,
			"countries_lookup" => $countries_lookup,
			"sizecharts" => $sizechart,
			"deviser_sizecharts" => $deviser_sizecharts,
			"mus" => $mus
		]);
	}

	/**
	 * @param $slug
	 *
	 * @return Person
	 * @deprecated
	 */
	public function actionUploadHeaderPhoto($slug)
	{
		/* @var $person \app\models\Person */
		$person = Person::findOne(["slug" => $slug]);
		$deviser_path = Utils::join_paths(Yii::getAlias("@deviser"), $person->short_id);

		$res = $this->savePostedFile($deviser_path, ("header." . uniqid()));
		if ($res !== false) {
			//Delete the old header picture if it didn't get overridden by the new one
//			if (isset($person["media"]["header"]) && strcmp($person["media"]["header"], $res) !== 0) {
//				@unlink(Utils::join_paths($deviser_path, $person["media"]["header"]));
//			}

			$person->mediaFiles->header = $res;
			$person->save(false);

			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_ADMIN);
			return $person;
		}
	}

	/**
	 * @param $slug
	 *
	 * @return Person
	 * @deprecated
	 */
	public function actionUploadProfilePhoto($slug)
	{
		/* @var $person \app\models\Person */
		$person = Person::findOne(["slug" => $slug]);
		$deviser_path = Utils::join_paths(Yii::getAlias("@deviser"), $person->short_id);

		$res = $this->savePostedFile($deviser_path, ("profile." . uniqid()));
		if ($res !== false) {
			//Delete the old profile picture if it didn't get overridden by the new one
//			if (isset($person["media"]["profile"]) && strcmp($person["media"]["profile"], $res) !== 0) {
//				@unlink(Utils::join_paths($deviser_path, $person["media"]["profile"]));
//			}

			$person->mediaFiles->profile = $res;

			$person->save(false);

			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_ADMIN);
			return $person;
		}
	}

	/**
	 * @param $slug
	 * @param $short_id
	 *
	 * @return \app\models\Product|void
	 * @deprecated
	 */
	public function actionUploadProductPhoto($slug, $short_id)
	{
		/* @var $product \app\models\Product */
		$product = Product::findOne(["short_id" => $short_id]);

		$data = Yii::$app->request->getBodyParam("data", null);
		if ($data === null) return;

		$data = Json::decode($data);
		$product_path = Utils::join_paths(Yii::getAlias("@product"), $short_id);

		//If we passed a name, make sure to override the existing file
		$data["name"] = $data["name"] === "" ? null : $data["name"];
		$res = $this->savePostedFile($product_path, $data["name"]);

		if ($res !== false) {
			$media = $product->media;
			$media["photos"][] = [
				"name" => $res,
				"tags" => $data["tags"]
			];
			$product->media = $media;
			$product->save();

			Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_ADMIN);
			return $product;
		}
	}

	/**
	 * @param $slug
	 * @param $short_id
	 *
	 * @return \app\models\Product
	 * @deprecated
	 */
	public function actionDeleteProductPhoto($slug, $short_id)
	{
		/* @var $product \app\models\Product */
		$product = Product::findOne(["short_id" => $short_id]);
		$product_path = Utils::join_paths(Yii::getAlias("@product"), $product->short_id);
		$photo_name = $this->getJsonFromRequest("photo_name");

		@unlink(Utils::join_paths($product_path, $photo_name));

		$media = $product->media;
		$media["photos"] = array_values(array_filter($media["photos"], function ($photo) use ($photo_name) {
			return $photo["name"] !== $photo_name;
		}));
		$product->media = $media;
		$product->save();

		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_ADMIN);
		return $product;
	}
}
