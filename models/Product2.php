<?php
namespace app\models;

use app\models\Warranty;
use EasySlugger\Slugger;
use Exception;
use MongoDate;
use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\mongodb\ActiveQuery;
use yii\mongodb\Collection;
use yii\mongodb\Connection;
use yii\mongodb\rbac\MongoDbManager;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii2tech\ar\position\PositionBehavior;
use yii2tech\embedded\Mapping;
use yii\helpers\FileHelper;

/**
 * @property string deviser_id
 * @property array name
 * @property array slug
 * @property array description
 * @property array categories
 * @property ProductMedia $mediaFiles
 * @property FaqQuestion[] $faqInfo
*
 * @property array collections
 * @property array media
 * @property array options
 * @property array madetoorder
 * @property array sizechart
 * @property array bespoke
 * @property array preorder
 * @property array returns
 * @property array warranty
 * @property string currency
 * @property string weight_unit
 * @property array price_stock
 * @property array references
 * @property string $product_state
 * @property int position
 * @property MongoDate created_at
 * @property MongoDate updated_at
 * @property int enabled
 * @method   PositionBehavior moveToPosition($position)
 */
class Product2 extends Product {

    const PRODUCT_STATE_DRAFT = 'product_state_draft';
    const PRODUCT_STATE_ACTIVE = 'product_state_active';

	const SCENARIO_PRODUCT_OLD_API = 'scenario-product-old-api';
	const SCENARIO_PRODUCT_DRAFT = 'scenario-product-draft';
    const SCENARIO_PRODUCT_PUBLIC = 'scenario-product-public';

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
		return 'product';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'deviser_id',
			'enabled',
			'categories',
			'faq',
			'product_state',
			'collections',
			'name',
			'slug',
			'description',
			'media',
			'options',
			'madetoorder',
			'sizechart',
			'references',
			'bespoke',
			'preorder',
			'returns',
			'warranty',
			'currency',
			'weight_unit',
			'price_stock',
			'position',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public $translatedAttributes = ['name', 'description', 'slug'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(7);

		// initialize attributes
		$this->categories = [];
		$this->faq = [];
		$this->collections = [];
		$this->name = [];
		$this->slug = [];
		$this->description = [];
		$this->options = [];
		$this->madetoorder = [];
		$this->sizechart = [];
		$this->bespoke = [];
		$this->preorder = [];
		$this->returns = [];
		$this->warranty = [];
		$this->currency = "";
		$this->weight_unit = "";
		$this->price_stock = [];
		$this->references = [];
		$this->position = 0;

		$this->mediaFiles = new ProductMedia();
		$this->mediaFiles->setProduct($this);
	}

    public function setScenario($value)
    {
        parent::setScenario($value);
        $this->mediaFiles->setScenario($value);
    }

    public function embedMediaFiles()
    {
        return $this->mapEmbedded('media', ProductMedia::className());
    }

    public function embedFaqInfo()
    {
        return $this->mapEmbeddedList('faq', FaqQuestion::className());
    }

