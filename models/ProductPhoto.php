<?php
namespace app\models;

use yii\base\Model;

/**
 * @property string|mixed name
 * @property string|mixed title
 * @property string|mixed description
 * @property bool main_product_photo
 */
class ProductPhoto extends Model
{

	/** @var string */
	public $name;

	/** @var string */
	public $title;

	/** @var string */
	public $description;

	/** @var bool */
	public $main_product_photo;

	public function getParentAttribute()
	{
		return "photos";
	}

	public function init()
	{
		parent::init();

		$this->setScenario(Product::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT);
	}

	public function rules()
    {
        return [
//            [['name', 'title'], 'required', 'on' => Product::SCENARIO_PRODUCT_UPDATE_DRAFT],
	        [['name', 'title', 'description', 'main_product_photo'], 'safe', 'on' => [Product::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Product::SCENARIO_PRODUCT_UPDATE_DRAFT]],
	        [
		        'name',
		        'app\validators\TranslatableValidator',
		        'on' => [Product::SCENARIO_PRODUCT_UPDATE_DRAFT],
	        ],
	        [
		        'title',
		        'app\validators\TranslatableValidator',
		        'on' => [Product::SCENARIO_PRODUCT_UPDATE_DRAFT],
	        ],
	        [
		        'description',
		        'app\validators\TranslatableValidator',
		        'on' => [Product::SCENARIO_PRODUCT_UPDATE_DRAFT],
	        ],
	        [
		        'main_product_photo', 'boolean', 'on' => [Product::SCENARIO_PRODUCT_UPDATE_DRAFT],
	        ],
        ];
    }

}