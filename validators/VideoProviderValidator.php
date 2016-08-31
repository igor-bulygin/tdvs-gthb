<?php
namespace app\validators;

use app\helpers\Utils;
use app\models\Person;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\UrlValidator;
use yii\validators\Validator;
use yii\web\UrlManager;

class VideoProviderValidator extends Validator
{
	public $scenario;
	public $model;

	public function validate($value, &$error = null)
	{
		// TODO, parse URL, and verify available domains (youtube, vimeo, etc ...)
		return true;
	}

}