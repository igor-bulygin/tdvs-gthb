<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $about;
	public $ordernum;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', /*'about', 'subject',*/ 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
//            ['verifyCode', 'captcha'],
			['about', 'default']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $emailAddress the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($emailAddress)
	{
		if ($this->validate()) {

			// handle success
			$email = new PostmanEmail();
			$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_CONTACT_MESSAGE;
			$email->to_email = $emailAddress;
			$email->subject = 'TODEVISE - New contact';

			// add task only one send task (to allow retries)
			$task = new PostmanEmailTask();
			$task->date_send_scheduled = new \MongoDate();
			$email->addTask($task);

			$email->body_html = Yii::$app->view->render(
				'@app/mail/contact',
				[
					"form" => $this,
				],
				$this
			);
			$email->save();

			if ($email->send($task->id)) {
				$email->save();

				return true;
			};
		}

		return false;
	}
}
