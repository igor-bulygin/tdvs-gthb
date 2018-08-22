<?php
namespace app\models;

/**
 * @property string short_id
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
				['text'], 'safe', 'on' => [
					Product::SCENARIO_PRODUCT_COMMENT_REPLY,
				],
			],
			[
				['text'], 'required'
			],
			['person_id', 'app\validators\PersonIdValidator'],
		];
	}

	/**
	 * @return Person
	 */
	public function getPerson() {
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);

		$person = Person::findOneSerialized($this->person_id);

		return $person;
	}

	/**
	 * Get only preview attributes from Person
	 *
	 * @return array
	 */
	public function getPreviewSerialized()
	{
		return [
			"id" => $this->short_id,
			"person_id" => $this->person_id,
			'person' => $this->getPerson()->getPreviewSerialized(),
			'text' => $this->text,
			'created_at' => $this->created_at,
		];
	}
}