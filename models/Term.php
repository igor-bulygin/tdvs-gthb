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
     * Get an array with Faq entities, serialized for public queries
     *
     * @return array
     */
    public static function getSerializedPublic() {
        $terms = Term::find()->select(['short_id', 'title', 'terms'])->asArray()->all();
        // publish in the language selected by current user
        foreach ($terms as $key => &$oneTerm){
            Utils::l_collection($oneTerm['terms'], "question");
            Utils::l_collection($oneTerm['terms'], "answer");
            $oneTerm['title'] = Utils::l($oneTerm['title']);
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
