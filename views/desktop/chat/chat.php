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
					<h4 class="row">No available chats</h2>
					<p class="row">Go to any user’s profile and click the MESSAGE button to initiate a conversation</p>
				</div>
			</uib-tab>
		</uib-tabset>
	</div>
	<div class="col-lg-8">
		Please select a chat on left
	</div>
</div>