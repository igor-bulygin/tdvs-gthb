<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use Yii;
use yii\mongodb\ActiveQuery;

/**
 * @property array image
 * @property array alt_text
 * @property array link
 * @property string category_id
 * @property string type
 * @property int position
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Banner extends CActiveRecord
{
	const BANNER_TYPE_CAROUSEL = 'carousel';
	const BANNER_TYPE_HOME_BANNER = 'home_banner';

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
		return 'banner';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'image',
			'alt_text',
			'link',
			'type',
			'position',
			'category_id',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['image', 'alt_text', 'link'];

	public static $textFilterAttributes = [];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

		// initialize attributes
		$this->image = [];
		$this->alt_text = [];
		$this->link = [];
	}

	public function beforeSave($insert)
	{
		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function rules()
	{
		return [
			['short_id', 'unique'],

			[
				['image', 'alt_text', 'link', 'type', 'position'],
				'safe',
			],

			[
				['category_id'],
				'safe',
				'when' => function ($model) {
					return $model->type == self::BANNER_TYPE_CAROUSEL;
				}
			],

			['type', 'in', 'range' => [self::BANNER_TYPE_CAROUSEL, self::BANNER_TYPE_HOME_BANNER]],

			['category_id', 'app\validators\CategoryIdValidator'],

			['image', 'app\validators\TranslatableRequiredValidator'],
			['alt_text', 'app\validators\TranslatableRequiredValidator'],
			['link', 'app\validators\TranslatableRequiredValidator'],
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
				static::$serializeFields = [
					'id' => 'short_id',
					'image',
					'alt_text',
					'link',
					'type',
					'position',
					'category_id',
					'image_link' => 'imageLinkTranslated',
				];
				static::$retrieveExtraFields = [
				];

				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'image',
					'alt_text',
					'link',
					'type',
					'position',
					'category_id',
					'image_link' => 'imageLink',
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
	 * @return Story|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Story $story */
		$story = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($story);
		}

		return $story;
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 *
	 * @return Story[]
	 * @throws Exception
	 */
	public static function findSerialized($criteria = [])
	{

		// Order query
		$query = new ActiveQuery(static::className());

		// Retrieve only fields that gonna be used
		$query->select(self::getSelectFields());

		// if id is specified
		if ((array_key_exists("id", $criteria)) && (!empty($criteria["id"]))) {
			$query->andWhere(["short_id" => $criteria["id"]]);
		}

		// if deviser id is specified
		if ((array_key_exists("category_id", $criteria)) && (!empty($criteria["category_id"]))) {
			$query->andWhere(["category_id" => $criteria["category_id"]]);
		}

		// if story_state is specified
		if ((array_key_exists("type", $criteria)) && (!empty($criteria["type"]))) {
			$query->andWhere(["type" => $criteria["type"]]);
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
			$query->orderBy([
				"position" => SORT_ASC,
				"category_id" => SORT_ASC,
			]);
		}

		$stories = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($stories);
		}

		return $stories;
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

	public function getCategory()
	{
		Category::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);

		$category = Category::findOneSerialized($this->category_id);

		return $category;
	}

	public function getImageLink()
	{
		$imageLink = [];
		foreach ($this->image as $lang => $image) {
			$imageLink[$lang] = Yii::getAlias("@banner_url") . "/" . $image;
		}

		return $imageLink;
	}

	public function getImageLinkTranslated()
	{
		if (is_array($this->image)) {
			$image = Utils::l($this->image);
		} else {
			$image = $this->image;
		}

		return Yii::getAlias("@banner_url") . "/" . $image;
	}
}
