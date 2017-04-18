<?php
namespace app\models;

/**
 * @property string type
 *
 * @method Story getParentObject()
 */
class StoryComponent extends EmbedModel {

	public function attributes() {
		return [
			'type',
		];
	}

	public function getParentAttribute()
	{
		return "components";
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
		];
	}
}
