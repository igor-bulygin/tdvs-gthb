<?php
namespace app\models;

/**
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $vat_id
 * @property string $email
 * @property string $phone
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $zipcode
 *
 * @method Order getParentObject()
 */
class OrderAddress extends EmbedModel
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

	public function attributes() {
		return [
				'first_name',
				'last_name',
				'vat_id',
				'email',
				'phone',
				'country',
				'city',
				'address',
				'zipcode',
		];
	}

	public function rules()
	{
		return [
				[$this->attributes(), 'safe']
		];
	}

}