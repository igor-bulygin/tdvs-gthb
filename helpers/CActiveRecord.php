<?php
namespace app\helpers;

use Yii;
use yii\db\Query;
use yii\mongodb\ActiveRecord;

class CActiveRecord extends ActiveRecord
{

	public function genValidID()
	{
		//TODO: hacer algun tipo de bloqueo a nivel de base de datos del ID devuelto
		$_found = false;
		$_id = 0;
		while ($_found  === false) {
			$_id = Utils::shortID(6);

			$query = new Query;
			$query->
				select(['shortID'])->
				from($this->collectionName())->
				where(['shortID' => $_id]);
			$_found = empty($query->all());
		}

		return $_id;
	}

}