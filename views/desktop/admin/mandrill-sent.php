<?php

use app\assets\desktop\admin\InvitationsAsset;
use app\models\Invitation;
use app\models\Person;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $admins yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Mandrill (sent)',
	'url' => ['/admin/mandrill-sent']
];

InvitationsAsset::register($this);

$this->title = 'Todevise / Admin / Mandill sent emails';

/** @var Invitation $model */
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _ADMIN = " . Json::encode(Person::ADMIN) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Scheduled sent in Mandrill (last 7 days)"); ?></h2>
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
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{view}",
						'buttons' => [
							'view' => function($url, $item, $key) {
								return Html::a('', '/admin/mandrill-content/'.$item['_id'], [
									'target' => '_blank',
									"class" => "pointer glyphicon glyphicon-envelope fc-fff fs1",
								]);
							},
						],
						'header' => Html::tag("div", Yii::t("app/admin", "Actions")),

						'headerOptions' => [
							'class' => 'text-center'
						],
						'contentOptions' => [
							'class' => 'text-center'
						]
					],
					'_id',
					[
						'label' => 'date',
						'value' => function($item) {
							return gmdate('Y-m-d H:i:s', $item['ts']);
						}
					],
					'sender',
					'template',
					'subject',
					'email',
					'opens',
					'clicks',
				]
			]);
		?>

	</div>
</div>
