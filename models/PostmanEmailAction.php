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
 * @property string $text_data
 * @property string $code_email_action_type
 * @property string $code_email_action_state
 * @property MongoDate $date_begin_available
 * @property MongoDate $date_end_available
 * @property MongoDate $date_first_use
 * @property MongoDate $date_last_use
 * @property integer $amount_uses
 * @property MongoDate $created_at
 * @property MongoDate $updated_at
 * @property MongoDate $deleted_at
 */
class PostmanEmailAction extends CActiveRecord
{

	const EMAIL_ACTION_TYPE_DEVISER_INVITATION_ACCEPT = 'deviser-invitation-accept';

	const EMAIL_ACTION_STATE_PENDING = 'pending';
	const EMAIL_ACTION_STATE_UNNECESSARY = 'unnecessary';
	const EMAIL_ACTION_STATE_CANCELED = 'canceled';
	const EMAIL_ACTION_STATE_USED = 'sent';


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

}