<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Lang;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\UrlValidator;
use yii\validators\Validator;

class PersonFaqValidator extends Validator
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
		$faqs = $object->{$attribute};

		if (!is_array($faqs)) {
			$this->addError($object, $attribute, ' must be an array');
		}

		$translatableValidator = new TranslatableValidator();
		foreach ($faqs as $faq) {
			if (!$translatableValidator->validate($faq["question"], $error)) {
				$this->addError($object, $attribute, $error);
			}
			if (!$translatableValidator->validate($faq["answer"], $error)) {
				$this->addError($object, $attribute, $error);
			}
		}
	}

}