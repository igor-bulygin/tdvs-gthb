<?php
namespace app\models;

/**
 * @property string $header
 * @property string $header_cropped
 * @property string $profile
 * @property string $profile_cropped
 * @property array $photos
 * @property array $videos_links
 *
 * @method Person getParentObject()
 */
class PersonMedia extends EmbedModel
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
			'header',
			'header_cropped',
			'profile',
			'profile_cropped',
			'photos',
			'videos_links',
		];
	}

	public function getParentAttribute()
	{
		return "media";
	}

	public function init() {
		if (!is_array($this->photos)) {
			$this->photos = [];
		}
		if (!is_array($this->videos_links)) {
			$this->videos_links = [];
		}
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
			[
				['header', 'header_cropped', 'profile', 'profile_cropped', 'photos'],
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_CLIENT_UPDATE,
				]
			],
			[
				['header', 'header_cropped', 'profile', 'profile_cropped'],
				'required',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
				],
			],
			[
				['header', 'header_cropped', 'profile', 'profile_cropped'],
				'validatePersonPhotoItemExists',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Person::SCENARIO_CLIENT_UPDATE,
				],
			],
			[
				['photos'],
				'validatePersonPhotosExists',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Person::SCENARIO_CLIENT_UPDATE,
				],
			],
			[
				['photos'],
				'validateAmountPhotos',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Person::SCENARIO_CLIENT_UPDATE,
				],
			],
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
	public function validatePersonPhotoItemExists($attribute, $params)
	{
		$filename = $this->$attribute;
		if (!$this->getParentObject()->existMediaFile($filename)) {
			$this->addError($attribute, sprintf('File %s not found', $filename));
		}
	}

	/**
	 * Custom validator for amount of photos
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validatePersonPhotosExists($attribute, $params)
	{
		$photos = $this->$attribute;
		foreach ($photos as $filename) {
			if (!$this->getParentObject()->existMediaFile($filename)) {
				$this->addError($attribute, sprintf('File %s not found', $filename));
			}
		}
	}
}