<?php
namespace app\controllers;

use app\helpers\CController;
use app\models\Chat;
use app\models\Person;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class ChatController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	public function actionChat()
	{
		$person = Yii::$app->user->identity; /* @var $person Person */

		if (!$person) {
			throw new NotFoundHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/chat/chat", [
			'person' => $person,
			'personToChat' => null,
			'chatId' => null,
		]);
	}

	public function actionConversation($slug, $person_id)
	{
		$person = Yii::$app->user->identity; /* @var $person Person */

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);
		$personToChat = Person::findOneSerialized($person_id);

		if (!$personToChat) {
			throw new NotFoundHttpException();
		}

		$person_ids = [
			$person->short_id,
			$personToChat->short_id,
		];

		$chats = Chat::findSerialized([
			"person_ids" => $person_ids,
			"limit" => 1,
		]);

		if ($chats) {
			$chat = $chats[0];
			$chat->markAsReadByPerson($person);
			$chatId = $chat->short_id;
		} else {
			$chatId = null;
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/chat/chat", [
			'person' => $person,
			'personToChat' => $personToChat,
			'chatId' => $chatId,
		]);
	}
}
