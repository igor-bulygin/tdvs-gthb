<?php

use app\assets\desktop\chat\GlobalAsset;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */
/** @var Person $personToChat */
/** @var string $chatId */

if ($personToChat) {
	$this->title = Yii::t('app/public', 'CHAT_CONVERSATION_TITLE', ['person_name' => $personToChat->getName()]);
} else {
	$this->title = Yii::t('app/public', 'CHAT_TITLE');
}

$this->params['person'] = $person;
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD);
$this->registerJs('var person_to_chat = ' .Json::encode($personToChat), yii\web\View::POS_HEAD);
$this->registerJs('var chat_id = ' .Json::encode($chatId), yii\web\View::POS_HEAD);

?>

<div class="our-devisers-wrapper" ng-controller="chatCtrl as chatCtrl">
	<div class="container">
		<div class="our-devisers-body chat-body" ng-if="!chatCtrl.loading" ng-cloak>
			<div id="chat-chats" class="col-xs-12 col-sm-4">
				<uib-tabset active="chatCtrl.active">
					<uib-tab index="$index" ng-repeat="tab in chatCtrl.tabs" heading="{{tab.title}}" ng-click="chatCtrl.changeChatFilter(tab.id)" class="col-xs-3">
						<div ng-if="chatCtrl.chats.length<1" class="text-center" style="padding:50px;">
							<h4 class="row" translate="chat.NO_CHATS"></h4>
							<p class="row" translate="chat.CLICK_MSG"></p>
						</div>
						<ul>
							<li ng-repeat="chat in chatCtrl.chats" ng-class="chatCtrl.activeChat(chat)">
								<a ng-click="chatCtrl.selectChat(chat)" class="col-xs-12">
									<span class="col-xs-12">
										<div class="col-sm-2">
											<img class="avatar-logued-user" ng-src="{{ chatCtrl.parseImage(chat.preview.image)}}">
										</div>
										<div class="col-sm-10">
											<span class="col-xs-12 red-text chat-tit" ng-bind="chat.preview.title"></span>
											<span class="col-xs-12 chat-text" ng-bind="chat.preview.text"></span>
										</div>
									</span>
								</a>
							</li>
						</ul>
					</uib-tab>
				</uib-tabset>
			</div>
			<div id="chat-messages" class="col-xs-12 col-sm-8" ng-if="!chatCtrl.loadingChat">
				<div ng-if="!chatCtrl.currentChat" class="text-center" style="padding:50px;">
					<span translate="chat.SELECT_CHAT"></span>
				</div>
				<div class="chat-header">chat</div>
				<div ng-if="chatCtrl.currentChat" >
					<div class="col-xs-12" ng-repeat="msg in chatCtrl.currentChat.messages | orderBy: (chatCtrl.parseDate(msg.date.sec*1000))">
						<div ng-class="chatCtrl.msgOwner(msg)">
							<div class="col-sm-2">
								<a ng-href="{{msg.person_info.main_link}}">
									<img class="avatar-logued-user" ng-src="{{ msg.person_info.profile_image}}">
								</a>
							</div>
							<div class="col-sm-10">
								<span class="col-xs-12 red-text chat-tit" ng-bind="msg.person_info.name" ng-if="msg.showOwner"></span>
								<span class="col-xs-12 chat-text" ng-bind="msg.text"></span>
								<span class="col-xs-8 text-right chat-time">
									<span ng-cloak>{{chatCtrl.parseDate(msg.date.sec*1000) | date:'dd/MM/yy hh:mm'}}</span>
								</span>
							</div>
						</div>
					</div>
					<form class="col-xs-12 chat-send">
						<div class="col-xs-8">
							<input class="col-xs-12" type="text" ng-model="chatCtrl.newMsg" on-press-enter="chatCtrl.sendMsg()">
						</div>
						<div class="col-xs-4">
							<button class="col-xs-12 btn btn-small btn-red" ng-click="chatCtrl.sendMsg()" translate="chat.SEND"></button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-lg-8 mt-40 tdvs-loading" ng-if="chatCtrl.loadingChat" style="padding: 20px;" ng-cloak>
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				<span class="sr-only" translate="global.LOADING"></span>
			</div>
		</div>
	</div>
	<div class="mt-40 tdvs-loading" ng-if="chatCtrl.loading" style="padding: 20px;" ng-cloak>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<span class="sr-only" translate="global.LOADING"></span>
	</div>
</div>