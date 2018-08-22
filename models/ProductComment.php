<?php
namespace app\models;

/**
 * @property string person_id
 * @property string text
 * @property int stars
 * @property ProductComment[] $repliesInfo
 * @property \MongoDate created_at
 *
 * @method Product getParentObject()
 */
class ProductComment extends EmbedModel
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

	public $replies;

	public function attributes()
	{
		return [
			'short_id',
			'person_id',
			'text',
			'stars',
			'created_at',
			'replies',
		];
	}

	public function getParentAttribute()
	{
		return "comments";
	}

	public function embedRepliesInfo()
	{
		return $this->mapEmbeddedList('replies', ProductCommentReply::className(), array('unsetSource' => false));
	}

	public function setParentOnEmbbedMappings()
	{
		foreach ($this->repliesInfo as $item) {
			$item->setParentObject($this);
		}

		parent::setParentOnEmbbedMappings();
	}

	public function rules()
	{
		return [
			[
				['person_id', 'text'], 'safe', 'on' => [
					Product::SCENARIO_PRODUCT_COMMENT,
					Product::SCENARIO_PRODUCT_COMMENT_REPLY,
				],
			],
			[
				['person_id', 'text'], 'required'
			],
			[
				['stars'], 'required', 'on' => [Product::SCENARIO_PRODUCT_COMMENT],
			],
			['person_id', 'app\validators\PersonIdValidator'],
			['stars', 'integer',  'min' => 0,  'max' => 5,],
			['repliesInfo', 'app\validators\EmbedDocValidator'], // to apply rules
		];
	}
}