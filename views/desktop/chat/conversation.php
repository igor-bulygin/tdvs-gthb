<?php

use app\assets\desktop\chat\GlobalAsset;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */
/** @var Person $personToChat */
/** @var string $chatId */

$this->title = Yii::t('app/public', 'CHAT_CONVERSATION_TITLE', ['person_name' => $personToChat->getName()]);

$this->params['person'] = $person;
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD);
$this->registerJs('var person_to_chat = ' .Json::encode($personToChat), yii\web\View::POS_HEAD);
$this->registerJs('var chat_id = ' .Json::encode($chatId), yii\web\View::POS_HEAD);

?>

<div class="row" ng-controller="chatCtrl as chatCtrl">
	<div class="col-lg-4" style="border-right:1px #D8D8D8 solid;">
		<uib-tabset active="active">
			<uib-tab index="$index" ng-repeat="tab in chatCtrl.tabs" heading="{{tab.title}}">
				<div ng-if="chatCtrl.chats.length<1" class="text-center" style="padding:50px;">
					<h4 class="row" translate="chat.NO_CHATS"></h2>
					<p class="row" translate="chat.CLICK_MSG"></p>
				</div>
				<div ng-repeat="chat in chatCtrl.chats">
					<span class="row red-text" ng-bind="chat.preview.title"></span>
					<span class="row" ng-bind="chat.preview.text"></span>
				</div>
			</uib-tab>
		</uib-tabset>
	</div>
	<div class="col-lg-8">
		<div ng-if="!chatCtrl.currentChat" class="text-center" style="padding:50px;">
			<span translate="chat.SELECT_CHAT"></span>
		</div>
		<div ng-if="chatCtrl.currentChat" >
			<div class="col-xs-12" ng-repeat="msg in chatCtrl.currentChat.messages | orderBy: (chatCtrl.parseDate(msg.date.sec*1000)): true">
				<div class="col-sm-2">
					<a ng-href="{{msg.person_info.url}}">
						<img class="avatar-logued-user" ng-src="{{ msg.person_info.photo}}">
					</a>
				</div>
				<div class="col-sm-10">
					<span class="row red-text" ng-bind="msg.person_info.name"></span>
					<span class="row" ng-bind="msg.text"></span>
				</div>
			</div>
			<div class="col-xs-12">
				<input class="col-xs-8"  type="text" ng-model="chatCtrl.newMsg">
				<button class="btn btn-small btn-red" ng-click="chatCtrl.sendMsg()" translate="chat.SEND"></button>
			</div>
		</div>
	</div>
</div>