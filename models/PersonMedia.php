<?php
namespace app\models;

use yii\base\Model;

/**
 * @property string $header
 * @property string $header_cropped
 * @property string $profile
 * @property string $profile_cropped
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
	public $header_cropped;

	/**
	 * @var string
	 */
	public $profile;

	/**
	 * @var string
	 */
	public $profile_cropped;

	/**
	 * @var array
	 */
	public $photos;

	/** @var  Person */
	protected $person;

	/**
	 * @return Person
	 */
	public function getPerson()
	{
		return $this->person;
	}

	/**
	 * @param Person $person
	 */
	public function setPerson($person)
	{
		$this->person = $person;
	}

	public function getParentAttribute()
	{
		return "media";
	}

	public function init()
	{
		parent::init();

		$this->photos = [];

		$this->setScenario(Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT);
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

		if (empty($this->header_cropped)) {
			$this->header_cropped = $this->header;
		}

		if (empty($this->profile_cropped)) {
			$this->profile_cropped = $this->profile;
		}

		return $loaded;
	}


	public function rules()
	{
		return [
			[['header', 'header_cropped', 'profile', 'profile_cropped', 'photos'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
			[['header', 'header_cropped', 'profile', 'profile_cropped'], 'validateDeviserMediaFileExist', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
			[['photos'], 'validateDeviserPhotosExists', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
			[['photos'], 'validateAmountPhotos', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
			[['header', 'header_cropped', 'profile', 'profile_cropped', 'photos'], 'safe', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_CREATE_DRAFT]],
		];
	}

	/**
	 * Custom validator for amount of photos
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateAmountPhotos($attribute, $params)
	{
		$photos = $this->$attribute;
		if ((count($photos) < 3) || (count($photos) > 7)) {
			$this->addError($attribute, 'Must upload between 3 and 7 photos.');
		}
	}

	/**
	 * Custom validator for amount of photos
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateDeviserMediaFileExist($attribute, $params)
	{
		$filename = $this->$attribute;
		if (!$this->person->existMediaFile($filename)) {
			$this->addError($attribute, sprintf('File %s not found', $filename));
		}
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
			if (!$this->person->existMediaFile($filename)) {
				$this->addError($attribute, sprintf('File %s not found', $filename));
			}
		}
	}
}