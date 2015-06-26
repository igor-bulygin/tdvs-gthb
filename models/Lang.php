<?php
namespace app\models;

use Yii;

class Lang {
	const EN_US = ['en-US' => 'English'];
	const ES_ES = ['es-ES' => 'Spanish'];
	const CA_ES = ['ca-ES' => 'Catalan'];

	function __construct() {
		Yii::t("app/admin", "English");
		Yii::t("app/admin", "Spanish");
		Yii::t("app/admin", "Catalan");
	}
}