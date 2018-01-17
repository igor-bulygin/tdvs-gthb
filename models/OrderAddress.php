<?php
namespace app\models;

/**
 *
 * @property string $name
 * @property string $last_name
 * @property string $vat_id
 * @property string $email
 * @property string $phone_number_prefix
 * @property string $phone_number
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $zip
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

	public function attributes()
	{
		return [
			'name',
			'last_name',
			'vat_id',
			'email',
			'phone_number_prefix',
			'phone_number',
			'country',
			'city',
			'address',
			'zip',
		];
	}

	public function rules()
	{
		return [
			[
				$this->attributes(),
				'safe',
				'on' => Order::SCENARIO_CART,
			],
		];
	}

	/**
	 * Returns the full name of customer, without spaces
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return trim($this->name . ' ' . $this->last_name);
	}

	/**
	 * Returns phone formmated
	 *
	 * @return string
	 */
	public function getPhone()
	{
		if (empty($this->phone_number)) {
			return null;
		}

		$phone1 = '';
		if (empty($this->phone_number_prefix)) {
			$phone1 .= '+' . $this->phone_number_prefix . ' ';
		}
		$phone1 .= $this->phone_number;

		return $phone1;
	}

	public function copyValuesFromPerson(Person $person) {
		$this->name = $person->personalInfoMapping->name;
		$this->last_name = $person->personalInfoMapping->last_name;
		$this->city = $person->personalInfoMapping->city;
		$this->country = $person->personalInfoMapping->country;
		$this->address = $person->personalInfoMapping->address;
		$this->zip = $person->personalInfoMapping->zip;
		$this->vat_id = $person->personalInfoMapping->vat_id;
		$this->phone_number_prefix = $person->personalInfoMapping->phone_number_prefix;
		$this->phone_number = $person->personalInfoMapping->phone_number;
//		$this->email = $person->getEmail();
	}
}