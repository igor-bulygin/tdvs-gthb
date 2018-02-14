<?php
namespace app\models;

/**
 * @property string person_id
 * @property string person_type
 */
class ChatMember extends EmbedModel
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

	public function attributes()
	{
		return [
			'person_id',
			'person_type',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = [];

	public static $textFilterAttributes = [];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();
	}

	public function rules()
	{
		return [
			['person_id', 'app\validators\PersonIdValidator'],
		];
	}

	/**
	 * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
	 * only the attributes needed for a query context
	 *
	 * @param $view
	 */
	public static function setSerializeScenario($view)
	{
		switch ($view) {
			case self::SERIALIZE_SCENARIO_PREVIEW:
			case self::SERIALIZE_SCENARIO_PUBLIC:
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'person_id',
					'person_info' => 'personInfo',
				];
				static::$retrieveExtraFields = [
				];


				static::$translateFields = false;
				break;

			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	/**
	 * @return Person
	 */
	public function getPerson()
	{
		return Person::findOne(['short_id' => $this->person_id]);
	}

	public function getPersonInfo()
	{
		$person = $this->getPerson();
		if ($person) {
			return [
				"slug" => $person->slug,
				"name" => $person->personalInfoMapping->getVisibleName(),
				"photo" => $person->getProfileImage(),
				'url' => $person->getMainLink(),
			];
		}

		return [];
	}
}