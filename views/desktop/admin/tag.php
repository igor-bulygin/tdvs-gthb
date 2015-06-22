<?php
use yii\web\View;
use app\models\Tag;
use yii\helpers\Json;
use app\models\TagOption;
use app\models\MetricUnit;
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

<div class="row no-gutter" ng-controller="tagCtrl" ng-init="init()">
	<div class="col-sm-12 col-md-12 col-lg-12">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _tag = " . Json::encode($tag) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _mus = " . Json::encode([
				["value" => MetricUnit::NONE, "text" => "None", "checked" => true],
				["value" => MetricUnit::SIZE, "text" => "Size"],
				["value" => MetricUnit::WEIGHT, "text" => "Weight"]
			]) . ";", View::POS_HEAD); ?>

		<div class="row">
			<div class="row-same-height">
				<div class="col-md-4 col-lg-4 col-height col-middle">
					<h4><?= Yii::t("app/admin", "Edit tag"); ?></h4>
				</div>
				<div class="col-md-8 col-lg-8 col-height col-middle">
					<div class="pull-right">
						<button class="btn btn-danger" ng-click="cancel()"><?php echo Yii::t("app/admin", "Cancel changes"); ?></button>
						<button class="btn btn-success" ng-click="save()"><?php echo Yii::t("app/admin", "Save changes"); ?></button>
					</div>
				</div>
			</div>
		</div>

		<br />

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
						api="api"
						id-property="short_id"
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

		<br />

		<div class="row">
			<div class="col-md-3 col-lg-3">
				<div class="checkbox">
					<label>
						<input type="checkbox" ng-model="tag.enabled" ng-checked="tag.enabled"> <?php echo Yii::t("app/admin", "Enabled"); ?>
					</label>
				</div>
			</div>
			<div class="col-md-3 col-lg-3">
				<div class="checkbox">
					<label ng-init="optional = !tag.required">
						<input type="checkbox" ng-model="optional" ng-change="tag.required = !optional"> <?php echo Yii::t("app/admin", "Optional"); ?>
					</label>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="row">
					<div class="row-same-height">
						<div class="col-md-6 col-lg-6 col-height col-middle">
							<?php echo Yii::t("app/admin", "What kind of tag is it?"); ?>
							<span class="glyphicon glyphicon-info-sign"></span>
						</div>
						<div class="col-md-6 col-lg-6 col-height col-middle">
							<div class="radio flex-justify-center">
								<label>
									<input type="radio" name="tagType" id="tagType1" value="<?php echo Tag::DROPDOWN; ?>" ng-model="tag.type">
									<?php echo Yii::t("app/admin", "With options"); ?>
								</label>
								<label>
									<input type="radio" name="tagType" id="tagType2" value="<?php echo Tag::FREETEXT; ?>" ng-model="tag.type">
									<?php echo Yii::t("app/admin", "Free field"); ?>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<br />

		<div class="ng-cloak" ng-show="tag.type == <?php echo Tag::DROPDOWN; ?> && pending_dialog_type === false">
			<div class="row">
				<div class="row-same-height">
					<div class="col-md-4 col-lg-4 col-height col-middle">
						<label><?php echo Yii::t("app/admin", "Options"); ?></label>
					</div>

					<div class="col-md-6 col-lg-6 col-height col-middle">
						<?php echo Yii::t("app/admin", "Does this tag allow combos?"); ?>
						<span class="glyphicon glyphicon-info-sign"></span>
						<?php echo Yii::t("app/admin", "Please specify how many options can be combined"); ?>

						<div class="input-group spinner">
							<input type="text" class="form-control" ng-model="tag.n_options">
							<div class="input-group-btn-vertical">
								<button class="btn btn-default btn-xs" type="button" ng-click="tag.n_options = tag.n_options + 1"><span class="glyphicon glyphicon-triangle-top"></span></button>
								<button class="btn btn-default btn-xs" type="button" ng-click="tag.n_options = tag.n_options - 1"><span class="glyphicon glyphicon-triangle-bottom"></span></button>
							</div>
						</div>
					</div>

					<div class="col-md-2 col-lg-2 col-height col-middle">
						<button class="btn btn-success" ng-click="edit_dropdown_option(-1)"><?php echo Yii::t("app/admin", "Create new"); ?></button>
					</div>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="row-same-height sortable-container" sv-root sv-part="tag.options">
					<div ng-repeat="option in tag.options" sv-element class="col-md-3 col-lg-3 col-height col-middle">
						<span class="glyphicon glyphicon-menu-hamburger pointer" sv-handle></span>
						<span>{{ option.text[lang] }}</span>
						<div class="pull-right">
							<span class="glyphicon glyphicon-pencil pointer" ng-click="edit_dropdown_option($index)"></span>
							<span class="glyphicon glyphicon-trash pointer" ng-click="delete_option($index)"></span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="ng-cloak" ng-show="tag.type == <?php echo Tag::FREETEXT; ?> && pending_dialog_type === false">
			<div class="row">
				<div class="row-same-height" ng-init="new_option = {}">
					<div class="col-md-4 col-lg-4 col-height col-middle">
						<label><?php echo Yii::t("app/admin", "Options"); ?></label>
					</div>

					<div class="col-md-3 col-lg-3 col-height col-middle">
						<span><?php echo Yii::t("app/admin", "Metric unit"); ?></span>
						<div
							angular-multi-select
							input-model="mus"
							output-model="selected_mu"
							tick-property="checked"
							item-label="{{ text }}"
							selection-mode="single"
							button-template="angular-multi-select-btn-data.htm"
							button-label="{{ text }}"
							helper-elements="noall nonone noreset nofilter">
						</div>
					</div>

					<div class="col-md-3 col-lg-3 col-height col-middle">
						<div class="radio flex-justify-center" ng-init="freetext_type = <?php echo TagOption::NUMERIC; ?>">
							<label>
								<input type="radio" name="optionsType" id="optionsType1" value="<?php echo TagOption::NUMERIC; ?>" ng-model="freetext_type">
								<?php echo Yii::t("app/admin", "Numeric"); ?>
							</label>
							<label>
								<input type="radio" name="optionsType" id="optionsType2" value="<?php echo TagOption::ALPHANUMERIC; ?>" ng-model="freetext_type">
								<?php echo Yii::t("app/admin", "Alphanumeric"); ?>
							</label>
						</div>
					</div>

					<div class="col-md-2 col-lg-2 col-height col-middle">
						<button class="btn btn-success" ng-click="create_freetext_option()"><?php echo Yii::t("app/admin", "Create new"); ?></button>
					</div>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="row-same-height" ng-repeat="option in tag.options">
					<div class="col-md-11 col-lg-11 col-height col-middle">
						<input type="text" class="form-control" placeholder="<?php echo Yii::t("app/admin", "Field name (optional)"); ?>" ng-model="tag.options[$index].text[lang]">
					</div>
					<div class="col-md-1 col-lg-1 col-height col-middle text-center">
						<span class="glyphicon glyphicon-trash pointer" ng-click="delete_option($index)"></span>
						<!-- TODO: Show the metric unit and the type? -->
					</div>
				</div>
			</div>
			</div>
		</div>

	</div>
