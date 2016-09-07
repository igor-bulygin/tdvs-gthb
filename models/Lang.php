<?php
namespace app\models;

use Yii;

class Lang {
	const EN_US = 'en-US';
	const ES_ES = 'es-ES';
	const CA_ES = 'ca-ES';

	// Do NOT add any other keys/values to those arrays or bad things will happen. You have been warned.
	const EN_US_DESC = [self::EN_US => 'English'];
	const ES_ES_DESC = [self::ES_ES => 'Spanish'];
	const CA_ES_DESC = [self::CA_ES => 'Catalan'];

	function __construct() {
		Yii::t("app/admin", "English");
		Yii::t("app/admin", "Spanish");
		Yii::t("app/admin", "Catalan");
	}

	static public function getAvailableLanguages()
    {
        return array_merge(
            self::EN_US_DESC,
            self::ES_ES_DESC,
            self::CA_ES_DESC
        );
    }
}