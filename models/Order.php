<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use yii\mongodb\ActiveQuery;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

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
 * @property array state_history
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
    const ORDER_STATE_FAILED = 'order_state_failed';

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
			'state_history',

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
			$this->setState(Order::ORDER_STATE_CART);
		}

		if (empty($this->order_date)) {
			$this->order_date = new MongoDate();
		}

		if (empty($this->billing_address)) {
			$this->billing_address = $this->shipping_address;
		}

		if ($insert) {
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
				'on' => [self::SCENARIO_CART, self::SCENARIO_ORDER],
			],
			[
				[
					'packs',
				],
				'app\validators\SubDocumentValidator',
				'on' => [self::SCENARIO_CART, self::SCENARIO_ORDER],
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
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'person_id',
					'person_info' => 'personInfo',
					'subtotal',
					'order_state',
					'order_date',
					'shipping_address',
					'billing_address',
					'packs',
					'payment_info',
					'state_history',

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
					'person_info' => 'personInfo',
					'subtotal',
					'order_state',
					'order_date',
					'shipping_address',
					'billing_address',
					'packs',
					'payment_info',
//					'state_history',

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
//					'state_history',

//					'charges',
				];
				static::$retrieveExtraFields = [
					'subtotal',
					'order_state',
					'payment_info',
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
		if ($person) {
			return [
				"slug" => $person->slug,
				"name" => $person->personalInfoMapping->getVisibleName(),
				"photo" => $person->getProfileImage(),
				'url' => $person->getMainLink(),
			];
		}
		return [];
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
     * @return Order[]
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
        	if ($criteria['pack_state'] == 'open') {
				$query->andWhere(["in", "packs.pack_state", [OrderPack::PACK_STATE_PAID, OrderPack::PACK_STATE_AWARE]]);
			} elseif ($criteria['pack_state'] == 'past') {
				$query->andWhere(["in", "packs.pack_state", [OrderPack::PACK_STATE_SHIPPED]]);
			} else {
				$query->andWhere(["packs.pack_state" => $criteria["pack_state"]]);
			}
		}

		// if deviser id is specified
		if ((array_key_exists("pack_id", $criteria)) && (!empty($criteria["pack_id"]))) {
			$query->andWhere(["packs.short_id" => $criteria["pack_id"]]);
		}

		// if order_state is specified
		if ((array_key_exists("order_state", $criteria)) && (!empty($criteria["order_state"]))) {
			$query->andWhere(["order_state" => $criteria["order_state"]]);
		}

		if ((array_key_exists("order_date_from", $criteria)) && (!empty($criteria["order_date_from"]))) {
			$query->andWhere([">", "order_date", $criteria["order_date_from"]]);
		}

		if ((array_key_exists("order_date_to", $criteria)) && (!empty($criteria["order_date_to"]))) {
			$query->andWhere(["<", "order_date", $criteria["order_date_to"]]);
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
				$packs = array_values($packs); // reorder indexes
				$order->setPacks($packs);
			}
		}

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($orders);
        }
        return $orders;
    }


    public function beforeValidate()
	{
		if ($this->scenario == 'default') {
			if ($this->isOrder()) {
				$this->setScenario(Order::SCENARIO_ORDER);
			} else {
				$this->setScenario(Order::SCENARIO_CART);
			}
		}

		return parent::beforeValidate();
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
			throw new BadRequestHttpException(sprintf("Product with id %s does not exists", $orderProduct->product_id));
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
		$this->save();
	}

	public function recalculateTotals() {
		$packs = $this->getPacks();
		$subtotal = 0;

		foreach ($packs as $i => $pack) {
			$subtotal += ($pack->pack_price + $pack->shipping_price);[];
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
	 * Returns TRUE if the order is in state "cart"
	 *
	 * @return bool
	 */
	public function isCart()
	{
		if ($this->order_state != Order::ORDER_STATE_CART) {
			return false;
		}

		return true;
	}


	/**
	 * Returns TRUE if the order is in state "order"
	 *
	 * @return bool
	 */
	public function isOrder()
	{
		if ($this->order_state != Order::ORDER_STATE_PAID) {
			return false;
		}

		return true;
	}


	/**
	 * Returns TRUE if the order is in state "failed"
	 *
	 * @return bool
	 */
	public function isFailed()
	{
		if ($this->order_state != Order::ORDER_STATE_FAILED) {
			return false;
		}

		return true;
	}


	/**
	 * Returns TRUE if the order can be edited by the current user
	 *
	 * @return bool
	 */
	public function isEditable()
	{
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
		return $this->getSubDocument('packs');
	}

	public function setPacks($value) {
		$this->setSubDocument('packs', $value);
		$this->recalculateTotals();
	}

	/**
	 * @return OrderAddress
	 */
	public function getShippingAddress()
	{
		return $this->getSubDocument('shipping_address');
	}

	public function setShippingAddress($value) {
		$this->setSubDocument('shipping_address', $value);
	}

	/**
	 * @return OrderAddress
	 */
	public function getBillingAddress()
	{
		return $this->getSubDocument('billing_address');
	}

	public function setBillingAddress($value) {
		$this->setSubDocument('billing_address', $value);
	}

	public function getPack($packId) {
		$packs = $this->getPacks();
		foreach ($packs as $pack) {
			if ($pack->short_id == $packId) {
				return $pack;
			}
		}

		return null;
	}

	public function setPack($packId, $pack)
	{
		$packs = $this->getPacks();
		$found = false;
		foreach ($packs as $k => $onePack) {
			if ($onePack->short_id == $packId) {
				$found = true;
				$packs[$k] = $pack;
			}
		}

		if (!$found) {
			throw new NotFoundHttpException(sprintf('Pack with id %s not found', $packId));
		}

		$this->setPacks($packs);
		$this->save();
	}

	public function checkOwnerAndTryToAssociate()
	{
		if (!\Yii::$app->user->isGuest) {
			$person = \Yii::$app->user->identity; /* @var Person $person */
			if (empty($this->person_id)) {

				if (empty($this->shipping_address)) {
					$shipping = $this->getShippingAddress();
					$shipping->copyValuesFromPerson($person);
					$this->setShippingAddress($shipping);
				}

				$this->person_id = $person->short_id;
				$this->save();

			} elseif ($this->person_id != $person->short_id) {
				throw new UnauthorizedHttpException();
			}
		} else {
			if (!empty($this->person_id)) {
				throw new UnauthorizedHttpException();
			}
		}
	}

	public function setState($newState)
	{
		if ($this->order_state == $newState) {
			return;
		}
		if ($newState == Order::ORDER_STATE_PAID) {
			$this->order_date = new MongoDate();
		}
		$this->order_state = $newState;
		$stateHistory = $this->state_history;
		$stateHistory[] =
			[
				'state' => $newState,
				'date' => new \MongoDate(),
			];
		$this->setAttribute('state_history', $stateHistory);
	}

}
