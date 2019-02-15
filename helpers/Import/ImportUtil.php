<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 11/02/2019
 * Time: 14:40
 */

namespace app\helpers\Import;

use app\models\Category;
use app\models\Tag;
use Yii;
use app\helpers\Utils;


class ImportUtil
{
    /**
     * @param $name string
     * @param $lang string
     * @return array
     * Searches for categories in DB that are like $name parameter
     */
    public static function getCategoryByName($name, $lang) {
        $res = array();

        if (!isset($name) or strlen($name) == 0) {
            return $res;
        }
        $cats = Category::find()->where(
            ['like', "name.".$lang, $name])->select(['short_id'])->asArray()->all();
        foreach ($cats as $v) {
            $res[] = $v['short_id'];
        }
        return $res;
    }

    public static function getOptionIdAndValue($name, $value, $lang) {
        $tag = Tag::find()->where(
            ['name.'.$lang => $name])->select(['short_id', 'options'])->asArray()->one();

        if (!$tag) return null;

        $val = false;
        foreach ($tag['options'] as $item) {
            if (array_search(strtolower(trim($value)), array_map('strtolower',$item['text']))) {
                $val = $item['value'];
            }
        }
        if ($val && strlen($val) > 0) {
            return array($tag['short_id'], $val);
        }
        return null;
    }

    public static function getOptionValue($short_id, $value) {
//        var_dump($short_id .'->'. $value);
        $tag = Tag::find()->where(
            ['short_id' => $short_id])->select(['options'])->asArray()->one();

        if (!$tag) return null;

        $val = false;
        foreach ($tag['options'] as $item) {
            if (array_search(strtolower(trim($value)), array_map('strtolower',$item['text']))) {
                $val = $item['value'];
            }
        }
        if ($val && strlen($val) > 0) {
            return $val;
        }
        return null;
    }


}