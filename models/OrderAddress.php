<?php
namespace app\models;

/**
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $id_number
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
	public function attributes() {
		return [
				'first_name',
				'last_name',
				'id_number',
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