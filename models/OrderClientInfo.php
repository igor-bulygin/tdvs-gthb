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
 *
 * @method Order getParentObject()
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

	/**
	 * Returns phone1 formmated
	 *
	 * @return string
	 */
	public function getPhone1() {
		return '+'.$this->phone1['prefix'].' '.$this->phone1['number'];
	}

	/**
	 * Returns phone2 formmated
	 *
	 * @return string
	 */
	public function getPhone2() {
		return '+'.$this->phone2['prefix'].' '.$this->phone2['number'];
	}

	/**
	 * Returns the full name of customer, without spaces
	 *
	 * @return string
	 */
	public function getFullName() {
		return trim($this->first_name.' '.$this->last_name);
	}

}