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
 * @property string box_id
 * @property string post_id
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Loved extends CActiveRecord
{

	const SCENARIO_LOVED_PRODUCT = 'scenario-loved-product';
	const SCENARIO_LOVED_BOX = 'scenario-loved-box';
	const SCENARIO_LOVED_POST = 'scenario-loved-post';

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
		return 'loved';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'person_id',
			'product_id',
			'box_id',
			'post_id',
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
		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
	{
		if ($insert) {
			$product = $this->getProduct();
			if ($product) {
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
			$box = $this->getBox();
			if ($box) {
				$collection = Yii::$app->mongodb->getCollection('box');
				$collection->update(
					[
						'short_id' => $box->short_id
					],
					[
						'loveds' => $box->loveds + 1
					]
				);
			}
			$post = $this->getPost();
			if ($post) {
				$collection = Yii::$app->mongodb->getCollection('post');
				$collection->update(
					[
						'short_id' => $post->short_id
					],
					[
						'loveds' => $post->loveds + 1
					]
				);
			}
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
		$box = $this->getBox();
		if ($box) {
			$collection = Yii::$app->mongodb->getCollection('box');
			$collection->update(
				[
					'short_id' => $box->short_id
				],
				[
					'loveds' => $box->loveds - 1
				]
			);
		}
		$post = $this->getPost();
		if ($post) {
			$collection = Yii::$app->mongodb->getCollection('post');
			$collection->update(
				[
					'short_id' => $post->short_id
				],
				[
					'loveds' => $post->loveds - 1
				]
			);
		}

		parent::afterDelete();
	}

	public function rules()
	{
		return [
			[['person_id'], 'validatePersonExists'],
			[['person_id'], 'validateUniqueLoved'],
			
			[['person_id', 'product_id'], 'required', 'on' => [self::SCENARIO_LOVED_PRODUCT]],
			[['product_id'], 'validateProductExists', 'on' => [self::SCENARIO_LOVED_PRODUCT]],

			[['person_id', 'box_id'], 'required', 'on' => [self::SCENARIO_LOVED_BOX]],
			[['box_id'], 'validateBoxExists', 'on' => [self::SCENARIO_LOVED_BOX]],

			[['person_id', 'post_id'], 'required', 'on' => [self::SCENARIO_LOVED_POST]],
			[['post_id'], 'validatePostExists', 'on' => [self::SCENARIO_LOVED_POST]],
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
		$product = Product::findOneSerialized($product_id);
		if (!$product) {
			$this->addError($attribute, sprintf('Product %s not found', $product_id));
			return;
		}
		if ($product->deviser_id == Yii::$app->user->identity->short_id) {
			$this->addError($attribute, 'You cannot love your own products');
		}
	}

	/**
	 * Custom validator for box_id
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateBoxExists($attribute, $params)
	{
		$short_id = $this->$attribute;
		$item = Box::findOneSerialized($short_id);
		if (!$item) {
			$this->addError($attribute, sprintf('Item %s not found', $short_id));
			return;
		}
		if ($item->person_id == Yii::$app->user->identity->short_id) {
			$this->addError($attribute, 'You cannot love your own items');
		}
	}

	/**
	 * Custom validator for box_id
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validatePostExists($attribute, $params)
	{
		$short_id = $this->$attribute;
		$item = Post::findOneSerialized($short_id);
		if (!$item) {
			$this->addError($attribute, sprintf('Item %s not found', $short_id));
			return;
		}
		if ($item->person_id == Yii::$app->user->identity->short_id) {
			$this->addError($attribute, 'You cannot love your own items');
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
		if ($this->product_id) {
			$product_id = $this->product_id;
			$loved = Loved::findSerialized([
				'person_id' => $person_id,
				'product_id' => $product_id,
			]);
			if ($loved) {
				$this->addError($attribute, sprintf('Product %s already loved by person %s', $product_id, $person_id));
			}
		}
		if ($this->box_id) {
			$box_id = $this->box_id;
			$loved = Loved::findSerialized([
				'person_id' => $person_id,
				'box_id' => $box_id,
			]);
			if ($loved) {
				$this->addError($attribute, sprintf('Box %s already loved by person %s', $box_id, $person_id));
			}
		}
		if ($this->post_id) {
			$post_id = $this->post_id;
			$loved = Loved::findSerialized([
				'person_id' => $person_id,
				'post_id' => $post_id,
			]);
			if ($loved) {
				$this->addError($attribute, sprintf('Post %s already loved by person %s', $post_id, $person_id));
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
					'product_id',
					'product' => "productPreview",
					'box_id',
					'post_id',
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
	 * @return Loved[]
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

		// if product id is specified
		if ((array_key_exists("box_id", $criteria)) && (!empty($criteria["box_id"]))) {
			$query->andWhere(["box_id" => $criteria["box_id"]]);
		}

		// if product id is specified
		if ((array_key_exists("post_id", $criteria)) && (!empty($criteria["post_id"]))) {
			$query->andWhere(["post_id" => $criteria["post_id"]]);
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
	 * Get the product related with this loved
	 *
	 * @return Product
	 */
	public function getProduct()
	{
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Product $product */
		$product = Product::findOneSerialized($this->product_id);

		return $product;
	}


	/**
	 * Get the box related with this loved
	 *
	 * @return Box
	 */
	public function getBox()
	{
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Box $box*/
		$box= Box::findOneSerialized($this->box_id);

		return $box;
	}


	/**
	 * Get the post related with this loved
	 *
	 * @return Post
	 */
	public function getPost()
	{
		Post::setSerializeScenario(Post::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Post $post */
		$post = Post::findOneSerialized($this->post_id);

		return $post;
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
	 * Get a preview version of the product related with this loved
	 *
	 * @return array
	 */
	public function getProductPreview()
	{
		$product = $this->getProduct();

		if ($product) {
			return $product->getPreviewSerialized();
		}

		return null;
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
		if (Yii::$app->user->identity->getId() == $this->person_id) {
			return true;
		}

		return false;
	}
}