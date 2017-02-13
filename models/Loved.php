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
 * @property string product_id
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Loved extends CActiveRecord
{

	const SCENARIO_LOVED_PRODUCT = 'scenario-loved-product';
	const SCENARIO_LOVED_BOX = 'scenario-loved-box';

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
		return 'loved';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'person_id',
			'product_id',
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
		if ($insert) {
			$product = $this->getProduct();
			$collection = Yii::$app->mongodb->getCollection('product');
			$collection->update(
				[
					'short_id' => $product->short_id
				],
				[
					'loveds' => $product->loveds + 1
				]
			);
		}
		parent::afterSave($insert, $changedAttributes);
	}

	public function afterDelete()
	{
		$product = $this->getProduct();
		if ($product) {
			$collection = Yii::$app->mongodb->getCollection('product');
			$collection->update(
				[
					'short_id' => $product->short_id
				],
				[
					'loveds' => $product->loveds - 1
				]
			);
		}

		parent::afterDelete();
	}

	public function rules()
	{
		return [
			[['person_id', 'product_id'], 'required', 'on' => [self::SCENARIO_LOVED_PRODUCT]],
			[['person_id'], 'validatePersonExists'],
			[['product_id'], 'validateProductExists', 'on' => [self::SCENARIO_LOVED_PRODUCT]],
			[['person_id'], 'validateUniqueLoved', 'on' => [self::SCENARIO_LOVED_PRODUCT], 'params' => ['product_id' => $this->product_id]],
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
	public function validateProductExists($attribute, $params)
	{
		$product_id = $this->$attribute;
		$product = Product2::findOneSerialized($product_id);
		if (!$product) {
			$this->addError($attribute, sprintf('Product %s not found', $product_id));
		}
	}

	/**
	 * Custom validator that limit one loved per person/product
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateUniqueLoved($attribute, $params)
	{
		$person_id = $this->$attribute;
		$product_id = $this->product_id;
		$loved = Loved::findSerialized([
			'person_id' => $person_id,
			'product_id' => $product_id,
		]);
		if ($loved) {
			$this->addError($attribute, sprintf('Product %s already loved by person %s', $product_id, $person_id));
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
					'product_id',
					'product' => "productPreview",
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
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return Loved|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Loved $loved */
		$loved = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($loved);
		}

		return $loved;
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
			$query->andWhere(["product_id" => $criteria["product_id"]]);
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
			$query->orderBy(["created_at" => SORT_ASC]);
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
	 * Get the product related with this loved
	 *
	 * @return Product2
	 */
	public function getProduct()
	{
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Product2 $product */
		$product = Product2::findOneSerialized($this->product_id);

		return $product;
	}


	/**
	 * Get a preview version of the product related with this loved
	 *
	 * @return array
	 */
	public function getProductPreview()
	{
		$product = $this->getProduct();

		return $product->getPreviewSerialized();
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
	 * Returns TRUE if the loved object can be edited by the current user
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
		if (Yii::$app->user->identity->getId() != $this->person_id) {
			return true;
		}

		return false;
	}
}