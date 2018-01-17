<?php

use app\assets\desktop\admin\InvitationsAsset;
use app\models\Invitation;
use app\models\Person;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $admins yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Admins',
	'url' => ['/admin/admins']
];

InvitationsAsset::register($this);

$this->title = 'Todevise / Admin / Mandill scheduled emails';

/** @var Invitation $model */
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _ADMIN = " . Json::encode(Person::ADMIN) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Scheduled emails in Mandrill"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">

				</div>
				<!--				<div class="col-xs-4 col-height col-middle">-->
				<!--					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="editInvitationCtrl.create_new()">--><?//= Yii::t("app/admin", "Create new"); ?><!--</button>-->
				<!--				</div>-->
			</div>
		</div>

		<?php
		echo GridView::widget([
			'id' => 'admins_list',
			'dataProvider' => $emails,
			'options' => [
				'class' => 'funiv fc-fff fs1-071',
			],
			'pager' => [
				'options' => [
					'class' => 'pagination flex flex-justify-center'
				]
			],
			'columns' => [
				'_id',
				'send_at',
				'from_email',
				'to',
				'subject',
			]
		]);
		?>

	</div>
</div>
