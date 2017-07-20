<?php
namespace app\models;

/**
 * @property string type
 * @property string photo
 *
 * @method Story getParentObject()
 */
class StoryMainMedia extends EmbedModel
{

	const STORY_MAIN_MEDIA_TYPE_PHOTO = 1;

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
			'type',
			'photo',
		];
	}

	public function getParentAttribute()
	{
		return "main_media";
	}

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->type = self::STORY_MAIN_MEDIA_TYPE_PHOTO;
	}

	public function rules()
	{
		return [
			[
				$this->attributes(),
				'safe',
				'on' => [
					Story::SCENARIO_STORY_CREATE_DRAFT,
					Story::SCENARIO_STORY_UPDATE_DRAFT,
					Story::SCENARIO_STORY_UPDATE_ACTIVE,
				]
			],
			[
				'photo',
				'validatePhoto',
				'when' => function ($model) {
					return $model->type == self::STORY_MAIN_MEDIA_TYPE_PHOTO;
				}
			]
		];
	}

	public function validatePhoto($attribute, $params)
	{
		$photo = $this->photo;
		$person = $this->getParentObject()->getPerson();
		if (!$person->existMediaFile($photo)) {
			$this->addError('photo', sprintf('Photo %s does not exists', $photo));
		}
	}

	/**
	 * Returns the url of the photo (if it photo main media)
	 *
	 * @return null|string
	 */
	public function getPhotoUrl()
	{
		if ($this->type == self::STORY_MAIN_MEDIA_TYPE_PHOTO) {
			$person = $this->getParentObject()->getPerson();

			return $person->getUrlImagesLocation() . $this->photo;
		}

		return null;
	}
}