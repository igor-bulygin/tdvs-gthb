<?php
namespace app\models;

use app\helpers\CActiveRecord;
use yii\base\Model;

class ProductPhoto extends Model
{
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var boolean
	 */
	public $main_product_photo;

	public function getParentAttribute()
	{
		return "photos";
	}

	/**
	 * @return ProductMedia
	 */
	public function getMedia()
	{
		return $this->media;
	}

	/**
	 * @param ProductMedia $media
	 */
	public function setMedia($media)
	{
		$this->media = $media;
	}

	public function beforeValidate()
	{
		$this->setScenario($this->getMedia()->getScenario());
		return parent::beforeValidate();
	}

	public function rules()
    {
        return [
	        [['name', 'main_product_photo'], 'safe', 'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC]],
            [['name'], 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
	        [['main_product_photo'], 'boolean'],
        ];
    }

}