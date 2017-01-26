<?php
namespace app\models;

/**
 * @property string|mixed name
 * @property array tags
 * @property bool $not_uploaded
 * @property bool $main_product_photo
 */
class ProductPhoto extends EmbedModel
{
	public function getParentAttribute()
	{
		return "photos";
	}

	public function attributes() {
		return [
			'name',
			'tags',
			'not_uploaded',
			'main_product_photo',
		];
	}

	public function rules()
    {
        return [
	        [['name', 'main_product_photo'], 'safe', 'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC]],
            [['name'], 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
	        [['main_product_photo'], 'boolean'],
        ];
    }

	/**
	 * Returns the (relative) url of the photo
	 *
	 * @return string
	 */
	public function getPhotoUrl()
	{
		$product = $this->getParentObject(); /** @var Product2 $product */
		return $product->getUrlImagesLocation().$this->name;
	}

}