<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Chat;
use app\models\ChatMember;
use app\models\Person;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class ChatController extends AppPrivateController
{
	public function actionView($chatId)
	{
		Chat::setSerializeScenario(Chat::SERIALIZE_SCENARIO_OWNER);

		$chat = Chat::findOneSerialized($chatId);
		if (empty($chat)) {
			throw new NotFoundHttpException(sprintf("Chat with id %s does not exists", $chatId));
		}

		if (!$chat->isVisible()) {
			throw new UnauthorizedHttpException("You have no access to this chat");
		}

		$chat->setSubDocumentsForSerialize();

		return $chat;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Chat::setSerializeScenario(Chat::SERIALIZE_SCENARIO_LIST);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$chats = Chat::findSerialized([
			"person_type" => Yii::$app->request->get('person_type', null),
			"person_id" => Yii::$app->user->identity->short_id,
			"limit" => $limit,
			"offset" => $offset,
		]);

		foreach ($chats as $chat) {
			$chat->setSubDocumentsForSerialize();
		}

		return [
			"items" => $chats,
			"meta" => [
				"total_count" => Chat::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionSendMessage($personId)
	{
		Chat::setSerializeScenario(Chat::SERIALIZE_SCENARIO_OWNER);

		$person = Person::findOne(['short_id' => $personId]);
		if (!$person) {
			throw new BadRequestHttpException(sprintf('Can not create a conversation with person %s',
				$personId));
		}

		$connectedPerson = Yii::$app->user->identity; /* @var Person $connectedPerson */

		$person_ids = [
			$connectedPerson->short_id,
			$personId,
		];

		$chats = Chat::findSerialized([
			"person_ids" => $person_ids,
			"limit" => 1,
		]);

		$members = [];
		if (empty($chats)) {
			$member = new ChatMember();
			$member->person_id = $personId;
			$member->person_type = $person->type;
			$members[] = $member;

			$member = new ChatMember();
			$member->person_id = $connectedPerson->short_id;
			$member->person_type = $connectedPerson->type;
			$members[] = $member;

			$chat = new Chat();
			$chat->setMembers($members);
			$chat->save();
		} else {
			$chat = $chats[0];
		}

		if (!$chat->isVisible()) {
			throw new UnauthorizedHttpException("You have no access to this chat");
		}

		$text = Yii::$app->request->post('text');
		$chat->addMessage($connectedPerson->short_id, $text);

		Yii::$app->response->setStatusCode(201); // Created

		return $chat;
	}

	public function actionMarkAsRead($chatId)
	{
		Chat::setSerializeScenario(Chat::SERIALIZE_SCENARIO_OWNER);

		$chat = Chat::findOneSerialized($chatId);
		if (empty($chat)) {
			throw new NotFoundHttpException(sprintf("Chat with id %s does not exists", $chatId));
		}

		if (!$chat->isVisible()) {
			throw new UnauthorizedHttpException("You have no access to this chat");
		}

		$connectedPerson = Yii::$app->user->identity; /* @var Person $connectedPerson */
		$chat->markAsReadByPerson($connectedPerson);

		$chat->setSubDocumentsForSerialize();

		Yii::$app->response->setStatusCode(200); // Ok

		return $chat;
	}

	public function actionMarkAsUnread($chatId)
	{
		Chat::setSerializeScenario(Chat::SERIALIZE_SCENARIO_OWNER);

		$chat = Chat::findOneSerialized($chatId);
		if (empty($chat)) {
			throw new NotFoundHttpException(sprintf("Chat with id %s does not exists", $chatId));
		}

		if (!$chat->isVisible()) {
			throw new UnauthorizedHttpException("You have no access to this chat");
		}

		$connectedPerson = Yii::$app->user->identity; /* @var Person $connectedPerson */
		$chat->markAsUnreadByPerson($connectedPerson);

		$chat->setSubDocumentsForSerialize();

		Yii::$app->response->setStatusCode(200); // Ok

		return $chat;
	}
}