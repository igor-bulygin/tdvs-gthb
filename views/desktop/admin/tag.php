<?php
use yii\web\View;
use yii\helpers\Json;
use app\assets\desktop\admin\TagAsset;

/* @var $this yii\web\View */
/* @var $categories ArrayObject */
/* @var $tags yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Tag',
	'url' => ['/admin/tag']
];

TagAsset::register($this);

$this->title = 'Todevise / Admin / Tag';
?>

<div class="row" ng-controller="tagCtrl" ng-init="init()">
	<div class="col-sm-12 col-md-12 col-lg-12">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _tag = " . Json::encode($tag) . ";", View::POS_HEAD) ?>

		<div class="row">
			<div class="row-same-height">
				<div class="col-md-4 col-lg-4 col-height col-middle">
					<h4><?= Yii::t("app/admin", "Edit tag"); ?></h4>
				</div>
				<div class="col-md-8 col-lg-8 col-height col-middle">
					<div class="pull-right">
						<button class="btn btn-default" ng-click="cancel()">Cancel changes</button>
						<button class="btn btn-default" ng-click="save()">Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4 col-lg-4">
				<label><?php echo Yii::t("app/admin", "Tag name"); ?></label>
				<input type="text" class="form-control" placeholder="" ng-model="tag.name[lang]">
			</div>
			<div class="col-md-4 col-lg-4">
				<label><?php echo Yii::t("app/admin", "Description"); ?></label>
				<input type="text" class="form-control" placeholder="" ng-model="tag.description[lang]">
			</div>
			<div class="col-md-4 col-lg-4">
				<div class="pull-right">
					<label><?php echo Yii::t("app/admin", "Categories"); ?></label>
					<div
						angular-multi-select
						input-model="categories"
						output-model="selectedCategories"

						group-property="sub"
						tick-property="check"

						item-label="{{ name[lang] }}"
						selection-mode="multi"
						search-property="name[lang]"
						min-search-length="3"
						hidden-property="hidden"
						helper-elements="noall nonone noreset nofilter">
					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-md-3 col-lg-3">
				<div class="checkbox">
					<label>
						<input type="checkbox"> Enabled
					</label>
				</div>
			</div>
			<div class="col-md-3 col-lg-3">
				<div class="checkbox">
					<label>
						<input type="checkbox"> Optional
					</label>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">

				<div class="row">

					<div class="row-same-height">
						<div class="col-md-6 col-lg-6 col-height col-middle">
							What kind of tag is it?
						</div>
						<div class="col-md-3 col-lg-3 col-height col-middle">
							<div class="radio">
								<label>
									<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
									With options
								</label>
							</div>
						</div>
						<div class="col-md-3 col-lg-3 col-height col-middle">
							<div class="radio">
								<label>
									<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
									Free field
								</label>
							</div>
						</div>
					</div>

				</div>


			</div>
		</div>

	</div>
</div>