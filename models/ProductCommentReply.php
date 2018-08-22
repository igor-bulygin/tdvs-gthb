<?php
namespace app\models;

/**
 * @property string person_id
 * @property string text
 * @property \MongoDate created_at
 *
 * @method ProductComment getParentObject()
 */
class ProductCommentReply extends EmbedModel
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
			'created_at',
		];
	}

	public function getParentAttribute()
	{
		return "replies";
	}

	public function rules()
	{
		return [
			[
				['person_id', 'text'], 'safe', 'on' => [
					Product::SCENARIO_PRODUCT_COMMENT_REPLY,
				],
			],
			[
				['person_id', 'text'], 'required'
			],
			['person_id', 'app\validators\PersonIdValidator'],
		];
	}
}