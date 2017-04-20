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
 * @property string $story_state
 * @property string person_id
 * @property array title
 * @property array categories
 * @property array tags
 * @property array components
 * @property array main_media
 * @property MongoDate created_at
 * @property MongoDate updated_at
 *
 * Mappings:
 * @property StoryComponent[] $componentsMapping
 */
class Story extends CActiveRecord {

    const STORY_STATE_DRAFT = 'story_state_draft';
    const STORY_STATE_ACTIVE = 'story_state_active';

	const SCENARIO_STORY_CREATE_DRAFT = 'story-create-draft';
	const SCENARIO_STORY_UPDATE_DRAFT = 'story-update-draft';
	const SCENARIO_STORY_UPDATE_ACTIVE = 'story-update-active';

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
		return 'story';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'story_state',
			'person_id',
			'title',
			'categories',
			'tags',
			'components',
			'main_media',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['title'];

	public static $textFilterAttributes = ['title'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

		// initialize attributes
		$this->components = [];
	}

    public function embedComponentsMapping()
    {
        return $this->mapEmbeddedList('components', StoryComponent::className(), array('unsetSource' => false));
    }

	public function setParentOnEmbbedMappings()
	{
		foreach ($this->componentsMapping as $item) {
			$item->setParentObject($this);
		}
	}

	public function beforeSave($insert) {
		if (empty($this->story_state)) {
			$this->story_state = Story::STORY_STATE_DRAFT;
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
			[['title'], 'safe', 'on' => [self::SCENARIO_STORY_CREATE_DRAFT, self::SCENARIO_STORY_UPDATE_DRAFT, self::SCENARIO_STORY_UPDATE_ACTIVE]],
			[['person_id'], 'required', 'on' => [self::SCENARIO_STORY_CREATE_DRAFT, self::SCENARIO_STORY_UPDATE_DRAFT, self::SCENARIO_STORY_UPDATE_ACTIVE]],
			[['person_id'], 'validatePersonExists'],
			[['components'], 'safe'],
			[['componentsMapping'], 'yii2tech\embedded\Validator'],
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
					'story_state',
					'person_id',
					'title',
					'categories',
					'tags',
					'components',
					'main_media',
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
     * @return array
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

		// if story_state is specified
		if ((array_key_exists("story_state", $criteria)) && (!empty($criteria["story_state"]))) {
			$query->andWhere(["story_state" => $criteria["story_state"]]);
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
	 * Returns the translated title of the story
	 *
	 * @return array|mixed
	 */
    public function getTitle() {

		if (is_array($this->title)) {
			$title = Utils::l($this->title);
		} else {
			$title = $this->title;
		}

		return $title;
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
}
