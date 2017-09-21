<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use yii\mongodb\ActiveQuery;

/**
 * @property string short_id
 * @property string email
 * @property int enabled
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Newsletter extends CActiveRecord
{

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
		return 'newsletter';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'email',
			'enabled',
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

	/**
	 * Revise some attributes before save in database
	 *
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (empty($this->created_at)) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function rules()
	{
		return [
			['short_id', 'unique'],
			['email', 'safe'],
			['email', 'unique'],
			['email', 'email'],
			['email', 'required'],
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
			default:
				static::$serializeFields = [
					'id' => 'short_id',
					'email',
					'enabled',
				];
				static::$retrieveExtraFields = [
				];

				static::$translateFields = false;
				break;
		}
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return Product|null
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
	 *
	 * @return Newsletter[]
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

		// if email is specified
		if ((array_key_exists("email", $criteria)) && (!empty($criteria["email"]))) {
			$query->andWhere(["email" => $criteria["email"]]);
		}

		// if enabled is specified
		if ((array_key_exists("enabled", $criteria)) && (!empty($criteria["enabled"]))) {
			$query->andWhere(["enabled" => $criteria["enabled"]]);
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

		if ((array_key_exists("order_col", $criteria)) && (!empty($criteria["order_col"])) &&
			(array_key_exists("order_dir", $criteria)) && (!empty($criteria["order_dir"]))) {
			$query->orderBy([
				$criteria["order_col"] => $criteria["order_dir"] == 'desc' ? SORT_DESC : SORT_ASC,
			]);
		} else {
			$query->orderBy("created_at");
		}

		$products = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($products);
		}

		return $products;
	}

	/**
	 * Suscribes the email to the newsletter
	 *
	 * @param string $email
	 * @param bool $validate if FALSE, no validations are fired
	 *
	 * @return Newsletter Object created
	 */
	public static function suscribeEmail($email, $validate = true)
	{
		$newsletter = static::findOne([
			'email' => $email
		]);
		if (!$newsletter) {
			$newsletter = new Newsletter();
			$newsletter->email = $email;
		}
		$newsletter->enabled = true;
		$newsletter->save($validate);

		return $newsletter;
	}


	/**
	 * Returns TRUE if the email is suscribed
	 *
	 * @param string $email
	 *
	 * @return bool
	 */
	public static function isSuscribedEmail($email)
	{
		$exists = Newsletter::findSerialized(['email' => $email]);

		return !empty($exists);
	}
}