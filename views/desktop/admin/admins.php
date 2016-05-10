<?php
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

AdminsAsset::register($this);

$this->title = 'Todevise / Admin / Admins';
?>

<div class="row no-gutter" ng-controller="adminsCtrl" ng-init="init()">
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
					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="create_new()"><?= Yii::t("app/admin", "Create new"); ?></button>
				</div>
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'admins_list',
				'dataProvider' => $admins,
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
						'template' => "{create} {delete}",
						'buttons' => [
							'create' => function($url, $model, $key) {
								$url = Url::to(["admin/admin", "short_id" => $model->short_id]);
								return Html::a('<span class="glyphicon glyphicon-user fc-fff fs1"></span><span class="glyphicon glyphicon-log-in fc-fff fs0-857"></span>', $url);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-fff fs1",
									"ng-click" => "delete('$model->short_id')"
								]);
							}
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
							return $model->personal_info["name"] . " " . join($model->personal_info["surnames"], " ");
						},
						'label' => Yii::t("app/admin", "Name")
					],
					[
						'value' => function($model){
							return $model->credentials["email"];
						},
						'label' => Yii::t("app/admin", "Email")
					]
				]
			]);
		?>

	</div>
</div>

<script type="text/ng-template" id="template/modal/tag/create_new.html">
	<form novalidate name="form" class="popup-new-admin">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create new admin"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Name"); ?></label>
			<div class="input-group">
				<input id="name" required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Name..."); ?>" aria-describedby="basic-addon-name" ng-model="name" name="name">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-name" ng-show="form.$submitted && !form.$valid && !form['name'].$valid">
					<span ng-show="form['name'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

			<br />

			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Surnames"); ?></label>
			<div class="row">
				<div class="col-xs-4" ng-repeat="surname in surnames track by $index">
					<input id="surname_{{ $index }}" required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", 'Surname...'); ?>" aria-describedby="basic-addon-surname_{{ $index }}" ng-model="surname.value" name="surname_{{ $index }}">
					<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-surname_{{ $index }}" ng-show="form.$submitted && !form.$valid && !form['surname_{{ $index }}'].$valid">
						<span ng-show="form['surname_{{ $index }}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					</span>
				</div>
			</div>

			<span class="pointer fc-9013fe funiv fs0-786 fs-upper" ng-click="surnames.push({value: ''})"><?= Yii::t("app/admin", "Add surname +"); ?></span>
			<br>
			<span class="pointer fc-f7284b funiv fs0-786 fs-upper" ng-show="surnames.length > 0" ng-click="surnames.pop()"><?= Yii::t("app/admin", "Remove surname -"); ?></span>

			<br />
			<br />
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Email"); ?></label>
			<div class="input-group">
				<input id="email" type="email" required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Email..."); ?>" aria-describedby="basic-addon-email" ng-model="email" name="email">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-email" ng-show="form.$submitted && !form.$valid && !form['email'].$valid">
					<span ng-show="form['email'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					<span ng-show="form['email'].$error.email"><?= Yii::t("app/admin", "Invalid!"); ?></span>
				</span>
			</div>

			<br />

			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Password"); ?></label>
			<div class="input-group">
				<input id="password" required="" type="password" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Password"); ?>" aria-describedby="basic-addon-password" ng-model="password" name="password">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-password" ng-show="form.$submitted && !form.$valid && !form['password'].$valid">
					<span ng-show="form['password'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					<span ng-show="form['password'].$error.pattern"><?= Yii::t("app/admin", "Invalid!"); ?></span>
				</span>
			</div>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
