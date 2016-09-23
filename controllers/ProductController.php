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
use app\helpers\CActiveRecord;
use yii\filters\AccessControl;

class ProductController extends CController {
	public $defaultAction = "detail";


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

	public function actionFixPosition()
	{
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
				$product->position = $i;
				$product->save();
				$cant++;
			}
		}
		Yii::$app->response->setStatusCode(200); // Success, without body
//		var_dump("done (" . $cant . ")");
	}

}
