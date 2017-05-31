<?php
namespace app\validators;

use app\models\Product2;
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

		foreach ($object->{$attribute} as $videoData) {
			// all items must be valid urls
			$url = $videoData["url"];
			if ($urlValidator->validate($url, $error)) {
				// and server by specific streaming providers
				if (!$videoProviderValidator->validate($url, $error)) {
					$this->addError($object, $attribute, sprintf($error . ' (%s)', $url));
				}
			} else {
				$this->addError($object, $attribute, sprintf($error . ' (%s)', $url));
			}

			// if products are specified, must exist
			if ((array_key_exists("products", $videoData)) && (!empty($videoData["products"])) && is_array($videoData["products"])) {
				foreach ($videoData["products"] as $id) {
					if (!Product2::findOne((["short_id" => $id]))) {
						$this->addError($object, $attribute, sprintf("Product %s not found", $id));
					}
				}
			}
		}
	}

}