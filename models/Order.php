<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use yii\mongodb\ActiveQuery;

/**
 * @property string short_id
 * @property string person_id
 * @property double subtotal
 * @property string order_state
 * @property MongoDate order_date
 * @property array shipping_address
 * @property array billing_address
 * @property array packs
 * @property array payment_info
 * @property array charges
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Order extends CActiveRecord {

	const SERIALIZE_SCENARIO_CLIENT_ORDER= 'serialize_scenario_client_order';
	const SERIALIZE_SCENARIO_DEVISER_PACK = 'serialize_scenario_deviser_pack';

	const SCENARIO_CART = 'scenario-cart';
	const SCENARIO_ORDER = 'scenario-order';

    const ORDER_STATE_CART = 'order_state_cart';
    const ORDER_STATE_PAID = 'order_state_paid';
    const ORDER_STATE_UNPAID = 'order_state_unpaid';

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

	public static function collectionName() {
		return 'order';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'person_id',
			'subtotal',
			'order_state',
			'order_date',
			'shipping_address',
			'billing_address',
			'packs',

			'payment_info',
			'charges',

			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = [];

	public static $textFilterAttributes = [];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

	}

	public function beforeSave($insert)
	{
		if (empty($this->order_state)) {
			$this->order_state = Order::ORDER_STATE_CART;
		}

		if (empty($this->order_date)) {
			$this->order_date = new MongoDate();
		}

		if (empty($this->created_at)) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

    public function rules()
	{
		return [
			[
				[
					'shipping_address',
					'billing_address',
					'packs',
				],
				'safe',
				'on' => self::SCENARIO_CART,
			],
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
			case self::SERIALIZE_SCENARIO_PREVIEW:
			case self::SERIALIZE_SCENARIO_PUBLIC:
			case self::SERIALIZE_SCENARIO_ADMIN:
			case self::SERIALIZE_SCENARIO_OWNER:
				static::$serializeFields = [
					'id' => 'short_id',
					'person_id',
					'subtotal',
					'order_state',
					'order_date',
					'shipping_address',
					'billing_address',
					'packs',

//					'payment_info',
//					'charges',
				];
				static::$retrieveExtraFields = [
				];


				static::$translateFields = false;
				break;

			case self::SERIALIZE_SCENARIO_CLIENT_ORDER:
				static::$serializeFields = [
					'id' => 'short_id',
					'person_id',
//					'person_info' => 'personInfo',
					'subtotal',
					'order_state',
					'order_date',
					'shipping_address',
					'billing_address',
					'packs',

//					'payment_info',
//					'charges',
				];
				static::$retrieveExtraFields = [
				];


				static::$translateFields = false;
				break;

			case self::SERIALIZE_SCENARIO_DEVISER_PACK:
				static::$serializeFields = [
					'id' => 'short_id',
					'person_id',
					'person_info' => 'personInfo',
//					'subtotal',
//					'order_state',
					'order_date',
					'shipping_address',
					'billing_address',
					'packs',

//					'payment_info',
//					'charges',
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
		OrderPack::setSerializeScenario($view);
//		OrderAddress::setSerializeScenario($view);
	}

	/**
	 * @return Person
	 */
	public function getPerson()
	{
		return Person::findOne(['short_id' => $this->person_id]);
	}

	public function getPersonInfo()
	{
		$person = $this->getPerson();
		return [
			"slug" => $person->slug,
			"name" => $person->personalInfoMapping->getVisibleName(),
			"photo" => $person->getAvatarImage128(),
			'url' => $person->getMainLink(),
		];
	}

	/**
     * Get one entity serialized
     *
     * @param string $id
     * @return Order|null
     * @throws Exception
     */
    public static function findOneSerialized($id)
    {
        /** @var Order $order */
        $order = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($order);
        }
        return $order;
    }

    /**
     * Get a collection of entities serialized, according to serialization configuration
     *
     * @param array $criteria
     * @return array
     * @throws Exception
     */
    public static function findSerialized($criteria = [])
    {

        // Order query
        $query = new ActiveQuery(static::className());

        // Retrieve only fields that gonna be used
        $query->select(self::getSelectFields());

        // if order id is specified
        if ((array_key_exists("id", $criteria)) && (!empty($criteria["id"]))) {
            $query->andWhere(["short_id" => $criteria["id"]]);
        }

        // if person id is specified
        if ((array_key_exists("person_id", $criteria)) && (!empty($criteria["person_id"]))) {
            $query->andWhere(["person_id" => $criteria["person_id"]]);
        }

		// if deviser id is specified
		if ((array_key_exists("deviser_id", $criteria)) && (!empty($criteria["deviser_id"]))) {
			$query->andWhere(["packs.deviser_id" => $criteria["deviser_id"]]);
		}

		// if deviser id is specified
		if ((array_key_exists("product_id", $criteria)) && (!empty($criteria["product_id"]))) {
			$query->andWhere(["packs.products.product_id" => $criteria["product_id"]]);
		}

		// if deviser id is specified
		if ((array_key_exists("pack_state", $criteria)) && (!empty($criteria["pack_state"]))) {
			$query->andWhere(["packs.pack_state" => $criteria["pack_state"]]);
		}

		// if order_state is specified
		if ((array_key_exists("order_state", $criteria)) && (!empty($criteria["order_state"]))) {
			$query->andWhere(["order_state" => $criteria["order_state"]]);
		}

        // Count how many items are with those conditions, before limit them for pagination
        static::$countItemsFound = $query->count();

        // limit
        if ((array_key_exists("limit", $criteria)) && (!empty($criteria["limit"]))) {
            $query->limit($criteria["limit"]);
        }

        // offset for pagination
        if ((array_key_exists("offset", $criteria)) && (!empty($criteria["offset"]))) {
            $query->offset($criteria["offset"]);
        }

		if ((array_key_exists("order_by", $criteria)) && (!empty($criteria["order_by"]))) {
			$query->orderBy($criteria["order_by"]);
		} else {
			$query->orderBy([
				"created_at" => SORT_DESC,
			]);
		}

        $orders = $query->all();

		if ((array_key_exists("only_matching_packs", $criteria)) && (!empty($criteria["only_matching_packs"]))) {
			// Remove all non matching packs with deviser_id
			foreach ($orders as $order) {
				/* @var $order Order */
				$packs = $order->getPacks();
				foreach ($packs as $i => $pack) {
					if ($pack->deviser_id != $criteria['deviser_id']) {
						unset($packs[$i]);
					}
				}
				$order->setPacks($packs);
			}
		}

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($orders);
        }
        return $orders;
    }


    /**
     * Add additional error to make easy show labels in client side
     */
    public function afterValidate()
    {
        parent::afterValidate();
        foreach ($this->errors as $attribute => $error) {
            switch ($attribute) {
                default:
					if (Utils::isRequiredError($error)) {
						$this->addError("required", $attribute);
					}
					$this->addError("fields", $attribute);
                    break;
            }
        };
    }

	public function addProduct(OrderProduct $orderProduct) {
		$product = Product::findOneSerialized($orderProduct->product_id); /* @var Product $product */
		if (empty($product)) {
			throw new Exception(sprintf("Product with id %s does not exists", $orderProduct->product_id));
		}

		$packs = $this->getPacks();

		$found = false;
		foreach ($packs as $onePack) {
			if ($onePack->deviser_id == $product->deviser_id) {
				$onePack->addProduct($orderProduct);
				$found = true;
				break;
			}
		}

		if (!$found) {

			$deviser = $product->getDeviser();
			$pack = new OrderPack();
			$pack->setParentObject($this);
			$pack->deviser_id = $product->deviser_id;
			$pack->currency = $deviser->settingsMapping->currency;
			$pack->weight_measure = $deviser->settingsMapping->weight_measure;
			$pack->addProduct($orderProduct);
			$packs[] = $pack;
		}
		$this->setPacks($packs);

		$this->recalculateTotals();
		$this->save();
	}

	public function deleteProduct($priceStockId) {

    	$packs = $this->getPacks();
    	foreach ($packs as $pack) {
    		$pack->deleteProduct($priceStockId);
		}

    	// Remove empty packs
		foreach ($packs as $i => $pack) {
			if (count($pack->getProducts()) == 0) {
				unset($packs[$i]);
			}
		}
		$this->setPacks($packs);

		$this->recalculateTotals();
		$this->save();
	}

	public function recalculateTotals() {
		$packs = $this->getPacks();
		$subtotal = 0;

		foreach ($packs as $i => $pack) {
			$subtotal += ($pack->pack_price);[];
		}

		$this->subtotal = $subtotal;
	}

	/**
	 * @param bool $send Sends the email
	 * @return PostmanEmail
	 */
	public function composeEmailOrderPaid($send) {
		$email = new PostmanEmail();
		$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_ORDER_PAID;
		$email->to_email = $this->getPerson()->credentials['email'];
		$email->subject = 'Todevise - '.$this->short_id.' - Your purchase is complete';

		// add task only one send task (to allow retries)
		$task = new PostmanEmailTask();
		$task->date_send_scheduled = new MongoDate();
		$email->addTask($task);

		$email->body_html = \Yii::$app->view->render(
			'@app/mail/order/order-paid',
			[
				"order" => $this,
			],
			$this
		);
		$email->save();

		if ($send) {
			$email->send($task->id);
		}

		return $email;
	}

	/**
	 * Returns the payment method of the order (if exists)
	 *
	 * @return string
	 */
	public function getPaymentMethod()
	{
		if (!empty($this->payment_info) && isset($this->payment_info['card'])) {
			return $this->payment_info['card']['brand'] . ' **** ' . $this->payment_info['card']['last4'];
		}

		return '';
	}



	/**
	 * Returns TRUE if the person can be edited by the current user
	 *
	 * @return bool
	 */
	public function isCartEditable()
	{
		if ($this->order_state != Order::ORDER_STATE_CART) {
			return false;
		}
		if (\Yii::$app->user->isGuest) {
			if (!empty($this->person_id)) {
				return false;
			}
		} elseif (\Yii::$app->user->identity->short_id != $this->person_id) {
			return false;
		}

		return true;
	}

	public function subDocumentsConfig() {
		return [
			'packs' => [
				'class' => OrderPack::className(),
				'type' => 'list',
			],
			'shipping_address' => [
				'class' => OrderAddress::className(),
				'type' => 'single',
			],
			'billing_address' => [
				'class' => OrderAddress::className(),
				'type' => 'single',
			],
		];
	}

	/**
	 * @return OrderPack[]
	 */
	public function getPacks()
	{
		$embedded =$this->getSubDocument('packs');

		return $embedded;
	}

	public function setPacks($value) {
		$this->setSubDocument('packs', $value);
	}

	/**
	 * @return OrderAddress
	 */
	public function getShippingAddress()
	{
		$embedded = $this->getSubDocument('shipping_address');

		return $embedded;
	}

	public function setShippingAddress($value) {
		$this->setSubDocument('shipping_address', $value);
	}

	/**
	 * @return OrderAddress
	 */
	public function getBillingAddress()
	{
		$embedded = $this->getSubDocument('billing_address');

		return $embedded;
	}

	public function setBillingAddress($value) {
		$this->setSubDocument('billing_address', $value);
	}

}
