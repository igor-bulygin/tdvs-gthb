<?php
namespace app\models;

/**
 * @property string person_id
 * @property string text
 * @property int stars
 * @property ProductComment[] $repliesInfo
 * @property \MongoDate created_at
 * @property mixed $helpfulsInfo
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
	public $helpfuls;

	public function attributes()
	{
		return [
			'short_id',
			'person_id',
			'text',
			'stars',
			'created_at',
			'replies',
			'helpfuls',
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

	public function embedHelpfulsInfo()
	{
		return $this->mapEmbeddedList('helpfuls', ProductCommentHelpful::className(), array('unsetSource' => false));
	}

	public function setParentOnEmbbedMappings()
	{
		foreach ($this->repliesInfo as $item) {
			$item->setParentObject($this);
		}

		foreach ($this->helpfulsInfo as $item) {
			$item->setParentObject($this);
		}

		parent::setParentOnEmbbedMappings();
	}

	public function rules()
	{
		return [
			[
				['text'], 'safe', 'on' => [
					Product::SCENARIO_PRODUCT_COMMENT,
					Product::SCENARIO_PRODUCT_COMMENT_REPLY,
					Product::SCENARIO_PRODUCT_COMMENT_HELPFUL,
				],
			],
			[
				['text'], 'required'
			],
			[
				['stars'], 'required', 'on' => [Product::SCENARIO_PRODUCT_COMMENT],
			],
			['person_id', 'app\validators\PersonIdValidator'],
			['stars', 'integer',  'min' => 0,  'max' => 5,],
			['repliesInfo', 'app\validators\EmbedDocValidator'], // to apply rules
			['helpfulsInfo', 'app\validators\EmbedDocValidator'] // to apply rules
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
		$replies = [];
		foreach ($this->repliesInfo as $reply) {
			$replies[] = $reply->getPreviewSerialized();
		}
		return [
			"id" => $this->short_id,
			"person_id" => $this->person_id,
			'person' => $this->getPerson()->getPreviewSerialized(),
			'text' => $this->text,
			'stars' => $this->stars,
			'replies' => $replies,
			'helpfuls' => $this->helpfuls,
			'created_at' => $this->created_at,

		];
	}
}