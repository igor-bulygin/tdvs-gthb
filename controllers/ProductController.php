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
		$product = Product::findOne(["short_id" => $product_id]);

		$tags = $product->getTags();

		// get the deviser
		$deviser = Person::findOne(["short_id" => $product->deviser_id]);

		// get other products of the deviser
		$deviserProducts = Product::find()->where(["deviser_id" => $deviser->short_id])->all();

//		print_r($product->options);

		$this->layout = '/desktop/public-2.php';
		return $this->render("detail", [
			'product' => $product,
			'deviser' => $deviser,
			'deviserProducts' => $deviserProducts,
		]);
	}

}
