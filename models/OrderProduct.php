<?php
namespace app\models;

/**
 * @property string $product_id
 * @property string $price_stock_id
 * @property int $quantity
 * @property string $deviser_id
 * @property double $price
 * @property double $weight
 * @property array $options
 *
 * @method Order getParentObject()
 */
class OrderProduct extends EmbedModel
{
	private $product;

	public function getParentAttribute()
	{
		return "products";
	}

	public function attributes() {
		return [
				'product_id',
				'price_stock_id',
				'quantity',
				'price',
				'weight',
				'options',
		];
	}

	public function rules()
	{
		return [
				[$this->attributes(), 'safe']
		];
	}

	/**
	 * @return Product
	 */
	public function getProduct()
	{
		if (empty($this->product)) {
			Product::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);
			$this->product = Product::findOneSerialized($this->product_id);
		}
		return $this->product;

	}

}