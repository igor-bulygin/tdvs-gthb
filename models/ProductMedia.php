<?php
namespace app\models;

use yii\base\Model;

/**
 * @property array $photos
 */
class ProductMedia extends Model
{

	/**
	 * @var array
	 */
	public $photos;

	/** @var  Product2 */
	protected $product;

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

	public function getParentAttribute()
	{
		return "media";
	}

	public function init()
	{
		parent::init();

		$this->photos = [];
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

		return $loaded;
	}


	public function rules()
	{
		return [
//			[['photos'], 'validateDeviserPhotosExists', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC], //TODO: implement this validation
			[['photos'], 'safe', 'on' => [Product2::SCENARIO_PRODUCT_DRAFT]],
			[['photos'], 'required', 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC]],
		];
	}

	/**
	 * Custom validator for amount of photos
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateDeviserPhotosExists($attribute, $params)
	{
		$photos = $this->$attribute;
		foreach ($photos as $filename) {
			if (!$this->product->existMediaFile($filename)) {
				$this->addError($attribute, sprintf('File %s not found', $filename));
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