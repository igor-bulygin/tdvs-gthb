<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Category;
use app\models\Lang;
use app\models\Person;
use app\models\Tag;
use app\models\TagOption;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\UrlValidator;
use yii\validators\Validator;
use yii\web\UrlManager;

class OptionsValidator extends Validator
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
		$options = $object->{$attribute};
		foreach ($options as $optionId => $values) {
			if ($optionId == 20000) {
				//wtf do here??
				continue;
			}
			$tag = Tag::findOne(["short_id" => $optionId]); /* @var $tag Tag */
			if (!$tag) {
				$this->addError($object, $attribute, sprintf('Option %s not found', $optionId));
			} else {
				foreach ($values as $value) {
					if (is_array($value)) {
						foreach ($value as $oneValue) {
							$optionTag = $tag->getOptionTagByValue($oneValue);
							if (!$optionTag) {
								$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $oneValue, $optionId));
							}
						}
					} else {
						$optionTag = $tag->getOptionTagByValue($value);
						if (!$optionTag) {
							$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $value, $optionId));
						}
					}
				}
			}
		}
	}
}