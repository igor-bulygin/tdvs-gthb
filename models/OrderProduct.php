<?php
namespace app\models;

/**
 * @property string $product_id
 * @property string $price_stock_id
 * @property int $quantity
 * @property double $price
 * @property double $weight
 * @property array $options
 *
 * @method Order getParentObject()
 */
class OrderProduct extends EmbedModel
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
		return Product::findOne(['short_id' => $this->product_id]);
	}

}