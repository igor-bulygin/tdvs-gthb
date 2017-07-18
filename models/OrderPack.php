<?php
namespace app\models;

use app\helpers\Utils;
use yii\base\Exception;

/**
 *
 * @property int $deviser_id
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
	 * @var Person
	 */
	private $deviser;

	public function attributes() {
		return [
				'deviser_id',
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
				static::$serializeFields = [
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
				static::$retrieveExtraFields = [
				];


				static::$translateFields = false;
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
		Product::setSerializeScenario($view);
	}

	/**
	 * @return Person
	 */
	public function getDeviser()
	{
		if (empty($this->deviser)) {
			$this->deviser = Person::findOne(['short_id' => $this->deviser_id]);
		}
		return $this->deviser;
	}

	public function getDeviserInfo() {
		return $this->getDeviser()->getPreviewSerialized();
	}

	public function getProductsInfo() {
		$deviser = $this->getDeviser();
		$products = $this->products;

		$result = [];
		foreach ($products as $p) {
			$product = Product::findOneSerialized($p['product_id']);
			$p['product_name'] = $product->name;
			$p['product_photo'] = Utils::url_scheme() . Utils::thumborize($product->getMainImage());
			$p['product_slug'] = $product->slug;
			$p['product_url'] = $product->getViewLink();
			$p['deviser_name'] = $deviser->name;
			$p['deviser_photo'] = Utils::url_scheme() . Utils::thumborize($deviser->getAvatarImage());
			$p['deviser_slug'] = $deviser->slug;
			$p['deviser_url'] = $deviser->getStoreLink();
			$result[] = $p;
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


		$shipping_info = $this->shipping_info;
		$price = $deviser->getShippingPrice($pack_weight, $order->shippingAddressMapping->country, $this->shipping_info['type']);
		$shipping_info['price'] = $price;

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



	public function deleteProduct($priceStockId) {
		$products = $this->productsMapping;
		foreach ($products as $i => $item) {
			if ($item->price_stock_id == $priceStockId) {
				$item->offsetUnset($i);
			}
		}

		$this->recalculateTotals();

		return null;
	}

}