<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use EasySlugger\Slugger;
use Exception;
use MongoDate;
use Yii;
use yii\helpers\Url;
use yii\mongodb\ActiveQuery;

/**
 * @property string short_id
 * @property string $story_state
 * @property string person_id
 * @property array title
 * @property array slug
 * @property array categories
 * @property array tags
 * @property array components
 * @property array main_media
 * @property MongoDate created_at
 * @property MongoDate updated_at
 *
 * Mappings:
 * @property StoryComponent[] $componentsMapping
 * @property StoryMainMedia $mainMediaMapping
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
			'slug',
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
	public static $translatedAttributes = ['title', 'slug'];

	public static $textFilterAttributes = ['title'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

		// initialize attributes
		$this->title = [];
		$this->slug = [];
		$this->categories = [];
		$this->tags = [];
		$this->components = [];
	}

    public function embedComponentsMapping()
    {
        return $this->mapEmbeddedList('components', StoryComponent::className(), array('unsetSource' => false));
    }

    public function embedMainMediaMapping()
    {
        return $this->mapEmbedded('main_media', StoryMainMedia::className(), array('unsetSource' => false));
    }

	public function setParentOnEmbbedMappings()
	{
		foreach ($this->componentsMapping as $item) {
			$item->setParentObject($this);
		}
		$this->mainMediaMapping->setParentObject($this);
	}

	public function beforeSave($insert) {
		if (empty($this->story_state)) {
			$this->story_state = Story::STORY_STATE_ACTIVE;
		}

		$slugs = [];
		foreach ($this->title as $lang => $text) {
			$slugs[$lang] = Slugger::slugify($text);
		}
		$this->setAttribute("slug", $slugs);

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

			[
				['title', 'categories', 'tags', 'components'],
				'safe',
				'on' => [
					self::SCENARIO_STORY_CREATE_DRAFT,
					self::SCENARIO_STORY_UPDATE_DRAFT,
					self::SCENARIO_STORY_UPDATE_ACTIVE
				]
			],

			[
				['title', 'components', 'main_media'],
				'required',
				'on' => [
					self::SCENARIO_STORY_UPDATE_ACTIVE
				]
			],

			['story_state', 'in', 'range' => [self::STORY_STATE_ACTIVE, self::STORY_STATE_DRAFT]],

			['person_id', 'app\validators\PersonIdValidator'],

			['title', 'app\validators\TranslatableRequiredValidator'],

			['categories', 'app\validators\CategoriesValidator'],

			['components', 'safe'],
			['componentsMapping', 'yii2tech\embedded\Validator'],

			['main_media', 'safe'],
			['mainMediaMapping', 'yii2tech\embedded\Validator'],

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
					'story_state',
					'person_id',
					'title',
					'slug',
					'categories',
					'tags',
					'components',
					'main_media',
					'view_link' => 'viewLink',
					'first_text' => 'firstText',
					'main_photo_url' => 'mainPhotoUrl',
				];
				static::$retrieveExtraFields = [
				];

				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'story_state',
					'person_id',
					'title',
					'slug',
					'categories',
					'tags',
					'components',
					'main_media',
					'view_link' => 'viewLink',
					'first_text' => 'firstText',
					'main_photo_url' => 'mainPhotoUrl',
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

		// if categories are specified
		if ((array_key_exists("categories", $criteria)) && (!empty($criteria["categories"]))) {
			if (is_array($criteria["categories"])) {
				$ids = [];
				foreach ($criteria["categories"] as $categoryId) {
					$category = Category::findOne(["short_id" => $categoryId]);
					if ($category) {
						$ids = array_merge($ids, $category->getShortIds());
					}
				}
			} else {
				$ids = [];
				$category = Category::findOne(["short_id" => $criteria["categories"]]);
				if ($category) {
					$ids = array_merge($ids, $category->getShortIds());
				}
			}
			$query->andWhere(["categories" => $ids]);
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
				$query->andFilterWhere(["in", "person_id", "dummy_person"]); // Force no results if there are no stories
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
				$query->andFilterWhere(["in", "person_id", "dummy_person"]); // Force no results if there are no stories
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
	 * Returns TRUE if the story object can be edited by the current user
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
	 * Returns the first component of type "text" (if exists)
	 *
	 * @return StoryComponent|null
	 */
	public function getFirstTextComponent() {
		return $this->getFirstComponentByType(StoryComponent::STORY_COMPONENT_TYPE_TEXT);
	}

	/**
	 * Returns the texts of the first component of type "text" (if exists)
	 *
	 * @return null|string
	 */
	public function getFirstText() {
		$firstTextComponent = $this->getFirstTextComponent();
		if ($firstTextComponent) {
			return $firstTextComponent->getText();
		}

		return null;
	}

	/**
	 * Returns the absolute url to the main media photo (if exists)
	 *
	 * @return null|string
	 */
	public function getMainPhotoUrl() {
		return $this->mainMediaMapping->getPhotoUrl();
	}

	/**
	 * Returns the first component of the specified type
	 * @param $componentType
	 *
	 * @return StoryComponent|null
	 */
	protected function getFirstComponentByType($componentType) {
		$components = $this->componentsMapping;

		$sortedComponents = [];
		foreach ($components as $component) {
			$sortedComponents[$component->position] = $component;
		}

		foreach ($sortedComponents as $component) {
			if ($component->type == $componentType) {
				return $component;
			}
		}

		return null;
	}

	/**
	 * Returns the url to view the story detail
	 *
	 * @return string
	 */
	public function getViewLink()
	{
		$person = $this->getPerson();

		return Url::to([
			"/person/story-detail",
			"slug" => $person->getSlug(),
			"person_id" => $person->short_id,
			"story_id" => $this->short_id,
			"person_type" => $person->getPersonTypeForUrl(),
			"slug_story" => $this->getSlug(),
		]);
	}

	/**
	 * Returns the url to view the story detail
	 *
	 * @return string
	 */
	public function getEditLink()
	{
		$person = $this->getPerson();

		return Url::to([
			"/person/story-edit",
			"slug" => $person->getSlug(),
			"person_id" => $person->short_id,
			"story_id" => $this->short_id,
			"person_type" => $person->getPersonTypeForUrl(),
		]);
	}

	public function getSlug() {
		if (is_array($this->slug)) {
			$slug = Utils::l($this->slug);
		} else {
			$slug = $this->slug;
		}
		return $slug;
	}

	/**
	 * Returns a number of random stories
	 *
	 * @param int $limit
	 * @return Story[]
	 */
	public static function getRandomStories($limit)
	{
		$conditions = [];

		// Actived boxces
		$conditions[] =
			[
				'$match' => [
					"story_state" => [
						'$eq' => self::STORY_STATE_ACTIVE,
					]
				]
			];


		// Of active persons
		$activePersons = Person::findSerialized(['account_state' => Person::ACCOUNT_STATE_ACTIVE]);
		$idsActivePersons = [];
		foreach ($activePersons as $activePerson) {
			$idsActivePersons[] = $activePerson->short_id;
		}
		$conditions[] =
			[
				'$match' => [
					'person_id' => [
						'$in' => $idsActivePersons,
					],
				]
			];

		// Randomize
		$conditions[] =
			[
				'$sample' => [
					'size' => $limit,
				]
			];

		$randomStories = Yii::$app->mongodb->getCollection('story')->aggregate($conditions);

		$storyId = [];
		foreach ($randomStories as $work) {
			$storyId[] = $work['short_id'];
		}
		$stories = self::findSerialized(['id' => $storyId]);
		shuffle($stories);

		return $stories;
	}
}
