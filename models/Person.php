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
use yii\mongodb\Collection;
use yii\web\IdentityInterface;

/**
 * @property string $slug
 * @property string $text_short_description
 * @property string $text_biography
 * @property string $account_state
 * @property mixed $type
 * @property array $categories
 * @property array $collections
// * @property PersonPreferences $preferencesMapping
 * @property PersonPersonalInfo $personalInfoMapping
 * @property PersonMedia $mediaMapping
 * @property PersonSettings $settingsMapping
 * @property array $press
 * @property PersonVideo[] $videosMapping
 * @property FaqQuestion[] $faqMapping
 * @property array $credentials
 * @property array $preferences
 * @property string $curriculum
 * @property PersonShippingSettings[] $shippingSettingsMapping
 * @property double $application_fee
 * @property int $profile_views
 * @property double $available_earnings
 * @property double $total_won_so_far
 * @property string $affiliate_id
 * @property string $parent_affiliate_id
 * @property array $follow
 * @property MongoDate $created_at
 * @property MongoDate $updated_at
 */
class Person extends CActiveRecord implements IdentityInterface
{

	const ADMIN = 0;
	const CLIENT = 1;
	const DEVISER = 2;
	const INFLUENCER = 3;

	const ACCOUNT_STATE_DRAFT = 'draft';
	const ACCOUNT_STATE_ACTIVE = 'active';
	const ACCOUNT_STATE_BLOCKED = 'blocked';

	const SCENARIO_USER_PROFILE_UPDATE = 'user-profile-update';

	const SCENARIO_DEVISER_CREATE_DRAFT = 'deviser-create-draft';
	const SCENARIO_DEVISER_UPDATE_DRAFT = 'deviser-update-draft';
	const SCENARIO_DEVISER_UPDATE_PROFILE = 'deviser-update-profile';

	const SCENARIO_INFLUENCER_CREATE_DRAFT = 'influencer-create-draft';
	const SCENARIO_INFLUENCER_UPDATE_DRAFT = 'influencer-update-draft';
	const SCENARIO_INFLUENCER_UPDATE_PROFILE = 'influencer-update-profile';

	const SCENARIO_CLIENT_CREATE = 'client-create';
	const SCENARIO_CLIENT_UPDATE = 'client-update';

	const SCENARIO_AFFILIATES = 'scenario-affiliates';

	const SCENARIO_ADMIN = 'scenario-admin';

	const SCENARIO_TREND_SETTER_PROFILE_UPDATE = 'trend-setter-profile-update';

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


	//public $accessToken;

	public static function collectionName()
	{
		return 'person';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'text_short_description',
			'text_biography',
			'slug',
			'type',
			'account_state',
			'categories',
			'collections',
			'personal_info',
      'product_discovery_from_user',
      'historic',
      'earnings_by_user',
//			'personalInfoMapping',
			'curriculum',
			'media',
			'settings',
			'credentials',
//			'preferences',
			'press',
			'videos',
			'faq',
			'shipping_settings',
			'application_fee',
			'profile_views',
      'available_earnings',
      'total_won_so_far',
			'affiliate_id',
			'parent_affiliate_id',
			'follow',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['text_short_description', 'text_biography', 'faq.question', 'faq.answer'];

	/**
	 * The attributes that should be used when a keyword search is done
	 *
	 * @var array
	 */
	public static $textFilterAttributes = [
		'personal_info.name',
		'personal_info.last_name',
		'personal_info.brand_name',
		/*'text_short_description'*/
	];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(7);

		// initialize attributes
		$this->categories = [];
		$this->collections = [];
		$this->press = [];
		$this->type = [];
		$this->credentials = [];

		$this->videos = [];
		$this->faq = [];
		$this->shipping_settings = [];

		$this->follow = [];

	}

//	public function embedPreferencesMapping()
//	{
//		return $this->mapEmbedded('preferences', PersonPreferences::className(), ['unsetSource' => false]);
//	}

	public function embedPersonalInfoMapping()
	{
		return $this->mapEmbedded('personal_info', PersonPersonalInfo::className(), ['unsetSource' => false]);
	}

	public function embedMediaMapping()
	{
		return $this->mapEmbedded('media', PersonMedia::className(), ['unsetSource' => false]);
	}

	public function embedSettingsMapping()
	{
		return $this->mapEmbedded('settings', PersonSettings::className(), ['unsetSource' => false]);
	}

	public function embedVideosMapping()
	{
		return $this->mapEmbeddedList('videos', PersonVideo::className(), ['unsetSource' => false]);
	}

	public function embedFaqMapping()
	{
		return $this->mapEmbeddedList('faq', FaqQuestion::className(), ['unsetSource' => false]);
	}

	public function embedShippingSettingsMapping()
	{
		return $this->mapEmbeddedList('shipping_settings', PersonShippingSettings::className(),
			['unsetSource' => false]);
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return Person|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Person $person */
		$person = Person::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($person);
		}

