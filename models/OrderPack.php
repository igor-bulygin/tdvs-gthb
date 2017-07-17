<?php
namespace app\models;

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
	 * @return Person
	 */
	public function getDeviser()
	{
		if (empty($this->deviser)) {
			$this->deviser = Person::findOne(['short_id' => $this->deviser_id]);
		}
		return $this->deviser;

	}

	public function getProductsInfo() {
		$deviser = $this->getDeviser();
		$products = $this->products;

		$result = [];
		foreach ($products as $p) {
			$product = Product::findOne(['short_id' => $p['product_id']]);
			$p['product_name'] = $product->name;
			$p['product_photo'] = $product->getMainImage();
			$p['product_slug'] = $product->slug;
			$p['product_url'] = $product->getViewLink();
			$p['deviser_name'] = $deviser->name;
			$p['deviser_photo'] = $deviser->getAvatarImage();
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