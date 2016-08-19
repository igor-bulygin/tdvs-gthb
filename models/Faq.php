<?php
namespace app\models;

use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class Faq extends CActiveRecord {


	public static function collectionName() {
		return 'faq';
	}

    /**
     * Get an array with Faq entities, serialized for public queries
     *
     * @return array
     */
	public static function getSerialized() {
        $faqs = Faq::find()->select(['short_id' , 'title', 'faqs'])->asArray()->all();
        // publish in the language selected by current user
        foreach ($faqs as $key => &$oneFaq){
            Utils::l_collection($oneFaq['faqs'], "question");
            Utils::l_collection($oneFaq['faqs'], "answer");
            $oneFaq['title'] = Utils::l($oneFaq['title']);
        }

        $faqs = Faq::find()->select(['short_id' , 'title', 'faqs'])->all();
        return $faqs;
	}

	public static function setSerializeView($view)
    {
        switch ($view) {
            case CActiveRecord::SERIALIZE_VIEW_PUBLIC:
                static::$serializeFields = [
                    // field name is "email", the corresponding attribute name is "email_address"
                    'id' => 'short_id',
                    'title',
                    'faqs',
                ];
                break;
            default:
                // unrecognized
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

    public function fields()
    {
        return static::$serializeFields;
    }

}
