<?php
namespace app\models;

use app\helpers\Utils;
use yii\base\Model;

/**
 * @property string $url
 * @property array $products
 */
class PersonVideo extends Model
{

	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var array
	 */
	public $products = [];

	public function getParentAttribute()
	{
		return "videos";
	}

	public function init()
	{
		parent::init();

		$this->setScenario(Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT);
	}

	/**
	 * Parse a Youtube URL video, and return the embedded url version, to use in a <iframe>
	 * (No external library found, to parse the url, without have to register and use API KEY)
	 *
	 * @return string
	 */
	public function getUrlEmbeddedYoutubePlayer()
	{
		return Utils::getUrlEmbeddedYoutubePlayer($this->url);
	}

	public function rules()
	{
		return [
			[['url'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
			[['url'], 'url'],
			[['products'], 'safe', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
			[['products'], 'validateProductIds'],
			[['url', 'products'], 'safe', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT]],
		];
	}

	public function validateProductIds($attribute, $params)
	{
		foreach ($this->$attribute as $id) {
			$product = Product::findOne(["short_id" => $id]);
			if (!$product) {
				$this->addError($attribute, sprintf("Product %s not found", $id));
			}
		}
	}
}