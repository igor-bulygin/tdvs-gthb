<?php

namespace app\components;

use app\helpers\Utils;
use app\models\Product;
use Yii;
use yii\base\Widget;

class ProductsGrid extends Widget {

	/** @var  Product[] $products */
	public $products;

	public $css_class = 'col-xs-6 col-sm-4 col-md-2';

	public function run() {
		return $this->render('ProductsGrid', [
			'css_class' => $this->css_class,
			'products' => $this->products,
		]);
	}

	public function getViewPath()
	{
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'ProductsGrid', Yii::getAlias('@device'));
		}

		return Utils::join_paths('@app', 'components', 'views', 'ProductsGrid');
	}
}
