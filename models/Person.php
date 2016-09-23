<?php
namespace app\models;

use app\helpers\Utils;
use EasySlugger\Slugger;
use Exception;
use MongoDate;
use Yii;
use app\helpers\CActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\SluggableBehavior;
use yii\mongodb\Collection;
use yii\web\IdentityInterface;
use yii2tech\embedded\ContainerInterface;
use yii2tech\embedded\ContainerTrait;
use yii2tech\embedded\Mapping;

/**
 * @property string $slug
 * @property string $text_short_description
 * @property string $text_biography
 * @property string $account_state
 * @property mixed $type
 * @property array $categories
 * @property array $collections
 * @property PersonPreferences $preferencesInfo
 * @property PersonPersonalInfo $personalInfo
 * @property PersonMedia $mediaFiles
 * @property array $press
 * @property Mapping $videosInfo
 * @property Mapping $faqInfo
 * @property array $credentials
 * @property array $preferences
 * @property array $curriculum
 * @property MongoDate $created_at
 * @property MongoDate $updated_at
 */
class Person extends CActiveRecord implements IdentityInterface
{

	const ADMIN = 0;
	const CLIENT = 1;
	const DEVISER = 2;
	const COLLABORATOR = 3;

	const ACCOUNT_STATE_DRAFT = 'draft';
	const ACCOUNT_STATE_ACTIVE = 'active';
	const ACCOUNT_STATE_BLOCKED = 'blocked';

	const SCENARIO_USER_PROFILE_UPDATE = 'user-profile-update';

	const SCENARIO_DEVISER_CREATE_DRAFT = 'deviser-create-draft';
	const SCENARIO_DEVISER_UPDATE_DRAFT = 'deviser-update-draft';
	const SCENARIO_DEVISER_PUBLISH_PROFILE = 'deviser-publish-profile';
	const SCENARIO_DEVISER_UPDATE_PROFILE = 'deviser-update-profile';
//	const SCENARIO_DEVISER_PRESS_UPDATE = 'deviser-press-update';
//	const SCENARIO_DEVISER_VIDEOS_UPDATE = 'deviser-videos-update';
//	const SCENARIO_DEVISER_FAQ_UPDATE = 'deviser-faq-update';

