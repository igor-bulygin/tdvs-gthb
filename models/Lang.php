<?php
namespace app\models;

use Yii;

class Lang {
	// Do NOT add any other keys/values to those arrays or bad things will happen. You have been warned.
	const EN_US = ['en-US' => 'English'];
	const ES_ES = ['es-ES' => 'Spanish'];
	const CA_ES = ['ca-ES' => 'Catalan'];

	function __construct() {
		Yii::t("app/admin", "English");
		Yii::t("app/admin", "Spanish");
		Yii::t("app/admin", "Catalan");
	}

	static public function getAvailableLanguages()
    {
        return array_merge(
            self::EN_US,
            self::ES_ES,
            self::CA_ES
        );
    }
}