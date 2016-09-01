<?php
namespace app\models;

use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class Faq extends CActiveRecord {

    /**
     * The attributes that should be translated
     *
     * @var array
     */
    public $translatedAttributes = ['title', 'faqs.question', 'faqs.answer'];

	public static function collectionName() {
		return 'faq';
	}

    /**
     * Get a collection of entities serialized, according to serialization configuration
     *
     * @return array
     */
	public static function getSerialized() {

        // retrieve only fields that want to be serialized
        $faqs = Faq::find()->select(self::getSelectFields())->all();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($faqs);
        }
        return $faqs;
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
                    // field name is "email", the corresponding attribute name is "email_address"
                    'id' => 'short_id',
                    'title',
                    'faqs',
                ];
                static::$translateFields = true;
                break;
            case self::SERIALIZE_SCENARIO_ADMIN:
                static::$serializeFields = [
                    // field name is "email", the corresponding attribute name is "email_address"
                    'id' => 'short_id',
                    'title',
                    'faqs',
                ];
                static::$translateFields = false;
                break;
            default:
                // now available for this Model
                static::$serializeFields = [];
                break;
        }
    }

	public function attributes() {
		return [
			'_id',
			'short_id',
			'title',
			'faqs',
		];
	}

	public function getFaqs($current_path = null) {
		return $this->faqs;
		//
		// if ($current_path === null) {
		// 	$current_path = $this->path . $this->short_id . "/";
		// }
		//
		// return (new Query)->
		// 	select([])->
		// 	from('faqs')->
		// 	where(["REGEX", "path", "/^$current_path/"])->all();
	}

	public function beforeSave($insert) {

		if($this->faqs == null) {
			$this["faqs"] = [];
		}

		return parent::beforeSave($insert);
	}


}