</div>

<script type="text/ng-template" id="template/modal/tag/create_new.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?php echo Yii::t("app/admin", "Create new option"); ?></h3>
		</div>
		<div class='modal-body'>
			<label><?php echo Yii::t("app/admin", "Title"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in data.langs">
				<span class="input-group-addon" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control" placeholder="<?php echo Yii::t("app/admin", "Option name..."); ?>"
				       aria-describedby="basic-addon-{{ $index }}" ng-model="data.option.text[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger" ng-show="form.$submitted && !form.$valid && !form['{{lang_k}}'].$valid">
					<span ng-show="form['{{lang_k}}'].$error.required"><?php echo Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>
			<br />

			<label><?php echo Yii::t("app/admin", "Value"); ?></label>
			<div class="input-group">
				<input id="value" required="" ui-validate="'(data.options | filter:{value:$value}:true).length == 0'"
				       ui-validate-watch="'data.option.value'" type="text" class="form-control" placeholder="<?php echo Yii::t("app/admin", "Value..."); ?>"
				       aria-describedby="basic-addon-desc" ng-model="data.option.value" name="value">
				<span class="input-group-addon alert-danger" id="basic-addon-value" ng-show="form.$submitted && !form.$valid && !form['value'].$valid">
					<span ng-show="form['value'].$error.required"><?php echo Yii::t("app/admin", "Required!"); ?></span>
					<span ng-show="!form['value'].$error.required && form['value'].$error.validator"><?php echo Yii::t("app/admin", "Duplicated value!"); ?></span>
				</span>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?php echo Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?php echo Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>