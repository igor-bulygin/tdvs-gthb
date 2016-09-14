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
				<div class="col-xs-4 col-height col-middle">
					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="editInvitationCtrl.create_new()"><?= Yii::t("app/admin", "Create new"); ?></button>
				</div>
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'admins_list',
				'dataProvider' => $invitations,
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
						'template' => "{view} {delete}",
						'buttons' => [
							'send' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-send fc-fff fs1",
									"ng-click" => "editInvitationCtrl.send('$model->uuid')"
								]);
							},

							'view' => function($url, $model, $key) {
								$emailId = $model->getPostmanEmail()->uuid;
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-envelope fc-fff fs1",
									"ng-click" => "editInvitationCtrl.view('$emailId')",
								]);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-fff fs1",
									"ng-click" => "editInvitationCtrl.delete('$model->uuid')"
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
						'label' => Yii::t("app/admin", "id")
					],
					[
						'value' => function($model){
							return $model->email;
						},
						'label' => Yii::t("app/admin", "Email")
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

<script type="text/ng-template" id="template/modal/tag/create_new.html">
	<form novalidate name="create_newCtrl.form" class="popup-new-admin">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create new invitation"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Email"); ?></label>
			<div class="input-group">
				<input id="email" type="email" required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Email..."); ?>" aria-describedby="basic-addon-email" ng-model="create_newCtrl.email" name="email">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-email" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['email'].$valid">
					<span ng-show="create_newCtrl.form['email'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					<span ng-show="create_newCtrl.form['email'].$error.email"><?= Yii::t("app/admin", "Invalid!"); ?></span>
				</span>
			</div>

			<br />

			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Message"); ?></label>
			<div class="input-group">
				<input id="message" required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Message"); ?>" ng-model="create_newCtrl.message" name="message">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-password" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['message'].$valid">
					<span ng-show="create_newCtrl.form['message'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					<span ng-show="create_newCtrl.form['message'].$error.pattern"><?= Yii::t("app/admin", "Invalid!"); ?></span>
				</span>
			</div>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='create_newCtrl.form.$submitted = true; create_newCtrl.form.$valid && create_newCtrl.ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='create_newCtrl.cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>