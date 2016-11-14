<?php
namespace app\models;

use app\helpers\CActiveRecord;
use yii\base\Model;
use yii2tech\embedded\ContainerInterface;
use yii2tech\embedded\ContainerTrait;

/**
 * @property ProductPhoto[] $photosInfo
 * @property ProductDescriptionPhoto[] $descriptionPhotosInfo
 * @property array $videos_links
 */
class ProductMedia extends CActiveRecord
{
	public $photos;

	/** @var  Product2 */
	protected $product;

	public function attributes()
	{
		return [
			'photos',
			'description_photos',
			'videos_links',
		];
	}

	public function getParentAttribute()
	{
		return "media";
	}

	/**
	 * @return Product2
	 */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * @param Product2 $product
	 */
	public function setProduct($product)
	{
		$this->product = $product;
	}

	public function beforeValidate()
	{
		foreach ($this->photosInfo as $photo) {
			$photo->setMedia($this);
		}
		foreach ($this->descriptionPhotosInfo as $descriptionPhoto) {
			$descriptionPhoto->setMedia($this);
		}
		$this->setScenario($this->getProduct()->getScenario());

		return parent::beforeValidate();
	}

	public function embedPhotosInfo()
	{
		return $this->mapEmbeddedList('photos', ProductPhoto::className(), array('unsetSource' => false));
	}

	public function embedDescriptionPhotosInfo()
	{
		return $this->mapEmbeddedList('description_photos', ProductDescriptionPhoto::className(), array('unsetSource' => false));
	}

	/**
	 * Assign some default attributes for historical objects
	 *
	 * @param array $data
	 * @param null $formName
	 * @return bool
	 */
	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);

//		if (array_key_exists('photos', $data)) {
//			$this->photosInfo->load($data, 'photos');
//		}

		return $loaded;
	}


	public function rules()
	{
		return [
			['photos', 'safe'], // to load data posted from WebServices
			['photos', 'required', 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC]],
			['photosInfo', 'app\validators\EmbedDocValidator'], // to apply rules
//			['photosInfo', 'validateAmountPhotos'],
//			['photosInfo', 'validateProductMediaFileExists'], // commented, cause the mediafile can exists in a temporal folder, so we must to check against temporal uploads and product uploads.
			['description_photos', 'safe'], // to load data posted from WebServices
			['descriptionPhotosInfo', 'app\validators\EmbedDocValidator'], // to apply rules
			['descriptionPhotosInfo', 'validateAmountDescriptionPhotos'],
			['videos_links', 'safe', 'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC]],
		];
	}

	/**
	 * Custom validator for amount of photos
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateAmountDescriptionPhotos($attribute, $params)
	{
		$photos = $this->$attribute;
		if (count($photos) > 4) {
			$this->addError($attribute, 'Must upload a maximum of 4 photos.');
		}
	}

	/**
	 * Custom validator for amount of photos
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateProductMediaFileExists($attribute, $params)
	{
		$photos = $this->$attribute; /* @var ProductPhoto[] $photos */
		foreach ($photos as $photo) {
			if (!$this->product->existMediaFile($photo->name)) {
				$this->addError($attribute, sprintf('File %s not found', $photo->name));
			}
		}
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
						$this->getProduct()->addError("required", $attribute);
					}
					break;
			}
		};
	}

}