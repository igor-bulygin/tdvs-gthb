<?php

namespace app\helpers;

use Yii;

class CAccessRule extends \yii\filters\AccessRule {

	protected function matchRole($user) {
		if (empty($this->roles)) {
			return true;
		}

		foreach ($this->roles as $role) {
			if ($role === '?') {
				if (Yii::$app->user->isGuest) {
					return true;
				}
			} elseif ($role === '@') {
				return !Yii::$app->user->isGuest;
			} elseif ($role === 'admin') {
				if (Yii::$app->user->isGuest) {
					return false;
				}
				return Yii::$app->user->identity->isAdmin();
			} elseif ($role === 'deviser') {
				if (Yii::$app->user->isGuest) {
					return false;
				}
				return Yii::$app->user->identity->isDeviser();
			}
		}

		return false;
	}
}
