<?php

namespace app\components;

use app\helpers\Utils;
use app\models\Category;
use app\models\Login;
use Yii;
use yii\base\Widget;

class PublicHeader2 extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		$model = new Login();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//
		}

		$selectedCategory = isset($this->view->params['selectedCategory']) ? $this->view->params['selectedCategory'] : null;
		$categoryId = $selectedCategory ? $selectedCategory->short_id : null;

		Category::setSerializeScenario(Category::SERIALIZE_SCENARIO_PUBLIC);

		return $this->render('PublicHeader2', [
			'selectedCategory' => $selectedCategory,
			'categoryId' => $categoryId,
			'categories' => Category::getHeaderCategories(),
			'login_model' => $model,
			'q' => Yii::$app->request->get('q'),
            'searchTypeId' => Yii::$app->request->get('searchTypeId'), // variable indices id of search objects (products, boxes, devisers, influencers). Used to make selected choice
		]);
	}

	public function getViewPath() {
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'PublicHeader2', Yii::getAlias('@device'));
		}
		return Utils::join_paths('@app', 'components', 'views', 'PublicHeader2');
	}
}