    /**
     * Load sub documents after find the object
     *
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->mediaFiles->load($this, 'media');
        $this->mediaFiles->setProduct($this);
    }


	public function beforeSave($insert) {

        if ($insert) {

			foreach ($this->media['photos'] as $onephoto) {
				$find_photo = $onephoto['name'];
				$source_tmp = Utils::join_paths(Yii::getAlias("@product"), "temp",$find_photo);
				$path_destination = $this->getUploadedFilesPath();
				$destination = Utils::join_paths($path_destination, $find_photo);

				if(!file_exists($destination)) {

					if(file_exists($source_tmp)) {

						if(!file_exists($path_destination)) FileHelper::createDirectory($path_destination);
						rename($source_tmp, $destination );

					}
				}
			}
        }

		$slugs = [];
		foreach ($this->name as $lang => $text) {
			$slugs[$lang] = Slugger::slugify($text);
		}
		$this->setAttribute("slug", $slugs);

        if (empty($this->product_state)) {
            $this->product_state = Product2::PRODUCT_STATE_DRAFT;
        }

		if (empty($this->created_at)) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

//		if (empty($this->slug)) {
//			$this->slug = [
//				Lang::EN_US => Slugger::slugify($this->name[Lang::EN_US])
//			];
//		}

		return parent::beforeSave($insert);
	}

	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'positionBehavior' => [
					'class' => PositionBehavior::className(),
					'positionAttribute' => 'position',
					'groupAttributes' => [
						'deviser_id' // multiple lists varying by 'deviser_id'
					],
				],
			]
		);
	}

    public function rules()
    {
        return [
            [
                [
                    'deviser_id',
                ],
                'required',
                'on' => [self::SCENARIO_PRODUCT_DRAFT]
            ],
            [
                [
                    'deviser_id',
                    'name',
                    'categories',
                ],
                'required',
                'on' => [self::SCENARIO_PRODUCT_PUBLIC],
            ],
            [
                [
                    'deviser_id',
                    'name',
                    'slug',
                    'description',
                    'categories',
                    'media',
                    'faq',
					'collections',
					'options',
					'madetoorder',
					'sizechart',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
					'weight_unit',
					'price_stock',
                    'position',
                    'product_state',
                ],
                'safe',
                'on' => [self::SCENARIO_PRODUCT_OLD_API, self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC]
            ],
            [
                'product_state',
                'in',
                'range' => [self::PRODUCT_STATE_DRAFT, self::PRODUCT_STATE_ACTIVE],
            ],
            [
                'name',
                'app\validators\TranslatableValidator',
                'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
            ],
            [
                'description',
                'app\validators\TranslatableValidator',
                'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
            ],
            [
                'categories',
                'app\validators\CategoriesValidator',
                'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
            ],
            [   'mediaFiles', 'app\validators\EmbedDocValidator'], // to apply rules
            [   'faq', 'safe'], // to load data posted from WebServices
            [   'faqInfo', 'app\validators\EmbedDocValidator'], // to apply rules


//			[
//				['references'],
//				'app\validators\EmbedDocValidator',
//				'required',
//				'on' => [self::SCENARIO_PRODUCT_OLD_API, self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
//				'model' => '\app\models\ProductReference'
//			],
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
					'name',
					'slug',
					'deviser' => "deviserPreview",
					'url_image_preview' => "imagePreview128",
				];
				static::$retrieveExtraFields = [
					'deviser_id',
				];
				break;
            case self::SERIALIZE_SCENARIO_PUBLIC:
                static::$serializeFields = [
                    'id' => 'short_id',
                    'deviser' => "deviserPreview",
                    'name',
                    'slug',
                    'description',
                    'categories',
                    'media',
					'mediaFiles' => 'mediaInfoAttributes',
					'faq',
                    'product_state',
					'enabled',
					'collections',
					'madetoorder',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
					'weight_unit',
					'references',
					'options' => 'productOptions',
					'url_images' => 'urlImagesLocation',
                    'position',
					'price_stock',
                ];
                static::$retrieveExtraFields = [
                    'deviser_id',
					'options',
					'sizechart',

                ];

                static::$translateFields = true;
                break;
            case self::SERIALIZE_SCENARIO_OWNER:
                static::$serializeFields = [
                    'id' => 'short_id',
                    'deviser' => "deviserPreview",
                    'name',
                    'slug',
                    'description',
                    'categories',
                    'media',
					'mediaFiles' => 'mediaInfoAttributes',
					'faq',
                    'product_state',
					'enabled',
					'collections',
					'madetoorder',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
					'weight_unit',
					'references',
					'options',
					'url_images' => 'urlImagesLocation',
                    'position',
					'price_stock',
                ];
                static::$retrieveExtraFields = [
                    'deviser_id',
					'options',
					'sizechart',
//
                ];

                static::$translateFields = false;
                break;
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'_id',
					'short_id',
					'deviser_id',
					'enabled',
					'categories',
					'collections',
					'name',
					'slug',
					'description',
					'media',
					'options',
					'madetoorder',
					'sizechart',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
					'weight_unit',
					'price_stock',
					'url_images' => 'urlImagesLocation',
                    'product_state',
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
     * @return Product2|null
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
     * @return array
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

        // if deviser id is specified
        if ((array_key_exists("deviser_id", $criteria)) && (!empty($criteria["deviser_id"]))) {
            $query->andWhere(["deviser_id" => $criteria["deviser_id"]]);
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

        // if name is specified
        if ((array_key_exists("name", $criteria)) && (!empty($criteria["name"]))) {
//			// search the word in all available languages
            $query->andFilterWhere(Utils::getFilterForTranslatableField("name", $criteria["name"]));
        }

        // if name is specified
        if ((array_key_exists("text", $criteria)) && (!empty($criteria["text"]))) {
            // TODO, find not only in description, in name, and other text attributes to be specified too
//			// search the word in all available languages
            $query->andFilterWhere(Utils::getFilterForTranslatableField("description", $criteria["text"]));
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

        $query->orderBy("deviser_id, position");

        $products = $query->all();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($products);
        }
        return $products;
    }

    public function deletePhotos() {
        $product_path = Utils::join_paths(Yii::getAlias("@product"), $this->short_id);

        Utils::rmdir($product_path);
    }

	/**
	 * Get the path to main product image
	 * 
	 * @return string
	 */
	public function getMainImage($urlify = true)
	{
		$image = "";
		$fallback = "product_placeholder.png";

		if (isset($this->media) && isset($this->media["photos"])) {
			foreach ($this->media["photos"] as $key => $photo) {
				if (isset($photo["main_product_photo"]) && $photo["main_product_photo"]) {
					$image = $photo["name"];
					break;
				}
			}
		}

		if ($image === "") {
			if (count($this->media["photos"]) == 0) {
				$image = $fallback;
			} else {
				$image = $this->media["photos"][0]["name"];
			}
		}

		if ($urlify === true) {
			if ($image === $fallback) {
				$image = Yii::getAlias("@web") . "/imgs/" . $image;
			} else {
				$image = Yii::getAlias("@product_url") . "/" . $this->short_id . "/" . $image;
			}
		}

		return $image;
	}

