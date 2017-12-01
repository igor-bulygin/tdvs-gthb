<?php
namespace app\modules\api\pub\v1\forms;

use app\models\Person;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class ResetPasswordForm extends Model {

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $action_id;

	/**
	 * @var string
	 */
	public $person_id;

	/**
	 * @var string
	 */
	public $new_password;

	/**
	 * @var string
	 */
	public $repeat_password;

	public function rules()
	{
		return [
			[['email', 'action_id', 'person_id', 'new_password', 'repeat_password'], 'required'],
			[['email'], 'email'],
			['new_password', 'compare', 'compareAttribute' => 'repeat_password'],
			['person_id', 'validatePersonId'],
		];
	}



	/**
	 * Custom validator for amount of products published
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validatePersonId($attribute, $params)
	{
		$person = Person::findOne([
			'short_id' => $this->person_id,
		]);
		if (empty($person)) {
			$this->addError('person_id', 'Person not found.');
			return;
		}
		if (!$person->checkPersonByEmailActionUuid($this->action_id) || $person->getEmail() != $this->email) {
			$this->addError('action_id', 'Invalid action_id.');
			return;
		}
	}

	public function changePassword()
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findByEmail($this->email);
		if (!$person) {
			throw new NotFoundHttpException(sprintf("Email %s not found", $this->email));
		}
		$person->setPassword($this->new_password);
		$person->save();
	}

}
