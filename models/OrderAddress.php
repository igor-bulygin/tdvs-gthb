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
}