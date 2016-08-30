<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\validators\Validator;

class DeviserMediaFilesValidator extends Validator
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
	    if (is_array($attr)) {
		    /** @var Model $model */
		    $model = new $this->model;
		    if($this->scenario){
			    $model->scenario = $this->scenario;
		    }
		    $model->attributes = $attr;
		    if (!$model->validate()) {
			    foreach ($model->getErrors() as $errorAttr) {
				    foreach ($errorAttr as $value) {
					    $this->addError($object, $attribute, $value);
				    }
			    }
		    }
	    } else {
		    $this->addError($object, $attribute, 'should be an array');
	    }

	    if (array_key_exists('profile', $attr)) {
	    	$filePath = $deviserFilesPath . '/' . $attr["profile"];
	    	if (!file_exists($filePath)) {
			    $this->addError($object, $attribute, sprintf('File %s not found', $attr["profile"]));
		    }
	    }

	    if (array_key_exists('header', $attr)) {
	    	$filePath = $deviserFilesPath . '/' . $attr["header"];
	    	if (!file_exists($filePath)) {
			    $this->addError($object, $attribute, sprintf('File %s not found', $attr["header"]));
		    }
	    }

	    if (array_key_exists('photos', $attr)) {
	    	foreach ($attr["photos"] as $photo) {
			    $filePath = $deviserFilesPath . '/' . $photo;
			    if (!file_exists($filePath)) {
				    $this->addError($object, $attribute, sprintf('File %s not found', $photo));
			    }
		    }
	    }
    }

}