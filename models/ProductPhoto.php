<?php
namespace app\models;

/**
 * @property string|mixed name
 * @property string|mixed name_cropped
 * @property array tags
 * @property bool $not_uploaded
 * @property bool $main_product_photo
 *
 * @method ProductMedia getParentObject()
 */
class ProductPhoto extends EmbedModel
{

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

	public function getParentAttribute()
	{
		return "photos";
	}

	public function attributes() {
		return [
			'name',
			'name_cropped',
			'tags',
			'not_uploaded',
			'main_product_photo',
		];
	}

	public function rules()
    {
        return [
	        [['name', 'main_product_photo'], 'safe', 'on' => [Product::SCENARIO_PRODUCT_DRAFT, Product::SCENARIO_PRODUCT_PUBLIC]],
            [['name'], 'required', 'on' => Product::SCENARIO_PRODUCT_PUBLIC],
            [
            	'name_cropped',
				'required',
				'when' => function($model) {
					return $model->main_product_photo;
				},
			],
	        [['main_product_photo'], 'boolean'],
        ];
	}

	public function beforeValidate()
	{
		if ($this->main_product_photo && empty($this->name_cropped)) {
			$this->name_cropped = $this->name;
		}

		return parent::beforeValidate();
	}

	/**
	 * Returns the (relative) url of the photo
	 *
	 * @return string
	 */
	public function getPhotoUrl()
	{
		$product = $this->getParentObject(); /** @var Product $product */
		return $product->getUrlImagesLocation().$this->name;
	}

}