<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\base\ViewContextInterface;

class PublicController extends CController {
	public $defaultAction = "index";

	public function actionError() {
		//error_log("Error public", 4);
		//die();
	}

	public function actionIndex() {
		$banners = [];
		for ($i = 0; $i < 15; $i++) {
			$banners[] = [
				'img' => '/imgs/susan.jpg',
				'caption' => [
					'name' => 'Foo bar ' . $i,
					'category' => 'Foo bar ' . $i
				]
			];
		}

		$categories = [
			[
				'short_id' => '123456',
				'name' => "All categories"
			],
			[
				'short_id' => '321654',
				'name' => "Art"
			],
			[
				'short_id' => '123654',
				'name' => "Fashion"
			],
			[
				'short_id' => '654123',
				'name' => "Industrial design"
			],
			[
				'short_id' => '231546',
				'name' => "Jewelry"
			],
			[
				'short_id' => '132645',
				'name' => "More"
			]
		];

		$tmp = [];
		for ($i=0; $i < 300; $i++) {
			$tmp[] = [
				'img' => 'https://unsplash.it/600/400/?random&t=' . $i,
				'width' => 600,
				'height' => 400,
				'name' => 'Foo bar ' . $i,
				'price' => $i
			];
		}
		$data = new ArrayDataProvider([
			'allModels' => $tmp
		]);

		return $this->render("index", [
			'data' => $data,
			'banners' => $banners,
			'categories' => $categories
		]);
	}

	public function actionCategory($category_id, $slug) {
		var_dump("public / category");
		die();
	}

	public function actionProduct($category_id, $product_id, $slug) {
		echo "<br />";
		echo Url::to(["deviser/upload-profile-photo", "slug" => "pepe"]);
		echo "<br />";
		echo Url::to(["/public/product", "category_id" => 12456, "product_id" => 9876543, "slug" => "asfo-asdg-asd-gasd-gasdgsdgasdg-asdgasdg"]);
		echo "<br />";
		echo Url::to(["/public/category", "category_id" => 12456, "slug" => "asfo-asdg-asd-gasd-gasdgsdgasdg-asdgasdg"]);
		echo "<br />";
		echo "$category_id | $product_id | $slug";
		die();
	}
}
