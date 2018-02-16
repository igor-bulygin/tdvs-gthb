<?php

use app\assets\desktop\chat\GlobalAsset;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'CHAT_TITLE');

$this->params['person'] = $person;
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD);

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
					<span class="row" ng-bind="chat.preview.title"></span>
					<span class="row" ng-bind="chat.preview.text"></span>
				</div>
			</uib-tab>
		</uib-tabset>
	</div>
	<div class="col-lg-8">
		<span translate="chat.SELECT_CHAT"></span>
	</div>
</div>