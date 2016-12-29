<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use Yii;
use yii\mongodb\ActiveQuery;

/**
 * @property string short_id
 * @property string order_state
 * @property string client_id
 * @property array client_info
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

    /**
     * Load sub documents after find the object
     *
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();
    }

	public function beforeValidate()
	{
		$this->clientInfoMapping->setModel($this);

		foreach ($this->productsMapping as $product) {
			$product->setModel($this);
		}
		return parent::beforeValidate();
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
			$p['deviser_name'] = $deviser->name;
			$p['deviser_photo'] = $deviser->getAvatarImage();
			$result[] = $p;
		}

		return $result;
	}

    /**
     * Get one entity serialized
     *
     * @param string $id
     * @return Product2|null
     * @throws Exception
     */
    public static function findOneSerialized($id)
    {
        /** @var Product $product */
        $product = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($product);
        }
        return $product;
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

        // Products query
        $query = new ActiveQuery(static::className());

        // Retrieve only fields that gonna be used
        $query->select(self::getSelectFields());

        // if product id is specified
        if ((array_key_exists("id", $criteria)) && (!empty($criteria["id"]))) {
            $query->andWhere(["short_id" => $criteria["id"]]);
        }

        // if deviser id is specified
        if ((array_key_exists("client_id", $criteria)) && (!empty($criteria["client_id"]))) {
            $query->andWhere(["client_id" => $criteria["client_id"]]);
        }

		// if product_state is specified
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

        $products = $query->all();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($products);
        }
        return $products;
    }

	/**
	 * Spread data for sub documents
	 *
	 * @param array $data
	 * @param null $formName
	 * @return bool
	 */
	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);

//		if (array_key_exists('products', $data)) {
//            $this->productsMapping->load($data, 'products');
//        }
//
//		if (array_key_exists('client_info', $data)) {
//			$this->clientInfoMapping->load($data, 'client_info');
//		}

		return ($loaded);
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
                    //TODO: Fix this! Find other way to determine if was a "required" field
                    if (strpos($error[0], 'cannot be blank') !== false || strpos($error[0], 'no puede estar vacío') !== false) {
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
				$item->setModel($this);
				return $item;
			}
		}
		return null;
	}

}