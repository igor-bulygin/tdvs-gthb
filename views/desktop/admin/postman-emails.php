<?php
use app\assets\desktop\admin\InvitationsAsset;
use app\models\Invitation;
use yii\web\View;
use app\models\Lang;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use app\models\Person;
use yii\grid\GridView;
use app\assets\desktop\admin\AdminsAsset;

/* @var $this yii\web\View */
/* @var $admins yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Admins',
	'url' => ['/admin/admins']
];

InvitationsAsset::register($this);

$this->title = 'Todevise / Admin / Invitations';

/** @var Invitation $model */
?>

<div class="row no-gutter" ng-controller="editInvitationCtrl as editInvitationCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _ADMIN = " . Json::encode(Person::ADMIN) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Admins"); ?></h2>
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
							'view' => function($url, $model, $key) {
								$emailId = $model->uuid;
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-envelope fc-fff fs1",
									"ng-click" => "editInvitationCtrl.view('$emailId')",
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
					[
						'value' => function($model){
							return $model->uuid;
						},
						'label' => Yii::t("app/admin", "Id")
					],
					[
						'value' => function($model){
							return $model->to_email;
						},
						'label' => Yii::t("app/admin", "Email")
					],
					[
						'value' => function($model){
							return $model->subject;
						},
						'label' => Yii::t("app/admin", "Subject")
					],
					[
						'value' => function($model){
							return $model->created_at->toDateTime()->format('Y-m-d H:i:s');
						},
						'label' => Yii::t("app/admin", "Date")
					],
				]
			]);
		?>

	</div>
</div>
