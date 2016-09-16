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

<div class="row no-gutter" ng-controller="tagCtrl as tagCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _tag = " . Json::encode($tag) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _tagoption_txt = " . Json::encode(TagOption::TXT) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _colors = " . Json::encode(TagOption::COLORS) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _mus = " . Json::encode($mus) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-4 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Edit tag"); ?></h2>
				</div>
				<div class="col-xs-8 col-height col-middle">
					<div class="pull-right">
						<button class="btn btn-grey fc-fff funiv fs-upper fs0-786" ng-click="tagCtrl.cancel()"><?= Yii::t("app/admin", "Cancel changes"); ?></button>
						<button class="btn btn-light-green fc-18 funiv fs-upper fs0-786" ng-click="tagCtrl.save()"><?= Yii::t("app/admin", "Save changes"); ?></button>
					</div>
				</div>
			</div>
		</div>

		<div class="row no-gutter page-title-row flex flex-row">
			<div class="col-xs-3 tag flex-prop-1 margin-l0-r1">
				<label class="funiv fs-upper fs0-786 fc-c7 fnormal"><?= Yii::t("app/admin", "Tag name"); ?></label>
				<input type="text" class="form-control fc-fff funiv fs1" placeholder="" ng-model="tagCtrl.tag.name[tagCtrl.lang]">
			</div>
			<div class="col-xs-6 tag flex-prop-2-1 margin-l1-r1">
				<label class="funiv fs-upper fs0-786 fc-c7 fnormal"><?= Yii::t("app/admin", "Description"); ?></label>
				<input type="text" class="form-control fc-fff funiv fs1" placeholder="" ng-model="tagCtrl.tag.description[tagCtrl.lang]">
			</div>
			<div class="col-xs-3 tag flex-prop-1 margin-l1-r1">
				<label class="funiv fs-upper fs0-786 fc-c7 fnormal"><?= Yii::t("app/admin", "Categories"); ?></label><br>
				<div
					angular-multi-select
					input-model="tagCtrl.categories"
					output-model="tagCtrl.tag.categories"
					output-keys="short_id"
					output-type="values"

					id-property="short_id"
					checked-property="check"
					children-property="sub"

					dropdown-label="<[ '<(name[&quot;{{ tagCtrl.lang }}&quot;])>' | outputModelIterator : this : ', ']>"
					node-label="<[ name['{{ tagCtrl.lang }}'] ]>"
					leaf-label="<[ name['{{ tagCtrl.lang }}'] ]>"

					preselect="{{ tagCtrl.tag.categories | arrpatch : 'short_id' }}"

					hide-helpers="check_all, check_none, reset"
				></div>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-2 div-bordered tag_settings">
				<div class="checkbox">
					<label class="funiv fs0-929 fc-c7">
						<input type="checkbox" ng-model="tagCtrl.tag.enabled" ng-checked="tagCtrl.tag.enabled"> <?= Yii::t("app/admin", "Enabled"); ?>
					</label>
				</div>
			</div>
			<div class="col-xs-2 div-bordered tag_settings">
				<div class="checkbox">
					<label class="funiv fs0-929 fc-c7">
						<input type="checkbox" ng-model="tagCtrl.tag.required" ng-change="tagCtrl.tag.required"> <?= Yii::t("app/admin", "This tag is required"); ?>
					</label>
				</div>
			</div>
			<div class="col-xs-3 div-bordered tag_settings">
				<div class="checkbox">
					<label class="funiv fs0-929 fc-c7">
						<input type="checkbox" ng-model="tagCtrl.tag.stock_and_price" ng-checked="tagCtrl.tag.stock_and_price"> <?= Yii::t("app/admin", "Use in price and stock chart"); ?>
					</label>
				</div>
			</div>
			<div class="col-xs-5 div-bordered tag_settings">
				<div class="row no-gutter">
					<div class="row-same-height">
						<div class="col-xs-6 col-height col-middle funiv fs0-929 fc-c7">
							<?= Yii::t("app/admin", "What kind of tag is it?"); ?>
							<span class="glyphicon glyphicon-info-sign"></span>
						</div>
						<div class="col-xs-6 col-height col-middle">
							<div class="radio flex flex-justify-around">
								<label class="funiv fs0-786 fc-c7 fs-upper">
									<input type="radio" name="tagType" id="tagType1" value="<?= Tag::DROPDOWN; ?>" ng-model="tagCtrl.tag.type">
									<label for="tagType1"><span></span><?= Yii::t("app/admin", "With options"); ?></label>
								</label>
								<label class="funiv fs0-786  fc-c7 fs-upper">
									<input type="radio" name="tagType" id="tagType2" value="<?= Tag::FREETEXT; ?>" ng-model="tagCtrl.tag.type">
									<label for="tagType2"><span></span><?= Yii::t("app/admin", "Free field"); ?></label>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<br />

		<div class="ng-cloak" ng-show="tagCtrl.tag.type == <?= Tag::DROPDOWN; ?> && tagCtrl.pending_dialog_type === false">
			<div class="row no-gutter page-title-row bgcolor-3d">
				<div class="row-same-height">
					<div class="col-xs-4 col-height col-middle">
						<label class="funiv fc-fff fs-upper fs0-929 fnormal"><?= Yii::t("app/admin", "Options"); ?></label>
					</div>

					<div class="col-xs-6 col-height col-middle">
						<label class="funiv fs0-929 fc-c7 fnormal"><?= Yii::t("app/admin", "Does this tag allow combos?"); ?></label>
						<span class="glyphicon glyphicon-info-sign fc-c7"></span>
						<label class="funiv fs0-929 fc-c7 fnormal"><?= Yii::t("app/admin", "Please specify how many options can be combined"); ?></label>

						<div class="input-group spinner">
							<input type="text" class="form-control" ng-model="tagCtrl.tag.n_options">
							<div class="input-group-btn-vertical">
								<button class="btn btn-default btn-xs" type="button" ng-click="tagCtrl.tag.n_options = tagCtrl.tag.n_options + 1"><span class="glyphicon glyphicon-triangle-top"></span></button>
								<button class="btn btn-default btn-xs" type="button" ng-click="tagCtrl.tag.n_options = tagCtrl.tag.n_options - 1"><span class="glyphicon glyphicon-triangle-bottom"></span></button>
							</div>
						</div>
					</div>

					<div class="col-xs-2 col-height col-middle">
						<button class="btn btn-purple fc-fff funiv fs0-786 fs-upper" ng-click="tagCtrl.edit_dropdown_option(-1)"><?= Yii::t("app/admin", "Create new"); ?></button>
					</div>
				</div>
			</div>

			<br />

			<div class="row no-gutter">
				<div class="sortable-container" sv-root sv-part="tagCtrl.tag.options">
					<div ng-repeat="option in tagCtrl.tag.options" sv-element class="col-xs-3 tag-checkbox-option-holder">
						<div class="flex flex-align-stretch">
							<div class="arrow-left"></div>

							<div class="flex flex-align-center controls-holder flex-prop-1">
								<span class="glyphicon glyphicon-menu-hamburger pointer fc-68  flex-prop" sv-handle></span>
								<span class="funiv fs1 fc-fff flex-prop-1">{{ option.text[tagCtrl.lang] }}</span>
								<div class="flex flex-justify-end flex-prop-1">
									<span class="glyphicon glyphicon-pencil pointer fc-68" ng-click="tagCtrl.edit_dropdown_option($index)"></span>
									<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="tagCtrl.delete_option($index)"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="ng-cloak" ng-show="tagCtrl.tag.type == <?= Tag::FREETEXT; ?> && tagCtrl.pending_dialog_type === false">
			<div class="row no-gutter bgcolor-3d page-title-row">
				<div class="row-same-height" ng-init="tagCtrl.new_option = {}">
					<div class="col-xs-4 col-height col-middle">
						<label class="funiv fc-fff fs-upper fs0-929 fnormal"><?= Yii::t("app/admin", "Options"); ?></label>
					</div>

					<div class="col-xs-3 col-height col-middle">
						<span class="funiv fc-c7 fs-upper fs0-786"><?= Yii::t("app/admin", "Metric type"); ?></span>
						<div
							angular-multi-select
							input-model="tagCtrl.mus"
							output-model="tagCtrl.selected_mu"
							output-keys="value"
							output-type="value"

							checked-property="checked"

							max-checked-leafs="1"
							dropdown-label="<[ '<(text)>' | outputModelIterator : this : ', ']>"
							node-label="<[ text ]>"
							leaf-label="<[ text ]>"

							hide-helpers="check_all, check_none, reset"
						></div>
					</div>

					<div class="col-xs-3 col-height col-middle">
						<div class="radio flex flex-justify-around" ng-init="tagCtrl.freetext_type = <?= TagOption::NUMERIC; ?>">
							<label class="funiv fc-c7 fs-upper fs0-786">
								<input type="radio" name="optionsType" id="optionsType1" value="<?= TagOption::NUMERIC; ?>" ng-model="tagCtrl.freetext_type">
								<label for="optionsType1"><span></span><?= Yii::t("app/admin", "Numeric"); ?></label>
							</label>
							<label class="funiv fc-c7 fs-upper fs0-786">
								<input type="radio" name="optionsType" id="optionsType2" value="<?= TagOption::ALPHANUMERIC; ?>" ng-model="tagCtrl.freetext_type">
								<label for="optionsType2"><span></span><?= Yii::t("app/admin", "Alphanumeric"); ?></label>
							</label>
						</div>
					</div>

					<div class="col-xs-2 col-height col-middle">
						<button class="btn btn-purple fc-fff funiv fs0-786 fs-upper" ng-click="tagCtrl.create_freetext_option()"><?= Yii::t("app/admin", "Create new"); ?></button>
					</div>
				</div>
			</div>

			<div class="row no-gutter">
				<div class="row-same-height row-free-field" ng-repeat="option in tagCtrl.tag.options">
					<div class="col-xs-10 col-height col-middle tag">
						<input type="text" class="form-control funiv fc-e8 fs1" placeholder="<?= Yii::t("app/admin", "Field name (optional)"); ?>" ng-model="tagCtrl.tag.options[$index].text[tagCtrl.lang]">
					</div>
					<div class="col-xs-1 col-height col-middle text-center funiv fc-e8">
						<span>{{ tagCtrl.get_mu_type(tagCtrl.tag.options[$index].metric_type) }}</span>
						/
						<span>{{ tagCtrl.get_input_type(tagCtrl.tag.options[$index].type) }}</span>
					</div>
					<div class="col-xs-1 col-height col-middle text-center">
						<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="tagCtrl.delete_option($index)"></span>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/ng-template" id="template/modal/tag/create_new.html">
	<form novalidate name="create_newCtrl.form" class="popup-tag">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create new option"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class='modal-title funiv fs1 fnormal fc-18'><?= Yii::t("app/admin", "Title"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in create_newCtrl.data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Option name..."); ?>"
					aria-describedby="basic-addon-{{ $index }}" ng-model="create_newCtrl.data.options[create_newCtrl.data.index].text[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['{{lang_k}}'].$valid">
					<span ng-show="create_newCtrl.form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

			<br />

			<div class="radio flex flex-column opt_value_type" ng-init="create_newCtrl.data.options[create_newCtrl.data.index].is_color = create_newCtrl.data.options[create_newCtrl.data.index].is_color || 0">
				<label class="funiv fs0-786 fc-000 fs-upper">
					<input type="radio" name="optionsRadios" id="optionsRadios1" ng-model="create_newCtrl.data.options[create_newCtrl.data.index].is_color" value="0">
					<label for="optionsRadios1"><span></span><?= Yii::t("app/admin", "This option has a fixed value"); ?></label>
				</label>
				<label class="funiv fs0-786  fc-000 fs-upper">
					<input type="radio" name="optionsRadios" id="optionsRadios2" ng-model="create_newCtrl.data.options[create_newCtrl.data.index].is_color" value="1">
					<label for="optionsRadios2"><span></span><?= Yii::t("app/admin", "This option is a color/animal print"); ?></label>
				</label>
			</div>

			<div ng-if="create_newCtrl.data.options[create_newCtrl.data.index].is_color == 0">
				<label class='modal-title funiv fs1 fnormal fc-18'><?= Yii::t("app/admin", "Value"); ?></label>
				<div class="input-group">
					<input id="value" required="" ui-validate="'create_newCtrl.is_duplicated($value)'"
						ui-validate-watch="'create_newCtrl.data.options[create_newCtrl.data.index].value'" ng-pattern="/^[0-9a-z\-]*$/" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Value..."); ?>"
						aria-describedby="basic-addon-desc" ng-model="create_newCtrl.data.options[create_newCtrl.data.index].value" name="value">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-value" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['value'].$valid">
					<span ng-show="create_newCtrl.form['value'].$error.required"><?= Yii::t("app/admin", "Required!"); ?><br /></span>
					<span ng-show="!create_newCtrl.form['value'].$error.required && create_newCtrl.form['value'].$error.validator"><?= Yii::t("app/admin", "Duplicated value!"); ?><br /></span>
					<span ng-show="!create_newCtrl.form['value'].$error.required && create_newCtrl.form['value'].$invalid"><?= Yii::t("app/admin", "Invalid value!"); ?></span>
				</span>
				</div>
			</div>

			<div ng-if="create_newCtrl.data.options[create_newCtrl.data.index].is_color == 1">
				<label class='modal-title funiv fs1 fnormal fc-18'><?= Yii::t("app/admin", "Currently selected color"); ?>  </label>
				<div class="flex flex-align-center">
					<div class="color-cell {{ create_newCtrl.get_color_from_value(create_newCtrl.data.options[create_newCtrl.data.index].value).class }} pull-left"></div>
					<span class="funiv fs1 fnormal fc-18">{{ create_newCtrl.get_color_from_value(create_newCtrl.data.options[create_newCtrl.data.index].value).text }}</span>
				</div>

				<label class='modal-title funiv fs1 fnormal fc-18'><?= Yii::t("app/admin", "Select a color"); ?></label>
				<div class="flex">
					<div ng-repeat="color in create_newCtrl.colors">
						<div class="color-cell {{ color.class }}" data-toggle="tooltip" title="{{ color.text }}" ng-click="create_newCtrl.data.options[create_newCtrl.data.index].value = color.value"></div>
					</div>
				</div>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='create_newCtrl.form.$submitted = true; create_newCtrl.form.$valid && create_newCtrl.ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='create_newCtrl.cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