	/**
	 * Get a resized version of main image, to 128px width
	 *
	 * @return string
	 */
	public function getImagePreview128()
	{
		$image = $this->getMainImage();
		// force max widht
		$url = Utils::url_scheme() . Utils::thumborize($image)->resize(128, 0);
		return $url;
	}

	/**
	 * Get videos related with this Product
	 *
	 * @return array
	 */
	public function getVideos()
	{
		$videos = [];
		$persons = Person::find()->where(["videos.products" => $this->short_id])->all();
		/** @var Person $person */
		foreach ($persons as $person) {
			/** @var PersonVideo $video */
			foreach ($person->findVideosByProductId($this->short_id) as $video) {
				$videos[] = $video;
			}
		}
		return $videos;
	}

	/**
	 * Helper to determine the minimum price in stock & price data.
	 * In the future, this must be in Product class.
	 *
	 * @return float|null
	 */
	public function getMinimumPrice()
	{
		// TODO find minimum price, not first one
		// some products hasn't price and stock in database !!
		if (isset($this->price_stock)) {
			if (count($this->price_stock) > 0) {
				return $this->price_stock[0]["price"];
			}
		}
		return null;
	}

	/**
	 * Get the URLs of images to use in a gallery
	 *
	 * @return array
	 */
	public function getUrlGalleryImages()
	{
		$images = [];
		if (array_key_exists("photos", $this->media)) {
			foreach ($this->media["photos"] as $imageData) {
				$images[] = Yii::getAlias('@product_url') .'/'.  $this->short_id .'/'. $imageData['name'];
			}
		}

		return $images;
	}

	/**
	 * @param null $level
	 * @return Category
	 */
	public function getCategory($level = null)
	{
		$category = Category::findOne(['short_id' => $this->categories[0]]);
		if ($level) {
			// remove first slash, and find id of second level category
			$ancestors = explode('/', rtrim(ltrim($category->path, '/'), '/'));
			$category_id = (count($ancestors) > $level) ? $ancestors[$level] : $ancestors[count($ancestors)-1];
			$levelCategory = Category::findOne(['short_id' => $category_id]);
		}

		// return the ancestor specified in level param, otherwise, return product category
		return (isset($levelCategory)) ? $levelCategory : $category;
	}

	/**
	 * Get the tag options related with this product
	 *
	 * @return array
	 */
	public function getTags()
	{
		$tags = [];
		foreach ($this->options as $tag_id => $detail) {
			/** @var Tag $tag */
			$tag = Tag::findOne(['short_id' => $tag_id]);
			if ($tag) {
				$tag->setAttribute('options', $this->getProductOptionsForTag($tag_id));
				$tags[] = $tag;
			}
		}
		return $tags;
	}

	/**
	 * Build an structure with all references to be handled by client side.
	 * By reference, we understand a combination of a product, and each options combinations where
	 * the product has stock, for example, "t-shirt = X + color = blue + size = xxl"
	 *
	 * @return array
	 */
	public function getReferences()
	{
		$references = [];
		foreach ($this->price_stock as $stock) {
			$options = [];
			foreach ($stock["options"] as $key => $values) {
				if (isset($values[0])) {
					// remove the array
					$options[$key] = $values[0];
				}
			}
			$references[] = [
				"reference_id" => "temp_random_id_" . uniqid(), // TODO retrieve from database
				"options" => $options,
				"stock" => $stock["stock"],
				"price" => $stock["price"],
			];
		}
		return $references;
	}
	
