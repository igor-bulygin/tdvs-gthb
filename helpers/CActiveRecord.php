<?php
namespace app\helpers;

use Yii;
use yii\base\Model;
use yii\db\Query;

class CModel extends Model
{

	public function genValidID($collection)
	{
		$_found = false;
		$_id = 0;
		while ($_found  === false) {
			$_id = Utils::shortID(6);

			$query = new Query;
			$query->select(['shortID'])->from($collection)->where(['shortID' => $_id]);
			$_found = empty($query->all());
		}

		return $_id;
	}


}