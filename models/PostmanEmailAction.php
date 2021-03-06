<?php
namespace app\models;

use app\helpers\CActiveRecord;
use MongoDate;
use Ramsey\Uuid\Uuid;


/**
 * @property string $uuid
 * @property string $text_data
 * @property string $code_email_action_type
 * @property string $code_email_action_state
 * @property MongoDate $date_begin_available
 * @property MongoDate $date_end_available
 * @property MongoDate $date_first_use
 * @property MongoDate $date_last_use
 * @property integer $amount_uses
 * @property string $person_id
 * @property MongoDate $created_at
 * @property MongoDate $updated_at
 * @property MongoDate $deleted_at
 */
class PostmanEmailAction extends CActiveRecord
{

	const EMAIL_ACTION_TYPE_DEVISER_INVITATION_ACCEPT = 'deviser-invitation-accept';
	const EMAIL_ACTION_TYPE_INFLUENCER_INVITATION_ACCEPT = 'influencer-invitation-accept';
	const EMAIL_ACTION_TYPE_PERSON_FORGOT_PASSWORD= 'person-forgot-password';

	const EMAIL_ACTION_STATE_PENDING = 'pending';
	const EMAIL_ACTION_STATE_UNNECESSARY = 'unnecessary';
	const EMAIL_ACTION_STATE_CANCELED = 'canceled';
	const EMAIL_ACTION_STATE_USED = 'sent';

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
			'uuid',
			'text_data',
			'code_email_action_type',
			'code_email_action_state',
			'date_begin_available',
			'date_end_available',
			'date_first_use',
			'date_last_use',
			'amount_uses',
			'person_id',
			'created_at',
			'updated_at',
			'deleted_at',
		];
	}

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->uuid = Uuid::uuid4()->toString();
		$this->code_email_action_state = PostmanEmailAction::EMAIL_ACTION_STATE_PENDING;
		$this->date_begin_available = new MongoDate();
		$this->created_at = new MongoDate();
	}

	/**
	 * @param $uuid
	 *
	 * @return null|PostmanEmailAction
	 */
	public static function findOneByUuid($uuid)
	{
		$emails = PostmanEmail::findSerialized([
			'action_uuid' => $uuid,
		]);

		if ($emails) {
			$email = reset($emails); // get first
			foreach ($email->actions as $action) {
				if ($action['uuid'] == $uuid) {
					$actionObject = new PostmanEmailAction();
					$actionObject->setAttributes($action, false);
					return $actionObject;
				}
			}
		}

		return null;
	}

	/**
	 * @return bool
	 */
	public function canUse()
	{
		return
			($this->amount_uses === null || $this->amount_uses > 0) &&
			(empty($this->date_end_available) || $this->date_end_available > new MongoDate())
		;
	}

	/**
	 * Mark the action as used
	 */
	public function markAsUsed()
	{
		$email = PostmanEmail::findOne([
			'actions.uuid' => $this->uuid,
		]);

		if ($email) {
			$actions = $email->actions;
			foreach ($actions as $i => $action) {
				if ($action['uuid'] == $this->uuid) {
					$action['date_first_use'] = new MongoDate();
					$action['date_last_use'] = new MongoDate();
					if (isset($action['amount_uses'])) {
						$action['amount_uses'] = $action['amount_uses'] - 1;
					}
					$action['updated_at'] = new MongoDate();
					$actions[$i] = $action;
				}
			}
			$email->setAttribute('actions', $actions);
			$email->save();
		}
	}
}