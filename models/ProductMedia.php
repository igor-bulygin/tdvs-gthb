<?php
namespace app\models;

/**
 * @property ProductPhoto[] $photosInfo
 * @property ProductDescriptionPhoto[] $descriptionPhotosInfo
 * @property array $videos_links
 *
 * @method Product2 getParentObject()
 */
class ProductMedia extends EmbedModel
{
	public $photos;

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

	public function embedPhotosInfo()
	{
		return $this->mapEmbeddedList('photos', ProductPhoto::className(), array('unsetSource' => false));
	}

	public function embedDescriptionPhotosInfo()
	{
		return $this->mapEmbeddedList('description_photos', ProductDescriptionPhoto::className(), array('unsetSource' => false));
	}

	public function setParentOnEmbbedMappings()
	{
		foreach ($this->photosInfo as $item) {
			$item->setParentObject($this);
		}
		foreach ($this->descriptionPhotosInfo as $item) {
			$item->setParentObject($this);
		}

		parent::setParentOnEmbbedMappings();
	}

	public function rules()
	{
		return [
			[$this->attributes(), 'safe', 'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC]], // to load data posted from WebServices
			['photos', 'required', 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC]],
			['photosInfo', 'app\validators\EmbedDocValidator'], // to apply rules
//			['photosInfo', 'validateAmountPhotos'],
//			['photosInfo', 'validateProductMediaFileExists'], // commented, cause the mediafile can exists in a temporal folder, so we must to check against temporal uploads and product uploads.
			['descriptionPhotosInfo', 'app\validators\EmbedDocValidator'], // to apply rules
			['descriptionPhotosInfo', 'validateAmountDescriptionPhotos'],
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
		$product = $this->getParentObject(); /* @var Product2 $product */
		$photos = $this->$attribute; /* @var ProductPhoto[] $photos */
		foreach ($photos as $photo) {
			if (!$product->existMediaFile($photo->name)) {
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
						$this->getParentObject()->addError("required", $attribute);
					}
					break;
			}
		};
	}

}