	/**
	 * Build an structure with all references to be handled by client side.
	 * By reference, we understand a combination of a product, and each options combinations where
	 * the product has stock, for example, "t-shirt = X + color = blue + size = xxl"
	 *
	 * @return array
	 */
	public function getProductOptions()
	{
		$options = [];
		foreach ($this->options as $tag_id => $option) {

			/** @var Tag $tag */
			$tag = Tag::findOne(["short_id" => $tag_id]);
			if ($tag) {
				$tag->setFilterProduct($this);
				$options[] = clone $tag;
			}
		}
		// add size as a common tag
		if ((isset($this->sizechart)) && (array_key_exists("values", $this->sizechart)) && (count($this->sizechart["values"]) > 0)){
			$tag = new Tag();
			$tag->forceIsSizeTag = true; // TODO Temp attribute, until products options are refactored
			$tag->sizeCart = $this->sizechart; // TODO Temp attribute, until products options are refactored
			$tag->short_id = "size";
			$tag->required = true;
			$tag->name = [Lang::EN_US => "Size", Lang::ES_ES => "Talla", Lang::CA_ES => "Talla"];
			$tag->description = [Lang::EN_US => "Size", Lang::ES_ES => "Talla", Lang::CA_ES => "Talla"];
			$options[] = $tag;

		}
		Tag::setSerializeScenario(Tag::SERIALIZE_SCENARIO_PRODUCT_OPTION);
		Utils::translate($options);
		return $options;
	}

	/**
	 * Get a preview version of a Deviser
	 *
	 * @return array
	 */
	public function getDeviserPreview()
	{
		/** @var Person $deviser */
		$deviser = Person::findOneSerialized($this->deviser_id);
		return $deviser->getPreviewSerialized();
	}

	/**
	 * @deprecated
	 * @param $tag_id
	 * @return array
	 */
	private function getProductOptionsForTag($tag_id)
	{
		$values = [];
		foreach ($this->options as $id => $option) {
			if ($id == $tag_id) {
				if (count($option)>0) {
					$value = $option[0][0];
					$values[] = ["text" => ["en-US" => $value], "value" => $value];
				}
			}
		}
		return $values;
	}

	/**
	 * Get label to show warranty conditions
	 *
	 * @return string
	 */
	public function getWarrantyLabel()
	{
		$warrantyType = $this->warranty["type"];
		$label = '';
		if (($warrantyType != Warranty::NONE) && (array_key_exists("value", $this->warranty))) {
			$label .= $this->warranty["value"] . ' ';
		}
		$label .= Warranty::getDescription($this->warranty["type"]);
		return $label;
	}

	/**
	 * Get the url to get the images of a Deviser
	 *
	 * @return string
	 */
	public function getUrlImagesLocation()
	{
		return Yii::getAlias("@product_url") . "/" . $this->short_id . "/";
	}

    /**
     * Get media files attributes from their own Model, not from array.
     *
     * @return array
     */
    public function getMediaInfoAttributes()
    {
        $media = $this->mediaFiles->getAttributes();

        return $media;
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

		if (array_key_exists('media', $data)) {
            $this->mediaFiles->load($data, 'media');
        }

		// use position behavior method to move it (only if it has primary key)
		// commented until be needed...
//		if (array_key_exists("position", $data) && $data['id']) {
//			$this->moveToPosition($data["position"]);
//		}

		return ($loaded);
	}

	/**
	 * Get few attributes to use in preview sections
	 *
	 * @return array
	 */
	public function getPreviewSerialized()
	{
		return [
			"id" => $this->short_id,
			"slug" => $this->slug,
			"name" => $this->name,
			"media" => $this->media,
			"deviser" => $this->getDeviserPreview(),
			'url_image_preview' => $this->getImagePreview128(),
			"url_images" => $this->getUrlImagesLocation(),
		];
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
                    //TODO: Fix this! Find other way to determine if was a "required" field
                    if (strpos($error[0], 'cannot be blank') !== false || strpos($error[0], 'no puede estar vacío') !== false) {
                        $this->addError("required", $attribute);
                    }
                    break;
            }
        };
    }

    public function getUploadedFilesPath()
    {
        return Utils::join_paths(Yii::getAlias("@product"), $this->short_id);
    }

    /**
     * Check if Deviser media file is exists
     *
     * @param string $filename
     * @return bool
     */
    public function existMediaFile($filename)
    {
        if (empty($filename)) { return false; }

        $filePath = $this->getUploadedFilesPath() . '/' . $filename;
        return file_exists($filePath);
    }

}
