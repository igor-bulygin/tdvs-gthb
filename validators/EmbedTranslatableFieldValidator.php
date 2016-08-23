<?php
namespace app\validators;

use app\models\Lang;
use yii\base\Model;
use yii\validators\Validator;

class EmbedTranslatableFieldValidator extends Validator
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
        $attr = $object->{$attribute};

        if (!is_array($attr)) {
            $this->addError($object, $attribute, ' must be an array');
        }

        if (count($attr) == 0) {
            $this->addError($object, $attribute, ' can not be empty');
        }

        foreach ($attr as $key => $item) {
            if (!array_key_exists($key, Lang::getAvailableLanguages())) {
                $this->addError($object, $attribute, sprintf('Language %s not available', $key));
            }
            if (empty($item)) {
                $this->addError($object, $attribute, sprintf('Language %s can not be empty', $key));
            }
        }
    }

}