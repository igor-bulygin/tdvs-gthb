<?php
namespace app\models;

use app\helpers\Utils;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use Yii;
use app\helpers\CActiveRecord;
use yii\base\NotSupportedException;
use yii\mongodb\ActiveQuery;
use yii\mongodb\Collection;

class Invitation extends CActiveRecord
{

//	const SCENARIO_TREND_SETTER_PROFILE_UPDATE = 'trend-setter-profile-update';

	/** @var string */
	public $uuid;

	/** @var string */
	public $email;

	/** @var string */
	public $message;

	/** @var DateTime */
	public $date_sent;

	/** @var DateTime */
	public $date_first_use;

	/** @var DateTime */
	public $date_last_use;

	/** @var DateTime */
	public $code_use_state;

	/** @var int */
	public $code_invitation_type;

	/** @var int */
	public $person_id;


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

		$products = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($products);
		}
		return $products;
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
			[['email', 'message'], 'required'],
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
}