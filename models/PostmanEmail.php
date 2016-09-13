<?php
namespace app\models;

use app\helpers\Utils;
use DateTime;
use Exception;
use MongoDate;
use Ramsey\Uuid\Uuid;
use Yii;
use app\helpers\CActiveRecord;
use yii\base\NotSupportedException;
use yii\mongodb\ActiveQuery;
use yii\mongodb\Collection;

/**
 * @property string $uuid
 * @property string $mail_from
 * @property string $sender
 * @property string $return_path
 * @property string $reply_to
 * @property string $from_email
 * @property string $from_name
 * @property string $to_email
 * @property string $to_name
 * @property string $subject
 * @property string $body_plain_text
 * @property string $body_html
 * @property string $code_charset
 * @property string $code_email_content_type
 * @property string $invitation_id
 * @property array $tasks
 * @property array $actions
 * @property MongoDate $created_at
 * @property MongoDate $updated_at
 * @property MongoDate $deleted_at
 */
class PostmanEmail extends CActiveRecord
{

	const EMAIL_CONTENT_TYPE_DEVISER_REQUEST_INVITATION = 'deviser-request-invitation';
	const EMAIL_CONTENT_TYPE_DEVISER_INVITATION = 'deviser-invitation';

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $retrieveExtraFields = [];

	public static function collectionName()
	{
		return 'postman_email';
	}

	public function attributes()
	{
		return [
			'_id',
			'uuid',
			'mail_from',
			'sender',
			'return_path',
			'reply_to',
			'from_email',
			'from_name',
			'to_email',
			'to_name',
			'subject',
			'body_plain_text',
			'body_html',
			'code_charset',
			'code_email_content_type',
			'created_at',
			'updated_at',
			'deleted_at',
			'tasks',
			'actions',
			'invitation_id',
		];
	}
	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->from_email = 'info@todevise.com'; // TODO get this from .env file
		$this->uuid = Uuid::uuid4()->toString();
		$this->code_charset = 'utf-8';

		$this->tasks = [];
		$this->actions = [];

		PostmanEmail::setSerializeScenario(Invitation::SERIALIZE_SCENARIO_PUBLIC);
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 * @return array
	 * @throws Exception
	 */
	public static function findSerialized($criteria = [])
	{

		// Products query
		$query = new ActiveQuery(PostmanEmail::className());

		// Retrieve only fields that gonna be used
		$query->select(self::getSelectFields());


		// Count how many items are with those conditions, before limit them for pagination
		static::$countItemsFound = $query->count();

		// limit
		if ((array_key_exists("limit", $criteria)) && (!empty($criteria["limit"]))) {
			$query->limit($criteria["limit"]);
		}

		// offset for pagination
		if ((array_key_exists("offset", $criteria)) && (!empty($criteria["offset"]))) {
			$query->offset($criteria["offset"]);
		}

		if ((array_key_exists("order_by", $criteria)) && (!empty($criteria["order_by"]))) {
			$query->orderBy($criteria["order_by"]);
		}


		$items = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($items);
		}
		return $items;
	}


	/**
	 * Get one entity serialized
	 *
	 * @param string $token
	 * @return Invitation|null
	 * @throws Exception
	 */
	public static function findOneSerialized($token)
	{
		/** @var Invitation $invitation */
		$invitation = PostmanEmail::find()->select(self::getSelectFields())->where(["uuid" => $token])->one();

		return $invitation;
	}

	public function rules()
	{
		return [
			[['from_email', 'to_email', 'body_html'], 'required'],
			[['from_email', 'to_email'], 'email'],
		];
	}

	/**
	 * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
	 * only the attributes needed for a query context
	 *
	 * @param $view
	 */
	public static function setSerializeScenario($view)
	{
		switch ($view) {
			case self::SERIALIZE_SCENARIO_PUBLIC:
				static::$serializeFields = [
					'uuid',
					'from_name',
					'from_email',
					'to_name',
					'to_email',
					'subject',
				];
				static::$retrieveExtraFields = [
					'tasks',
					'actions',
				];
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	/**
	 * Check some attributes before save model
	 *
	 * @param bool $insert
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		$now = new MongoDate();
		if (empty($this->created_at)) {
			$this->created_at = $now;
		}
		$this->updated_at = $now;

		return parent::beforeSave($insert);
	}

	/**
	 * Add a new task to array
	 *
	 * @param PostmanEmailTask $task
	 * @return PostmanEmail
	 */
	public function addTask(PostmanEmailTask $task)
	{
		$this->tasks = array_merge($this->tasks, [$task->getDirtyAttributes()]);

		return $this;
	}

	/**
	 * Add a new action link to array
	 *
	 * @param PostmanEmailAction $action
	 * @return PostmanEmail
	 */
	public function addAction(PostmanEmailAction $action)
	{
		$this->actions = array_merge($this->actions, [$action->getDirtyAttributes()]);

		return $this;
	}

	/**
	 * Send the email
	 *
	 * @return bool
	 */
	public function send()
	{
		return Yii::$app->mailer->compose()
			->setFrom([$this->from_email => $this->from_name])
			->setTo([$this->to_email => $this->to_name])
			->setSubject($this->subject)
			->setHtmlBody($this->body_html)
			->setTextBody($this->body_plain_text)
			->send();
	}
}