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
 * @property string $person_id
 * @property string name
 * @property string description
 * @property BoxProduct[] productsMapping
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Box extends CActiveRecord
{

	const SCENARIO_BOX_CREATE = 'scenario-box-create';
	const SCENARIO_BOX_ADD_PRODUCT= 'scenario-box-add-product';

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

	public static function collectionName()
	{
		return 'box';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'person_id',
			'name',
			'description',
			'products',
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

		$this->short_id = Utils::shortID(7);

		$this->products = [];
	}

	public function embedProductsMapping()
	{
		return $this->mapEmbeddedList('products', BoxProduct::className(), array('unsetSource' => false));
	}

	public function setParentOnEmbbedMappings()
	{
		foreach ($this->productsMapping as $item) {
			$item->setParentObject($this);
		}
	}

	public function beforeSave($insert)
	{
		if (empty($this->created_at)) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
	}

	public function afterDelete()
	{
		$products = $this->getProducts();
		foreach ($products as $item) {
			$product = Product2::findOneSerialized($item->product_id);
			if ($product) {
				$collection = Yii::$app->mongodb->getCollection('product');
				$collection->update(
					[
						'short_id' => $product->short_id
					],
					[
						'boxes' => $product->boxes - 1
					]
				);
			}
		}

		parent::afterDelete();
	}

	public function rules()
	{
		return [
			[$this->attributes(), 'safe'],
			[['person_id', 'name'], 'required', 'on' => [self::SCENARIO_BOX_CREATE]],
			[['person_id'], 'validatePersonExists'],
			[['products'], 'validateProductsExists', 'on' => [self::SCENARIO_BOX_ADD_PRODUCT]],
			[
				'productsMapping',
				'yii2tech\embedded\Validator',
				'on' => [self::SCENARIO_BOX_ADD_PRODUCT],
			],
		];
	}

	/**
	 * Custom validator for person_id
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validatePersonExists($attribute, $params)
	{
		$person_id = $this->$attribute;
		$person = Person::findOneSerialized($person_id);
		if (!$person) {
			$this->addError($attribute, sprintf('Person %s not found', $person_id));
		}
	}

	/**
	 * Custom validator for product_id
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateProductsExists($attribute, $params)
	{
		$products = $this->$attribute;
		foreach ($products as $item) {
			$product = Product2::findOneSerialized($item['product_id']);
			if (!$product) {
				$this->addError($attribute, sprintf('Product %s not found', $item->product_id));
			}
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
			case self::SERIALIZE_SCENARIO_PREVIEW:
			case self::SERIALIZE_SCENARIO_PUBLIC:
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:

				static::$serializeFields = [
					'id' => 'short_id',
					'person_id',
					'person' => "personPreview",
					'name',
					'description',
					'products' => "productsPreview",
				];

				static::$retrieveExtraFields = [
					'products'
				];

				static::$translateFields = false;

				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return Box|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Box $box */
		$box = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($box);
		}

		return $box;
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 *
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

		// if person id is specified
		if ((array_key_exists("person_id", $criteria)) && (!empty($criteria["person_id"]))) {
			$query->andWhere(["person_id" => $criteria["person_id"]]);
		}

		// if product id is specified
		if ((array_key_exists("product_id", $criteria)) && (!empty($criteria["product_id"]))) {
			$query->andWhere(["product.product_id" => $criteria["product_id"]]);
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
			$query->orderBy(["created_at" => SORT_DESC]);
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
	 *
	 * @return bool
	 */
	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);

		return ($loaded);
	}


	/**
	 * Get the products related with this box
	 *
	 * @return Product2[]
	 */
	public function getProducts()
	{
		$return = [];
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);
		$products = $this->productsMapping;
		foreach ($products as $item) {
			$product = Product2::findOneSerialized($item->product_id);
			$return[$item->created_at.'_'.$item->product_id] = $product;
		}
		ksort($return); // Sort by key, to force products in creation order
		$return = array_reverse($return); // Reverse to return in inverse order of creation

		return $return;
	}


	/**
	 * Get a preview version of the products related with this box
	 *
	 * @return array
	 */
	public function getProductsPreview()
	{
		$return = [];

		$products = $this->getProducts();

		foreach ($products as $product) {
			$return[] = $product->getPreviewSerialized();
		}

		return $return;
	}
	
	public function getPerson() {
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);

		$person = Person::findOneSerialized($this->person_id);

		return $person;
	}

	public function getPersonPreview() {
		$person = $this->getPerson();

		return $person->getPreviewSerialized();
	}

	/**
	 * Returns TRUE if the box object can be edited by the current user
	 *
	 * @return bool
	 */
	public function isEditable()
	{
		if (Yii::$app->user->isGuest) {
			return false;
		}
		if (Yii::$app->user->identity->isAdmin()) {
			return true;
		}
		if (Yii::$app->user->identity->getId() == $this->person_id) {
			return true;
		}

		return false;
	}

	/**
	 * @param BoxProduct $boxProduct
	 *
	 * @throws Exception
	 */
	public function addProduct($boxProduct)
	{
		$product = Product2::findOneSerialized($boxProduct->product_id);
		/* @var Product2 $product */
		if (empty($product)) {
			throw new Exception(sprintf("Product with id %s does not exists", $boxProduct->product_id));
		}

		$products = $this->productsMapping;
		$key = null;
		foreach ($products as $i => $item) {
			if ($item->product_id == $boxProduct->product_id) {
				$key = $i;
				break;
			}
		}
		if (!isset($key)) {
			$this->productsMapping[] = $boxProduct;

			$this->save();

			$collection = Yii::$app->mongodb->getCollection('product');
			$collection->update(
				[
					'short_id' => $product->short_id
				],
				[
					'boxes' => $product->boxes + 1
				]
			);
		}
	}

	public function deleteProduct($productId) {
		$products = $this->productsMapping; /* @var \ArrayObject $products */
		$key = null;
		foreach ($products as $i => $item) {
			if ($item->product_id == $productId) {
				$products->offsetUnset($i);
				break;
			}
		}
		$this->productsMapping = $products;

		$this->save();
	}
}