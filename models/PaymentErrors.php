<?php

namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Yii;
use yii\mongodb\ActiveQuery;
use MongoDate;

/**
 * @property string $short_id
 * @property string $person_id
 * @property string $order_id
 * @property string $pack_id
 * @property double $amount_earned
 * @property string $error_type_id
 * @property string $error_type_description
 * @property MongoDate $created_at
 */
class PaymentErrors extends CActiveRecord
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


	//public $accessToken;

	public static function collectionName()
	{
		return 'payment_errors';
	}

  public function attributes()
	{
		return [
			'_id',
			'short_id',
			'person_id',
			'order_id',
			'pack_id',
			'amount_earned',
			'error_type_id',
			'error_type_description',
			'created_at',
		];
	}

  /**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(7);
	}

  /**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return PaymentErrors|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var PaymentErrors $paymenterror */
		$paymenterror = PaymentErrors::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		return $paymenterror;
	}


  /**
	 * Revise some attributes before save in database
	 *
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{

  }

  public static function createPaymentError($person_id, $order_id, $pack_id, $amount_earned, $error_type_id, $error_type_description) {

    $short_id = Utils::shortID(7);

    Yii::$app->mongodb->getCollection('payment_errors')->insert(
      [
         'short_id' => $short_id,
         'person_id' => $person_id,
         'order_id' => $order_id,
         'pack_id' => $pack_id,
         'amount_earned' => $amount_earned,
         'error_type_id' => $error_type_id,
         'error_type_description' => $error_type_description,
         'created_at' => new MongoDate(),
      ]
    );
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
									'id' => 'short_id',
									'short_id',
				          'person_id',
				          'order_id',
				          'pack_id',
				          'amount_earned',
				          'error_type_id',
				          'error_type_description',
				          'created_at',
							];
							static::$translateFields = true;
							break;
					case self::SERIALIZE_SCENARIO_ADMIN:
							static::$serializeFields = [
									'id' => 'short_id',
									'short_id',
									'person_id',
									'order_id',
									'pack_id',
									'amount_earned',
									'error_type_id',
									'error_type_description',
									'created_at',
							];
							static::$translateFields = false;
							break;
					default:
							// now available for this Model
							static::$serializeFields = [];
							break;
			}
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @return array
	 */
	public static function getSerialized() {

			// retrieve only fields that want to be serialized
			$payment_errors = PaymentErrors::find()->select(self::getSelectFields())->all();

			// if automatic translation is enabled
			if (static::$translateFields) {
					Utils::translate($payment_errors);
			}
			return $payment_errors;
	}

}
