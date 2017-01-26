<?php
namespace app\models;

use app\helpers\CActiveRecord;

class EmbedModel extends CActiveRecord
{

	/** @var  CActiveRecord */
	protected $parentObject;

	/**
	 * @return CActiveRecord
	 */
	public function getParentObject()
	{
		return $this->parentObject;
	}

	/**
	 * @param CActiveRecord $object
	 */
	public function setParentObject($object)
	{
		$this->parentObject = $object;
	}

	public function beforeValidate()
	{
		if ($this->getParentObject()) {
			$this->setScenario($this->getParentObject()->getScenario());
		}

		return parent::beforeValidate();
	}


	/**
	 * Add additional error to make easy show labels in client side
	 */
	public function afterValidate()
	{
		if ($this->getParentObject()) {
			foreach ($this->errors as $attribute => $error) {
				foreach ($error as $oneError) {
					$this->getParentObject()->addError($attribute, $oneError);
				}
			};
			$this->clearErrors();
		}
		parent::afterValidate();
	}

}