<?php
namespace app\models;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property array $phone1
 * @property array $phone2
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $zipcode
 */
class OrderClientInfo extends EmbedModel
{

	public function getParentAttribute()
	{
		return "client_info";
	}

	public function attributes() {
		return [
			'first_name',
			'last_name',
			'email',
			'phone1',
			'phone2',
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