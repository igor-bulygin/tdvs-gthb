<?php
namespace app\models;

use app\helpers\Utils;
use yii\base\Exception;

/**
 *
 * @property int $deviser_id
 * @property string $short_id
 * @property array $shipping_info
 * @property double $pack_weight
 * @property double $pack_price
 * @property double $pack_percentage_fee
 * @property string $currency
 * @property string $weight_measure
 * @property array $products
 *
 * @property OrderProduct[] $productsMapping
 *
 * @method Order getParentObject()
 */
class OrderPack extends EmbedModel
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
				'deviser_id',
				'short_id',
				'shipping_info',
				'pack_weight',
				'pack_price',
				'pack_percentage_fee',
				'currency',
				'weight_measure',
				'products',
		];
	}

	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

//		$this->products = [];

		$this->setAttribute('shipping_info', ['type' => 'standard']);
	}

	public function rules()
	{
		return [
				[$this->attributes(), 'safe']
		];
	}

	public function embedProductsMapping()
	{
		return $this->mapEmbeddedList('products', OrderProduct::className(), array('unsetSource' => false));
	}

	/**
	 * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
	 * only the attributes needed for a query context
	 *
	 * @param $view
	 */
	public static function setSerializeScenario($view)
	{
		switch ($view) {

			case self::SERIALIZE_SCENARIO_PREVIEW:
			case self::SERIALIZE_SCENARIO_PUBLIC:
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				self::$serializeFields = [
					'deviser_id',
					'deviser_info' => 'deviserInfo',
					'shipping_info',
					'pack_weight',
					'pack_price',
					'pack_percentage_fee',
					'currency',
					'weight_measure',

//					'payment_info',
//					'charges',
					'products' => 'productsInfo',
				];
			self::$retrieveExtraFields = [
					'products',
				];


			self::$translateFields = false;
				break;

			case Order::SERIALIZE_SCENARIO_CLIENT_ORDER:
				self::$serializeFields = [
					'deviser_id',
					'deviser_info' => 'deviserInfo',
					'shipping_info',
					'pack_weight',
					'pack_price',
					'pack_percentage_fee',
					'currency',
					'weight_measure',

//					'payment_info',
//					'charges',
					'products' => 'productsInfo',
				];
				self::$retrieveExtraFields = [
					'products',
				];


				self::$translateFields = false;
				break;

			case Order::SERIALIZE_SCENARIO_DEVISER_PACK:
				self::$serializeFields = [
					'deviser_id',
					'shipping_info',
					'pack_weight',
					'pack_price',
					'pack_percentage_fee',
					'currency',
					'weight_measure',

//					'payment_info',
//					'charges',
					'products' => 'productsInfo',
				];
				self::$retrieveExtraFields = [
					'products',
				];


				self::$translateFields = false;
				break;

			default:
				// now available for this Model
				self::$serializeFields = [];
				break;
		}
//		Product::setSerializeScenario($view);
	}

	/**
	 * @return Person
	 */
	public function getDeviser()
	{
		return Person::findOne(['short_id' => $this->deviser_id]);
	}

	public function getDeviserInfo() {
		$order = $this->getParentObject();
		$deviser = $this->getDeviser();

		$deviser_info = [
			"slug" => $deviser->slug,
			"name" => $deviser->personalInfoMapping->getVisibleName(),
			"photo" => $deviser->getAvatarImage128(),
			'url' => $deviser->getMainLink(),
		];

		$shippingSetting = $deviser->getShippingSettingByCountry($order->shippingAddressMapping->country);

		if ($shippingSetting) {
			$price = $shippingSetting->getShippingSettingRange($this->pack_weight);
			if ($price) {
				$deviser_info['shipping_time'] = $shippingSetting->shipping_time;
				$deviser_info['price'] = $price['price'];
				$deviser_info['price_express'] = $price['price_express'];
				$deviser_info['shipping_express_time'] = $shippingSetting->shipping_express_time;
			}
		}

		return $deviser_info;
	}

	public function getProductsInfo() {
		$products = $this->products;

		$result = [];
		if ($products) {
			foreach ($products as $p) {
				$product = Product::findOne(['short_id' => $p['product_id']]);
				$p['product_info'] = [
					'name' => $product->getName(),
					'photo' => Utils::url_scheme() . Utils::thumborize($product->getMainImage()),
					'slug' => $product->getSlug(),
					'url' => $product->getViewLink(),
				];
				$result[] = $p;
			}
		}

		return $result;
	}

	public function addProduct(OrderProduct $orderProduct) {

		$product = $orderProduct->getProduct();
		$priceStock = $product->getPriceStockItem($orderProduct->price_stock_id);
		if (empty($priceStock)) {
			throw new Exception(sprintf("Price stock item with id %s does not exists", $orderProduct->price_stock_id));
		}

		$found = false;
		foreach ($this->productsMapping as $productMapping) {
			if ($productMapping->price_stock_id == $orderProduct->price_stock_id) {

				$newQuantity = $productMapping->quantity + $orderProduct->quantity;
				if ($newQuantity > $priceStock['stock']) {
					throw new Exception(sprintf("Stock %s unavailable. Available stock is %s", $newQuantity, $priceStock['stock']));
				}
				$productMapping->quantity = $newQuantity;
				$found = true;
			}
		}
		if (!$found) {
			$orderProduct->price = $priceStock['price'];
			$orderProduct->weight = $priceStock['weight'];
			$orderProduct->options = $priceStock['options'];
			$this->productsMapping[] = $orderProduct;
		}

		$this->recalculateTotals();
	}

	public function recalculateTotals() {
		$order = $this->getParentObject();
		$deviser = $this->getDeviser();

		$pack_weight = 0;
		$pack_price = 0;
		foreach ($this->productsMapping as $productMapping) {
			$product = $productMapping->getProduct();
			$priceStock = $product->getPriceStockItem($productMapping->price_stock_id);
			$pack_weight += $priceStock['weight'];
			$pack_price += $priceStock['price'] * $productMapping->quantity;

		}
		$this->pack_weight = $pack_weight;
		$this->pack_price = $pack_price;

		$pricePack = null;

		$shippingSetting = $deviser->getShippingSettingByCountry($order->shippingAddressMapping->country);

		if ($shippingSetting) {
			$price = $shippingSetting->getShippingSettingRange($pack_weight);
			if ($price) {
				switch ($this->shipping_info['type']) {
					case 'standard':
						$pricePack = $price['price'];
						break;
					case 'express':
						$pricePack = $price['price_express'];
						break;
				}
			}
		}

		$shipping_info = $this->shipping_info;
		$shipping_info['price'] = $pricePack;

		$this->setAttribute('shipping_info',$shipping_info);
	}

	/**
	 * @param $priceStockId
	 * @return OrderProduct|null
	 */
	public function getPriceStockItem($priceStockId) {
		$products = $this->productsMapping;
		foreach ($products as $item) {
			if ($item->price_stock_id == $priceStockId) {
				$item->setParentObject($this);
				return $item;
			}
		}
		return null;
	}



	public function deleteProduct($priceStockId)
	{
		$products = $this->productsMapping;
		$indexes = [];
		foreach ($products as $i => $item) {
			if ($item->price_stock_id == $priceStockId) {
				$indexes[] = $i;
			}
		}

		foreach ($indexes as $index) {
			$products->offsetUnset($index);
		}

		$this->recalculateTotals();

		return null;
	}

}