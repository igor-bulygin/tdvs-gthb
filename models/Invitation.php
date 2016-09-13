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
 * @property string uuid
 * @property string email
 * @property string message
 * @property MongoDate date_sent
 * @property MongoDate date_first_use
 * @property MongoDate date_last_use
 * @property string code_use_state
 * @property string code_invitation_type
 * @property string person_id
 * @property string postman_email_id
 */
class Invitation extends CActiveRecord
{

	const INVITATION_TYPE_DEVISER = 'invitation-deviser';
	const INVITATION_TYPE_TREND_SETTER = 'invitation-trend-setter';

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

	/** @var  int */
	static public $countItemsFound = 0;

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
			'message',
			'date_sent',
			'date_first_use',
			'date_last_use',
			'code_use_state',
			'code_invitation_type',
			'person_id',
			'postman_email_id',
		];
	}

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->uuid = Uuid::uuid4()->toString();

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
	 * @param string $token
	 * @return Invitation|null
	 * @throws Exception
	 */
	public static function findOneSerialized($token)
	{
		/** @var Invitation $invitation */
		$invitation = Invitation::find()->select(self::getSelectFields())->where(["uuid" => $token])->one();

		return $invitation;
	}

	public function rules()
	{
		return [
			[['email', 'message', 'code_invitation_type'], 'required'],
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
					'date_sent',
				];
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
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

}