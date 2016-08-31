<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\UrlValidator;
use yii\validators\Validator;

class PersonVideosValidator extends Validator
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
		$urlValidator = new UrlValidator();
		$videoProviderValidator = new VideoProviderValidator();

		foreach ($object->{$attribute} as $urlVideo) {
			// all items must be valid urls
			if ($urlValidator->validate($urlVideo, $error)) {
				// and server by specific streaming providers
				if (!$videoProviderValidator->validate($urlVideo, $error)) {
					$this->addError($object, $attribute, sprintf($error . ' (%s)', $urlVideo));
				}
			} else {
				$this->addError($object, $attribute, sprintf($error . ' (%s)', $urlVideo));
			}

		}
	}

}