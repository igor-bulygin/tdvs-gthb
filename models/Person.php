<?php
namespace app\models;

use app\helpers\Utils;
use Yii;
use app\helpers\CActiveRecord;
use yii\base\NotSupportedException;
use yii\mongodb\Collection;
use yii\web\IdentityInterface;

/**
 * @property string slug
 * @property string text_short_description
 * @property string text_biography
 * @property mixed type
 * @property array categories
 * @property array collections
 * @property array personal_info
 * @property array media
 * @property array credentials
 * @property array preferences
 */
class Person extends CActiveRecord implements IdentityInterface {

	const ADMIN = 0;
	const CLIENT = 1;
	const DEVISER = 2;
	const COLLABORATOR = 3;

    const SCENARIO_DEVISER_PROFILE_UPDATE = 'deviser-profile-update';
    const SCENARIO_DEVISER_PRESS_UPDATE = 'deviser-press-update';
    const SCENARIO_USER_PROFILE_UPDATE = 'user-profile-update';
    const SCENARIO_TREND_SETTER_PROFILE_UPDATE = 'trend-setter-profile-update';

	//public $accessToken;

	public static function collectionName() {
		return 'person';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'text_short_description',
			'text_biography',
			'slug',
			'type',
			'categories',
			'collections',
			'personal_info',
			'media',
			'credentials',
			'preferences',
			'press',
		];
	}

    /**
     * The attributes that should be translated
     *
     * @var array
     */
    public $translatedAttributes = ['text_biography'];


	public static function findIdentity($id) {
		return Person::findOne(['short_id' => $id]);
	}

	public static function findIdentityByAccessToken($token, $type = null) {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	public static function findByEmail($username) {
		return Person::findOne(['credentials.email' => $username]);
	}

	public function getId() {
		return $this->short_id;
	}

	public function getAuthKey() {
		return $this->credentials["auth_key"];
	}

	public function validateAuthKey($auth_key) {
		return $this->getAuthKey() === $auth_key;
	}

	public function validatePassword($password) {
		return $this->credentials["password"] === bin2hex(Yii::$app->Scrypt->calc($password, $this->credentials["salt"], 8, 8, 16, 32));
	}

	public function beforeSave($insert) {
		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->categories == null) {
			$this["categories"] = [];
		}

		if($this->collections == null) {
			$this["collections"] = [];
		}

		if($this->type == null) {
			$this["type"] = [];
		}

		if($this->personal_info == null) {
			$this["personal_info"] = [
				"country" => "",
				"city" => ""
			];
		}

		if($this->media == null) {
			$this["media"] = [
				"videos_links" => [],
				"photos" => []
			];
		}

		if($this->credentials == null) {
			$this["credentials"] = [];
		}

		if($this->preferences == null) {
			$this["preferences"] = [];
		}

		if (!array_key_exists("auth_key", $this->credentials) || $this->credentials["auth_key"] === null) {
			$this->credentials = array_merge_recursive($this->credentials, [
				"auth_key" => Yii::$app->getSecurity()->generateRandomString(128)
			]);
		}

		return parent::beforeSave($insert);
	}

	public function setPassword($password) {
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$password = bin2hex(Yii::$app->Scrypt->calc($password, $salt, 8, 8, 16, 32));
		$this->credentials = array_merge_recursive($this->credentials, [
			"salt" => $salt,
			"password" => $password
		]);
	}

	public function setLanguage($lang) {
		$this->preferences = array_merge_recursive($this->preferences, [
			"lang" => $lang
		]);
	}

    public function rules()
    {
        return [
            // the name, email, subject and body attributes are required
            [['slug', 'categories'], 'required'],
            [['text_short_description'], 'required', 'on' => [self::SCENARIO_DEVISER_PROFILE_UPDATE]],
//            [['text_biography'], 'safe', 'on' => [self::SCENARIO_DEVISER_PROFILE_UPDATE]],
            [
                'text_biography',
                'app\validators\EmbedTranslatableFieldValidator',
                'scenario' => self::SCENARIO_DEVISER_PROFILE_UPDATE,
            ],
            [['preferences'], 'required', 'on' => self::SCENARIO_DEVISER_PROFILE_UPDATE],
            [
                'preferences',
                'app\validators\EmbedDocValidator',
                'scenario' => self::SCENARIO_DEVISER_PROFILE_UPDATE,
                'model'=>'\app\models\PersonPreferences'
            ],
            [['personal_info'], 'required', 'on' => self::SCENARIO_DEVISER_PROFILE_UPDATE],
            [
                'personal_info',
                'app\validators\EmbedDocValidator',
                'scenario' => self::SCENARIO_DEVISER_PROFILE_UPDATE,
                'model'=>'\app\models\PersonPersonalInfo'
            ],
	        [['media'], 'required', 'on' => self::SCENARIO_DEVISER_PROFILE_UPDATE],
	        [
		        'media',
		        'app\validators\PersonMediaFilesValidator',
		        'scenario' => self::SCENARIO_DEVISER_PROFILE_UPDATE,
		        'model'=>'\app\models\PersonMedia'
	        ],
	        [['press'], 'required', 'on' => self::SCENARIO_DEVISER_PRESS_UPDATE],
	        [
		        'press',
		        'app\validators\PersonPressFilesValidator',
		        'scenario' => self::SCENARIO_DEVISER_PRESS_UPDATE,
	        ],
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
            case CActiveRecord::SERIALIZE_SCENARIO_PUBLIC:
                static::$serializeFields = [
                    'id' => 'short_id',
                    'slug',
                    'text_short_description',
                    'text_biography',
                    'categories',
                    'personal_info',
                    'media',
                ];
                break;
            case CActiveRecord::SERIALIZE_SCENARIO_OWNER:
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
                    'credentials',
                    'preferences',
                ];
                break;
            case CActiveRecord::SERIALIZE_SCENARIO_ADMIN:
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
                    'credentials',
                    'preferences',
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
     * Get brand name from Person
     *
     * @return string
     */
	public function getBrandName()
	{
		if (!isset($this->personal_info)) return "";

		return $this->personal_info['name'] . ' ' . implode(" ", $this->personal_info['surnames']);
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

			if (!file_exists(Yii::getAlias("@web") . "/" . $this->short_id . "/" . $image )) {
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
	 * @return string
	 */
	public function getAvatarImage($urlify = true)
	{
		$image = "";
		$fallback = "deviser_placeholder.png";

		if (isset($this->media) && isset($this->media['profile'])) {
			$image = $this->media['profile'];

			if (!file_exists(Yii::getAlias("@web") . "/" . $this->short_id . "/" . $image )) {
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
	 * Get the location from Person.
	 * First get city, otherwise get country
	 *
	 * @return mixed|null
	 */
	public function getCityLabel()
	{
		if (isset($this->personal_info)) {
			if (isset($this->personal_info['city'])) {
				return $this->personal_info['city'];
			} elseif (isset($this->personal_info['country'])) {
				/** @var Country $country */
				$country = Country::findOne(['country_code' => $this->personal_info['country']]);
				return Utils::l($country->country_name);
			}
		}

		return null;
	}

	/**
	 * Get the location from Person.
	 * First get city, otherwise get country
	 *
	 * @return mixed|null
	 */
	public function getLocationLabel()
	{
		$location = [];
		if (isset($this->personal_info)) {
			if (isset($this->personal_info['city'])) {
				$location[] = $this->personal_info['city'];
			}
			/** @var Country $country */
			if (isset($this->personal_info['country'])) {
				$country = Country::findOne(['country_code' => $this->personal_info['country']]);
				if ($country) {
					$location[] = Utils::l($country->country_name);
				}
			}
		}

		return implode(", ", $location);
	}

	/**
	 * Get the location from Person.
	 * First get city, otherwise get country
	 *
	 * @return mixed|null
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
	 * Get the location from Person.
	 * First get city, otherwise get country
	 *
	 * @return mixed|null
	 */
	public function getShortDescription()
	{
		return empty($this->text_short_description) ? 'I\'m so happy to be here, always ready.' : $this->text_short_description;

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

}