	const SCENARIO_TREND_SETTER_PROFILE_UPDATE = 'trend-setter-profile-update';

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
//			'personalInfo',
			'curriculum',
			'media',
			'credentials',
			'preferences',
			'press',
			'videos',
			'faq',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public $translatedAttributes = ['text_short_description', 'text_biography', 'faq.question', 'faq.answer'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(7);

		$this->preferencesInfo = new PersonPreferences();
		$this->personalInfo = new PersonPersonalInfo();
		$this->mediaFiles = new PersonMedia();

		// initialize attributes
		$this->categories = [];
		$this->collections = [];
		$this->press = [];
		$this->videos = [];
		$this->faq = [];
		$this->account_state = self::ACCOUNT_STATE_DRAFT;
		$this->text_short_description = [
			Lang::EN_US => "I'm so happy to be here, always ready.",
		];
		$this->text_biography = [
			Lang::EN_US => "<p>I am a UX Designer and Art Director from Austria living in Berlin.</p>
							<p>Artworks and illustrations were my gateway to the creative industry which led to the foundation of my own studio and to first steps in the digital world.</p>
							<p>Out of this love for aesthetic design my passion for functionality and structure evolved. Jumping right into Photoshop didn’t feel accurate anymore and skipping the steps of building a framework based on functionality and usability became inevitable.</p>"
		];

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);
	}

	public function embedPreferencesInfo()
	{
		return $this->mapEmbedded('preferences', PersonPreferences::className());
	}

	public function embedPersonalInfo()
	{
		return $this->mapEmbedded('personal_info', PersonPersonalInfo::className());
	}

	public function embedMediaFiles()
	{
		return $this->mapEmbedded('media', PersonMedia::className());
	}

	public function embedVideosInfo()
	{
		return $this->mapEmbeddedList('videos', PersonVideo::className());
	}

	public function embedFaqInfo()
	{
		return $this->mapEmbeddedList('faq', FaqQuestion::className());
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
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
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	public static function findByEmail($username)
	{
		return Person::findOne(['credentials.email' => $username]);
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
		return $this->credentials["password"] === bin2hex(Yii::$app->Scrypt->calc($password, $this->credentials["salt"], 8, 8, 16, 32));
	}

	public function afterFind()
	{
		parent::afterFind();

		$this->preferencesInfo->load($this, 'preferences');
		$this->personalInfo->load($this, 'personal_info');
		$this->mediaFiles->load($this, 'media');
	}

	public function beforeSave($insert)
	{
		/*
		 * Create empty data holders if they don't exist
		 */
		if ($this->categories == null) {
			$this["categories"] = [];
		}

		if ($this->collections == null) {
			$this["collections"] = [];
		}

		if ($this->type == null) {
			$this["type"] = [];
		}

//		if ($this->personal_info == null) {
//			$this["personal_info"] = [
//				"country" => "",
//				"city" => ""
//			];
//		}

//		if ($this->media == null) {
//			$this["media"] = [
//				"videos_links" => [],
//				"photos" => []
//			];
//		}

		if ($this->credentials == null) {
			$this["credentials"] = [];
		}

//		if ($this->preferences == null) {
//			$this["preferences"] = [];
//		}

		if (!array_key_exists("auth_key", $this->credentials) || $this->credentials["auth_key"] === null) {
			$this->credentials = array_merge_recursive($this->credentials, [
				"auth_key" => Yii::$app->getSecurity()->generateRandomString(128)
			]);
		}

		if (empty($this->slug)) {
			$this->slug = Slugger::slugify($this->personalInfo->getBrandName());
		}

		if (empty($this->created_at)) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function setPassword($password)
	{
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$password = bin2hex(Yii::$app->Scrypt->calc($password, $salt, 8, 8, 16, 32));
		$this->credentials = array_merge_recursive($this->credentials, [
			"salt" => $salt,
			"password" => $password
		]);
	}

	public function setLanguage($lang)
	{
		$this->preferencesInfo->lang = $lang;
	}

	public function rules()
	{
		// TODO use core validators: "in", "each" (http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html)

		return [
			// the name, email, subject and body attributes are required
			[
				[
					'personalInfo',
					'credentials',
				],
				'required',
				'on' => [self::SCENARIO_DEVISER_CREATE_DRAFT]
			],
			[
				[
					'personal_info',
					'categories',
					'credentials',
					'text_short_description',
					'text_biography',
					'preferences',
					'curriculum',
					'media',
					'press',
					'videos',
					'faq',
					'slug',
				],
				'safe',
				'on' => [self::SCENARIO_DEVISER_UPDATE_DRAFT]
			],
			[
				'text_short_description',
				'app\validators\TranslatableValidator',
				'on' => [self::SCENARIO_DEVISER_CREATE_DRAFT],
			],
			[
				'text_biography',
				'app\validators\TranslatableValidator',
				'on' => [self::SCENARIO_DEVISER_CREATE_DRAFT],
			],
			[
				'preferencesInfo',
				'yii2tech\embedded\Validator',
				'on' => [self::SCENARIO_DEVISER_CREATE_DRAFT],
			],
			[
				'personalInfo',
				'yii2tech\embedded\Validator',
				'on' => [self::SCENARIO_DEVISER_UPDATE_DRAFT],
			],
			[
				'mediaFiles',
				'yii2tech\embedded\Validator',
				'on' => [self::SCENARIO_DEVISER_UPDATE_DRAFT],
			],
			[
				'curriculum',
				'app\validators\PersonCurriculumValidator',
				'on' => self::SCENARIO_DEVISER_UPDATE_PROFILE,
			],
			[   'press', 'app\validators\PersonPressFilesValidator'],
			[   'videosInfo', 'yii2tech\embedded\Validator'],
//			[   'faqInfo', 'yii2tech\embedded\Validator'], // why don't work like videoInfo validator do?
			[   'faq', 'app\validators\PersonFaqValidator'],
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
				static::$serializeFields = [
					'id' => 'short_id',
					'slug',
					'name' => "brandName",
					'url_avatar' => "avatarImage128",
				];
				break;
			case self::SERIALIZE_SCENARIO_PUBLIC:
				static::$serializeFields = [
					'id' => 'short_id',
					'slug',
					'text_short_description',
					'text_biography',
					'categories',
					'personal_info',
					'media',
					'press',
					'videos' => 'videosPreview',
					'faq',
					'name' => "brandName",
					'url_images' => 'urlImagesLocation',
					'url_avatar' => "avatarImage128",
				];

				static::$retrieveExtraFields = [
					'videos'
				];

				break;
			case self::SERIALIZE_SCENARIO_OWNER:
				static::$serializeFields = [
					'id' => 'short_id',
					'short_id', // TODO Remove when all calls are migrated to new API
					'slug',
					'text_short_description',
					'text_biography',
					'categories',
					'collections',
					'personal_info',
					'media',
					'press',
					'videos' => 'videosPreview',
					'faq',
//                    'credentials',
					'preferences',
					'url_images' => 'urlImagesLocation',
				];

				static::$retrieveExtraFields = [
					'videos'
				];

				break;
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'short_id', // TODO Remove when all calls are migrated to new API
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
//                    'credentials',
					'preferences',
					'url_images' => 'urlImagesLocation',
				];

				// field name is "name", its value is defined by a PHP callback
//            'name' => function () {
//                return $this->first_name . ' ' . $this->last_name;
//            },
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
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
		return Yii::getAlias("@deviser_url") . "/" . $this->short_id . "/" . $this->curriculum;
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
	 * Get first name from Person
	 *
	 * @return string
	 */
	public function getName()
	{
		if (!isset($this->personal_info)) return "";

		return $this->personal_info['name'];
	}

	/**
	 * Get the path to header background image
	 *
	 * @return string
	 */
	public function getHeaderBackgroundImage($urlify = true)
	{
		$image = "";
		$fallback = "deviser_header_placeholder.jpg";

		if (isset($this->media) && isset($this->media['header'])) {
			$image = $this->media['header'];

			if (!file_exists(Yii::getAlias("@web") . "/" . $this->short_id . "/" . $image)) {
				$imge = $fallback;
			}
		} else {
			$image = $fallback;
		}

		if ($urlify === true) {
			if ($image === $fallback) {
				$image = Yii::getAlias("@web") . "/imgs/" . $image;
			} else {
				$image = Yii::getAlias("@deviser_url") . "/" . $this->short_id . "/" . $image;
			}
		}

		return $image;
	}

	/**
	 * Get the path to avatar image
	 *
	 * @param bool $urlify
	 * @param int $minHeight
	 * @param int $minWidth
	 *
	 * @return string
	 */
	public function getAvatarImage($urlify = true, $minHeight = null, $minWidth = null)
	{
		$image = "";
		$fallback = "deviser_placeholder.png";

		if (isset($this->media) && isset($this->media['profile'])) {
			$image = $this->media['profile'];

			if (!file_exists(Yii::getAlias("@web") . "/" . $this->short_id . "/" . $image)) {
				$imge = $fallback;
			}
		} else {
			$image = $fallback;
		}

		if ($urlify === true) {
			if ($image === $fallback) {
				$image = Yii::getAlias("@web") . "/imgs/" . $image;
			} else {
				$image = Yii::getAlias("@deviser_url") . "/" . $this->short_id . "/" . $image;
			}
		}

		if ((!empty($minHeight)) || (!empty($minWidth))) {
			// force resize
			$image = Utils::url_scheme() . Utils::thumborize($image)->resize(
					($minWidth) ? $minWidth : 0,
					($minHeight) ? $minHeight : 0
				);
		}

		return $image;
	}

	/**
	 * Get a resized version of avatar image, to 128px width
	 *
	 * @return string
	 */
	public function getAvatarImage128()
	{
		$image = $this->getAvatarImage();
		// force max width
		$url = Utils::url_scheme() . Utils::thumborize($image)->resize(128, 0);
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
		foreach ($this->videosInfo as $item) {
			/** @var PersonVideo $item*/
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
		$products = Product::find()->select(['short_id', 'categories', 'media'])->where(['deviser_id' => $this->short_id])->all();
		$detailCategoriesIds = [];
		/** @var Product $product */
		foreach ($products as $product) {
			$detailCategoriesIds = array_unique(array_merge($detailCategoriesIds, $product->categories));
		}

		// now, get the models for those categories
		$detailCategories = Category::find()->select(['short_id', 'name', 'slug', 'path'])->where(['short_id' => $detailCategoriesIds])->all();
		$level2Ids = [];
		/** @var Category $category */
		foreach ($detailCategories as $category) {
			// remove first slash, and find id of second level category
			$ancestors = explode('/', rtrim(ltrim($category->path, '/'), '/'));
			$level2Id = (count($ancestors) > 1) ? $ancestors[1] : $ancestors[0];
			$level3Id = (count($ancestors) > 2) ? $ancestors[2] : null;
			if (array_key_exists($level2Id, $level2Ids)) {
				$level2Ids[$level2Id][] = array_merge($level2Ids[$level2Id], [$level3Id]);
			} else {
				$level2Ids[$level2Id][] = $level3Id;
			}
		}

		/** @var Category $category */
		foreach ($level2Ids as $id => $subIds) {
			$category = Category::findOne(['short_id' => $id]);
			if ($category) {
				// assign one product of the deviser, related with this category
				$category->setDeviserProduct(Product::findOne(["deviser_id" => $this->short_id, "categories" => $category->getShortIds()]));
				$category->setDeviserSubcategories(Category::find()->where(['short_id' => $subIds])->all());
				$level2Categories[] = $category;
			}
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
			"name" => $this->personalInfo->getBrandName(),
			"url_avatar" => $this->getAvatarImage128()
		];
	}

	/**
	 * Shortcut to get the brand name
	 *
	 * @return string
	 */
	public function getBrandName()
	{
		return $this->personalInfo->getBrandName();
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
		foreach ($this->mediaFiles->photos as $filename) {
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
		parent::setScenario($value);
		$this->personalInfo->setScenario($value);
		$this->mediaFiles->setScenario($value);
		$this->preferencesInfo->setScenario($value);
	}

	/**
	 * Spread data for sub documents
	 *
	 * @param array $data
	 * @param null $formName
	 * @return bool
	 */
	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);

		if (array_key_exists('personal_info', $data)) {
			$this->personalInfo->load($data, 'personal_info');
		}
		if (array_key_exists('media', $data)) {
			$this->mediaFiles->load($data, 'media');
		}
		if (array_key_exists('preferences', $data)) {
			$this->preferencesInfo->load($data, 'preferences');
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

}