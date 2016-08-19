<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\Utils;
use yii\grid\GridView;
use app\assets\desktop\admin\TagsAsset;

/* @var $this yii\web\View */
/* @var $categories ArrayObject */
/* @var $tags yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Tags',
	'url' => ['/admin/tags']
];

TagsAsset::register($this);

$this->title = 'Todevise / Admin / Tags';
?>

<div class="row no-gutter" ng-controller="tagsCtrl as tagsCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD) ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Tag list"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">
					<div class="funiv fs-upper fc-9b fs0-786 flex-inline"><?= Yii::t("app/admin", "Filter by category"); ?></div>
					<div
						angular-multi-select
						input-model="tagsCtrl.categories"
						output-model="tagsCtrl.selectedCategories"

						name="categories"
						id-property="short_id"
						checked-property="check"
						children-property="sub"

						dropdown-label="<[ '<(name[&quot;{{ tagsCtrl.lang }}&quot;])>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/admin', 'Select category') ?>']>"
						node-label="<[ name['{{ tagsCtrl.lang }}'] ]>"
						leaf-label="<[ name['{{ tagsCtrl.lang }}'] ]>"

						max-checked-leafs="1"
						hide-helpers="check_all, check_none, reset"
					></div>
				</div>
				<div class="col-xs-4 col-height col-middle">
					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="tagsCtrl.create_new()"><?= Yii::t("app/admin", "Create new"); ?></button>
				</div>
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'tags_list',
				'dataProvider' => $tags,
				'options' => [
					'class' => 'funiv fc-fff fs1',
				],
				'columns' => [
					[
						'value' => function($model){
							return Utils::l($model->name);
						},
						'label' => Yii::t("app/admin", "Title")
					],
					[
						'value' => function($model){
							return Utils::l($model->description);
						},
						'label' => Yii::t("app/admin", "Description")
					],
					[
						'class' => 'yii\grid\CheckboxColumn',
						'checkboxOptions' => function($model){
							return [
								"checked" => $model->enabled,
								"ng-click" => "tagsCtrl.toggle_prop(\$event, '$model->short_id', 'enabled')"
							];
						},
						'header' => Html::tag("div", Yii::t("app/admin", "Enabled")),

						'headerOptions' => [
							'class' => 'text-center'
						],
						'contentOptions' => [
							'class' => 'text-center'
						],
					],
					[
						'class' => 'yii\grid\CheckboxColumn',
						'checkboxOptions' => function($model){
							return [
								"checked" => $model->required,
								"ng-click" => "tagsCtrl.toggle_prop(\$event, '$model->short_id', 'required')"
							];
						},
						'header' => Html::tag("div", Yii::t("app/admin", "Required")),

						'headerOptions' => [
							'class' => 'text-center'
						],
						'contentOptions' => [
							'class' => 'text-center'
						],
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{update} {delete}",
						'buttons' => [
							'update' => function($url, $model, $key) {
								$url = Url::to(["/admin/tag", "tag_id" => $model->short_id]);
								return Html::a('<span class="glyphicon glyphicon-pencil fc-fff fs1"></span>', $url);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-fff fs1",
									"ng-click" => "tagsCtrl.delete('$model->short_id')"
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
					]
				]
			]);
		?>

	</div>
</div>

<script type="text/ng-template" id="template/modal/tag/create_new.html"> 
	<form novalidate name="create_newCtrl.form" class="popup-tag">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create new tag"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Tag name"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in create_newCtrl.data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Tag name..."); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="create_newCtrl.langs[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['{{lang_k}}'].$valid">
					<span ng-show="create_newCtrl.form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

			<br />

			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Description"); ?></label>
			<div class="input-group">
				<input id="description" required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Description..."); ?>" aria-describedby="basic-addon-desc" ng-model="create_newCtrl.description" name="desc">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-desc" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['desc'].$valid">
					<span ng-show="create_newCtrl.form['desc'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='create_newCtrl.form.$submitted = true; create_newCtrl.form.$valid && create_newCtrl.ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='create_newCtrl.cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
