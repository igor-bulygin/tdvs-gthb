<?php
namespace app\models;

use app\helpers\CActiveRecord;
use MongoDate;


/**
 * @property string  $id
 * @property MongoDate  $date_send_scheduled
 * @property MongoDate  $date_sent_actual
 * @property string $code_process_state
 * @property integer $number_attempts
 * @property integer $number_batch_process_id
 * @property integer $code_priority
 * @property string $code_process_channel
 * @property MongoDate $created_at
 * @property MongoDate $updated_at
 * @property MongoDate $deleted_at
 */
class PostmanEmailTask extends CActiveRecord
{

	const PROCESS_STATE_PENDING = 'pending';
	const PROCESS_STATE_UNNECESSARY = 'unnecessary';
	const PROCESS_STATE_CANCELED = 'canceled';
	const PROCESS_STATE_SENT = 'sent';

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
			'id',
			'date_send_scheduled',
			'date_sent_actual',
			'code_process_state',
			'number_attempts',
			'number_batch_process_id',
			'code_priority',
			'code_process_channel',
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

		$this->id = uniqid();
		$this->code_priority = 5;
		$this->code_process_channel = 'default';
		$this->code_process_state = self::PROCESS_STATE_PENDING;
		$this->created_at = new MongoDate();
	}

}