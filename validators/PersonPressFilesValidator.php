<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\validators\Validator;

class PersonPressFilesValidator extends Validator
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
	    /** @var Person $object*/
	    $deviserFilesPath = Utils::join_paths(Yii::getAlias("@deviser"), $object->short_id);

	    $attr = $object->{$attribute};

	    if (array_key_exists('press', $attr)) {
	    	foreach ($attr["press"] as $imageName) {
			    $filePath = $deviserFilesPath . '/' . $imageName;
			    if (!file_exists($filePath)) {
				    $this->addError($object, $attribute, sprintf('File %s not found', $imageName));
			    }
		    }
	    }
    }

}