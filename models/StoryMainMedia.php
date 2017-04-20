<?php
namespace app\models;

/**
 * @property string type
 * @property string photo
 *
 * @method Story getParentObject()
 */
class StoryMainMedia extends EmbedModel {

	const STORY_MAIN_MEDIA_TYPE_PHOTO = 'photo';

	public function attributes() {
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
}
