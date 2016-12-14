<?php
namespace app\controllers;

use app\helpers\CController;
use app\models\Person;
use app\models\Product;
use app\models\Product2;
use Yii;
use yii\filters\AccessControl;
use yii\mongodb\Collection;
use yii\web\BadRequestHttpException;

class ProductController extends CController
{
	public $defaultAction = "detail";

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

		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_OWNER);
		$products = Product2::findSerialized();
		foreach ($products as $product) {
			// saving the product, we force to create any missing short_id on price&stock
			$product->save();
		}
		Yii::$app->response->setStatusCode(200); // Success, without body
	}

}
