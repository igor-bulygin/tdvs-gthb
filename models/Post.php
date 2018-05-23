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
 * @property string $post_state
 * @property string person_id
 * @property array text
 * @property string photo
 * @property int loveds
 * @property MongoDate published_at
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Post extends CActiveRecord
{

	const POST_STATE_DRAFT = 'post_state_draft';
	const POST_STATE_ACTIVE = 'post_state_active';

	const SCENARIO_POST_CREATE_DRAFT = 'post-create-draft';
	const SCENARIO_POST_UPDATE_DRAFT = 'post-update-draft';
	const SCENARIO_POST_UPDATE_ACTIVE = 'post-update-active';

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
		return 'post';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'post_state',
			'person_id',
			'text',
			'photo',
			'loveds',
			'published_at',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['text'];

	public static $textFilterAttributes = ['text'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

		// initialize attributes
		$this->text = [];

		$this->loveds = 0;
	}

	public function beforeSave($insert)
	{
		if (!isset($this->loveds)) {
			$this->loveds = 0;
		}

		if (empty($this->post_state)) {
			$this->post_state = Post::POST_STATE_ACTIVE;
		}

		if ($this->post_state == Post::POST_STATE_ACTIVE && empty($this->published_at)) {
			$this->published_at = new MongoDate();
		}

		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function beforeDelete() {

		$loveds = $this->getLoveds();
		foreach ($loveds as $loved) {
			$loved->delete();
		}

		return parent::beforeDelete();
	}

	public function rules()
	{
		return [
			['short_id', 'unique'],

			[
				['text', 'photo', 'person_id'],
				'required',
				'on' => [
					self::SCENARIO_POST_CREATE_DRAFT,
					self::SCENARIO_POST_UPDATE_DRAFT,
					self::SCENARIO_POST_UPDATE_ACTIVE
				]
			],

			['post_state', 'in', 'range' => [self::POST_STATE_ACTIVE, self::POST_STATE_DRAFT]],

			['person_id', 'app\validators\PersonIdValidator'],

			['text', 'app\validators\TranslatableRequiredValidator'],

			[
				'photo',
				'validatePhoto',
			]
		];
	}

	public function validatePhoto($attribute, $params)
	{
		$photo = $this->photo;
		$person = $this->getPerson();
		if (!$person->existMediaFile($photo)) {
			$this->addError('photo', sprintf('Photo %s does not exists', $photo));
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
				static::$serializeFields = [
					'id' => 'short_id',
					'post_state',
					'person_id',
					'text',
					'photo',
					'photo_url' => 'photoUrl',
					'loveds',
					'published_at',
				];
				static::$retrieveExtraFields = [
				];

				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'post_state',
					'person_id',
					'text',
					'photo',
					'loveds',
					'published_at',
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
	 * @return Post|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Post $post */
		$post = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($post);
		}

		return $post;
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 *
	 * @return Post[]
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
		if ((array_key_exists("person_id", $criteria)) && (!empty($criteria["person_id"]))) {
			$query->andWhere(["person_id" => $criteria["person_id"]]);
		}

		// if post_state is specified
		if ((array_key_exists("post_state", $criteria)) && (!empty($criteria["post_state"]))) {
			$query->andWhere(["post_state" => $criteria["post_state"]]);
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
				"created_at" => SORT_DESC,
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

	/**
	 * Returns the translated text of the post
	 *
	 * @return array|mixed
	 */
	public function getText()
	{

		if (is_array($this->text)) {
			$title = Utils::l($this->text);
		} else {
			$title = $this->text;
		}

		return $title;
	}

	public function getPerson()
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);

		$person = Person::findOneSerialized($this->person_id);

		return $person;
	}

	public function getPersonPreview()
	{
		$person = $this->getPerson();

		return $person->getPreviewSerialized();
	}

	/**
	 * Returns TRUE if the post object can be edited by the current user
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
	 * Returns the url of the photo (if it photo main media)
	 *
	 * @return null|string
	 */
	public function getPhotoUrl()
	{
		$person = $this->getPerson();

		return $person->getUrlImagesLocation() . $this->photo;
	}

	/**
	 * Returns Loveds from the post
	 *
	 * @return Loved[]
	 */
	public function getLoveds() {
		return Loved::findSerialized(['post_id' => $this->short_id]);
	}
}
