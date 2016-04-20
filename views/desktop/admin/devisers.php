<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use app\models\Person;
use yii\grid\GridView;
use app\assets\desktop\admin\DevisersAsset;

/* @var $this yii\web\View */
/* @var $countries ArrayObject */
/* @var $devisers yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Devisers',
	'url' => ['/admin/devisers']
];

DevisersAsset::register($this);

$this->title = 'Todevise / Admin / Devisers';
?>

<div class="row no-gutter" ng-controller="devisersCtrl" ng-init="init()">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _DEVISER = " . Json::encode(Person::DEVISER) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _countries = " . Json::encode($countries) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Devisers"); ?></h2>
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
				'id' => 'devisers_list',
				'dataProvider' => $devisers,
				'options' => [
					'class' => 'funiv fc-fff fs1-071',
				],
				'columns' => [
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{update} {delete}",
						'buttons' => [
							'update' => function($url, $model, $key) {
								$url = Url::to(["deviser/edit-info", "slug" => $model->slug]);
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
						],
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
					],
					[
						'value' => function($model) use ($countries_lookup){
							return $countries_lookup[$model->personal_info["country"]];
						},
						'header' => Html::tag("div", Yii::t("app/admin", "Country")),
					]
				]
			]);
		?>

	</div>
</div>

<script type="text/ng-template" id="template/modal/tag/create_new.html">
	<form novalidate name="form" class="popup-new-deviser">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create new deviser"); ?></h3>
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

			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Select country"); ?></label>

			<div
				angular-multi-select
				input-model="data.countries"
				output-model="selected_country"

				checked-property="checked"

				dropdown-label="<[ '<(country_name[&quot;{{ data.lang }}&quot;])>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/public', 'Select country') ?>']>"
				leaf-label="<[ country_name['{{ data.lang }}'] ]>"

				max-checked-leafs="1"
				hide-helpers="check_all, check_none, reset"
			></div>

			<div class="alert alert-danger funiv fs0-929" role="alert" ng-show="form.$submitted && !form.$valid && selected_country.length === 0">
				<span><?= Yii::t("app/admin", "Required!"); ?></span>
			</div>

			<br />

			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Slug"); ?></label>
			<div class="input-group">
				<input id="slug" required="" ng-pattern="/^[0-9a-zA-Z\-]+?$/" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Slug..."); ?>" aria-describedby="basic-addon-slug" ng-model="slug" name="slug">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-slug" ng-show="form.$submitted && !form.$valid && !form['slug'].$valid">
					<span ng-show="form['slug'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					<span ng-show="form['slug'].$error.pattern"><?= Yii::t("app/admin", "Invalid!"); ?></span>
				</span>
			</div>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
