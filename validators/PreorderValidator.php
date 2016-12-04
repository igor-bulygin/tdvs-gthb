<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Category;
use app\models\Lang;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\mongodb\validators\MongoDateValidator;
use yii\validators\DateValidator;
use yii\validators\UrlValidator;
use yii\validators\Validator;
use yii\web\UrlManager;

class PreorderValidator extends Validator
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
		$preorder = $object->{$attribute};
		if (!is_array($preorder)) {
			$this->addError($object, $attribute, 'Preorder must be an array');
		}
		if (!isset($preorder['type']) || ($preorder['type'] != 0 && $preorder['type'] != 1)) {
			$this->addError($object, $attribute, 'Preorder must have a "type" field, with values 0 or 1');
		}
		if ($preorder['type'] == 1) {
			if (!isset($preorder['ship']) || !isset($preorder['end'])) {
				$this->addError($object, $attribute, 'Preorder must have "ship" and "end" date fields');
			} else {
				/*
				$dateValidator = new DateValidator();
				if (!$dateValidator->validate($preorder['ship'], $error)) {
					$this->addError($object, $attribute, $error);
				}
				if (!$dateValidator->validate($preorder['end'], $error)) {
					$this->addError($object, $attribute, $error);
				}
				*/
			}
		}
	}
}