<?php
namespace app\models;

use app\helpers\Utils;
use app\validators\TranslatableRequiredValidator;
use yii\validators\UrlValidator;

/**
 * @property string type
 * @property int position
 * @property array items
 *
 * @method Story getParentObject()
 */
class StoryComponent extends EmbedModel
{

	const STORY_COMPONENT_TYPE_TEXT = 1;
	const STORY_COMPONENT_TYPE_PHOTOS = 2;
	const STORY_COMPONENT_TYPE_WORKS = 3;
	const STORY_COMPONENT_TYPE_VIDEOS = 4;

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
			'position',
			'items',
		];
	}

	public function getParentAttribute()
	{
		return "components";
	}

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
				'type',
				'in',
				'range' => [
					self::STORY_COMPONENT_TYPE_TEXT,
					self::STORY_COMPONENT_TYPE_PHOTOS,
					self::STORY_COMPONENT_TYPE_VIDEOS,
					self::STORY_COMPONENT_TYPE_WORKS,
				]
			],
			[
				'items',
				'app\validators\TranslatableRequiredValidator',
				'when' => function ($model) {
					return $model->type == self::STORY_COMPONENT_TYPE_TEXT;
				}
			],
			[
				'items',
				'validatePhotos',
				'when' => function ($model) {
					return $model->type == self::STORY_COMPONENT_TYPE_PHOTOS;
				}
			],
			[
				'items',
				'validateWorks',
				'when' => function ($model) {
					return $model->type == self::STORY_COMPONENT_TYPE_WORKS;
				}
			],
			[
				'items',
				'validateVideos',
				'when' => function ($model) {
					return $model->type == self::STORY_COMPONENT_TYPE_VIDEOS;
				}
			],
			[
				'position',
				'number',
				'min' => 0
			]
		];
	}

	public function validatePhotos($attribute, $params)
	{
		$photos = $this->$attribute;
		$person = $this->getParentObject()->getPerson();
		foreach ($photos as $item) {
			if (!is_array($item) || !isset($item['photo']) || !isset($item['position'])) {
				$this->addError('items', sprintf('Invalid format for photo item'));
				break;
			}
			$photo = $item['photo'];
			$position = $item['position'];
			if (!is_int($position) || $position < 0) {
				$this->addError('items', sprintf('Invalid position (%s) for photo %s', $position, $photo));
			}
			if (!$person->existMediaFile($photo)) {
				$this->addError('items', sprintf('Photo %s does not exists', $photo));
			}
		}
	}

	public function validateWorks($attribute, $params)
	{
		$works = $this->$attribute;
		foreach ($works as $item) {
			if (!is_array($item) || !isset($item['work']) || !isset($item['position'])) {
				$this->addError('items', sprintf('Invalid format for work item'));
				break;
			}
			$workId = $item['work'];
			$position = $item['position'];
			if (!is_int($position) || $position < 0) {
				$this->addError('items', sprintf('Invalid position (%s) for work %s', $position, $workId));
			}
			$work = Product::findOneSerialized($workId);
			if (!$work) {
				$this->addError('items', sprintf('Work %s does not exists', $workId));
			}
		}
	}

	public function validateVideos($attribute, $params)
	{
		$videos = $this->$attribute;
		foreach ($videos as $urlVideo) {
			$validator = new UrlValidator();
			if (!$validator->validate($urlVideo)) {
				$this->addError('items', sprintf('Vídeo %s is not a valid url', $urlVideo));
			}
		}
	}

	/**
	 * Custom validator for person_id
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateType($attribute, $params)
	{
		$type = $this->$attribute;
		switch ($type) {
			case self::STORY_COMPONENT_TYPE_TEXT:
				$texts = $this->items;
				if (empty($texts)) {
					$this->addError($attribute, sprintf('You must to specify some text'));
				}
				$transatableRequiredvalidator = new TranslatableRequiredValidator();
				$valid = $transatableRequiredvalidator->validate($this->items);
				if (!$valid) {
					$this->addError($attribute, sprintf('Component text must be a translatable field'));
				}
				break;
			case self::STORY_COMPONENT_TYPE_PHOTOS:
				break;
			case self::STORY_COMPONENT_TYPE_VIDEOS:
				break;
			case self::STORY_COMPONENT_TYPE_WORKS:
				break;
		}
	}

	/**
	 * Returns the text of the component (if it is a "text" component)
	 *
	 * @return string|null
	 */
	public function getText()
	{
		return Utils::l($this->items);
	}
}