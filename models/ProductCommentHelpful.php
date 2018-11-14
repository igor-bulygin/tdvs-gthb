<?php
namespace app\models;

/**
 * @property mixed $yes
 * @property mixed $no
 *
 * @method ProductComment getParentObject()
 */
class ProductCommentHelpful extends EmbedModel
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

	public $helpfuls;

	public function attributes()
	{
		return [
			'yes',
			'no',
		];
	}

	public function getParentAttribute()
	{
		return "helpfuls";
	}

	public function rules()
	{
		return [
			[['yes', 'no'], 'integer']
		];
	}

	/**
	 * Get only preview attributes from Person
	 *
	 * @return array
	 */
	public function getPreviewSerialized()
	{
		return [
			'yes' => $this->yes,
			'no' => $this->no,
		];
	}
}