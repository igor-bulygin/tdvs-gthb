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
use yii\helpers\Url;
use yii\mongodb\ActiveQuery;
use yii\mongodb\Collection;

/**
 * @property string $uuid
 * @property string $email
 * @property string $first_name
 * @property string $message
 * @property MongoDate $date_sent
 * @property MongoDate $date_use
 * @property MongoDate $date_ends_availability
 * @property string $code_use_state
 * @property string $code_invitation_type
 * @property string $person_id
 * @property string $postman_email_id
 * @property MongoDate $created_at
 * @property MongoDate $updated_at
 */
class Invitation extends CActiveRecord
{

	const INVITATION_TYPE_DEVISER = 'invitation-deviser';
	const INVITATION_TYPE_TREND_SETTER = 'invitation-trend-setter';

	const USE_STATE_UNUSED = 'unused';
	const USE_STATE_USED = 'used';
	const USE_STATE_CANCELED = 'canceled';

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
		return 'invitation';
	}

	public function attributes()
	{
		return [
			'_id',
			'uuid',
			'email',
			'first_name',
			'message',
			'date_sent',
			'date_use',
			'date_ends_availability',
			'code_use_state',
			'code_invitation_type',
			'person_id',
			'postman_email_id',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->uuid = Uuid::uuid4()->toString();
		$this->code_use_state = Invitation::USE_STATE_UNUSED;

		Invitation::setSerializeScenario(Invitation::SERIALIZE_SCENARIO_PUBLIC);
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
		$query = new ActiveQuery(Invitation::className());

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
	 * @param string $uuid
	 * @return Invitation|null
	 * @throws Exception
	 */
	public static function findOneSerialized($uuid)
	{
		/** @var Invitation $invitation */
		$invitation = Invitation::find()->select(self::getSelectFields())->where(["uuid" => $uuid])->one();

		return $invitation;
	}


	/**
	 * Get one entity serialized, searching by an "email action id" related with the invitation
	 *
	 * @param string $actionId
	 * @return Invitation|null
	 * @throws Exception
	 */
	public static function findByEmailAction($actionId)
	{
		/** @var PostmanEmail $email */
		$email = PostmanEmail::findByEmailAction($actionId);
		if ($email) {
			return $email->getInvitation();
		}

		return null;
	}

	public function rules()
	{
		return [
			[['email', 'first_name', 'code_invitation_type'], 'required'],
			[['message'], 'safe'],
			[['email'], 'email'],
			[['code_invitation_type'], 'validateInvitationType'],
		];
	}

	/**
	 * Custom validator for type param
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateInvitationType($attribute, $params)
	{
		switch ($this->$attribute) {
			case Invitation::INVITATION_TYPE_DEVISER:
			case Invitation::INVITATION_TYPE_TREND_SETTER:
				break;
			default:
				$this->addError($attribute, 'Invalid type');
				break;
		}
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
					'email',
					'first_name',
				];
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	/**
	 * Revise some attributes before save in database
	 *
	 * @param bool $insert
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (empty($this->created_at)) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	/**
	 * Compose the email to be sent to user with the invitation
	 *
	 * @return PostmanEmail
	 */
	public function composeEmail()
	{
		$email = new PostmanEmail();
		$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_DEVISER_INVITATION;
		$email->to_email = $this->email;
		$email->subject = $this->getEmailSubject();

		// add task only one send task (to allow retries)
		$task = new PostmanEmailTask();
		$task->date_send_scheduled = new MongoDate();
		$email->addTask($task);

		// register the attached action: invitation link
		$action = new PostmanEmailAction();
		$action->code_email_action_type = PostmanEmailAction::EMAIL_ACTION_TYPE_DEVISER_INVITATION_ACCEPT;
		$email->addAction($action);

		$email->body_html = Yii::$app->view->render(
			$this->getEmailView(),
			[
				"message" => $this->message,
				"invitation" => $this,
				"actionAccept" => $action,
			]
		);
		$email->save();

		// relate the invitation with the email
		$this->postman_email_id = $email->_id;
		$this->save(true, ["postman_email_id"]);

		return $email;
	}

	/**
	 * Get the email related with the invitation
	 *
	 * @return PostmanEmail
	 */
	public function getPostmanEmail()
	{
		/** @var PostmanEmail $postmanEmail */
		$postmanEmail = $this->hasOne(PostmanEmail::className(), ['_id' => 'postman_email_id'])->one();
		return $postmanEmail;
	}

	/**
	 * Get the subject for the email, according to invitation type
	 *
	 * @return string
	 */
	private function getEmailSubject()
	{
		return 'Invitation to todevise.com';
	}

	/**
	 * Get the view to use for the email, according to invitation type
	 *
	 * @return string
	 */
	private function getEmailView()
	{
		return '@app/mail/deviser/invitation';
	}

	/**
	 * Indicate if an invitation can be used, or not (because it is already used, out of date, etc ...)
	 *
	 * @param MongoDate $datetime
	 * @return bool
	 */
	public function canUse(MongoDate $datetime = null)
	{
		// initialize
		$datetime = ($datetime) ? $datetime : new MongoDate();

		// must be unused
		if ($this->code_use_state != Invitation::USE_STATE_UNUSED) {
			return false;
		}

		// must be used before ends availability
		if ((!empty($this->date_ends_availability)) && ($this->date_ends_availability < $datetime)) {
			return false;
		}

		return true;
	}

	/**
	 * Indicate if an invitation was used
	 *
	 * @return bool
	 */
	public function isUsed()
	{
		return ($this->code_use_state == self::USE_STATE_USED);
	}

	/**
	 * Set the properties to indicate that the invitation has been used
	 *
	 * @param MongoDate $datetime
	 * @return Invitation
	 */
	public function setAsUsed(MongoDate $datetime = null)
	{
		// initialize
		$datetime = ($datetime) ? $datetime : new MongoDate();

		$this->date_use = $datetime;
		$this->code_use_state = Invitation::USE_STATE_USED;

		// stop to sent future task
		$email = $this->getPostmanEmail();
		if ($email) {
			$email->stopTasks()->save();
		}

		return $this;
	}

}