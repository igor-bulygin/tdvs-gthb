<?php
namespace app\controllers;

use app\helpers\CController;
use app\models\Bespoke;
use app\models\Lang;
use app\models\MadeToOrder;
use app\models\Person;
use app\models\Product;
use app\models\Product2;
use Yii;
use yii\filters\AccessControl;
use yii\mongodb\Collection;
use yii\web\BadRequestHttpException;

class ProductController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => ['create', 'edit'],
						'rules' => [
								[
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
		];
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);

		$defaultLimit = 300;
		$maxLimit = 300;
		// set pagination values
		$limit = Yii::$app->request->get('limit', $defaultLimit);
		$limit = max(1, $limit);
	    $limit = min($limit, $maxLimit);
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$text = Yii::$app->request->get("q"); // search in name, description, and more
		$products = Product2::findSerialized([
				"name" => Yii::$app->request->get("name"), // search only in name attribute
				"id" => Yii::$app->request->get("id"),
				"text" => $text,
				"deviser_id" => Yii::$app->request->get("deviser"),
				"categories" => Yii::$app->request->get("categories"),
				"product_state"=>  null, //TODO Product2::PRODUCT_STATE_ACTIVE,
				"limit" => $limit,
				"offset" => $offset,
		]);

		// divide then in blocks to be rendered in bottom section
		$moreWork = [];
		for ($i = 0; $i < 19; $i++) {
			$start = $i * 15;
			$moreWork[] =  [
					"twelve" => array_slice($products, $start, 12),
					"three" => array_slice($products, ($start + 12), 3),
			];
		}

		$this->layout = '/desktop/public-2.php';

		return $this->render("products", [
			'text' => $text,
			'total' => Product2::$countItemsFound,
			'products' => $products,
			'moreWork' => $moreWork,
		]);
	}


	public function actionDetail($slug, $product_id)
	{

		// get the product
		$product = Product::findOneSerialized($product_id);

		// get the deviser
		$deviser = Person::findOneSerialized($product->deviser_id);

		// get other products of the deviser
		$deviserProducts = Product::findSerialized(["deviser_id" => $product->deviser_id]);

		$this->layout = '/desktop/public-2.php';
		return $this->render("detail", [
			'product' => $product,
			'deviser' => $deviser,
			'deviserProducts' => $deviserProducts,
		]);
	}

	public function actionCreate($slug, $deviser_id)
	{
		/** @var Person $deviser */
		$deviser = Person::findOneSerialized($deviser_id);

		if (!$deviser) {
			throw new BadRequestHttpException("Not found");
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("product-create", [
			'deviser' => $deviser,
		]);
	}

	public function actionEdit($slug, $deviser_id, $product_id)
	{
		/** @var Person $deviser */
		$deviser = Person::findOneSerialized($deviser_id);

		$product = Product::findOneSerialized($product_id);

		if (!$product) {
			throw new BadRequestHttpException("Not found");
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("product-edit", [
			'deviser' => $deviser,
			'product' => $product,
		]);
	}

	/**
	 * @deprecated
	 */
	public function actionFixPosition()
	{
//		ini_set('memory_limit', '2048M');

		// set pagination values
		$deviser_id = Yii::$app->request->get('deviser_id');

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);
		$query = Person::find();
		if (!empty($deviser_id)) {
			$query->where(["short_id" => $deviser_id]);
		}
		$devisers = $query->all();

		$cant = 0;
		/** @var Person $deviser */
		foreach ($devisers as $deviser) {
			$products = Product::find()->where(["deviser_id" => $deviser->short_id])->all();
			$i = 0;
			/** @var Product $product */
			foreach ($products as $product) {
				$i++;
				// Update directly in low level, to avoid no desired behaviors of ActiveRecord
				/** @var Collection $collection */
				$collection = Yii::$app->mongodb->getCollection('product');
				$collection->update(
						[
							'short_id' => $product->short_id
						],
						[
							'position' => $i
						]
					);
				$cant++;
			}
		}
		Yii::$app->response->setStatusCode(200); // Success, without body
		var_dump("done (" . $cant . ")");
	}

	public function actionFixProducts()
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(-1);

		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_OWNER);

		/* @var Product2[] $products */
		$products = Product2::findSerialized();
		foreach ($products as $product) {
			// fix name field, must be an array
			if (!empty($product->name) && !is_array($product->name)) {
				$name = [];
				foreach (Lang::getAvailableLanguagesDescriptions() as $key => $langName) {
					$name[$key] = $product->name;
				}
				$product->setAttribute('name', $name);
			}
			// set product state. If it has minimal info, we set the product as public
			if (empty($product->product_state)) {
				if (empty($product->categories) || empty($product->deviser_id) || empty($product->name) || empty($product->price_stock) || empty($product->mediaFiles) || empty($product->mediaFiles->photos)) {
					$product->product_state = Product2::PRODUCT_STATE_DRAFT;
				} else {
					$product->product_state = Product2::PRODUCT_STATE_ACTIVE;
				}
			}
			// available on price_stock
			$priceStock = $product->price_stock;
			foreach ($priceStock as $k => $item) {
				if (!isset($item['available'])) {
					$priceStock[$k]['available'] = true;
				}
			}
			$product->setAttribute('price_stock', $priceStock);

			// bespoke default
			if (empty($product->bespoke)) {
				$bespoke = ['type' => Bespoke::NO];
				$product->setAttribute('bespoke', $bespoke);
			}

			// madetoorder...
			$madeToOrder = $product->madetoorder;
			$value = isset($madeToOrder['value']) ? $madeToOrder['value'] : null;
			$type = isset($madeToOrder['type']) ? $madeToOrder['type'] : null;
			if (!empty($value)) {
				$madeToOrder['type'] = MadeToOrder::DAYS;
				$madeToOrder['value'] = (int) $value;
			} else {
				$madeToOrder['type'] = MadeToOrder::NONE;
				unset($madeToOrder['value']);
			}
			$product->setAttribute('madetoorder', $madeToOrder);

			// save make other fixes (created_at and updated_at dates, short_ids on price&stock....)
			$product->save(false);
		}
		Yii::$app->response->setStatusCode(200); // Success, without body
	}

}
