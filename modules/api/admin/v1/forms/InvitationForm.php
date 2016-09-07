<?php
namespace app\modules\api\admin\v1\forms;

use app\models\Person;
use Yii;
use app\helpers\Utils;
use yii\base\Model;
use yii\web\UploadedFile;

class InvitationForm extends Model {

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $message;

	public function rules()
	{
		return [
			[['email', 'message'], 'required'],
		];
	}
}
