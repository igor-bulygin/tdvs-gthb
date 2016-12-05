<?php
namespace app\validators;

use app\models\MetricType;
use Yii;
use yii\validators\Validator;

class WeightUnitValidator extends Validator
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
		$dimensionUnit = $object->{$attribute};
		if (!in_array($dimensionUnit, MetricType::getAvailableWeights())) {
			$this->addError($object, $attribute, 'Weight unit not valid');
		}
	}
}