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
				'img' => 'https://unsplash.it/1200/600/?random&t=' . $i,
				'caption' => [
					'name' => 'Foo bar ' . $i,
					'category' => 'Foo bar ' . $i
				]
			];
		}

		$devisers = [];
		for ($i = 0; $i < 20; $i++) {
			$works = [];

			for ($j = 0; $j < 4; $j++) {
				$works[] = [
					'short_id' => $j,
					'img' => 'https://unsplash.it/100/100/?random&t=' . $j,
					'slug' => 'foo-bar'
				];
			}

			$devisers[] = [
				'name' => 'Foo bar ' . $i,
				'category' => 'Foo bar',
				'slug' => 'foo-bar',
				'img' => 'https://unsplash.it/200/200/?random&t=' . $i,
				'works' => $works
			];
		}

		$categories = [
			[
				'short_id' => '123456',
				'name' => "All categories",
				'products' => []
			],
			[
				'short_id' => '321654',
				'name' => "Art",
				'products' => []
			],
			[
				'short_id' => '123654',
				'name' => "Fashion",
				'products' => []
			],
			[
				'short_id' => '654123',
				'name' => "Industrial design",
				'products' => []
			],
			[
				'short_id' => '231546',
				'name' => "Jewelry",
				'products' => []
			]
		];

		foreach ($categories as $i => &$category) {
			$tmp = [];
			for ($j = 0; $j < 300; $j++) {
				$tmp[] = [
					'product_id' => '1234567',
					'img' => 'https://unsplash.it/300/200/?random&t=' . $i . $j,
					'width' => 600,
					'height' => 400,
					'name' => 'Foo bar ' . $j,
					'price' => $j,
					'category_id' => $category['short_id'],
					'slug' => 'foo-bar'
				];
			}

			$category['products'] = new ArrayDataProvider([
				'allModels' => $tmp
			]);
		}

		return $this->render("index", [
			'banners' => $banners,
			'devisers' => $devisers,
			'categories' => $categories
		]);
	}

	public function actionCategory($category_id, $slug) {
		var_dump("public / category");
		die();
	}

	public function actionProduct($category_id, $product_id, $slug) {
		return $this->render("product", [
			'category_id' => $category_id,
			'product_id' => $product_id,
			'slug' => $slug
		]);
	}
}
