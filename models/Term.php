<?php
namespace app\models;

use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class Term extends CActiveRecord {
	public static function collectionName() {
		return 'term';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'title',
			'terms',
		];
	}

    /**
     * The attributes that should be translated
     *
     * @var array
     */
    public $translatedAttributes = ['title', 'terms.question', 'terms.answer'];

	public function getTerms($current_path = null) {
		return $this->terms;
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

    /**
     * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
     * only the attributes needed for a query context
     *
     * @param $view
     */
    public static function setSerializeScenario($view)
    {
        switch ($view) {
            case CActiveRecord::SERIALIZE_SCENARIO_PUBLIC:
                static::$serializeFields = [
                    // field name is "email", the corresponding attribute name is "email_address"
                    'id' => 'short_id',
                    'title',
                    'terms',
                ];
                static::$translateFields = true;
                break;
            case CActiveRecord::SERIALIZE_SCENARIO_ADMIN:
                static::$serializeFields = [
                    // field name is "email", the corresponding attribute name is "email_address"
                    'id' => 'short_id',
                    'title',
                    'terms',
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
        $terms = Term::find()->select(array_values(static::$serializeFields))->all();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($terms);
        }
        return $terms;
    }

	public function beforeSave($insert) {

		if($this->terms == null) {
			$this["terms"] = [];
		}

		return parent::beforeSave($insert);
	}



}
