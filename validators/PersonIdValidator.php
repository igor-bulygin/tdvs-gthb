<?php
namespace app\validators;

use app\models\Person;
use yii\validators\Validator;

class PersonIdValidator extends Validator
{
	public $scenario;
	public $model;

	public function validate($value, &$error = null)
	{
		$person_id = $value;
		$person = Person::findOneSerialized($person_id);
		if (!$person) {
			$error = sprintf('Person %s not found', $person_id);
			return false;
		}

		return true;
	}

	/**
	 * Validates a single attribute.
	 * Child classes must implement this method to provide the actual validation logic.
	 *
	 * @param \yii\mongodb\ActiveRecord $object the data object to be validated
	 * @param string $attribute the name of the attribute to be validated.
	 */
	public function validateAttribute($object, $attribute)
	{
		$person_id = $object->{$attribute};
		$person = Person::findOneSerialized($person_id);
		if (!$person) {
			$this->addError($object, $attribute, sprintf('Person %s not found', $person_id));
		}
	}
}