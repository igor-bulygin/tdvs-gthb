<?php
namespace app\models;

use app\helpers\Utils;

/**
 * @property string $url
 * @property array $products
 *
 * @method Person getParentObject()
 */
class PersonVideo extends EmbedModel
{

	public function attributes()
	{
		return [
			'url',
			'products',
		];
	}

	public function getParentAttribute()
	{
		return "videos";
	}

	public function rules()
	{
		return [
			[
				$this->attributes(),
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE
				]
			],
			[
				['url'],
				'required',
				'on' => [Person::SCENARIO_DEVISER_UPDATE_PROFILE, Person::SCENARIO_INFLUENCER_UPDATE_PROFILE]
			],
			[['url'], 'url'],
			[['products'], 'validateProductIds'],
		];
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