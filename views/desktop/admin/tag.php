<?php
use yii\web\View;
use app\models\Tag;
use yii\helpers\Json;
use app\models\TagOption;
use app\models\MetricType;
use app\assets\desktop\admin\TagAsset;

/* @var $this yii\web\View */
/* @var $categories ArrayObject */
/* @var $tag ArrayObject */

$this->params['breadcrumbs'][] = [
	'label' => 'Tag',
	'url' => ['/admin/tag']
];

TagAsset::register($this);

$this->title = 'Todevise / Admin / Tag';
?>

<div class="row no-gutter" ng-controller="tagCtrl" ng-init="init()">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _tag = " . Json::encode($tag) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _mus = " . Json::encode([
			["value" => MetricType::NONE, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::NONE]), "checked" => true],
			["value" => MetricType::SIZE, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE])],
			["value" => MetricType::WEIGHT, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT])]
		]) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-4 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Edit tag"); ?></h2>
				</div>
				<div class="col-xs-8 col-height col-middle">
					<div class="pull-right">
						<button class="btn btn-gray fc-fff funiv fs-upper fs0-786" ng-click="cancel()"><?php echo Yii::t("app/admin", "Cancel changes"); ?></button>
						<button class="btn btn-light-green fc-18 funiv fs-upper fs0-786" ng-click="save()"><?php echo Yii::t("app/admin", "Save changes"); ?></button>
					</div>
				</div>
			</div>
		</div>

		<div class="row no-gutter page-title-row flex flex-row">
			<div class="col-xs-3 tag flex-prop-1 margin-l0-r1">
				<label class="funiv fs-upper fs0-786 fc-c7 fnormal"><?php echo Yii::t("app/admin", "Tag name"); ?></label>
				<input type="text" class="form-control fc-fff funiv fs1" placeholder="" ng-model="tag.name[lang]">
			</div>
			<div class="col-xs-6 tag flex-prop-2-1 margin-l1-r1">
				<label class="funiv fs-upper fs0-786 fc-c7 fnormal"><?php echo Yii::t("app/admin", "Description"); ?></label>
				<input type="text" class="form-control fc-fff funiv fs1" placeholder="" ng-model="tag.description[lang]">
			</div>
			<div class="col-xs-3 tag flex-prop-1 margin-l1-r1">
				<label class="funiv fs-upper fs0-786 fc-c7 fnormal"><?php echo Yii::t("app/admin", "Categories"); ?></label><br>
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

		<br />

		<div class="row no-gutter">
			<div class="col-xs-3 div-bordered tag_settings">
				<div class="checkbox">
					<label class="funiv fs0-929 fc-c7">
						<input type="checkbox" ng-model="tag.enabled" ng-checked="tag.enabled"> <?php echo Yii::t("app/admin", "Enabled"); ?>
					</label>
				</div>
			</div>
			<div class="col-xs-3 div-bordered tag_settings">
				<div class="checkbox">
					<label class="funiv fs0-929 fc-c7" ng-init="optional = !tag.required">
						<input type="checkbox" ng-model="optional" ng-change="tag.required = !optional"> <?php echo Yii::t("app/admin", "Is this tag optional?"); ?>
					</label>
				</div>
			</div>
			<div class="col-xs-6 div-bordered tag_settings">
				<div class="row no-gutter">
					<div class="row-same-height">
						<div class="col-xs-6 col-height col-middle funiv fs0-929 fc-c7">
							<?php echo Yii::t("app/admin", "What kind of tag is it?"); ?>
							<span class="glyphicon glyphicon-info-sign"></span>
						</div>
						<div class="col-xs-6 col-height col-middle">
							<div class="radio flex flex-justify-around">
								<label class="funiv fs0-786 fc-c7 fs-upper">
									<input type="radio" name="tagType" id="tagType1" value="<?php echo Tag::DROPDOWN; ?>" ng-model="tag.type">
									<label for="tagType1"><span></span><?php echo Yii::t("app/admin", "With options"); ?></label>
								</label>
								<label class="funiv fs0-786  fc-c7 fs-upper">
									<input type="radio" name="tagType" id="tagType2" value="<?php echo Tag::FREETEXT; ?>" ng-model="tag.type">
									<label for="tagType2"><span></span><?php echo Yii::t("app/admin", "Free field"); ?></label>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<br />

		<div class="ng-cloak" ng-show="tag.type == <?php echo Tag::DROPDOWN; ?> && pending_dialog_type === false">
			<div class="row no-gutter page-title-row bgcolor-3d">
				<div class="row-same-height">
					<div class="col-xs-4 col-height col-middle">
						<label class="funiv fc-fff fs-upper fs0-929 fnormal"><?php echo Yii::t("app/admin", "Options"); ?></label>
					</div>

					<div class="col-xs-6 col-height col-middle">
						<label class="funiv fs0-929 fc-c7 fnormal"><?php echo Yii::t("app/admin", "Does this tag allow combos?"); ?></label>
						<span class="glyphicon glyphicon-info-sign fc-c7"></span>
						<label class="funiv fs0-929 fc-c7 fnormal"><?php echo Yii::t("app/admin", "Please specify how many options can be combined"); ?></label>

						<div class="input-group spinner">
							<input type="text" class="form-control" ng-model="tag.n_options">
							<div class="input-group-btn-vertical">
								<button class="btn btn-default btn-xs" type="button" ng-click="tag.n_options = tag.n_options + 1"><span class="glyphicon glyphicon-triangle-top"></span></button>
								<button class="btn btn-default btn-xs" type="button" ng-click="tag.n_options = tag.n_options - 1"><span class="glyphicon glyphicon-triangle-bottom"></span></button>
							</div>
						</div>
					</div>

					<div class="col-xs-2 col-height col-middle">
						<button class="btn btn-purple fc-fff funiv fs0-786 fs-upper" ng-click="edit_dropdown_option(-1)"><?php echo Yii::t("app/admin", "Create new"); ?></button>
					</div>
				</div>
			</div>

			<br />

			<div class="row no-gutter">
				<div class="sortable-container" sv-root sv-part="tag.options">
					<div ng-repeat="option in tag.options" sv-element class="col-xs-3 tag-checkbox-option-holder">
						<div class="flex flex-align-stretch">
							<div class="arrow-left"></div>

							<div class="flex flex-align-center controls-holder flex-prop-1">
								<span class="glyphicon glyphicon-menu-hamburger pointer fc-68  flex-prop" sv-handle></span>
								<span class="funiv fs1 fc-fff flex-prop">{{ option.text[lang] }}</span>
								<div class="flex flex-justify-end flex-prop-1">
									<span class="glyphicon glyphicon-pencil pointer fc-68" ng-click="edit_dropdown_option($index)"></span>
									<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="delete_option($index)"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="ng-cloak" ng-show="tag.type == <?php echo Tag::FREETEXT; ?> && pending_dialog_type === false">
			<div class="row no-gutter bgcolor-3d page-title-row">
				<div class="row-same-height" ng-init="new_option = {}">
					<div class="col-xs-4 col-height col-middle">
						<label class="funiv fc-fff fs-upper fs0-929 fnormal"><?php echo Yii::t("app/admin", "Options"); ?></label>
					</div>

					<div class="col-xs-3 col-height col-middle">
						<span class="funiv fc-c7 fs-upper fs0-786"><?php echo Yii::t("app/admin", "Metric type"); ?></span>
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

					<div class="col-xs-3 col-height col-middle">
						<div class="radio flex flex-justify-around" ng-init="freetext_type = <?php echo TagOption::NUMERIC; ?>">
							<label class="funiv fc-c7 fs-upper fs0-786">
								<input type="radio" name="optionsType" id="optionsType1" value="<?php echo TagOption::NUMERIC; ?>" ng-model="freetext_type">
								<label for="optionsType1"><span></span><?php echo Yii::t("app/admin", "Numeric"); ?></label>
							</label>
							<label class="funiv fc-c7 fs-upper fs0-786">
								<input type="radio" name="optionsType" id="optionsType2" value="<?php echo TagOption::ALPHANUMERIC; ?>" ng-model="freetext_type">
								<label for="optionsType2"><span></span><?php echo Yii::t("app/admin", "Alphanumeric"); ?></label>
							</label>
						</div>
					</div>

					<div class="col-xs-2 col-height col-middle">
						<button class="btn btn-purple fc-fff funiv fs0-786 fs-upper" ng-click="create_freetext_option()"><?php echo Yii::t("app/admin", "Create new"); ?></button>
					</div>
				</div>
			</div>

			<div class="row no-gutter">
				<div class="row-same-height row-free-field" ng-repeat="option in tag.options">
					<div class="col-xs-10 col-height col-middle tag">
						<input type="text" class="form-control funiv fc-e8 fs1" placeholder="<?php echo Yii::t("app/admin", "Field name (optional)"); ?>" ng-model="tag.options[$index].text[lang]">
					</div>
					<div class="col-xs-1 col-height col-middle text-center funiv fc-e8">
						<span>{{ get_mu_type(tag.options[$index].metric_type) }}</span>
					</div>
					<div class="col-xs-1 col-height col-middle text-center">
						<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="delete_option($index)"></span>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/ng-template" id="template/modal/tag/create_new.html">
	<form novalidate name="form" class="popup-tag">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?php echo Yii::t("app/admin", "Create new option"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class='modal-title funiv fs1 fnormal fc-18'><?php echo Yii::t("app/admin", "Title"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?php echo Yii::t("app/admin", "Option name..."); ?>"
					aria-describedby="basic-addon-{{ $index }}" ng-model="data.option.text[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="form.$submitted && !form.$valid && !form['{{lang_k}}'].$valid">
					<span ng-show="form['{{lang_k}}'].$error.required"><?php echo Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

			<br />

			<label class='modal-title funiv fs1 fnormal fc-18'><?php echo Yii::t("app/admin", "Value"); ?></label>
			<div class="input-group">
				<input id="value" required="" ui-validate="'(data.options | filter:{value:$value}:true).length == 0'"
					ui-validate-watch="'data.option.value'" type="text" class="form-control funiv fs1" placeholder="<?php echo Yii::t("app/admin", "Value..."); ?>"
					aria-describedby="basic-addon-desc" ng-model="data.option.value" name="value">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-value" ng-show="form.$submitted && !form.$valid && !form['value'].$valid">
					<span ng-show="form['value'].$error.required"><?php echo Yii::t("app/admin", "Required!"); ?></span>
					<span ng-show="!form['value'].$error.required && form['value'].$error.validator"><?php echo Yii::t("app/admin", "Duplicated value!"); ?></span>
				</span>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='form.$submitted = true; form.$valid && ok()'><?php echo Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-gray fc-fff funiv fs-upper fs0-786' ng-click='cancel()' type="submit"><?php echo Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
