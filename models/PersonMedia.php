<?php
namespace app\models;

use yii\base\Model;

/**
 * @property string $header
 * @property string $profile
 * @property array $photos
 */
class PersonMedia extends Model
{

	/**
	 * @var string
	 */
	public $header;

	/**
	 * @var string
	 */
	public $profile;

	/**
	 * @var array
	 */
	public $photos;

	public function init()
	{
		parent::init();

		$this->photos = [];

		$this->setScenario(Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT);
	}


	public function rules()
	{
		return [
			[['header', 'profile'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
			[['header', 'profile', 'photos'], 'safe', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT]],
		];
	}

}