<?php
namespace app\models;

use app\helpers\Utils;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 *
 * @property int $deviser_id
 * @property string $short_id
 * @property string $shipping_type
 * @property double $shipping_price
 * @property array $shipping_info
 * @property double $pack_weight
 * @property double $pack_price
 * @property double $pack_percentage_fee
 * @property string $currency
 * @property string $weight_measure
 * @property string $pack_state
 * @property array $products
 * @property array $state_history
 *
 * @method Order getParentObject()
 */
class OrderPack extends EmbedModel
{
	const PACK_STATE_CART = 'cart';
	const PACK_STATE_PAID = 'paid';
	const PACK_STATE_AWARE = 'aware';
	const PACK_STATE_SHIPPED = 'shipped';

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
			'shipping_type',
			'shipping_price',
			'shipping_info',
			'pack_weight',
			'pack_price',
			'pack_percentage_fee',
			'currency',
			'weight_measure',
			'pack_state',
			'products',
			'state_history',
		];
	}

	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

		if (empty($this->shipping_type)) {
			$this->shipping_type = 'standard';
		}

		if (empty($this->pack_state)) {
			$this->setState(OrderPack::PACK_STATE_CART);
		}
	}

	public function rules()
	{
		return [
				[
					[
						'shipping_type',
					],
					'in',
					'range' => ['standard', 'express'],
					'on' => Order::SCENARIO_CART
				]
		];
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

			case self::SERIALIZE_SCENARIO_ADMIN:
			case Order::SERIALIZE_SCENARIO_CLIENT_ORDER:
			case Order::SERIALIZE_SCENARIO_DEVISER_PACK:
				self::$serializeFields = [
					'short_id',
					'deviser_id',
					'deviser_info' => 'deviserInfo',
					'shipping_type',
					'shipping_price',
					'shipping_info',
					'pack_weight',
					'pack_price',
					'pack_percentage_fee',
					'currency',
					'weight_measure',
					'pack_state',
					'pack_state_name' => 'packStateName',

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

	public function getPackStateName()
	{
		$pack_states = [
			self::PACK_STATE_CART => 'Cart',
			self::PACK_STATE_PAID => 'Paid',
			self::PACK_STATE_AWARE => 'Deviser aware / in preparation',
			self::PACK_STATE_SHIPPED => 'Shipped',
		];
		return $pack_states[$this->pack_state];
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

		$shippingSetting = $deviser->getShippingSettingByCountry($order->getShippingAddress()->country);

		if ($shippingSetting) {
			$price = $shippingSetting->getShippingSettingRange($this->pack_weight);
			if ($price) {
				$deviser_info['shipping_time'] = $shippingSetting->shipping_time;
				$deviser_info['shipping_express_time'] = $shippingSetting->shipping_express_time;
				$deviser_info['price'] = $price['price'];
				$deviser_info['price_express'] = isset($price['price_express']) ? $price['price_express'] : null;
			}
		}

		return $deviser_info;
	}

	public function getProductsInfo() {
		$products = $this->products;

		$result = [];
		if ($products) {
			foreach ($products as $k => $p) {
				$product = Product::findOne(['short_id' => $p['product_id']]);
				if (empty($product)) {
					unset($products[$k]);
				}
				$priceStock = $product->getPriceStockItem($p['price_stock_id']);
				if (empty($priceStock)) {
					unset($products[$k]);
				}
				$p['product_info'] = [
					'name' => $product->getName(),
					'photo' => Utils::url_scheme() . Utils::thumborize($product->getMainImage()),
					'slug' => $product->getSlug(),
					'url' => $product->getViewLink(),
					'stock' => $priceStock['stock'],
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
			throw new NotFoundHttpException(sprintf("Price stock item with id %s does not exists", $orderProduct->price_stock_id));
		}

		$found = false;
		$products = $this->getProducts();
		foreach ($products as $item) {
			if ($item->price_stock_id == $orderProduct->price_stock_id) {
				$newQuantity = $item->quantity + $orderProduct->quantity;
				if ($newQuantity > $priceStock['stock']) {
					throw new BadRequestHttpException(sprintf("Stock %s unavailable. Available stock is %s", $newQuantity, $priceStock['stock']));
				}
				$item->quantity = $newQuantity;
				$found = true;
			}
		}
		if (!$found) {
			if ($orderProduct->quantity > $priceStock['stock']) {
				throw new BadRequestHttpException(sprintf("Stock %s unavailable. Available stock is %s", $orderProduct->quantity, $priceStock['stock']));
			}

			$orderProduct->price = $priceStock['price'];
			$orderProduct->weight = $priceStock['weight'];
			$orderProduct->options = $priceStock['options'];
			$products[] = $orderProduct;
		}
		$this->setProducts($products);

		$this->recalculateTotals();
	}

	public function recalculateTotals() {
		$order = $this->getParentObject();
		$deviser = $this->getDeviser();

		$pack_weight = 0;
		$pack_price = 0;
		$products = $this->getProducts();
		foreach ($products as $item) {
			$product = $item->getProduct();
			$priceStock = $product->getPriceStockItem($item->price_stock_id);
			$pack_weight += $priceStock['weight'];
			$pack_price += $priceStock['price'] * $item->quantity;

		}
		$this->pack_weight = $pack_weight;
		$this->pack_price = $pack_price;

		$pricePack = null;

		$shippingSetting = $deviser->getShippingSettingByCountry($order->getShippingAddress()->country);

		if ($shippingSetting) {
			$price = $shippingSetting->getShippingSettingRange($pack_weight);
			if ($price) {
				switch ($this->shipping_type) {
					case 'standard':
						$pricePack = $price['price'];
						break;
					case 'express':
						$pricePack = $price['price_express'];
						break;
				}
			}
		}

		$this->shipping_price = $pricePack;
	}

	/**
	 * @param $priceStockId
	 * @return OrderProduct|null
	 */
	public function getPriceStockItem($priceStockId) {
		$products = $this->getProducts();
		foreach ($products as $item) {
			if ($item->price_stock_id == $priceStockId) {
				return $item;
			}
		}
		return null;
	}



	public function deleteProduct($priceStockId)
	{
		$products = $this->getProducts();
		foreach ($products as $i => $item) {
			if ($item->price_stock_id == $priceStockId) {
				unset($products[$i]);
			}
		}
		$this->setProducts($products);

		$this->recalculateTotals();

		return null;
	}

	public function subDocumentsConfig() {
		return [
			'products' => [
				'class' => OrderProduct::className(),
				'type' => 'list',
			],
		];
	}

	/**
	 * @return OrderProduct[]
	 */
	public function getProducts()
	{
		return $this->getSubDocument('products');
	}

	public function setProducts($value) {
		$this->setSubDocument('products', $value);
	}

	public function setState($newState)
	{
		if ($this->pack_state == $newState) {
			return;
		}
		$this->pack_state = $newState;
		$stateHistory = $this->state_history;
		$stateHistory[] =
			[
				'state' => $newState,
				'date' => new \MongoDate(),
			];
		$this->setAttribute('state_history', $stateHistory);
	}

}