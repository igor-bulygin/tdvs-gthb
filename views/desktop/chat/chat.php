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
		
		<div class="our-devisers-body chat-body mt-20 mb-20" >
			<div class="hidden-sm hidden-md hidden-lg mt-20">
				<div class="mt-20 mb-20" >
				<div class="mt-40" ng-if="chatCtrl.loading" style="padding: 20px;" ng-cloak>
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				</div>
					<div ng-if="!chatCtrl.loading" ng-cloak>
						<div class="col-xs-12 col-sm-4 pl-15" ng-if="chatCtrl.currentChat">
							<a class="col-xs-4 btn btn-small btn-red auto-center" role="button" ng-click="chatCtrl.unselectChat()"><span translate="chat.BACK"></span></a>
						</div>
						<div class="col-xs-12 col-sm-4" ng-if="!chatCtrl.currentChat">
							<uib-tabset active="chatCtrl.active">
								<uib-tab index="$index" ng-repeat="tab in chatCtrl.tabs" heading="{{tab.title}}" ng-click="chatCtrl.changeChatFilter(tab.id)" class="col-xs-3">
									<div ng-if="chatCtrl.chats.length<1" class="text-center" style="padding:50px;">
										<h4 class="row" translate="chat.NO_CHATS"></h4>
										<p class="row" translate="chat.CLICK_MSG"></p>
									</div>
									<ul>
										<li ng-repeat="chat in chatCtrl.chats" ng-class="chatCtrl.activeChat(chat)">
											<a ng-click="chatCtrl.selectChat(chat)" class="col-xs-12" role="button">
												<span class="col-xs-12">
													<div class="col-xs-3 col-sm-2-5">
														<img class="avatar-logued-user" ng-src="{{ chatCtrl.parseImage(chat.preview.image)}}">
													</div>
													<div class="col-xs-9 col-sm-9-5">
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
					</div>
				</div>
			</div>
			<div id="chat-chats" class="col-xs-12 col-sm-4 hidden-xs">
			<div class="mt-40" ng-if="chatCtrl.loading" style="padding: 20px;" ng-cloak>
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
			</div>
				<uib-tabset active="chatCtrl.active" ng-if="!chatCtrl.loading" ng-cloak>
					<uib-tab index="$index" ng-repeat="tab in chatCtrl.tabs" heading="{{tab.title}}" ng-click="chatCtrl.changeChatFilter(tab.id)" class="col-xs-3">
						<div ng-if="chatCtrl.chats.length<1" class="text-center" style="padding:50px;">
							<h4 class="row" translate="chat.NO_CHATS"></h4>
							<p class="row" translate="chat.CLICK_MSG"></p>
						</div>
						<ul>
							<li ng-repeat="chat in chatCtrl.chats" ng-class="chatCtrl.activeChat(chat)">
								<a ng-click="chatCtrl.selectChat(chat)" class="col-xs-12" role="button">
									<span class="col-xs-12">
										<div class="col-xs-3 col-sm-2-5">
											<img class="avatar-logued-user" ng-src="{{ chatCtrl.parseImage(chat.preview.image)}}">
										</div>
										<div class="col-xs-9 col-sm-9-5">
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
				<div ng-if="chatCtrl.currentChat" >
					<div class="chat-header" >
						<span translate="chat.CHAT_WITH" translate-values="{ personToChat: chatCtrl.personToChat.name }"></span>
					</div>
					<div class="chat-message-chat">
						<div class="col-xs-12" ng-repeat="msg in chatCtrl.currentChat.messages | orderBy: (chatCtrl.parseDate(msg.date.sec*1000))">
							<div ng-class="chatCtrl.msgOwner(msg)">
								<div class="col-xs-3 col-sm-2">
									<a ng-href="{{msg.person_info.main_link}}" ng-if="msg.showOwner" ng-cloak>
										<img class="avatar-logued-user" ng-src="{{ msg.person_info.profile_image}}" >
									</a>
								</div>
								<div class="col-xs-9 col-sm-10">
									<span class="col-xs-12 red-text chat-tit" ng-bind="msg.person_info.name" ng-if="msg.showOwner"></span>
									<span class="col-xs-12 chat-text" ng-bind="msg.text"></span>
									<span class="col-xs-12 text-right chat-time">
										<span am-time-ago="chatCtrl.parseDate(msg.date.sec*1000)"></span>
									</span>
								</div>
								<div ng-if="msg.isLast" id="bottomChat" ng-cloak style="margin-top:100px;"></div>
							</div>
						</div>
					</div>
					<form class="col-xs-12 chat-send" >
						<div class="col-xs-7 col-sm-8" style="margin: 7.5px 0px;">
							<input class="col-xs-12" type="text" ng-model="chatCtrl.newMsg" on-press-enter="chatCtrl.sendMsg()">
						</div>
						<div class="col-xs-5 col-sm-4" style="margin: 7.5px 0px;">
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
</div>
