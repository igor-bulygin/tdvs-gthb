<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Category;
use app\models\Lang;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\UrlValidator;
use yii\validators\Validator;
use yii\web\UrlManager;

class CategoriesValidator extends Validator
{
	public $scenario;
	public $model;

	/**
	 * Validates a single attribute.
	 * Child classes must implement this method to provide the actual validation logic.
	 *
	 * @param \yii\mongodb\ActiveRecord $object the data object to be validated
	 * @param string $attribute the name of the attribute to be validated.
	 */
	public function validateAttribute($object, $attribute)
	{
		$categories = $object->{$attribute};
		foreach ($categories as $categoryId) {
			$category = Category::findOne(["short_id" => $categoryId]);
			if (!$category) {
				$this->addError($object, $attribute, sprintf('Category %s not found', $categoryId));
			}
		}
	}
}