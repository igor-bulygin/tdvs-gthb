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
 * @property string person_id
 * @property string action_type
 * @property string target_id
 * @property int loveds
 * @property MongoDate date
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Timeline extends CActiveRecord
{
	const SCENARIO_TIMELINE_CREATE = 'timeline-create';

	const ACTION_BOX_CREATED = 'box_created';
	const ACTION_BOX_UPDATED = 'box_updated';
	const ACTION_BOX_LOVED = 'box_loved';
	const ACTION_PRODUCT_CREATED = 'product_created';
	const ACTION_PRODUCT_UPDATED = 'product_updated';
	const ACTION_PRODUCT_LOVED = 'product_loved';
	const ACTION_POST_CREATED = 'post_created';
	const ACTION_POST_UPDATED = 'post_updated';
	const ACTION_POST_LOVED = 'post_loved';
	const ACTION_TIMELINE_LOVED = 'timeline_loved';
	const ACTION_PERSON_FOLLOWED = 'person_followed';

	protected $timeline_detail = null;

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
		return 'timeline';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'person_id',
			'action_type',
			'target_id',
			'loveds',
			'date',
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
		$this->loveds = 0;
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
				['person_id'],
				'required',
				'on' => [
					self::SCENARIO_TIMELINE_CREATE,
				]
			],

			['person_id', 'app\validators\PersonIdValidator'],

			[
				'action_type',
				'validateActionType',
			]
		];
	}

	public function validateActionType($attribute, $params)
	{
		$action_type = $this->$attribute;
		$target_id = $this->target_id;
		// TODO validate
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
					'person_id',
					'person' => 'personPreview',
					'action_type',
					'action_name' => 'actionName',
					'target_id',
					'title' => 'title',
					'description' => 'description',
					'photo' => 'photo',
					'link' => 'link',
					'loveds',
					'isLoved' => 'isLoved',
					'date',
				];
				static::$retrieveExtraFields = [
				];

				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'person_id',
					'action_type',
					'target_id',
					'loveds',
					'date',
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
				"date" => SORT_DESC,
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
	 * Returns Loveds from the post
	 *
	 * @return Loved[]
	 */
	public function getLoveds() {
		return Loved::findSerialized(['timeline_id' => $this->short_id]);
	}

	/**
	 * Wrapper of isLovedByCurrentUser to use in serialized fields
	 *
	 * @return bool
	 */
	public function getIsLoved() {
		return $this->isLovedByCurrentUser();
	}

	/**
	 * Returns TRUE if the post is loved by the connected user
	 *
	 * @return bool
	 */
	public function isLovedByCurrentUser() {
		if (Yii::$app->user->isGuest) {
			return false;
		}
		$person_id = Yii::$app->user->identity->short_id;

		return Utils::timelineLovedByPerson($this->short_id, $person_id);
	}

	/**
	 * @return TimelineDetail
	 */
	public function getTimelineDetail() {
		if (empty($this->timeline_detail)) {
			$this->timeline_detail = new TimelineDetail($this->action_type, $this->target_id);
		}

		return $this->timeline_detail;
	}

	public function getActionName() {
		return $this->getTimelineDetail()->action_name;
	}

	public function getTitle() {
		return $this->getTimelineDetail()->title;
	}

	public function getDescription() {
		return $this->getTimelineDetail()->description;
	}

	public function getPhoto() {
		return $this->getTimelineDetail()->photo;
	}

	public function getLink() {
		return $this->getTimelineDetail()->link;
	}

	/**
	 * Get only preview attributes from post
	 *
	 * @return array
	 */
	public function getPreviewSerialized()
	{
		return [
			'id' => $this->short_id,
			'person_id' => $this->person_id,
			'action_type' => $this->action_type,
			'target_id' => $this->target_id,
			'loveds' => $this->loveds,
			'date' => $this->date,
		];
	}

}
