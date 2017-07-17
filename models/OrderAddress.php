<?php
namespace app\models;

/**
 *
 * @propery string $first_name
 * @propery string $last_name
 * @propery string $id_number
 * @propery string $email
 * @propery string $phone
 * @propery string $country
 * @propery string $city
 * @propery string $address
 * @propery string $zipcode
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