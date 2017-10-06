<?php
namespace app\models;

use MongoDate;

/**
 * @property string product_id
 * @property MongoDate created_at
 * @property MongoDate updated_at
 *
 * @method Box getParentObject()
 */
class BoxProduct extends EmbedModel
{

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];

	public function getParentAttribute()
	{
		return "products";
	}

	public function attributes() {
		return [
				'product_id',
				'created_at',
				'updated_at',
		];
	}

	public function beforeSave($insert)
	{
		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function rules()
	{
		return [
				[$this->attributes(), 'safe']
		];
	}

}