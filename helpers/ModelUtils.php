<?php
namespace app\helpers;

use Yii;
use app\models\Lang;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Category;

class ModelUtils {

	public static function getCategory($category_id) {
		$cache = Yii::$app->cache;

		$cat = $cache->get("category_" . $category_id);
		if ($cat === false) {
			$cat = Category::find()->where(["short_id" => $category_id])->asArray()->one();
			$cache->set("category_" . $category_id, $cat);
		}

		return $cat;
	}

	public static function getSubCategories($category_id) {
		$cache = Yii::$app->cache;

		$sub_cats = $cache->get("sub_categories_" . $category_id);
		$categories = [];
		$categories_ids = []; //tmp helper
		if ($sub_cats === false) {
			foreach (Category::find()->asArray()->all() as $key => $category) {

				if (!isset($categories[$category['short_id']])) {
					$categories[$category['short_id']] = [];
					$categories_ids[$category['short_id']] = [];
				}

				if ($category['path'] == "/") {
					continue;
				}

				$path = explode("/", $category['path']);
				if (count($path) >= 3) {
					array_shift($path);
					array_pop($path);

					foreach ($path as $key => $category_id_from_path) {
						if (!isset($categories[$category_id_from_path])) {
							$categories[$category_id_from_path] = [];
							$categories_ids[$category_id_from_path] = [];
						}

						if (!in_array($category['short_id'], $categories_ids[$category_id_from_path])) {
							$categories[$category_id_from_path][] = $category;
							$categories_ids[$category_id_from_path][] = $category['short_id'];
						}
					}
				}
			}

			foreach ($categories as $key => $category) {
				$cache->set("sub_categories_" . $key, $categories[$key]);
			}

			$sub_cats = $categories[$category_id];
		}

		return $sub_cats;
	}

	public static function getDeviserFullName($deviser) {
		if (!isset($deviser['personal_info'])) return "";

		$pinfo =$deviser['personal_info'];
		return @$pinfo['name'] . ' ' . @implode(" ", @$pinfo['surnames']);
	}

	public static function getDeviserHeader($deviser, $urlify = true) {
		$image = "";
		$fallback = "deviser_header_placeholder.jpg";

		if (isset($deviser['media']) && isset($deviser['media']['header'])) {
			$image = $deviser['media']['header'];

			if (!file_exists(Yii::getAlias("@web") . "/" . $deviser["short_id"] . "/" . $image )) {
				$image = $fallback;
			}
		} else {
			$image = $fallback;
		}

		if ($urlify === true) {
			if ($image === $fallback) {
				$image = Yii::getAlias("@web") . "/imgs/" . $image;
			} else {
				$image = Yii::getAlias("@deviser_url") . "/" . $deviser["short_id"] . "/" . $image;
			}
		}

		return $image;
	}

	public static function getDeviserAvatar($deviser, $urlify = true) {
		$image = "";
		$fallback = "deviser_placeholder.png";

		if (isset($deviser['media']) && isset($deviser['media']['profile'])) {
			$image = $deviser['media']['profile'];

			if (!file_exists(Yii::getAlias("@web") . "/" . $deviser["short_id"] . "/" . $image )) {
				$image = $fallback;
			}
		} else {
			$image = $fallback;
		}

		if ($urlify === true) {
			if ($image === $fallback) {
				$image = Yii::getAlias("@web") . "/imgs/" . $image;
			} else {
				$image = Yii::getAlias("@deviser_url") . "/" . $deviser["short_id"] . "/" . $image;
			}
		}

		return $image;
	}

	public static function getProductCategoriesNames($product) {
		//This is possible because the categories are stored the same
		//name in the products as they are stored in the devisers.
		return ModelUtils::getDeviserCategoriesNames($product);
	}

	public static function getDeviserCategoriesNames($deviser) {
		$categories = [];
		if (isset($deviser['categories']) && count($deviser['categories']) > 0) {
			foreach ($deviser['categories'] as $key => $category_id) {
				$category = ModelUtils::getCategory($category_id);
				$categories[] = Utils::l($category['name']);
			}
		}

		if (count($categories) === 0) {
			$categories[] = '';
		}

		return $categories;
	}

	public static function getProductMainPhoto($product, $urlify = true) {
		$image = "";
		$fallback = "product_placeholder.png";

		if (isset($product["media"]) && isset($product["media"]["photos"])) {
			foreach ($product["media"]["photos"] as $key => $photo) {
				if (isset($photo["main_product_photo"]) && $photo["main_product_photo"]) {
					$image = $photo["name"];
					break;
				}
			}
			if ($image === "") {
				if (count($product["media"]["photos"]) == 0) {
					$image = $fallback;
				} else {
					$image = $product["media"]["photos"][0]["name"];

					if (!empty($image) && !file_exists(Yii::getAlias("@web") . "/" . $product["short_id"] . "/" . $image )) {
						$image = $fallback;
					}
				}
			}
		} else {
			$image = $fallback;
		}

		if ($urlify === true) {
			if ($image === $fallback) {
				$image = Yii::getAlias("@web") . "/imgs/" . $image;
			} else {
				$image = Yii::getAlias("@product_url") . "/" . $product["short_id"] . "/" . $image;
			}
		}

		return $image;
	}
}
