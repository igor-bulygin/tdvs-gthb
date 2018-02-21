<?php
namespace app\models;

use app\helpers\EmailsHelper;
use app\helpers\SmsHelper;
use app\helpers\Utils;
use yii\web\BadRequestHttpException;

/**
 *
 * @property int $deviser_id
 * @property string $short_id
 * @property double $pack_weight
 * @property string $shipping_type
 * @property array $shipping_info
 * @property double $shipping_price
 * @property double $pack_price
 * @property double $pack_total_price
 * @property double $pack_percentage_fee_todevise
 * @property double $pack_percentage_fee_vat
 * @property double $pack_percentage_fee
 * @property double $pack_total_fee_todevise
 * @property double $pack_total_fee_vat
 * @property double $pack_total_fee
 * @property string $currency
 * @property string $weight_measure
 * @property string $pack_state
 * @property array $charge_info
 * @property array $products
 * @property array $state_history
 * @property string $invoice_url
 * @property array $scheduled_emails
 * @property array $sms_sent
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
			'pack_weight',
			'shipping_type',
			'shipping_info',
			'shipping_price',
			'pack_price',
			'pack_total_price',
			'pack_percentage_fee_todevise',
			'pack_percentage_fee_vat',
			'pack_percentage_fee',
			'pack_total_fee_todevise',
			'pack_total_fee_vat',
			'pack_total_fee',
			'currency',
			'weight_measure',
			'pack_state',
			'charge_info',
			'products',
			'state_history',
			'invoice_url',
			'scheduled_emails',
			'sms_sent',
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
				'on' => [ORDER::SCENARIO_CART, ORDER::SCENARIO_ORDER],
			],
			[
				[
					'invoice_url',
				],
				'validateInvoiceUrl',
				'skipOnEmpty' => false,
				'when' => function($model) {
					return $model->pack_state == OrderPack::PACK_STATE_SHIPPED;
				},
				'on' => [ORDER::SCENARIO_CART, ORDER::SCENARIO_ORDER],
			],
		];
	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateInvoiceUrl($attribute, $params)
	{
		if (empty($this->invoice_url)) {
			$this->addError('invoice_url', 'Invoice url is required to set this pack as shipped.');
		} elseif (!$this->getDeviser()->existMediaFile($this->invoice_url)) {
			$this->addError('invoice_url', sprintf('File %s does not exists.', $this->invoice_url));
		}
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

			case Order::SERIALIZE_SCENARIO_CLIENT_ORDER:
				self::$serializeFields = [
					'short_id',
					'deviser_id',
					'deviser_info' => 'deviserInfo',
					'shipping_type',
					'shipping_price',
					'shipping_info',
					'pack_weight',
					'pack_price',
					'currency',
					'weight_measure',
					'pack_state',
					'pack_state_name' => 'packStateName',
					'shipping_date' => 'shippingDate',
					'invoice_link' => 'invoiceLink',

					'products' => 'productsInfo',
				];
				self::$retrieveExtraFields = [
					'products',
					'invoice_url',
				];


				self::$translateFields = false;
				break;

			case self::SERIALIZE_SCENARIO_ADMIN:
			case Order::SERIALIZE_SCENARIO_DEVISER_PACK:
				self::$serializeFields = [
					'short_id',
					'deviser_id',
					'deviser_info' => 'deviserInfo',
					'pack_weight',
					'shipping_type',
					'shipping_info',
					'shipping_price',
					'pack_price',
					'pack_total_price',
					'pack_percentage_fee_todevise',
					'pack_percentage_fee_vat',
					'pack_percentage_fee',
					'pack_total_fee_todevise',
					'pack_total_fee_vat',
					'pack_total_fee',
					'currency',
					'weight_measure',
					'pack_state',
					'pack_state_name' => 'packStateName',
					'shipping_date' => 'shippingDate',
					'invoice_link' => 'invoiceLink',
					'charge_info',
					'products',
					'state_history',
					'invoice_url',
					'products' => 'productsInfo',
				];
				self::$retrieveExtraFields = [
					'products',
					'invoice_url',
				];


				self::$translateFields = false;
				break;

			default:
				// now available for this Model
				self::$serializeFields = [];
				break;
		}
	}

	public function getShippingDate()
	{
		foreach ($this->state_history as $state) {
			if ($state['state'] == OrderPack::PACK_STATE_SHIPPED) {
				return $state['date'];
			}
		}

		return null;
	}

	public function getInvoiceLink()
	{
		if ($this->invoice_url) {
			return $this->getDeviser()->getDownloadFileUrl($this->invoice_url);
		}

		return null;
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
			"photo" => $deviser->getProfileImage(),
			'url' => $deviser->getMainLink(),
		];

		$shippingSetting = $deviser->getShippingSettingByCountry($order->getShippingAddress()->country);

		if ($shippingSetting) {
			$price = $shippingSetting->getShippingSettingRange($this->pack_price, $this->pack_weight);
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
					'photo' => $product->getImagePreview(0, 0),
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
			throw new BadRequestHttpException(sprintf("Price stock item with id %s does not exists", $orderProduct->price_stock_id));
		}
		if (!isset($priceStock['available']) || !$priceStock['available']) {
			throw new BadRequestHttpException(sprintf("Price stock item with id %s is not available", $orderProduct->price_stock_id));
		}

		$found = false;
		$products = $this->getProducts();
		foreach ($products as $item) {
			if ($item->price_stock_id == $orderProduct->price_stock_id) {
				$newQuantity = $item->quantity + $orderProduct->quantity;
				if ($priceStock['stock'] !== null && $newQuantity > $priceStock['stock']) {
					throw new BadRequestHttpException(sprintf("Stock %s unavailable. Available stock is %s", $newQuantity, $priceStock['stock']));
				}
				$item->quantity = $newQuantity;
				$found = true;
			}
		}
		if (!$found) {
			if ($priceStock['stock'] !== null && $orderProduct->quantity > $priceStock['stock']) {
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
			$price = $shippingSetting->getShippingSettingRange($this->pack_price, $this->pack_weight);
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
//		if ($this->pack_state == $newState) {
//			return;
//		}

		if ($newState == OrderPack::PACK_STATE_AWARE) {
			$this->cancelEmailsNewOrder();
			$this->scheduleEmailsNoShipped();
		}
		if ($newState == OrderPack::PACK_STATE_SHIPPED) {
			$this->cancelEmailsNoShipped();
			EmailsHelper::clientOrderShipped($this->getParentObject(), $this->short_id);
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

	public function scheduleEmailsNoShipped()
	{
		$order = $this->getParentObject();
		$scheduledEmails['deviser_no_shipped'][] = EmailsHelper::deviserOrderNoShippedReminder24($order, $this->short_id);
		$scheduledEmails['deviser_no_shipped'][] = EmailsHelper::deviserOrderNoShippedReminder48($order, $this->short_id);
		$scheduledEmails['deviser_no_shipped'][] = EmailsHelper::deviserOrderNoShippedReminder72($order, $this->short_id);
		$scheduledEmails['todevise_no_shipped'][] = EmailsHelper::todeviseOrderNoShippedReminder96($order, $this->short_id);
		$this->setAttribute('scheduled_emails', $scheduledEmails);
	}


	public function cancelEmailsNewOrder()
	{
		$scheduledEmails = $this->scheduled_emails;
		if (isset($scheduledEmails['deviser_new_order'])) {
			foreach ($scheduledEmails['deviser_new_order'] as $email) {
				EmailsHelper::cancelScheduled($email['_id']);
			}
		}
		if (isset($scheduledEmails['todevise_new_order'])) {
			foreach ($scheduledEmails['todevise_new_order'] as $email) {
				EmailsHelper::cancelScheduled($email['_id']);
			}
		}
	}

	public function cancelEmailsNoShipped()
	{
		$scheduledEmails = $this->scheduled_emails;
		if (isset($scheduledEmails['deviser_no_shipped'])) {
			foreach ($scheduledEmails['deviser_no_shipped'] as $email) {
				EmailsHelper::cancelScheduled($email['_id']);
			}
		}
		if (isset($scheduledEmails['todevise_no_shipped'])) {
			foreach ($scheduledEmails['todevise_no_shipped'] as $email) {
				EmailsHelper::cancelScheduled($email['_id']);
			}
		}
	}

	public function setInvoice($invoiceUrl)
	{
		$this->invoice_url = $invoiceUrl;
	}

	public function setPackShippingInfo($data)
	{
		$shippingInfo = [
			'company' => isset($data['company']) ? $data['company'] : null,
			'tracking_number' => isset($data['tracking_number']) ? $data['tracking_number'] : null,
			'tracking_link' => isset($data['tracking_link']) ? $data['tracking_link'] : null,
		];
		$this->setAttribute('shipping_info', $shippingInfo);
	}

	public function setInvoiceInfo($invoiceUrl)
	{
		$this->invoice_url = $invoiceUrl;
	}


	public function sendSmsNewOrder()
	{
		try {
			$order = $this->getParentObject();
			$result = SmsHelper::deviserNewOrder($order, $this->short_id);
			$message = '"'.$result['body'].'" sent to ' . $result['to'];
			$result = 'sent';
		} catch (\Exception $e) {
			$result = 'error';
			$message = $e->getMessage();
		}

		$sms_sent = $this->sms_sent;
		$sms_sent['deviser_new_order'][date('Y-m-d H:i:s')] = [
			'result' => $result,
			'message' => $message,
		];

		$this->setAttribute('sms_sent', $sms_sent);
	}

	public function sendSmsNewOrderReminder72()
	{
		try {
			$order = $this->getParentObject();

			$result = SmsHelper::deviserNewOrderReminder72($order, $this->short_id);
			$message = '"'.$result['body'].'" sent to ' . $result['to'];
			$result = 'sent';
		} catch (\Exception $e) {
			$result = 'error';
			$message = $e->getMessage();
		}

		$sms_sent = $this->sms_sent;
		$sms_sent['deviser_new_order_reminder_72'][date('Y-m-d H:i:s')] = [
			'result' => $result,
			'message' => $message,
		];

		$this->setAttribute('sms_sent', $sms_sent);
	}

	public function hasSentSmsNewOrder()
	{
		if (is_array($this->sms_sent) && isset($this->sms_sent['deviser_new_order'])) {
			foreach ($this->sms_sent['deviser_new_order'] as $item) {
				if (isset($item['result']) && $item['result'] == 'sent') {
					return true;
				}
			}
		}

		return false;
	}

	public function hasSentSmsNewOrderReminder72()
	{
		if (is_array($this->sms_sent) && isset($this->sms_sent['deviser_new_order_reminder_72'])) {
			foreach ($this->sms_sent['deviser_new_order_reminder_72'] as $item) {
				if (isset($item['result']) && $item['result'] == 'sent') {
					return true;
				}
			}
		}

		return false;
	}
}