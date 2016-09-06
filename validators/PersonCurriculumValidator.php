<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\validators\Validator;

class PersonCurriculumValidator extends Validator
{
    public $scenario;
    public $model;

    /**
     * Validates a single attribute.
     * Child classes must implement this method to provide the actual validation logic.
     *
     * @param \yii\mongodb\ActiveRecord $person the data object to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    public function validateAttribute($person, $attribute)
    {
	    /** @var Person $person*/
	    $cvFilename = $person->{$attribute};
	    $filePath = $person->getUploadedFilesPath() . '/' . $cvFilename;
	    if (!file_exists($filePath)) {
		    $this->addError($person, $attribute, sprintf('File %s not found', $cvFilename));
	    }
    }
}