		return $person;
	}

	public static function findIdentity($id)
	{
		return Person::findOne(['short_id' => $id]);
	}

	public static function findIdentityByAccessToken($token, $type = null)
	{
		return Person::findOne(['credentials.auth_key' => $token]);
	}

	public static function findByEmail($username)
	{
		return Person::findOne(['credentials.email' => $username]);
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

		// if person id is specified
		if ((array_key_exists("id", $criteria)) && (!empty($criteria["id"]))) {
			$query->andWhere(["short_id" => $criteria["id"]]);
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
			$query->andWhere(["personal_info.country" => $criteria['countries']]);
		}

		// if type is specified
		if ((array_key_exists("type", $criteria)) && (!empty($criteria["type"]))) {
			$query->andWhere(["type" => (int)$criteria["type"]]);
		}

		// if account_state is specified
		if ((array_key_exists("account_state", $criteria)) && (!empty($criteria["account_state"]))) {
			$query->andWhere(["account_state" => $criteria["account_state"]]);
		}

		// if name is specified
		if ((array_key_exists("name", $criteria)) && (!empty($criteria["name"]))) {
//			// search the word in all available languages
			$query->andFilterWhere(static::getFilterForText([
				'personal_info.name',
				'personal_info.last_name',
				'personal_info.brand_name'
			], $criteria["name"]));
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

		if ((array_key_exists("order_col", $criteria)) && (!empty($criteria["order_col"])) &&
			(array_key_exists("order_dir", $criteria)) && (!empty($criteria["order_dir"]))) {
			$query->orderBy([
				$criteria["order_col"] => $criteria["order_dir"] == 'desc' ? SORT_DESC : SORT_ASC,
			]);
		} else {
			$query->orderBy([
				"created_at" => SORT_DESC,
			]);
		}

		$persons = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($persons);
		}

		return $persons;
	}

	public function getUploadedFilesPath()
	{
		return Utils::join_paths(Yii::getAlias("@deviser"), $this->short_id);
	}

	public function getId()
	{
		return $this->short_id;
	}

	public function getAuthKey()
	{
		return $this->credentials["auth_key"];
	}

	public function validateAuthKey($auth_key)
	{
		return $this->getAuthKey() === $auth_key;
	}

	public function validatePassword($password)
	{
		return isset($this->credentials["password"]) && $this->credentials["password"] === bin2hex(Yii::$app->Scrypt->calc($password,
				$this->credentials["salt"], 8, 8, 16, 32));
	}

	public function getAccessToken()
	{
		return $this->getAuthKey();
	}

	public function setParentOnEmbbedMappings()
	{
//		$this->preferencesMapping->setParentObject($this);
		$this->personalInfoMapping->setParentObject($this);
		$this->mediaMapping->setParentObject($this);
		$this->settingsMapping->setParentObject($this);

		foreach ($this->videosMapping as $item) {
			$item->setParentObject($this);
		}
		foreach ($this->faqMapping as $item) {
			$item->setParentObject($this);
		}
		foreach ($this->shippingSettingsMapping as $item) {
			$item->setParentObject($this);
		}
	}

	public function validate($attributeNames = null, $clearErrors = true)
	{
		if (is_array($attributeNames) && !empty($attributeNames)) {
			if (in_array('personal_info', $attributeNames)) {
				$attributeNames[] = 'personalInfoMapping';
			};
			if (in_array('media', $attributeNames)) {
				$attributeNames[] = 'mediaMapping';
			}
			if (in_array('settings', $attributeNames)) {
				$attributeNames[] = 'settingsMapping';
			}
			if (in_array('videos', $attributeNames)) {
				$attributeNames[] = 'videosMapping';
			}
			if (in_array('faq', $attributeNames)) {
				$attributeNames[] = 'faqMapping';
			}
			if (in_array('shipping_settings', $attributeNames)) {
				$attributeNames[] = 'shippingSettingsMapping';
			}
		}

		return parent::validate($attributeNames, $clearErrors);
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
		// remove not allowed html tags
		$this->text_short_description = Person::stripNotAllowedHtmlTags($this->text_short_description);
		$this->text_biography = Person::stripNotAllowedHtmlTags($this->text_biography);
		$this->personalInfoMapping->name = Person::stripNotAllowedHtmlTags($this->personalInfoMapping->name, '');
		$this->personalInfoMapping->last_name = Person::stripNotAllowedHtmlTags($this->personalInfoMapping->last_name,
			'');
		$this->personalInfoMapping->brand_name = Person::stripNotAllowedHtmlTags($this->personalInfoMapping->brand_name,
			'');

		if (!array_key_exists("auth_key", $this->credentials) || $this->credentials["auth_key"] === null) {
			$this->credentials = array_merge_recursive($this->credentials, [
				"auth_key" => Yii::$app->getSecurity()->generateRandomString(128)
			]);
		}

		// Always update slug ?
//		if (empty($this->slug)) {
		$this->slug = Slugger::slugify($this->personalInfoMapping->getVisibleName());
//		}

		if (empty($this->account_state)) {
			$this->account_state = Person::ACCOUNT_STATE_DRAFT;
		}

		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function beforeDelete()
	{
		if ($this->isDeviser()) {
			$products = $this->getProducts();
			/* @var Product[] $products */
			foreach ($products as $item) {
				$item->delete();
			}
		}

		$boxes = $this->getBoxes();
		foreach ($boxes as $item) {
			$item->delete();
		}

		$loveds = $this->getLoveds();
		foreach ($loveds as $item) {
			$item->delete();
		}

		$stories = $this->getStories();
		foreach ($stories as $item) {
			$item->delete();
		}

		$timelines = $this->getTimelines();
		foreach ($timelines as $item) {
			$item->delete();
		}

		$this->deletePhotos();

		return parent::beforeDelete();
	}

	/**
	 * @return Product[]
	 */
	public function getProducts()
	{
		return Product::findSerialized(["deviser_id" => $this->id]);
	}

	/**
	 * @return Box[]
	 */
	public function getBoxes()
	{
		return Box::findSerialized(["person_id" => $this->id]);
	}

	/**
	 * @return Post[]
	 */
	public function getPosts()
	{
		return Post::findSerialized(["person_id" => $this->id]);
	}

	/**
	 * @return Loved[]
	 */
	public function getLoveds()
	{
		return Loved::findSerialized(["person_id" => $this->id]);
	}

	/**
	 * @return Loved[]
	 */
	public function getLovedsProduct()
	{
		$lovedsReturn = [];
		$loveds = $this->getLoveds();
		foreach ($loveds as $loved) {
			$product = $loved->getProduct();
			if (empty($product) || $product->product_state != \app\models\Product::PRODUCT_STATE_ACTIVE) {
				continue;
			}
			$lovedsReturn[] = $loved;
		}

		return $lovedsReturn;
	}

	/**
	 * @return Story[]
	 */
	public function getStories()
	{
		return Story::findSerialized(["person_id" => $this->id]);
	}

	/**
	 * @return Timeline[]
	 */
	public function getTimelines()
	{
		return Timeline::findSerialized(["person_id" => $this->id]);
	}

	public function deletePhotos()
	{
		Utils::rmdir($this->getUploadedFilesPath());
	}

	public function setPassword($password)
	{
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$password = bin2hex(Yii::$app->Scrypt->calc($password, $salt, 8, 8, 16, 32));
		$credentials = $this->credentials;
		$credentials['salt'] = $salt;
		$credentials['password'] = $password;
		$this->setAttribute('credentials', $credentials);
	}

	public function setLanguage($lang)
	{
//		$this->preferencesMapping->lang = $lang;
		$this->settingsMapping->lang = $lang;
	}

	public function rules()
	{
		return [
			[
				[
					'credentials',
					'type',
				],
				'required',
				'on' => [
					self::SCENARIO_DEVISER_CREATE_DRAFT,
					self::SCENARIO_INFLUENCER_CREATE_DRAFT,
					self::SCENARIO_CLIENT_CREATE
				]
			],
			[
				[
					'personal_info',
				],
				'required',
				'on' => [self::SCENARIO_CLIENT_CREATE]
			],
			[
				[
					'account_state',
					'application_fee',
				],
				'safe',
				'on' => [self::SCENARIO_ADMIN]
			],
			[
				[
					'personal_info',
					'categories',
					'credentials',
					'text_short_description',
					'text_biography',
//					'preferences',
					'curriculum',
					'media',
					'settings',
					'press',
					'videos',
					'faq',
					'slug',
				],
				'safe',
				'on' => [
					self::SCENARIO_DEVISER_UPDATE_DRAFT,
					self::SCENARIO_DEVISER_UPDATE_PROFILE,
					self::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					self::SCENARIO_INFLUENCER_UPDATE_PROFILE
				]
			],
			[
				[
					'shipping_settings',
				],
				'safe',
				'on' => [
					self::SCENARIO_DEVISER_UPDATE_DRAFT,
					self::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				[
					'personal_info',
					'credentials',
					'text_short_description',
					'text_biography',
//					'preferences',
					'media',
					'settings',
					'slug',
				],
				'safe',
				'on' => [
					Person::SCENARIO_CLIENT_UPDATE,
				]
			],
			[
				[
					'account_state',
				],
				'validateAmountProducts',
				'on' => [self::SCENARIO_DEVISER_UPDATE_PROFILE],
			],
			[
				[
					'text_short_description',
				],
				'required',
				'on' => [self::SCENARIO_INFLUENCER_UPDATE_PROFILE],
			],
			[
				'account_state',
				'in',
				'range' => [self::ACCOUNT_STATE_DRAFT, self::ACCOUNT_STATE_ACTIVE],
				'on' => [
					self::SCENARIO_DEVISER_UPDATE_PROFILE,
					self::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					self::SCENARIO_CLIENT_UPDATE
				],
			],
			[
				'text_short_description',
				'app\validators\TranslatableValidator',
//				'on' => [self::SCENARIO_DEVISER_UPDATE_PROFILE, self::SCENARIO_INFLUENCER_UPDATE_PROFILE, self::SCENARIO_CLIENT_UPDATE],
			],
			[
				'text_biography',
				'app\validators\TranslatableValidator',
//				'on' => [self::SCENARIO_DEVISER_UPDATE_PROFILE, self::SCENARIO_INFLUENCER_UPDATE_PROFILE, self::SCENARIO_CLIENT_UPDATE],
			],
//			[
//				'preferencesMapping',
//				'yii2tech\embedded\Validator',
//				'on' => [self::SCENARIO_DEVISER_CREATE_DRAFT, self::SCENARIO_INFLUENCER_CREATE_DRAFT, self::SCENARIO_CLIENT_UPDATE],
//			],
			[
				'personalInfoMapping',
				'app\validators\EmbedDocValidator',
				'on' => [
					self::SCENARIO_DEVISER_CREATE_DRAFT,
					self::SCENARIO_DEVISER_UPDATE_DRAFT,
					self::SCENARIO_DEVISER_UPDATE_PROFILE,
					self::SCENARIO_INFLUENCER_CREATE_DRAFT,
					self::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					self::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					self::SCENARIO_CLIENT_CREATE,
					self::SCENARIO_CLIENT_UPDATE,
					self::SCENARIO_AFFILIATES,
				],
			],
			[
				'mediaMapping',
				'app\validators\EmbedDocValidator',
				'on' => [
					self::SCENARIO_DEVISER_UPDATE_DRAFT,
					self::SCENARIO_DEVISER_UPDATE_PROFILE,
					self::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					self::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					self::SCENARIO_CLIENT_UPDATE,
				],
			],
			[
				'settingsMapping',
				'app\validators\EmbedDocValidator',
				'on' => [
					self::SCENARIO_DEVISER_UPDATE_DRAFT,
					self::SCENARIO_DEVISER_UPDATE_PROFILE,
					self::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					self::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					self::SCENARIO_CLIENT_UPDATE,
				],
			],
			[
				'curriculum',
				'app\validators\PersonCurriculumValidator',
				'on' => [self::SCENARIO_DEVISER_UPDATE_PROFILE, self::SCENARIO_INFLUENCER_UPDATE_PROFILE],
			],
			['press', 'app\validators\PersonPressFilesValidator'],
			['videosMapping', 'app\validators\EmbedDocValidator'],
			// to apply rules
			['faqMapping', 'app\validators\EmbedDocValidator'],
			// to apply rules
			['shippingSettingsMapping', 'app\validators\EmbedDocValidator'],
			// to apply rules
		];
	}

	/**
	 * Custom validator for amount of products published
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateAmountProducts($attribute, $params)
	{
		$products = Product::findSerialized([
			'deviser_id' => $this->id,
			'product_state' => Product::PRODUCT_STATE_ACTIVE,
		]);
		if (empty($products)) {
			$this->addError('products', 'Must have at least one work published.');
		}
	}

	/**
	 * Add additional error to make easy show labels in client side
	 */
	public function afterValidate()
	{
		parent::afterValidate();

		$sections = [];
		$viewSections = [
			'header_info' => [
				'header',
				'profile',
				'name',
				'last_name',
				'brand_name',
				'country',
				'city',
				'text_short_description',
			],
			'about' => [
				'photos',
				'text_biography',
				'categories',
			],
			'store' => [
				'products',
			],
		];
		foreach ($this->errors as $attribute => $error) {
			switch ($attribute) {
				default:
					if (Utils::isRequiredError($error)) {
						$this->addError("required", $attribute);
					}
					$this->addError("fields", $attribute);
					break;
			}
			foreach ($viewSections as $section => $fields) {
				if (in_array($attribute, $fields) && !in_array($section, $sections)) {
					$sections[] = $section;
					$this->addError("sections", $section);
				}
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
				self::$serializeFields = [
					'id' => 'short_id',
					'affiliate_id' => 'affiliate_id',
					'slug',
					'name' => "name",
					'url_avatar' => "profileImage",
					"header_image" => 'headerImage',
					"header_small_image" => 'headerSmallImage',
					"profile_image" => 'profileImage',
					'main_link' => 'mainLink',
					'store_link' => 'storeLink',
					'loved_link' => 'lovedLink',
					'boxes_link' => 'boxesLink',
					'stories_link' => 'storiesLink',
					'social_link' => 'socialLink',
					'about_link' => 'aboutLink',
					'press_link' => 'pressLink',
					'videos_link' => 'videosLink',
					'faq_link' => 'faqLink',
					'chat_link' => 'chatLink',
					'is_followed' => 'isFollowed',
					'person_type' => 'personTypeForUrl',
				];

				static::$retrieveExtraFields = [
					'follow',
				];

				self::$translateFields = true;

				break;
			case self::SERIALIZE_SCENARIO_PUBLIC:
				self::$serializeFields = [
					'id' => 'short_id',
					'affiliate_id' => 'affiliate_id',
					'slug',
					'text_short_description',
					'text_biography',
					'categories',
					'collections',
					'city' => 'city',
					'country' => 'country',
//					'personal_info',
					'media',
					'press',
					'videos' => 'videosPreview',
					'faq',
					'curriculum',
					'account_state',
					'name' => "name",
					'url_images' => 'urlImagesLocation',
					'url_avatar' => "profileImage",
					"header_image" => 'headerImage',
					"header_small_image" => 'headerSmallImage',
					"profile_image" => 'profileImage',
					'main_link' => 'mainLink',
					'store_link' => 'storeLink',
					'loved_link' => 'lovedLink',
					'boxes_link' => 'boxesLink',
					'stories_link' => 'storiesLink',
					'about_link' => 'aboutLink',
					'press_link' => 'pressLink',
					'videos_link' => 'videosLink',
					'faq_link' => 'faqLink',
					'chat_link' => 'chatLink',
					'is_followed' => 'isFollowed',
					'person_type' => 'personTypeForUrl',
					'type',
				];

				static::$retrieveExtraFields = [
					'videos',
					'personal_info',
					'follow',
				];

				self::$translateFields = true;

				break;
			case self::SERIALIZE_SCENARIO_OWNER:
				static::$serializeFields = [
					'id' => 'short_id',
					'short_id', // TODO Remove when all calls are migrated to new API
					'affiliate_id' => 'affiliate_id',
					'slug',
					'text_short_description',
					'text_biography',
					'categories',
					'collections',
					'personal_info',
					'media',
					'press',
					'videos',
					'faq',
					'shipping_settings',
					'curriculum',
					'account_state',
					'name' => 'name',
					'email' => 'email',
					'url_images' => 'urlImagesLocation',
					'url_avatar' => "profileImage",
					"header_image" => 'headerImage',
					"header_small_image" => 'headerSmallImage',
					"profile_image" => 'profileImage',
					'main_link' => 'mainLink',
					'store_link' => 'storeLink',
					'loved_link' => 'lovedLink',
					'boxes_link' => 'boxesLink',
					'stories_link' => 'storiesLink',
					'about_link' => 'aboutLink',
					'press_link' => 'pressLink',
					'videos_link' => 'videosLink',
					'faq_link' => 'faqLink',
					'chat_link' => 'chatLink',
					'is_followed' => 'isFollowed',
					'person_type' => 'personTypeForUrl',
					'type',
					'profile_views',
          'available_earnings',
          'total_won_so_far',
					'follow',
          'historic',
          'earnings_by_user',

					// only availables in owner scenario:
					'store_edit_link' => 'storeEditLink',
					'about_edit_link' => 'aboutEditLink',
					'press_edit_link' => 'pressEditLink',
					'videos_edit_link' => 'videosEditLink',
					'faq_edit_link' => 'faqEditLink',

					'settings',
					'available_countries' => 'availableCountries',
//					'preferences',
				];

				static::$retrieveExtraFields = [
					'credentials',
					'videos',
				];

				self::$translateFields = false;

				break;
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'short_id', // TODO Remove when all calls are migrated to new API
					'affiliate_id' => 'affiliate_id',
					'slug',
					'text_short_description',
					'text_biography',
					'categories',
					'collections',
					'personal_info',
					'media', //  => 'mediaInfoAttributes',
					'settings',
					'press',
					'videos',
					'faq',
					'shipping_settings',
					'curriculum',
					'account_state',
//					'preferences',
					'url_images' => 'urlImagesLocation',
				];

				self::$translateFields = false;

				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	public function getCity()
	{
		return $this->personalInfoMapping->city;
	}

	public function getCountry()
	{
		return $this->personalInfoMapping->country;
	}

	public function getAvailableCountries()
	{
		return [
			'ES',
		];
	}

	/**
	 * Get the url to download any file of the Person
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	public function getDownloadFileUrl($file)
	{
		return Yii::getAlias("@deviser_url") . "/" . $this->short_id . "/" . $file;
	}

	/**
	 * Get the url to get the images of a Deviser
	 *
	 * @return string
	 */
	public function getUrlImagesLocation()
	{
		return Yii::getAlias("@deviser_url") . "/" . $this->short_id . "/";
	}

	/**
	 * Get the url to get the images of a Deviser
	 *
	 * @return string
	 */
	public function getUrlResumeFile()
	{
		return $this->getDownloadFileUrl($this->curriculum);
	}

	/**
	 * Indicate if Deviser has a resume file attached
	 *
	 * @return bool
	 */
	public function hasResumeFile()
	{
		$filePath = $this->getUploadedFilesPath() . '/' . $this->curriculum;

		return (($this->curriculum) && (file_exists($filePath)));
	}

	/**
	 * Get a resized version of header image, to 1170px width
	 *
	 * @return string
	 */
	public function getHeaderImage($width = 1170, $height = 0, $fill = null)
	{
		$url = "/imgs/default-cover.jpg";

		$image = null;
		if (Person::existMediaFile($this->mediaMapping->header_cropped)) {
			$image = Person::getUrlImagesLocation() . $this->mediaMapping->header_cropped;
		} elseif (Person::existMediaFile($this->mediaMapping->header)) {
			$image = Person::getUrlImagesLocation() . $this->mediaMapping->header;
		}
		if ($image) {
			if ($fill) {
				$url = Utils::url_scheme() . Utils::thumborize($image)->fitIn($width, $height)->addFilter('fill', $fill);
			} else {
				$url = Utils::url_scheme() . Utils::thumborize($image)->resize($width, $height);
			}
		}

		return $url;
	}

	/**
	 * Get a resized version of header image
	 * This method tries to get the "header_cropped_small" image
	 *
	 * @return string
	 */
	public function getHeaderSmallImage($width = 702, $height = 450)
	{
		$url = "/imgs/default-cover.jpg";

		$image = null;
		if (Person::existMediaFile($this->mediaMapping->header_cropped_small)) {
			$image = Person::getUrlImagesLocation() . $this->mediaMapping->header_cropped_small;
		} elseif (Person::existMediaFile($this->mediaMapping->header_cropped)) {
			$image = Person::getUrlImagesLocation() . $this->mediaMapping->header_cropped;
		} elseif (Person::existMediaFile($this->mediaMapping->header)) {
			$image = Person::getUrlImagesLocation() . $this->mediaMapping->header;
		}
		if ($image) {
			$url = Utils::url_scheme() . Utils::thumborize($image)->resize($width, $height);
		}

		return $url;
	}

	/**
	 * Get a resized version of profile image, to 155x155
	 *
	 * @return string
	 */
	public function getProfileImage($width = 155, $height = 155)
	{
		$url = '/imgs/default-avatar.png';

		$image = null;
		if (Person::existMediaFile($this->mediaMapping->profile_cropped)) {
			$image = Person::getUrlImagesLocation() . $this->mediaMapping->profile_cropped;
		} elseif (Person::existMediaFile($this->mediaMapping->profile)) {
			$image = Person::getUrlImagesLocation() . $this->mediaMapping->profile;
		}
		if ($image) {
			$url = Utils::url_scheme() . Utils::thumborize($image)->resize($width, $height);
		}

		return $url;
	}

	/**
	 * Get press data with additional info for preview context
	 *
	 * @return array
	 */
	public function getVideosPreview()
	{
		$videos = [];
		foreach ($this->videosMapping as $item) {
			/** @var PersonVideo $item */
			$products = [];
			foreach ($item->products as $product_id) {
				/** @var Product $product */
				$product = Product::findOneSerialized($product_id);
				$products[] = $product->getPreviewSerialized();
			}
			$videos[] = [
				"url" => $item->url,
				"products" => $products,
			];
		}

		return $videos;
	}

	/**
	 * Get media files attributes from their own Model, not from array.
	 *
	 * @return array
	 */
	public function getMediaInfoAttributes()
	{
		$media = $this->mediaMapping->getAttributes();

		return $media;
	}

	/**
	 * Get the location from Person (city and country).
	 *
	 * @return string
	 */
//	public function getLocationLabel()
//	{
//		$location = [];
//		if (isset($this->personal_info)) {
//			if (isset($this->personal_info['city'])) {
//				$location[] = $this->personal_info['city'];
//			}
//			/** @var Country $country */
//			if (isset($this->personal_info['country'])) {
//				$country = Country::findOne(['country_code' => $this->personal_info['country']]);
//				if ($country) {
//					$location[] = Utils::l($country->country_name);
//				}
//			}
//		}
//
//		return implode(", ", $location);
//	}

	/**
	 * Get the category names to show in a label
	 *
	 * @return string
	 */
	public function getCategoriesLabel()
	{
		$categories = [];
		if (isset($this->categories)) {
			foreach ($this->categories as $category_id) {
				/** @var Category $category */
				$category = Category::findOne(['short_id' => $category_id]);
				if ($category) {
					$categories[] = Utils::l($category->name);
				}
			}
		}

		return implode(", ", $categories);
	}

	/**
	 * Return the second level categories related with products of the deviser.
	 *
	 * @return array
	 */
	public function getCategoriesOfProducts()
	{
		$level2Categories = [];

		// TODO could be optimized with an aggregation ??
		$products = Product::find()
			->select(
				[
					'short_id',
					'categories',
					'media'
				])
			->where(
				[
					'deviser_id' => $this->short_id,
					'product_state' => Product::PRODUCT_STATE_ACTIVE,
				])
			->all();

		$detailCategoriesIds = [];
		/** @var Product $product */
		foreach ($products as $product) {
			$detailCategoriesIds = array_unique(array_merge($detailCategoriesIds, $product->categories));
		}

		// now, get the models for those categories
		$detailCategories = Category::find()
			->select(
				[
					'short_id',
					'name',
					'slug',
					'path',
				])
			->where(
				[
					'short_id' => $detailCategoriesIds,
				])
			->all();

		$level2Ids = [];

		/** @var Category $category */
		foreach ($detailCategories as $category) {
			// remove first slash, and find id of second level category
			$ancestors = explode('/', rtrim(ltrim($category->path, '/'), '/'));
			if (count($ancestors) == 1) {
				// second level category => we add current category to array (with null as children)
				$level2Id = $category->short_id;
				$level3Id = null;
			} elseif (count($ancestors) > 1) {
				// third or more level => we add the 2nd level category from the path to array (with current category as children)
				$level2Id = $ancestors[1];
				$level3Id = $category->short_id;
			}
			if (isset($level2Id)) {
				if (array_key_exists($level2Id, $level2Ids)) {
					$level2Ids[$level2Id] = array_merge($level2Ids[$level2Id], [$level3Id]);
				} else {
					$level2Ids[$level2Id][] = $level3Id;
				}
			}
		}

		/** @var Category $category */
		foreach ($level2Ids as $id => $subIds) {
			$category = Category::findOne(['short_id' => $id]);
			if ($category) {

				$categoryProduct = Product::findOne([
					'deviser_id' => $this->short_id,
					'categories' => $category->getShortIds(),
					'product_state' => Product::PRODUCT_STATE_ACTIVE,
				]);
				if (!$categoryProduct) {
					throw new \yii\db\Exception("Cannot find a product of " . $this->getName() . " in the category " . $category->getName());
				}
				// assign one product of the deviser, related with this category
				$category->setDeviserProduct($categoryProduct);
				$category->setDeviserSubcategories(Category::find()->where(['short_id' => $subIds])->all());

				// if there are more than one subcategory, add "all" subcategory
				if (count($category->getDeviserSubcategories()) > 1) {
					$subcategoryAll = new Category();
					$subcategoryAll->name = [
						Lang::EN_US => "All",
						Lang::ES_ES => "Todos",
					];
					$category->setDeviserSubcategories(array_merge([$subcategoryAll],
						$category->getDeviserSubcategories()));
				}

				$level2Categories[] = $category;
			}
		}

		// if there are more than one category, add "all products category"
		if (count($level2Categories) > 1) {
			$category = new Category();
			$category->name = [
				Lang::EN_US => "All Products",
				Lang::ES_ES => "Todos los productos",
			];
			$category->setDeviserProduct(Product::findOne(["deviser_id" => $this->short_id]));
			$level2Categories = array_merge([$category], $level2Categories);
		}

		return $level2Categories;
	}

	/**
	 * Get only preview attributes from Person
	 *
	 * @return array
	 */
	public function getPreviewSerialized()
	{
		return [
			"id" => $this->short_id,
			"slug" => $this->slug,
			"name" => $this->personalInfoMapping->getVisibleName(),
			"url_avatar" => $this->getProfileImage(),
			"header_image" => $this->getHeaderImage(),
			"header_small_image" => $this->getHeaderSmallImage(),
			"profile_image" => $this->getProfileImage(),
			'main_link' => $this->getMainLink(),
			'store_link' => $this->getStoreLink(),
			'loved_link' => $this->getLovedLink(),
			'boxes_link' => $this->getBoxesLink(),
			'stories_link' => $this->getStoriesLink(),
			'social_link' => $this->getSocialLink(),
			'about_link' => $this->getAboutLink(),
			'press_link' => $this->getPressLink(),
			'videos_link' => $this->getVideosLink(),
			'faq_link' => $this->getFaqLink(),
			'chat_link' => $this->getChatLink(),
			'is_followed' => $this->getIsFollowed(),
			'person_type' => $this->getPersonTypeForUrl(),
      'available_earnings' => $this->available_earnings,
      'total_won_so_far' => $this->total_won_so_far,

			// TODO: delete this two fields
			"photo" => $this->getProfileImage(),
			'url' => $this->getMainLink(),

		];
	}

	/**
	 * Shortcut to get the name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->personalInfoMapping->getVisibleName();
	}

	/**
	 * Shortcut to get the complete address
	 *
	 * @return string
	 */
	public function getCompleteAddress()
	{
		return $this->personalInfoMapping->getCompleteAddress();
	}

	/**
	 * Shortcut to get the name
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return isset($this->credentials['email']) ? $this->credentials['email'] : null;
	}

	/**
	 * Get the urls of images to use in "about" deviser page
	 *
	 * @return array
	 */
	public function getAboutUrlImages()
	{
		// initialize
		$urls = [
			"/imgs/photo-grid-about-1.jpg",
			"/imgs/photo-grid-about-2.jpg",
			"/imgs/photo-grid-about-3.jpg",
			"/imgs/photo-grid-about-4.jpg",
			"/imgs/photo-grid-about-5.jpg",
			"/imgs/photo-grid-about-6.jpg",
			"/imgs/photo-grid-about-7.jpg",
		];

		// if photos are stored, then return them
		$urls = [];
		foreach ($this->mediaMapping->photos as $filename) {
			$urls[] = $this->getUrlImagesLocation() . $filename;
		}

		return $urls;
	}

	/**
	 * Spread scenario to sub documents
	 *
	 * @param string $value
	 */
	public function setScenario($value)
	{
		//TODO: delete this method? This behaviour should be controlled by EmbedModel
		parent::setScenario($value);
		$this->personalInfoMapping->setScenario($value);
		$this->mediaMapping->setScenario($value);
//		$this->preferencesMapping->setScenario($value);
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

		if (array_key_exists('personal_info', $data)) {
			$this->personalInfoMapping->load($data, 'personal_info');
		}
		if (array_key_exists('media', $data)) {
			$this->mediaMapping->load($data, 'media');
		}
//		if (array_key_exists('preferences', $data)) {
//			$this->preferencesMapping->load($data, 'preferences');
//		}
		if (array_key_exists('settings', $data)) {
			$this->settingsMapping->load($data, 'settings');
		}

		return ($loaded);
	}

	/**
	 * Indicate if current Person is in "draft" state
	 *
	 * @return bool
	 */
	public function isDraft()
	{
		return ($this->account_state == Person::ACCOUNT_STATE_DRAFT);
	}

	/**
	 * Check if Deviser media file is exists
	 *
	 * @param string $filename
	 *
	 * @return bool
	 */
	public function existMediaFile($filename)
	{
		if (empty($filename)) {
			return false;
		}

		$filePath = $this->getUploadedFilesPath() . '/' . $filename;

		return file_exists($filePath);
	}

	/**
	 * Filter videos that are related with a Product ID
	 *
	 * @param $productId
	 *
	 * @return array
	 */
	public function findVideosByProductId($productId)
	{
		$videos = [];
		/** @var PersonVideo $video */
		foreach ($this->videosMapping as $video) {
			if (in_array($productId, $video->products)) {
				$videos[] = $video;
			}
		}

		return $videos;
	}

	/**
	 * Returns TRUE if the person can be edited by the current user
	 *
	 * @return bool
	 */
	public function isPersonEditable()
	{
		if (Yii::$app->user->isGuest) {
			return false;
		}
		if (Yii::$app->user->identity->isAdmin()) {
			return true;
		}
		if ($this->isConnectedUser()) {
			return true;
		}

		return false;
	}

	/**
	 * Returns TRUE if the person is a deviser, and can be edited by the current user
	 *
	 * @return bool
	 */
	public function isDeviserEditable()
	{
		if (!$this->isDeviser() || !$this->isPersonEditable()) {
			return false;
		}

		return true;
	}

	/**
	 * Returns TRUE if the person is an influencer, and can be edited by the current user
	 *
	 * @return bool
	 */
	public function isInfluencerEditable()
	{
		if (!$this->isInfluencer() || !$this->isPersonEditable()) {
			return false;
		}

		return true;
	}

	/**
	 * Returns TRUE if the person is a deviser
	 *
	 * @return bool
	 */
	public function isDeviser()
	{
		return
			$this->type == self::DEVISER ||
			in_array(self::DEVISER, $this->type);
	}

	/**
	 * Returns TRUE if the person is a admin
	 *
	 * @return bool
	 */
	public function isAdmin()
	{
		return
			$this->type == self::ADMIN ||
			in_array(self::ADMIN, $this->type);
	}

	/**
	 * Returns TRUE if the person is a client
	 *
	 * @return bool
	 */
	public function isClient()
	{
		return
			$this->type == self::CLIENT ||
			in_array(self::CLIENT, $this->type);
	}

	/**
	 * Returns TRUE if the person is a client
	 *
	 * @return bool
	 */
	public function isInfluencer()
	{
		return
			$this->type == self::INFLUENCER ||
			in_array(self::INFLUENCER, $this->type);
	}

	/**
	 * Returns TRUE if the current connected users is this person
	 * @return bool
	 */
	public function isConnectedUser()
	{
		return
			!Yii::$app->user->isGuest &&            // has to be a connected user
			Yii::$app->user->id === $this->id        // the person must be the connected user
			;
	}

	public function getPersonTypeForUrl()
	{
		if ($this->isDeviser()) {
			return "deviser";
		} elseif ($this->isInfluencer()) {
			return "influencer";
		} elseif ($this->isClient()) {
			return "client";
		}

		return null;
	}

	public function getMainLink()
	{
		if ($this->showStore()) {
			return $this->getStoreLink();
		} elseif ($this->showSocial()) {
			return $this->getSocialLink();
		} elseif ($this->showLoved()) {
			return $this->getLovedLink();
		} elseif ($this->showBoxes()) {
			return $this->getBoxesLink();
		} elseif ($this->showStories()) {
			return $this->getStoriesLink();
		} elseif ($this->showAbout()) {
			return $this->getAboutLink();
		} elseif ($this->showPress()) {
			return $this->getPressLink();
		} elseif ($this->showVideos()) {
			return $this->getVideosLink();
		} elseif ($this->showFaq()) {
			return $this->getFaqLink();
		} else {
			return $this->getFollowLink();
		}

		return Yii::$app->getHomeUrl();
	}

	public function getSettingsLink($action = null)
	{
		return Url::to(["/settings/" . $action, "slug" => $this->getSlug(), 'person_id' => $this->short_id]);
	}

	public function getSettingsBaseLink($action = 'index')
	{
		return Url::to(["/settings/", "slug" => $this->getSlug(), 'person_id' => $this->short_id]);
	}

	public function getStoreLink($params = [])
	{
		if ($this->isDeviser()) {
			$params = array_merge(
				[
					"/person/store",
					"slug" => $this->getSlug(),
					'person_id' => $this->short_id,
					"person_type" => $this->getPersonTypeForUrl()
				],
				$params
			);

			return Url::to($params, true);
		}

		return null;
	}

	public function getStoreEditLink($params = [])
	{
		if ($this->isDeviser()) {
			$params = array_merge(
				[
					"/person/store-edit",
					"slug" => $this->getSlug(),
					'person_id' => $this->short_id,
					"person_type" => $this->getPersonTypeForUrl(),
				],
				$params
			);

			return Url::to($params, true);
		}

		return null;
	}

	public function getCompleteProfileLink()
	{
		return Url::to([
			"/person/complete-profile",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getPersonNotPublicLink()
	{
		return Url::to([
			"/person/person-not-public",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getLovedLink()
	{
		return Url::to([
			"/person/loved",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getBoxesLink()
	{
		return Url::to([
			"/person/boxes",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getStoriesLink()
	{
		return Url::to([
			"/person/stories",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getFollowersLink()
	{
		return Url::to([
			"/person/followers",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getFollowLink()
	{
		return Url::to([
			"/person/follow",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getSocialLink()
	{
		return Url::to([
			"/person/social",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getStoryCreateLink()
	{
		return Url::to([
			"/person/story-create",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getAboutLink()
	{
		return Url::to([
			"/person/about",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getAboutEditLink()
	{
		return Url::to([
			"/person/about-edit",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getPressLink()
	{
		return Url::to([
			"/person/press",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getPressEditLink()
	{
		return Url::to([
			"/person/press-edit",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getVideosLink()
	{
		return Url::to([
			"/person/videos",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getVideosEditLink()
	{
		return Url::to([
			"/person/videos-edit",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getFaqLink()
	{
		return Url::to([
			"/person/faq",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getFaqEditLink()
	{
		return Url::to([
			"/person/faq-edit",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getCreateWorkLink()
	{
		return Url::to([
			"/product/create",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getConnectWithInstagramLink()
	{
		return Url::to([
			"/person/connect-instagram",
			"slug" => $this->getSlug(),
			'person_id' => $this->short_id,
			"person_type" => $this->getPersonTypeForUrl()
		], true);
	}

	public function getChatLink()
	{
		return Url::to(["/chat/conversation", "slug" => $this->getSlug(), 'person_id' => $this->short_id], true);
	}

	public function getSlug()
	{
		if (is_array($this->slug)) {
			$slug = Utils::l($this->slug);
		} else {
			$slug = $this->slug;
		}

		return $slug;
	}

	/**
	 * Returns a number of random devisers.
	 *
	 * @param int $limit
	 * @param array $categories
	 *
	 * @return Product[]
	 */
	public static function getRandomDevisers($limit, $categories = [])
	{
		if (!empty($categories)) {

			// Filter by deviser and exclude unpublished profiles
			$conditions[] =
				[
					'$match' => [
						"type" => [
							'$in' => [
								Person::DEVISER,
							]
						],
						"account_state" => [
							'$nin' => [
								Person::ACCOUNT_STATE_BLOCKED,
								Person::ACCOUNT_STATE_DRAFT,
							]
						],
					],
				];

			// Here, we have all devisers active
			$activeDevisers = Yii::$app->mongodb->getCollection('person')->aggregate($conditions);
			$personIds = [];
			foreach ($activeDevisers as $person) {
				$personIds[] = $person['short_id'];
			}

			// Now, we find products in the category for this devisers
			$conditions = [];

			// Exclude drafts
			$conditions[] =
				[
					'$match' => [
						"product_state" => [
							'$eq' => Product::PRODUCT_STATE_ACTIVE,
						],
						"categories" => [
							'$in' => $categories,
						],
						"deviser_id" => [
							'$in' => $personIds,
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

			$randomWorks = Yii::$app->mongodb->getCollection('product')->aggregate($conditions);

			// And now we get deviser ids of this works
			$personIds = [];
			foreach ($randomWorks as $work) {
				$personIds[] = $work['deviser_id'];
			}

		} else {

			// Index

			$forcedIds = [
				'5c7020p',
				'4764a66',
				'64ce615',
				'54c30b0',
				'74fdc2v',
				'aeb317a',
				'0f6c308',
				'5e87525',
				'8252f39',
				'353e447',
				'b818a0w',
				'8216520',
				'8dd81bi',
				'c951bfk',
				'329504s',
				'e23e0bv',
				'9d5b9a9',
				'722044p',
			];

			// Filter by deviser and exclude unpublished profiles
			$conditions[] =
				[
					'$match' => [
						"type" => [
							'$in' => [
								Person::DEVISER,
							]
						],
						"account_state" => [
							'$nin' => [
								Person::ACCOUNT_STATE_BLOCKED,
								Person::ACCOUNT_STATE_DRAFT,
							]
						],
						"short_id" => [
							'$in' => $forcedIds,
						],
					],
				];
			// Randomize
			$conditions[] =
				[
					'$sample' => [
						'size' => $limit,
					]
				];

			$randomPersons = Yii::$app->mongodb->getCollection('person')->aggregate($conditions);

			$personIds = [];
			foreach ($randomPersons as $deviser) {
				$personIds[] = $deviser['short_id'];
			}
		}


		if ($personIds) {
			$query = new ActiveQuery(Person::className());
			$query->where(['in', 'short_id', $personIds]);
			$persons = $query->all();
			shuffle($persons);
		} else {
			$persons = [];
		}

		return $persons;
	}

	/**
	 * Returns a number of random devisers.
	 *
	 * @param int $limit
	 * @param array $categories
	 *
	 * @return Product[]
	 */
	public static function getRandomInfluencers($limit, $categories = [])
	{
		// Filter by influencer and exclude unpublished profiles
		$conditions[] =
			[
				'$match' => [
					"type" => [
						'$in' => [
							Person::INFLUENCER,
						]
					],
					"account_state" => [
						'$nin' => [
							Person::ACCOUNT_STATE_BLOCKED,
							Person::ACCOUNT_STATE_DRAFT,
						]
					],
				],
			];

		if (!empty($categories)) {
			// Filter by category
			$conditions[] =
				[
					'$match' => [
						"categories" => [
							'$in' => $categories
						]
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

		$randomPersons = Yii::$app->mongodb->getCollection('person')->aggregate($conditions);

		$personIds = [];
		foreach ($randomPersons as $influencer) {
			$personIds[] = $influencer['short_id'];
		}

		if ($personIds) {
			$query = new ActiveQuery(Person::className());
			$query->where(['in', 'short_id', $personIds]);
			$persons = $query->all();
			shuffle($persons);
		} else {
			$persons = [];
		}

		return $persons;
	}

	/**
	 * Returns TRUE if profile is completed: all fields in "step 2" of register are filled
	 *
	 * @return bool
	 */
	public function isCompletedProfile()
	{

		if ($this->isPublic()) {
			return true;
		}

		if ($this->isDeviser()) {
			return (
				!empty($this->personalInfoMapping->name) &&
				!empty($this->personalInfoMapping->brand_name) &&
				!empty($this->getCity()) &&
				!empty($this->categories) &&
				!empty($this->text_short_description) &&
				!empty($this->text_biography) &&
				!empty($this->mediaMapping->header) &&
				!empty($this->mediaMapping->profile) &&
				true
			);
		} elseif ($this->isInfluencer()) {
			return (
				!empty($this->personalInfoMapping->name) &&
				!empty($this->personalInfoMapping->last_name) &&
				!empty($this->getCity()) &&
				!empty($this->text_short_description) &&
				!empty($this->mediaMapping->header) &&
				!empty($this->mediaMapping->profile) &&
				true
			);
		}

		return true;
	}

	/**
	 * Returns TRUE if profile is public
	 *
	 * @return bool
	 */
	public function isPublic()
	{
		return $this->account_state == Person::ACCOUNT_STATE_ACTIVE;
	}

	/**
	 * Returns TRUE if profile can be published
	 *
	 * @return bool
	 */
	public function canPublishProfile()
	{

		if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()) {
			//Admin user always can publish profiles
			return true;
		}

		if ($this->isDeviser()) {
			return
				$this->hasShippingSettings() &&
				$this->hasStripeInfo() &&
				$this->hasPublishedProducts();
		}

		return true;
	}

	/**
	 * Returns TRUE if the user (deviser) has any shipping settings
	 *
	 * @return bool
	 */
	public function hasShippingSettings()
	{
		foreach ($this->shippingSettingsMapping as $shippingSettings) {
			if (count($shippingSettings->prices) > 0) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns TRUE if the user (deviser) has any stripe connection
	 *
	 * @return bool
	 */
	public function hasStripeInfo()
	{
		return !empty($this->settingsMapping->stripeInfoMapping->access_token);
	}

	/**
	 * Returns TRUE if the user (deviser) has any published product
	 *
	 * @return bool
	 */
	public function hasPublishedProducts()
	{
		$products = Product::findSerialized(
			[
				'deviser_id' => $this->short_id,
				'product_state' => Product::PRODUCT_STATE_ACTIVE,
			]
		);

		return !empty($products);
	}

	/**
	 * @param string|null $country_code
	 *
	 * @return PersonShippingSettings|null
	 */
	public function getShippingSettingByCountry($country_code = null)
	{
		$country_code = $country_code ?: Country::getDefaultContryCode();

		$shippings = $this->shippingSettingsMapping;
		foreach ($shippings as $shipping) {
			if ($shipping->country_code == $country_code) {
				return $shipping;
			}
		}

		return null;
	}

	/**
	 * @param $weight
	 * @param string|null $country_code
	 *
	 * @return array
	 */
	public function getShippingSettingRange($amount, $weight, $country_code = null)
	{
		$shippingSetting = $this->getShippingSettingByCountry($country_code);

		if ($shippingSetting) {
			return $shippingSetting->getShippingSettingRange($amount, $weight);
		}

		return null;
	}

	/**
	 * Returns the application fee to be charged to the user in his sales.
	 * Its a double number in 100base, for example returns 0.145 to represent a 14.5% percentage
	 *
	 * @return double
	 */
	public function getSalesApplicationFee()
	{
		$todeviseFee = $this->getTodeviseFee();

		$fee = $todeviseFee * (1 + $this->getVatOverFee());

		return $fee;
	}

	/**
	 * Returns the application fee to be charged to the user in his sales after discount.
	 * First discount is assumed by Todevise default fee
	 * Its a double number in 100base, for example returns 0.145 - 0.10 = 0.045 to represent a 4.5% percentage
	 * @param double $discount
	 *
	 * @return double
	 */
	public function getSalesApplicationFeeAfterDiscount($discount)
	{
		$todeviseFee = $this->getTodeviseFee();
		$todeviseFee -= $discount;

		$fee = $todeviseFee * (1 + $this->getVatOverFee());

		return $fee;
	}

	/**
	 * Returs the "todevise" fee to apply in the orders of the deviser.
	 * It can be de default value, or a custom value set in the deviser.
	 * Its a double number in 100base, for example returns 0.145 to represent a 14.5% percentage
	 *
	 * @return double
	 */
	public function getTodeviseFee()
	{
		if ($this->application_fee && is_double($this->application_fee)) {
			$todeviseFee = $this->application_fee;
		} else {
			$todeviseFee = Yii::$app->params['default_todevise_fee'];
		}

		return $todeviseFee;
	}

	/**
	 * Returs the "todevise" fee to apply in the orders of the deviser after discount.
	 * It can be de default value, or a custom value set in the deviser.
	 * Its a double number in 100base, for example returns 0.145 - 0.10 = 0.045 to represent a 4.5% percentage
	 * @param double $discount
	 *
	 * @return double
	 */
	public function getTodeviseFeeAfterDiscount($discount)
	{
		if ($this->application_fee && is_double($this->application_fee)) {
			$todeviseFee = $this->application_fee - $discount;
		} else {
			$todeviseFee = Yii::$app->params['default_todevise_fee'] - $discount;
		}

		return $todeviseFee;
	}

	/**
	 * Returns the percentage to apply VAT over the fee in the orders of the deviser, if needed
	 * Its a double number in 100base, for example returns 0.145 to represent a 14.5% percentage
	 *
	 * @return double
	 */
	public function getVatOverFee()
	{
		// At this moment, only Spain devisers apply spain default VAT (21%);
		if (strtoupper($this->personalInfoMapping->country) == 'ES') {
			return Yii::$app->params['default_spain_vat'];
		}

		return 0;
	}

	/**
	 * Returns TRUE if the user is registered in a Country in the European Union
	 * @return bool
	 */
	public function isFromEU()
	{
		if (!empty($this->personalInfoMapping->country)) {
			return Country::isEUCountry($this->personalInfoMapping->country);
		}

		return false;
	}

	public function sendForgotPasswordEmail()
	{
		// handle success
		$email = new PostmanEmail();
		$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_PERSON_FORGOT_PASSWORD;
		$email->to_email = $this->getEmail();
		$email->subject = 'TODEVISE - ' . Yii::t('app/public', 'FORGOT_PASSWORD');

		// add task only one send task (to allow retries)
		$task = new PostmanEmailTask();
		$task->date_send_scheduled = new \MongoDate();
		$email->addTask($task);

		// register the attached action: invitation link
		$action = new PostmanEmailAction();
		$action->code_email_action_type = PostmanEmailAction::EMAIL_ACTION_TYPE_PERSON_FORGOT_PASSWORD;
		$action->person_id = $this->short_id;
		$dateEndAvailable = (new \DateTime('now'))->modify('+48 hours')->getTimestamp();
		$action->date_end_available = new MongoDate($dateEndAvailable);
		$action->amount_uses = 1;
		$email->addAction($action);

		$actionUrl = Url::to(["/public/reset-password", "action_id" => $action->uuid, "person_id" => $this->short_id],
			true);
		$email->body_html = Yii::$app->view->render(
			'@app/mail/person/forgot-password',
			[
				"form" => $this,
				"actionUrl" => $actionUrl,
			],
			$this
		);
		$email->save();

		if ($email->send($task->id)) {
			$email->save();

			return true;
		};

		return false;
	}

	/**
	 * Checks if a PostmanEmailAction defined by an uuid, is associated with the current person object
	 *
	 * @param string $actionUuid
	 *
	 * @return bool
	 */
	public function checkPersonByEmailActionUuid($actionUuid)
	{
		$action = PostmanEmailAction::findOneByUuid($actionUuid);
		if ($action && $action->person_id == $this->short_id && $action->canUse()) {
			return true;
		}

		return false;
	}

	public function addVisit()
	{
		$this->profile_views++;

		// Update directly in low level, to avoid no desired behaviors of ActiveRecord
		/** @var Collection $collection */
		$collection = Yii::$app->mongodb->getCollection('person');
		$collection->update(
			[
				'short_id' => $this->short_id
			],
			[
				'profile_views' => $this->profile_views,
			]
		);
	}

	public function showStore()
	{
		return $this->isDeviser();
	}

	public function showFollowers()
	{
		return $this->isInfluencer() || $this->isDeviser() || $this->isClient();
	}

	public function showSocial()
	{
		$posts = $this->getPosts();
		return
			($this->isInfluencer() || $this->isDeviser()) || $this->isClient() &&
			($this->isPersonEditable() || !empty($posts));
	}

	public function showLoved()
	{
		$loveds = $this->getLovedsProduct();
		return $this->isPersonEditable() || count($loveds) > 0;
	}

	public function showBoxes()
	{
		$boxes = $this->getBoxes();
		return $this->isPersonEditable() || count($boxes) > 0;
	}

	public function showStories()
	{
		return false; //$this->isDeviser() || $this->isInfluencer()
	}

	public function showAbout()
	{
		return
			($this->isDeviser() || $this->isInfluencer()) &&
			($this->isPersonEditable() || !empty($this->text_biography) || !empty($this->mediaMapping->photos));
	}

	public function showPress()
	{
		return
			($this->isDeviser() || $this->isInfluencer()) &&
			($this->isPersonEditable() || !empty($this->press));
	}

	public function showVideos()
	{
		return
			($this->isDeviser() || $this->isInfluencer()) &&
			($this->isPersonEditable() || count($this->videosMapping) > 0);
	}

	public function showFaq()
	{
		return
			($this->isDeviser()) &&
			($this->isPersonEditable() || count($this->faqMapping) > 0);
	}

	public function isActive()
	{
		return $this->account_state == Person::ACCOUNT_STATE_ACTIVE;
	}

	public function isFollowedByConnectedUser()
	{
		if (Yii::$app->user->isGuest) {
			return false;
		}
		/** @var Person $current */
		$current = Yii::$app->user->identity;

		return in_array($this->short_id, $current->follow);
	}

	public function getIsFollowed() {
		return $this->isFollowedByConnectedUser();
	}

	public function getFollow() {
		$persons = [];
		foreach ($this->follow as $person_id) {
			$person = Person::findOneSerialized($person_id);
			if ($person && $person->isActive()) {
				$persons[] = $person;
			}
		}

		return $persons;
	}

	public function getFollowers() {

		/** @var Collection $collection */
		$collection = Yii::$app->mongodb->getCollection('person');
		$persons  =	$collection->find(
			[
				'follow' => $this->short_id
			]
		);

		$ids = [];
		foreach ($persons as $item) {
			$ids[] = $item['_id'];
		}

		if ($ids) {
			$query = new ActiveQuery(Person::className());
			$query->where(['in', '_id', $ids]);
			$persons = $query->all();
		} else {
			$persons= [];
		}

		return $persons;
	}

  public function setHistoric($type, $amount, $person_id)
  {
    // Getting the actual historic
    $historicToAdd = $this->historic;

    // Adding the new line to historic
    $historicToAdd[date('YmdHis')] = [
      "type" => $type,
      "amount" => $amount,
      "person_id" => (string)$person_id,
      "created_at" => new MongoDate(),
    ];
    $this->setAttribute('historic', $historicToAdd);
    $this->save(false);
  }

  public function getRecentHistoric($limit)
  {
    $recent_history = $this->historic;
    if(!empty($recent_history)) {
      krsort($recent_history); // Order by date DESC
      $recent_history = array_slice($recent_history, 0, $limit, true); // Limit rows
    }
    return $recent_history;
  }

  public function setEarningsByUser($order_id, $amount, $person_id)
  {
    $earningsByUser = $this->earnings_by_user;

    $earningsByUser[$person_id]['earnings_by_order']["ORDER".$order_id] = $amount;

    $this->setAttribute('earnings_by_user', $earningsByUser);
    $this->save(false);
  }

  public function getTotalEarnings($affiliates) {

    $affiliatesAux = array();
    foreach ($affiliates as $affiliate) {

      $affiliatesAux[$affiliate->short_id]['fullName'] = $affiliate->getName();
      $affiliatesAux[$affiliate->short_id]['mainLink'] = $affiliate->getMainLink();

      if(isset($this->earnings_by_user[$affiliate->short_id])) {
        foreach($this->earnings_by_user[$affiliate->short_id]['earnings_by_order'] as $earningOrder) {
          if(isset($affiliatesAux[$affiliate->short_id]['totalEarning']))
            $affiliatesAux[$affiliate->short_id]['totalEarning'] = (float)$affiliatesAux[$affiliate->short_id]['totalEarning'] + $earningOrder;
          else
            $affiliatesAux[$affiliate->short_id]['totalEarning'] = (float)$earningOrder;
        }
      }
      else {
          $affiliatesAux[$affiliate->short_id]['totalEarning'] = 0;
      }
    }

    return $affiliatesAux;
  }
}
