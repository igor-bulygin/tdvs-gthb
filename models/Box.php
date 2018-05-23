<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use Yii;
use yii\helpers\Url;
use yii\mongodb\ActiveQuery;

/**
 * @property string short_id
 * @property string $person_id
 * @property string name
 * @property string description
 * @property int loveds
 * @property BoxProduct[] productsMapping
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Box extends CActiveRecord
{

	const SCENARIO_BOX_CREATE = 'scenario-box-create';
	const SCENARIO_BOX_UPDATE = 'scenario-box-update';
	const SCENARIO_BOX_ADD_PRODUCT= 'scenario-box-add-product';

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
			'loveds',
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

	/**
	 * The attributes that should be used when a keyword search is done
	 *
	 * @var array
	 */
	public static $textFilterAttributes = ['name', 'description'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(7);

		$this->products = [];

		$this->loveds = 0;
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
		if (!isset($this->loveds)) {
			$this->loveds = 0;
		}

		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
	}

	public function beforeDelete() {

		$loveds = $this->getLoveds();
		foreach ($loveds as $loved) {
			$loved->delete();
		}

		return parent::beforeDelete();
	}

	public function afterDelete()
	{
		$products = $this->getProducts();
		foreach ($products as $product) {
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

		parent::afterDelete();
	}

	public function rules()
	{
		return [
			[['name', 'description'], 'safe', 'on' => [self::SCENARIO_BOX_CREATE, self::SCENARIO_BOX_UPDATE]],
			[['person_id', 'name'], 'required', 'on' => [self::SCENARIO_BOX_CREATE, self::SCENARIO_BOX_UPDATE]],
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
			$product = Product::findOneSerialized($item['product_id']);
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
					'loveds',
					'link' => 'viewLink',
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
	 * @return Box[]
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
			$query->andWhere(["products.product_id" => $criteria["product_id"]]);
		}

		// if ignore_empty_boxes is specified
		if ((array_key_exists("ignore_empty_boxes", $criteria)) && $criteria["ignore_empty_boxes"]) {
			$query->andWhere(["<>", "products", []]);
		}

		// if countries are specified
		if ((array_key_exists("countries", $criteria)) && (!empty($criteria["countries"]))) {

			// Get different person_ids available by country
			$queryPerson= new ActiveQuery(Person::className());
			$queryPerson->andWhere(["in", "personal_info.country", $criteria["countries"]]);
			$idsPerson = $queryPerson->distinct("short_id");

			if ($idsPerson) {
				$query->andFilterWhere(["in", "person_id", $idsPerson]);
			} else {
				$query->andFilterWhere(["in", "person_id", "dummy_person"]); // Force no results if there are no boxes
			}
		}

		// if only_active_persons are specified
		if ((array_key_exists("only_active_persons", $criteria)) && (!empty($criteria["only_active_persons"]))) {

			// Get different person_ids available by country
			$queryPerson= new ActiveQuery(Person::className());
			$queryPerson->andWhere(["account_state" => Person::ACCOUNT_STATE_ACTIVE]);
			$idsPerson = $queryPerson->distinct("short_id");

			if ($idsPerson) {
				$query->andFilterWhere(["in", "person_id", $idsPerson]);
			} else {
				$query->andFilterWhere(["in", "person_id", "dummy_person"]); // Force no results if there are no boxes
			}
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
			$query->orderBy(["created_at" => SORT_DESC]);
		}

		$boxes = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($boxes);
		}

		return $boxes;
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
	 * ATTENTION: If an unactive product is in the box, it will not be retrieved
	 *
	 * @return Product[]
	 */
	public function getProducts()
	{
		$return = [];
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);
		$products = $this->productsMapping;
		foreach ($products as $item) {
			$product = Product::findOneSerialized($item->product_id);
			if ($product) {
				if ($product->product_state == Product::PRODUCT_STATE_ACTIVE) {
					$return[$item->created_at . '_' . $item->product_id] = $product;
				}
			}
		}
		ksort($return); // Sort by key, to force products in creation order
		$return = array_reverse($return); // Reverse to return in inverse order of creation

		return $return;
	}

	public function getProductsPreview()
	{
		$return = [];
		$products = $this->getProducts();

		/*
		$sizes = [
			// one product
			1 => [
				[295, 372],
			],
			// two products
			2 => [
				[295, 115],
				[295, 257],
			],
			// three (or more) products
			3 => [
				[146, 116],
				[145, 116],
				[295, 257],
			],
		];
		if (count($products) >= 3) {
			$sizesSelected = $sizes[3];
		} elseif (count($products) == 2) {
			$sizesSelected = $sizes[2];
		} else {
			$sizesSelected = $sizes[1];
		}
		*/

		$i = 0;
		foreach ($products as $product) {
			$item = $product->getPreviewSerialized();

//			if ($i < count($sizesSelected)) {
//				$size = $sizesSelected[$i];
				$item['box_photo'] = $product->getImagePreview(350, 344);
//			}
			$return[] = $item;
			$i++;
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
		$product = Product::findOneSerialized($boxProduct->product_id);
		/* @var Product $product */
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
			$boxProduct->created_at = new MongoDate();
			$boxProduct->updated_at = new MongoDate();
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

	/**
	 * Returns the url to view the box detail
	 *
	 * @return string
	 */
	public function getViewLink()
	{
		$person = $this->getPerson();

		return Url::to([
			"/person/box-detail",
			"slug" => $person->getSlug(),
			"person_id" => $person->short_id,
			"box_id" => $this->short_id,
			"person_type" => $person->getPersonTypeForUrl()
		]);
	}

	/**
	 * Returns a number of random boxes
	 *
	 * @param int $limit
	 * @param int $idBoxIgnore A box id to ignore in the results
	 * @param bool $onlyActiveAndNotEmpty If TRUE, only not empty boxes of active accounts are retrieved
	 * @return Box[]
	 */
	public static function getRandomBoxes($limit, $idBoxIgnore = null, $onlyActiveAndNotEmpty = false)
	{
		$conditions = [];

		// Ignored box
		if ($idBoxIgnore) {
			$conditions[] =
				[
					'$match' => [
						"short_id" => [
							'$ne' => $idBoxIgnore,
						]
					]
				];
		}

		if ($onlyActiveAndNotEmpty) {
			$activePersons = Person::findSerialized(['account_state' => Person::ACCOUNT_STATE_ACTIVE]);
			$idsActivePersons = [];
			foreach ($activePersons as $activePerson) {
				$idsActivePersons[] = $activePerson->short_id;
			}
			$conditions[] =
				[
					'$match' => [
						'products' => [
							'$exists' => true,
							'$not' => [
								'$size' => 0,
							]
						],
						'person_id' => [
							'$in' => $idsActivePersons,
						],
					]
				];
		}

		// Randomize
		$conditions[] =
			[
				'$sample' => [
					'size' => $limit,
				]
			];

		$randomBoxes = Yii::$app->mongodb->getCollection('box')->aggregate($conditions);

		$boxId = [];
		foreach ($randomBoxes as $work) {
			$boxId[] = $work['_id'];
		}
		if ($boxId) {
			$query = new ActiveQuery(Box::className());
			$query->where(['in', '_id', $boxId]);
			$boxes = $query->all();
			shuffle($boxes);
		} else {
			$boxes= [];
		}

		return $boxes;
	}

	/**
	 * Returns Loveds from the box
	 *
	 * @return Loved[]
	 */
	public function getLoveds() {
		return Loved::findSerialized(['box_id' => $this->short_id]);
	}
}