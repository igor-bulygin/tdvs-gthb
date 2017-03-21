<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use yii\mongodb\ActiveQuery;

/**
 * @property string short_id
 * @property string order_state
 * @property string client_id
 * @property array client_info
 * @property array payment_info
 * @property array charges
 * @property array products
 * @property double subtotal
 * @property MongoDate created_at
 * @property MongoDate updated_at
 *
 * Mappings:
 * @property OrderClientInfo $clientInfoMapping
 * @property OrderProduct[] $productsMapping
 */
class Order extends CActiveRecord {

    const ORDER_STATE_CART = 'order_state_cart';
    const ORDER_STATE_PAID = 'order_state_paid';
    const ORDER_STATE_UNPAID = 'order_state_unpaid';

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $retrieveExtraFields = [];

	public static function collectionName() {
		return 'order';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'order_state',
			'client_id',
			'client_info',
			'payment_info',
			'charges',
			'products',
			'subtotal',
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

		// initialize attributes
		$this->products = [];
	}

    public function embedProductsMapping()
    {
        return $this->mapEmbeddedList('products', OrderProduct::className(), array('unsetSource' => false));
    }

    public function embedClientInfoMapping()
    {
        return $this->mapEmbedded('client_info', OrderClientInfo::className(), array('unsetSource' => false));
    }

	public function setParentOnEmbbedMappings()
	{
		$this->clientInfoMapping->setParentObject($this);

		foreach ($this->productsMapping as $product) {
			$product->setParentObject($this);
		}
	}

	public function beforeSave($insert) {
		if (empty($this->order_state)) {
			$this->order_state = Order::ORDER_STATE_CART;
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
                    'subtotal',
                ],
                'required',
            ],
		  	[   'products', 'safe'], // to load data posted from WebServices
            [   'productsMapping', 'app\validators\EmbedDocValidator'], // to apply rules
			[   'client_info', 'safe'], // to load data posted from WebServices
            [   'clientInfoMapping', 'app\validators\EmbedDocValidator'], // to apply rules
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
            case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
                static::$serializeFields = [
                    'id' => 'short_id',
					'order_state',
					'client_id',
					'client_info',
					'payment_info',
//					'charges',
					'products' => 'productsInfo',
					'subtotal',
                ];
                static::$retrieveExtraFields = [
					'products',
                ];

                static::$translateFields = false;
                break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	public function getProductsInfo() {
		$products = $this->products;

		$result = [];
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);
		foreach ($products as $p) {
			$product = Product2::findOneSerialized($p['product_id']);
			$deviser = Person::findOneSerialized($p['deviser_id']);
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

        // if deviser id is specified
        if ((array_key_exists("client_id", $criteria)) && (!empty($criteria["client_id"]))) {
            $query->andWhere(["client_id" => $criteria["client_id"]]);
        }

		// if order_state is specified
		if ((array_key_exists("order_state", $criteria)) && (!empty($criteria["order_state"]))) {
			$query->andWhere(["order_state" => $criteria["order_state"]]);
		}

        // if text is specified
        if ((array_key_exists("text", $criteria)) && (!empty($criteria["text"]))) {
//			// search the word in all available languages
			$query->andFilterWhere(static::getFilterForText(static::$textFilterAttributes, $criteria["text"]));
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
			$query->orderBy(["created_at" => "desc"]);
		}

        $orders = $query->all();

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
		$product = Product2::findOneSerialized($orderProduct->product_id); /* @var Product2 $product */
		if (empty($orderProduct)) {
			throw new Exception(sprintf("Product with id %s does not exists", $orderProduct->product_id));
		}
		$priceStock = $product->getPriceStockItem($orderProduct->price_stock_id);
		if (empty($priceStock)) {
			throw new Exception(sprintf("Price stock item with id %s does not exists", $orderProduct->price_stock_id));
		}

		$products = $this->productsMapping;
		$quantity = $orderProduct->quantity;
		$key = null;
		foreach ($products as $i => $item) {
			if ($item->price_stock_id == $orderProduct->price_stock_id) {
				$key = $i;
				$quantity += $item['quantity'];
				break;
			}
		}

		$orderProduct->deviser_id = $product->deviser_id;
		$orderProduct->quantity = $quantity;
		$orderProduct->weight = $priceStock['weight'];
		$orderProduct->price = $priceStock['price'];
		$orderProduct->options = $priceStock['options'];

		if (isset($key)) {
			$this->productsMapping[$key] = $orderProduct;
		} else {
			$this->productsMapping[] = $orderProduct;
		}
		$this->recalculateTotal();
	}

	public function updateProduct(OrderProduct $orderProduct) {
		$product = Product2::findOneSerialized($orderProduct->product_id); /* @var Product2 $product */
		if (empty($orderProduct)) {
			throw new Exception(sprintf("Product with id %s does not exists", $orderProduct->product_id));
		}
		$priceStock = $product->getPriceStockItem($orderProduct->price_stock_id);
		if (empty($priceStock)) {
			throw new Exception(sprintf("Price stock item with id %s does not exists", $orderProduct->price_stock_id));
		}

		$products = $this->productsMapping;
		$key = null;
		foreach ($products as $i => $item) {
			if ($item->price_stock_id == $orderProduct->price_stock_id) {
				$key = $i;
				break;
			}
		}
		$orderProduct->weight = $priceStock['weight'];
		$orderProduct->price = $priceStock['price'];
		$orderProduct->options = $priceStock['options'];
		$orderProduct->deviser_id = $product->deviser_id;

		if (isset($key)) {
			$this->productsMapping[$key] = $orderProduct;
		} else {
			$this->productsMapping[] = $orderProduct;
		}
		$this->recalculateTotal();
	}

	public function deleteProduct(OrderProduct $orderProduct) {
		$products = $this->productsMapping; /* @var \ArrayObject $products */
		$key = null;
		foreach ($products as $i => $item) {
			if ($item->price_stock_id == $orderProduct->price_stock_id) {
				$products->offsetUnset($i);
				break;
			}
		}
		$this->productsMapping = $products;
		$this->recalculateTotal();
	}

	public function recalculateTotal() {
		$products = $this->productsMapping;
		$subtotal = 0;
		foreach ($products as $product) {
			$subtotal += ($product->price * $product->quantity);
		}
		$this->subtotal = $subtotal;
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

	/**
	 * @param bool $send Sends the email
	 * @return PostmanEmail
	 */
	public function composeEmailOrderPaid($send) {
		$email = new PostmanEmail();
		$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_ORDER_PAID;
		$email->to_email = $this->clientInfoMapping->email;
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

}
