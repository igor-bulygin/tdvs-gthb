<?php
namespace app\models;

/**
 * @property string|mixed name
 * @property string|mixed title
 * @property string|mixed description
 *
 * @method ProductMedia getParentObject()
 */
class ProductDescriptionPhoto extends EmbedModel
{
	public function getParentAttribute()
	{
		return "description_photos";
	}

	public function attributes() {
		return [
			'name',
			'title',
			'description',
		];
	}

	public function rules()
    {
        return [
	        [['name'], 'safe', 'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC]],
            [['name'], 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
			[['title', 'description'], 'app\validators\TranslatableRequiredValidator'],
        ];
    }

	/**
	 * Returns the (relative) url of the photo
	 *
	 * @return string
	 */
    public function getPhotoUrl()
	{
		$product = $this->getParentObject()->getParentObject(); /** @var Product2 $product */
		return $product->getUrlImagesLocation().$this->name;
	}

}