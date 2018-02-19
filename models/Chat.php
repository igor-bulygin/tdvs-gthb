<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use yii\mongodb\ActiveQuery;

/**
 * @property string short_id
 * @property array members
 * @property array messages
 * @property array unread_by
 * @property MongoDate created_at
 * @property MongoDate updated_at
 */
class Chat extends CActiveRecord
{

	const SERIALIZE_SCENARIO_LIST= 'serialize_scenario_list'; // list last chats

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];

	public static function collectionName()
	{
		return 'chat';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'members',
			'messages',
			'unread_by',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = [];

	public static $textFilterAttributes = [];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(8);

		$this->unread_by = [];
	}

	public function beforeSave($insert)
	{
		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function rules()
	{
		return [
			[
				[
					'members',
					'messages'
				],
				'app\validators\SubDocumentValidator'
			],
		];
	}

	/**
	 * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
	 * only the attributes needed for a query context
	 *
	 * @param $view
	 */
	public static function setSerializeScenario($view)
	{
		switch ($view) {
			case self::SERIALIZE_SCENARIO_LIST:
				static::$serializeFields = [
					'id' => 'short_id',
					'preview' => 'preview',
					// 'unread_by',
				];
				static::$retrieveExtraFields = [
					'members',
					'messages',
					'unread_by',
				];


				static::$translateFields = false;
				break;
			case self::SERIALIZE_SCENARIO_PREVIEW:
			case self::SERIALIZE_SCENARIO_PUBLIC:
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'members',
					'messages',
					// 'unread_by',
				];
				static::$retrieveExtraFields = [
					'unread_by',
				];


				static::$translateFields = false;
				break;

			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
		ChatMember::setSerializeScenario($view);
		ChatMessage::setSerializeScenario($view);
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return Chat|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Chat $chat */
		$chat = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($chat);
		}

		return $chat;
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 *
	 * @return Chat[]
	 * @throws Exception
	 */
	public static function findSerialized($criteria = [])
	{

		// Order query
		$query = new ActiveQuery(static::className());

		// Retrieve only fields that gonna be used
		$query->select(self::getSelectFields());

		if ((array_key_exists("id", $criteria)) && (!empty($criteria["id"]))) {
			$query->andWhere(["short_id" => $criteria["id"]]);
		}

		if ((array_key_exists("person_id", $criteria)) && (!empty($criteria["person_id"]))) {
			$query->andWhere(["members.person_id" => $criteria["person_id"]]);
		}

		if ((array_key_exists("person_ids", $criteria)) && (!empty($criteria["person_ids"]))) {
			foreach ($criteria['person_ids'] as $person_id) {
				$query->andWhere(["members.person_id" => $person_id]);
			}
		}

		if (array_key_exists("person_type", $criteria) && (!empty($criteria["person_type"]))) {
			// Create a temp. list of users of the specified type (excluding connected user)
			$connectedPerson = \Yii::$app->user->identity; /* @var Person $connectedPerson */
			$persons = Person::findSerialized(['type' => $criteria['person_type']]);
			$personIds = [];
			foreach ($persons as $person) {
				if (!$connectedPerson || $person->short_id != $connectedPerson->short_id) {
					$personIds[] = $person->short_id;
				}
			}
			// Filter by those users
			$query->andWhere(['in', "members.person_id", $personIds]);
		}

		if ((array_key_exists("unread_by_person_id", $criteria)) && (!empty($criteria["unread_by_person_id"]))) {
			$query->andWhere(["messages.unread" => $criteria["unread_by_person_id"]]);
		}

		// Count how many items are with those conditions, before limit them for pagination
		static::$countItemsFound = $query->count();

		// limit
		if ((array_key_exists("limit", $criteria)) && (!empty($criteria["limit"]))) {
			$query->limit($criteria["limit"]);
		}

		// offset for pagination
		if ((array_key_exists("offset", $criteria)) && (!empty($criteria["offset"]))) {
			$query->offset($criteria["offset"]);
		}

		if ((array_key_exists("order_by", $criteria)) && (!empty($criteria["order_by"]))) {
			$query->orderBy($criteria["order_by"]);
		} else {
			$query->orderBy([
				"created_at" => SORT_DESC,
			]);
		}

		$chats = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($chats);
		}

		return $chats;
	}


	/*
	public function beforeValidate()
	{
		if ($this->scenario == 'default') {
			if ($this->isChat()) {
				$this->setScenario(Chat::SCENARIO_ORDER);
			} else {
				$this->setScenario(Chat::SCENARIO_CART);
			}
		}

		return parent::beforeValidate();
	}
	*/

	/**
	 * Add additional error to make easy show labels in client side
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		foreach ($this->errors as $attribute => $error) {
			switch ($attribute) {
				default:
					if (Utils::isRequiredError($error)) {
						$this->addError("required", $attribute);
					}
					$this->addError("fields", $attribute);
					break;
			}
		};
	}

	public function subDocumentsConfig()
	{
		return [
			'messages' => [
				'class' => ChatMessage::className(),
				'type' => 'list',
			],
			'members' => [
				'class' => ChatMember::className(),
				'type' => 'list',
			],
		];
	}

	/**
	 * @return ChatMessage[]
	 */
	public function getMessages()
	{
		return $this->getSubDocument('messages');
	}

	public function setMessages($value)
	{
		$this->setSubDocument('messages', $value);
	}

	/**
	 * @return ChatMember[]
	 */
	public function getMembers()
	{
		return $this->getSubDocument('members');
	}

	public function setMembers($value)
	{
		$this->setSubDocument('members', $value);
	}

	public function addMessage($person_id, $text)
	{
		$chatMessage = new ChatMessage();
		$chatMessage->person_id = $person_id;
		$chatMessage->text = $text;
		$chatMessage->date = new MongoDate();

		// Set the message in the subdocument
		$messages = $this->getMessages();
		$messages[] = $chatMessage;
		$this->setMessages($messages);

		// Set as unread for all the members (except sender)
		$members = $this->getMembers();
		$unread_by = $this->unread_by;
		foreach ($members as $member) {
			if ($member->person_id != $person_id && !in_array($member->person_id, $unread_by)) {
				$unread_by[] = $member->person_id;
			}
		}
		if (in_array($person_id, $unread_by)) {
			unset($unread_by[array_search($person_id, $unread_by)]);
		}

		$this->setAttribute('unread_by', $unread_by);
		$this->save();
	}

	public function getPreview()
	{
		$messages = $this->getMessages();

		return [
			'title' => $this->getTitle(),
			'text' => $messages ? $messages[count($messages) - 1]->text : null,
			'unread' => $this->isUnreadByConnectedUser(),
			'messages' => count($messages),
			'url' => $this->getUrl(),
		];
	}



	/**
	 * Returns TRUE if the chat is visible by the current user
	 *
	 * @return bool
	 */
	public function isVisible()
	{
		if (\Yii::$app->user->isGuest) {
			return false;
		}

		foreach ($this->getMembers() as $member) {
			if (\Yii::$app->user->identity->short_id == $member->person_id) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Marks the conversation as read by the person passed by parameter
	 *
	 * @param Person $person
	 */
	public function markAsReadByPerson(Person $person)
	{
		$index = array_search($person->short_id, $this->unread_by);
		if ($index !== false) {
			$unread_by = $this->unread_by;
			unset($unread_by[$index]);
			$this->setAttribute('unread_by', $unread_by);
			$this->save();
		}
	}

	/**
	 * Marks the conversation as unread by the person passed by parameter
	 *
	 * @param Person $person
	 */
	public function markAsUnreadByPerson(Person $person)
	{
		$index = array_search($person->short_id, $this->unread_by);
		if ($index === false) {
			$unread_by = $this->unread_by;
			$unread_by[] = $person->short_id;
			$this->setAttribute('unread_by', $unread_by);
			$this->save();
		}
	}

	/**
	 * Returns the last message in the conversation, ignoring messages from the person passed as parameter (if present)
	 *
	 * @param Person $personToIgnore
	 * @return ChatMessage
	 */
	public function getLastMessage($personToIgnore = null)
	{
		$messages = $this->getMessages();
		foreach ($messages as $message) {
			if (empty($personToIgnore) || $message->person_id != $personToIgnore->short_id) {
				return $message;
			}
		}

		return null;
	}

	/**
	 * Returns the "title" of the conversation. At this moment, the title is the names of the members, except connected user
	 *
	 * @return string
	 */
	public function getTitle()
	{
		$connectedPerson = \Yii::$app->user->identity; /* @var Person $connectedPerson */

		$members = $this->getMembers();

		$title = [];
		foreach ($members as $member) {
			if ($member->person_id != $connectedPerson->short_id) {
				$title[] = $member->getPerson()->getName();
			}
		}
		$title = implode(', ', $title);

		return $title;
	}

	/**
	 * Returns the "url" of the conversation. At this moment, the url is the link to chat with the member different of the current user
	 *
	 * @return string
	 */
	public function getUrl()
	{
		$connectedPerson = \Yii::$app->user->identity; /* @var Person $connectedPerson */

		$members = $this->getMembers();

		foreach ($members as $member) {
			if ($member->person_id != $connectedPerson->short_id) {
				return $member->getPerson()->getChatLink();
			}
		}

		return null;
	}

	/**
	 * Returns TRUE if the conversation is unread by the connected user
	 *
	 * @return bool
	 */
	public function isUnreadByConnectedUser()
	{
		$connected = \Yii::$app->user->identity;
		return $connected && in_array($connected->short_id, $this->unread_by);
	}
}
