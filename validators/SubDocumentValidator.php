<?php
namespace app\validators;

use app\helpers\CActiveRecord;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\validators\Validator;

class SubDocumentValidator extends Validator
{
	/**
	 * @var boolean whether to add an error message to embedded source attribute instead of embedded name itself.
	 */
	public $addErrorToSource = true;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		if ($this->message === null) {
			$this->message = Yii::t('yii', '{attribute} is invalid.');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute)
	{
		if (!($model instanceof CActiveRecord)) {
			throw new InvalidConfigException('Owner model must inherit CActiveRecord.');
		}

		$attribute = $model->buildSubDocumentName($attribute);
		if (!$model->hasSubdocument($attribute)) {
			throw new InvalidConfigException('Model ' . get_class($model) . ' does not have defined a subdocument in attribute ' . $attribute);
		}

		$value = $model->getSubDocument($attribute);
		$config = $model->subDocumentsConfig()[$attribute];

		if ($config['type'] == 'list') {

			if (!is_array($value) && !($value instanceof \IteratorAggregate)) {

			} else {
				foreach ($value as $valueModel) {
					if (!($valueModel instanceof Model)) {
						throw new InvalidConfigException('Element of type [' . gettype($valueModel) . '], representing attribute ' . $attribute . ' must be an instance or descendant of "' . Model::className() . '".');
					}
					if (!$valueModel->validate()) {
						foreach ($valueModel->errors as $messages) {
							foreach ($messages as $message) {
								$this->addError($model, $attribute, $message);
							}
						}
					}
				}
			}

		} else {

			if (!($value instanceof Model)) {
				throw new InvalidConfigException('Subdocument object "' . get_class($value) . '" must be an instance or descendant of "' . Model::className() . '".');
			}
			if (!$value->validate()) {
				foreach ($value->errors as $messages) {
					foreach ($messages as $message) {
						$this->addError($model, $attribute, $message);
					}
				}
			}
		}
	}
}