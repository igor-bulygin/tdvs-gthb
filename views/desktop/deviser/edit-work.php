<?php
use yii\web\View;
use app\models\Tag;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Returns;
use app\models\Bespoke;
use app\models\Currency;
use app\models\Preorder;
use app\models\Warranty;
use app\components\Crop;
use app\models\MetricType;
use app\models\MadeToOrder;
use app\assets\desktop\deviser\EditWorkAsset;

/* @var $this yii\web\View */
/* @var $tags ArrayObject */
/* @var $deviser ArrayObject */
/* @var $product ArrayObject */
/* @var $countries ArrayObject */
/* @var $categories ArrayObject */
/* @var $countries_lookup ArrayObject */
/* @var $deviser_sizecharts ArrayObject */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/deviser/edit-work']
];

EditWorkAsset::register($this);

$who = $deviser['personal_info']['name'] . " " . join($deviser['personal_info']['surnames'], " ");
$this->title = "$who / Todevise / Edit info";

$base_path_photos = Yii::getAlias("@deviser_url") . "/" . $deviser["short_id"] . "/";
$profile_photo_url = isset($deviser["media"]["profile"]) ? $base_path_photos . $deviser["media"]["profile"] : "";
?>

<div class="row no-gutter" ng-controller="productCtrl" ng-init="init()">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _tags = " . Json::encode($tags) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _product = " . Json::encode($product) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _currencies = " . Json::encode(Currency::CURRENCIES) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _countries = " . Json::encode($countries) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _countries_lookup = " . Json::encode($countries_lookup) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _sizecharts = " . Json::encode($sizecharts) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _deviser_sizecharts = " . Json::encode($deviser_sizecharts) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _mus = " . Json::encode($mus) . ";", View::POS_END); ?>

		<?php $this->registerJs("var _upload_product_photo_url = '" . Url::to(["deviser/upload-product-photo", "slug" => $deviser["slug"], "short_id" => $product["short_id"]]) . "';", View::POS_END); ?>
		<?php $this->registerJs("var _delete_product_photo_url = '" . Url::to(["deviser/delete-product-photo", "slug" => $deviser["slug"], "short_id" => $product["short_id"]]) . "';", View::POS_END); ?>

		<!--

		Header (deviser profile picture, save & publish buttons)

		-->

		<div class="row no-gutter flex flex-align-center internal-header">
			<div class="col-xs-4">
				<img class="deviser_photo" ngf-background="profilephoto" angular-img-dl angular-img-dl-url="<?= $profile_photo_url ?>" angular-img-dl-model="profilephoto" />
				<span><?= $deviser["personal_info"]["name"] ?> <?= implode(" ", $deviser["personal_info"]["surnames"]) ?></span>
			</div>
			<div class="col-xs-4 flex flex-justify-center">
				<span><?= Yii::t("app/deviser", "Editing work") ?></span>
			</div>
			<div class="col-xs-4">
				<div class="row no-gutter">
					<div class="col-xs-12 flex flex-justify-end">
						<div class="btn btn-grey fc-fff funiv fs-upper fs0-786 save" ng-click="save()">
							<span class="glyphicon glyphicon-download-alt tick" aria-hidden="true"><?= Yii::t("app/deviser", "Save progress") ?></span>
						</div>
						<div class="btn btn-light-green fc-18 funiv fs-upper fs0-786 publish" ng-click="publish()">
							<span class="glyphicon glyphicon-ok tick" aria-hidden="true"><?= Yii::t("app/deviser", "Publish") ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!--

		Main content (row)

		* Left column (product name, photos, videos)
		* Right column (categories, collections, tags)

		-->

		<div class="row no-gutter">

			<div class="flex">

				<div class="col-xs-9 flex main-left-holder">

					<div class="background-holder"></div>

					<div class="flex-inline flex-column flex-align-stretch main-left relative">

						<div class="languages-name fs0-857 fs-upper">
							<div class="language-name pointer funiv_bold fc-9b" ng-cloak ng-repeat="language in languages_name"
							     ng-class="{active: $parent.selected_lang_name === language.key}"
							     ng-click="$parent.selected_lang_name = language.key" ng-init="$parent.selected_lang_name = $parent.lang">
								{{ ::language.value }}
							</div>

							<div class="language-name"
								angular-multi-select
								input-model="langs"
								output-model="languages_name"

								checked-property="checked"

								dropdown-label="<[ '<( value )>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select language(s)') ?>']>"
								leaf-label="<[ value ]>"

								preselect="key, {{ lang }}"
								hide-helpers="check_all, check_none, reset"
							></div>
						</div>

						<div class="product-name-holder">
							<input type="text" placeholder="<?= Yii::t("app/deviser", "Product name"); ?>" ng-model="product.name[selected_lang_name]"
							       ng-change="product.slug[selected_lang_name] = (product.name[selected_lang_name] | slugify: {lang: selected_lang_name.split('-')[0]})" class="funiv_thin fs2-857 fc-9b fs-upper product_name">
						</div>

						<!--<pre>{{ dump(product.options) }}</pre>-->

						<div class="flex flex-prop-1-0 drop-box-holder">
							<div ngf-drop ngf-select ngf-keep="true" ngf-keep-distinct="true" ng-model="shadow_photos" class="flex flex-wrap-wrap drop-box" ngf-drag-over-class="dragover" ngf-multiple="true" ngf-allow-dir="true" ngf-accept="'image/*'">

								<div ng-cloak ng-repeat="photo in product.media.photos track by $index" class="photo-holder">
									<div class="photo-white-area" ng-click="$event.stopPropagation();">
										<span class="glyphicon glyphicon-remove fs0-786 btn_delete_photo pointer" ng-click="delete_photo($index, $event)"></span>

										<div ng-if="photo.name !== ''" ngf-background="photo.blob" angular-img-dl angular-img-dl-url="<?= Yii::getAlias('@product_url') ?>/{{ product.short_id }}/{{ photo.name }}" angular-img-dl-model="photo.blob" class="photo"></div>
										<div ng-if="photo.name === ''" ngf-background="photo.blob" angular-img-dl angular-img-dl-url="" angular-img-dl-model="photo.blob" class="photo"></div>
									</div>

									<div ng-clock class="photo-controls flex flex-align-center flex-justify-center" ng-show="photo.name !== ''"  ng-click="$event.stopPropagation();">
										<div class="set-as-main text-center pointer pull-left" ng-class="{active: is_main_photo($index) === true}" ng-click="set_main_photo($index)">
											<span class="fc-fff glyphicon glyphicon-star-empty"></span>
											<br />
											<span class="funiv fs0-786 fc-3d"><?= Yii::t("app/deviser", "Set as product's main photo") ?></span>
										</div>
										<div class="connect-with-tags text-center pointer">
											<span class="fc-fff glyphicon glyphicon-link"></span>
											<br />
											<span class="funiv fs0-786 fc-3d"><?= Yii::t("app/deviser", "Connect photo with tags") ?></span>
										</div>
									</div>

									<div ng-cloak class="photo-info flex flex-align-center flex-justify-center" ng-show="photo.not_uploaded === true || photo.progress"  ng-click="$event.stopPropagation();">
										<div ng-cloak class="not-uploaded text-center" ng-show="photo.not_uploaded === true && photo.progress === undefined">
											<span class="fc-fff glyphicon glyphicon-exclamation-sign"></span>
											<br />
											<span class="funiv fs0-786 fc-3d"><?= Yii::t("app/deviser", "This photo hasn't been uploaded yet") ?></span>
										</div>
										<div ng-cloak class="uploading text-center" ng-show="photo.progress">
											<span class="fc-fff glyphicon glyphicon-upload"></span>
											<div class="progress">
												<div class="progress-bar progress-bar-info progress-bar-striped active funiv fs0-786 fc-3d" role="progressbar" aria-valuenow="{{ photo.progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ photo.progress }}%;">
													<?= Yii::t("app/deviser", "Uploading...") ?> {{ photo.progress }}%
												</div>
											</div>
										</div>
									</div>

									<!--<pre>{{ dump(photo) }}</pre>-->
								</div>

							</div>
						</div>

						<div ng-cloak class="row no-gutter video_link_holder" ng-repeat="video in product.media.videos_links track by $index">
							<div class="row-same-height">
								<div class="col-xs-11-5 col-height col-middle">
									<div class="input-group video_link">
										<span class="input-group-addon fc-3d funiv fs1-071 fw-bold" id="video_link_{{ $index }}"><?= Yii::t("app/deviser", "Video") ?></span>
										<input type="text" class="form-control fc-2d funiv fs1-071" placeholder="http://" aria-describedby="video_link_{{ $index }}" ng-value="video">
									</div>
								</div>
								<div class="col-xs-0-5 col-height col-middle">
									<div class="fs0-857 funiv_bold pointer" ng-click="product.media.videos_links.splice($index,1)">
										<span class="glyphicon glyphicon-remove fc-fff pointer" aria-hidden="true"></span>
									</div>
								</div>
							</div>
						</div>

						<div class="row no-gutter">
							<div class="col-xs-1 text-center fc-fff funiv fs0-786 add_video_link pointer" ng-click="product.media.videos_links.push('')">
								<?= Yii::t("app/deviser", "Add video +") ?>
							</div>
							<div class="col-xs-11"></div>
						</div>

						<div class="row no-gutter tabs" ng-init="tab = 1">
							<div class="col-xs-4 text-center tab fc-6d funiv_bold fs0-786 fw-bold fs-upper pointer" ng-click="tab = 1" ng-class="{'active' : tab === 1}">description</div>
							<div class="col-xs-4 text-center tab fc-6d funiv_bold fs0-786 fw-bold fs-upper pointer" ng-click="tab = 2" ng-class="{'active' : tab === 2}">price & stock</div>
							<div class="col-xs-4 text-center tab fc-6d funiv_bold fs0-786 fw-bold fs-upper pointer" ng-click="tab = 3" ng-class="{'active' : tab === 3}">returns & warranty</div>
						</div>

					</div>

				</div>
				<div class="col-xs-3 flex main-right-holder">
					<div class="row no-gutter main-right">

						<div class="col-xs-12 dropdown-main">
							<div
								angular-multi-select
								input-model="categories"
								output-model="product.categories"
								output-keys="short_id"
								output-type="values"

								id-property="short_id"
								children-property="sub"
								checked-property="check"

								name="categories"
								dropdown-label="<[ '<(name)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select category/ies') ?>']>"
								node-label="<[ name ]>"
								leaf-label="<[ name ]>"

								preselect="{{ product.categories | arrpatch : 'short_id' }}"
								search-field="name"
								hide-helpers="check_all, check_none, reset"
							></div>
						</div>

						<!--
						<div class="col-xs-12">
							Dropdown with collections...
						</div>
						-->

						<span class="sep"></span>

						<div ng-cloak ng-form="formOptions" class="col-xs-12" ng-repeat="tag in tags_from_categories" ng-class="{disabled_tag: tag.enabled === false}">

							<!-- Dropdown tag -->
							<div ng-cloak ng-if="tag.type === 0">
								<span class="fs0-857 funiv_bold fc-6d tag-title-dropdown">
									<span class="fs0-714 fc-f7284b glyphicon glyphicon-asterisk" ng-if="tag.required === true"></span>
									{{ ::tag.name }}
									<span ng-cloak class="fc-f7284b" ng-show="tag.enabled === false"> - <?= Yii::t("app/deviser", "Disabled") ?></span>
								</span>

								<div class="combination-row" ng-repeat="values in product.options[tag.short_id]">
									<div class="row no-gutter flex flex-align-center">
										<div class="col-xs-0-5 fs0-857 funiv_bold fc-6d">
											{{ ::$index + 1 }}:
										</div>

										<div class="col-xs-11">
											<div
												angular-multi-select
												input-model="tag.options"
												output-model="product.options[tag.short_id][$index]"
												output-keys="value"
												output-type="values"

												checked-property="check"
												name="ams_tag_value_{{ tag.short_id }}_{{ $index }}"

												dropdown-label="<[ '<(text)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select option(s)') ?>']>"
												node-label="<[ text ]>"
												leaf-label="<[ text ]>"

												preselect="{{ values | arrpatch : 'value' }}"
												max-checked-leafs="{{ tag.n_options }}"

												hide-helpers="check_all, check_none, reset"
											></div>
											<div ng-cloak ng-show="tag.enabled === false" class="disabled_tag_area"></div>
										</div>

										<div class="col-xs-0-5 text-center">
											<span class=" fs0-857 funiv_bold pointer" ng-click="remove_product_option(tag.short_id, $index)">
												<span class="glyphicon glyphicon-remove fc-7aaa4a pointer" aria-hidden="true"></span>
											</span>
										</div>
									</div>

								</div>

								<br ng-cloak ng-show="tag.enabled === true" />
								<span ng-cloak ng-show="tag.enabled === true" class="fc-7aaa4a fs0-857 funiv_bold pointer new-combination" ng-click="create_product_option(tag.short_id)">
									<?= Yii::t("app/deviser", "Add another tag option +") ?>
								</span>

							</div>

							<!-- Freetext tag -->
							<div ng-cloak ng-if="tag.type === 1">

								<span class="fs0-857 funiv_bold fc-6d tag-title-dropdown">
									<span class="fs0-714 fc-f7284b glyphicon glyphicon-asterisk" ng-if="tag.required === true"></span>
									{{ ::tag.name }}
									<span ng-cloak class="fc-f7284b" ng-show="tag.enabled === false"> - <?= Yii::t("app/deviser", "Disabled") ?></span>
								</span>


								<div class="combination-row" ng-repeat="values in product.options[tag.short_id] track by $index">
									<div class="row no-gutter flex flex-align-center">

										<div class="col-xs-0-5 fs0-857 funiv_bold fc-6d">
											{{ $index + 1 }}:
										</div>
										<div class="col-xs-11">

											<div class="row no-gutter">

												<!-- For each option in the tag... -->
												<div ng-repeat="option in tag.options track by $index" class="flex freetext-row">
													<div class="col-xs-3 fs0-857 funiv_bold fc-6d flex flex-align-center flex-justify-center">
														{{ option.text }}
													</div>

													<!-- If the option doesn't have any metric unit, we want to take 100% of the width -->
													<div ng-class="option.metric_type > 0 ? 'col-xs-5' : 'col-xs-9'">

														<!-- Numeric type -->
														<div ng-cloak ng-if="option.type === 0">
															<!-- angular-unit-converter convert-from="{{ mus[option.metric_type].sub[0].value }}" convert-to="{{ product.options[tag.short_id][$parent.$parent.$index][$index].metric_unit }}" -->
															<input ng-model="product.options[tag.short_id][$parent.$parent.$index][$index].value" ng-pattern="/^\-?\d+(\.\d+)?$/"
																class="form-control" placeholder="{{ option.text }}" name="tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}" ng-change="build_price_stock_table()">
															<div role="alert">
																<span class="error" ng-show="!formOptions['tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}'].$valid"><?= Yii::t("app/deviser", "Numbers only") ?></span>
															</div>
														</div>

														<!-- Alphanumeric type -->
														<div ng-cloak ng-if="option.type === 1">
															<input ng-model="product.options[tag.short_id][$parent.$parent.$index][$index].value"
																class="form-control" placeholder="{{ option.text }}" name="tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}" ng-change="build_price_stock_table()">
															<div role="alert">
																<span class="error" ng-show="!formOptions['tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}'].$valid"><?= Yii::t("app/deviser", "Invalid input") ?></span>
															</div>
														</div>

													</div>

													<div class="col-xs-3 col-xs-offset-1" ng-if="option.metric_type > 0">
														<div
															angular-multi-select
															input-model="mus[option.metric_type].sub"
															output-model="product.options[tag.short_id][$parent.$parent.$index][$index].metric_unit"
															output-keys="value"
															output-type="value"

															checked-property="check"
															name="ams_tag_value_{{ tag.short_id }}_{{ $parent.$parent.$index }}_{{ $index }}"

															dropdown-label="<[ '<( text )>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select option(s)') ?>']>"
															node-label="<[ text ]>"
															leaf-label="<[ text ]>"

															preselect="value, {{ product.options[tag.short_id][$parent.$parent.$index][$index].metric_unit }}"
															max-checked-leafs="1"
															hide-helpers="check_all, check_none, reset"
														></div>
														<div ng-cloak ng-show="tag.enabled === false" class="disabled_tag_area"></div>
													</div>

												</div>
											</div>
										</div>
										<div class="col-xs-0-5">
											<span class=" fs0-857 funiv_bold pointer" ng-click="remove_product_option(tag.short_id, $index)">
												<span class="glyphicon glyphicon-remove fc-7aaa4a pointer" aria-hidden="true"></span>
											</span>
										</div>

									</div>

									<span class="sep_dark" ng-if="!$last"></span>

								</div>

								<br ng-cloak ng-show="tag.enabled === true" />
								<span ng-cloak ng-show="tag.enabled === true" class="fc-7aaa4a fs0-857 funiv_bold pointer new-combination" ng-click="create_product_option(tag.short_id)">
									<?= Yii::t("app/deviser", "Create new combination +") ?>
								</span>

							</div>

							<span class="sep"></span>

						</div>

					</div>
				</div>

			</div>

		</div>

		<!--

		Tabs

		-->

		<div class="row no-gutter">

			<!--

			Description tab

			-->

			<div ng-cloak ng-show="tab == 1" class="col-xs-12 description-content">

				<div class="row no-gutter">

					<div class="col-xs-12">
						<div class="languages-desc fs0-857 fs-upper flex">
							<div class="language-desc pointer funiv_bold fc-9b" ng-cloak ng-repeat="language in languages_description"
								ng-class="{active: $parent.selected_lang_description === language.key}"
								ng-click="$parent.selected_lang_description = language.key" ng-init="$parent.selected_lang_description = $parent.lang">
								{{ ::language.value }}
							</div>
							<div class="language-desc"
								angular-multi-select
								input-model="langs"
								output-model="languages_description"

								checked-property="checked"

								dropdown-label="<[ '<( value )>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select language(s)') ?>']>"
								leaf-label="<[ value ]>"

								preselect="key, {{ lang }}"
								hide-helpers="check_all, check_none, reset"
							></div>
						</div>
					</div>

					<br /><br />

					<div class="col-xs-12">
						<div class="product-desc-holder">
							<textarea type="text" placeholder="<?= Yii::t("app/deviser", "Product description"); ?>" ng-model="product.description[selected_lang_description]" class="funiv fs1-071 fc-3d product_desc"></textarea>
						</div>
					</div>

				</div>

			</div>

			<!--

			Price & Stock tab

			-->

			<div ng-cloak ng-show="tab == 2" class="col-xs-12 price-stock-content">
				<div class="sep_dark"></div>

				<!--

				Made to order, Pre-order and Bespoke

				-->

				<div class="row no-gutter">
					<div class="col-xs-6">

						<div class="row no-gutter">
							<div class="col-xs-12">
								<span class="funiv_bold fs-1 fc-3d"><?= Yii::t("app/deviser", "Made to order") ?></span>
							</div>

							<div class="col-xs-12">
								<div class="row no-gutter">
									<div class="row-same-height">
										<div class="col-xs-4 col-height col-middle">
											<label class="madetoorder-label funiv_bold fs0-857 fc-6d fw-normal">
												<input type="checkbox" ng-model="product.madetoorder.type" ng-false-value="<?= MadeToOrder::NONE ?>" ng-true-value="<?= MadeToOrder::DAYS ?>">
												<?= Yii::t("app/deviser", "This work is made-to-order") ?>
											</label>
										</div>
										<div class="col-xs-2 col-height col-middle" ng-hide="!product.madetoorder.type || product.madetoorder.type === <?= MadeToOrder::NONE ?>">
											<div class="input-group spinner" ng-init="product.madetoorder.value = product.madetoorder.value || 0">
												<input type="text" class="form-control" ng-model="product.madetoorder.value">
												<div class="input-group-btn-vertical">
													<button class="btn btn-default btn-xs" type="button" ng-click="product.madetoorder.value = product.madetoorder.value + 1">
														<span class="glyphicon glyphicon-triangle-top fs0-857"></span>
													</button>
													<button class="btn btn-default btn-xs" type="button" ng-click="product.madetoorder.value = product.madetoorder.value - 1">
														<span class="glyphicon glyphicon-triangle-bottom fs0-857"></span>
													</button>
												</div>
											</div>
										</div>
										<div class="col-xs-1 col-height col-middle"></div>
										<div class="col-xs-5 col-height col-middle" ng-hide="!product.madetoorder.type || product.madetoorder.type === <?= MadeToOrder::NONE ?>">
											<span class="funiv_bold fs0-857 fc-6d fw-normal"><?= Yii::t("app/deviser", "days to manufacturing") ?></span>
										</div>
									</div>
								</div>

								<br />

								<div class="row no-gutter" ng-cloak ng-show="false">
									<div class="row-same-height">
										<div class="col-xs-4 col-height col-middle">
											<label class="madetoorder-label funiv_bold fs0-857 fc-6d fw-normal">
												<input type="checkbox" ng-model="product.bespoke.type" ng-false-value="<?= Bespoke::NO ?>" ng-true-value="<?= Bespoke::YES ?>">
												<?= Yii::t("app/deviser", "This is a bespoke work") ?>
											</label>
										</div>
										<div class="-col-xs-8 col-height col-middle">
											dropdown bespoke
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="col-xs-6">

						<div class="row no-gutter">
							<div class="col-xs-12">
								<span class="funiv_bold fs-1 fc-3d"><?= Yii::t("app/deviser", "Pre-order") ?></span>
							</div>

							<div class="col-xs-12" ng-init="product.preorder.type = product.preorder.type || <?= Preorder::NO ?>">

								<div class="row no-gutter">
									<div class="row-same-height">
										<div class="col-xs-4 col-height col-top">
											<label class="preorder-label funiv_bold fs0-857 fc-6d fw-normal">
												<input type="checkbox" ng-model="product.preorder.type" ng-false-value="<?= Preorder::NO ?>" ng-true-value="<?= Preorder::YES ?>">
												<?= Yii::t("app/deviser", "This is in pre-order") ?>
											</label>
										</div>
										<div class="col-xs-4 col-height col-top" ng-show="product.preorder.type == <?= Preorder::YES ?> && 1 === 2">
											<span class="funiv_bold fs0-857 fc-6d fw-normal"><?= Yii::t("app/deviser", "When will the pre-order period end?") ?></span>
											<br />
											<span class="fpf fs0-714 fc-6d fw-normal"><?= Yii::t("app/deviser", "When the pre-order is over, you will be reminded to update the stock of the product.") ?></span>
										</div>
										<div class="col-xs-4 col-height col-top" ng-show="product.preorder.type == <?= Preorder::YES ?> && 1 === 2">
											<div class="dropdown">
												<div class="dropdown-periodend_date" id="periodend_date" role="button" data-toggle="dropdown">
													<div class="input-append">
														<span type="text" class="input-large">{{ product.preorder.preorder_end | date:'<?= Yii::$app->params["angular_datepicker"] ?>' }}</span>
														<span class="add-on">
															<i class="glyphicon glyphicon-calendar fc-6d"></i>
														</span>
													</div>
												</div>
												<ul class="dropdown-menu" role="menu" aria-labelledby="periodend_date">
													<datetimepicker data-ng-model="product.preorder.preorder_end" data-datetimepicker-config="{ dropdownSelector: '.dropdown-periodend_date', startView: 'month', minView: 'day' }"></datetimepicker>
												</ul>
											</div>
										</div>
									</div>
								</div>

								<div class="row no-gutter" ng-show="product.preorder.type == <?= Preorder::YES ?> && 1 === 2">
									<div class="row-same-height">
										<div class="col-xs-4 col-height col-middle">

										</div>
										<div class="col-xs-4 col-height col-middle">
											<span class="funiv_bold fs0-857 fc-6d fw-normal"><?= Yii::t("app/deviser", "When will the product be shipped?") ?></span>
										</div>
										<div class="col-xs-4 col-height col-middle">
											<div class="dropdown">
												<div class="dropdown-shipping_date" id="shipping_date" role="button" data-toggle="dropdown">
													<div class="input-append">
														<span type="text" class="input-large">{{ product.preorder.shipping | date:'<?= Yii::$app->params["angular_datepicker"] ?>' }}</span>
														<span class="add-on">
															<i class="glyphicon glyphicon-calendar fc-6d"></i>
														</span>
													</div>
												</div>
												<ul class="dropdown-menu" role="menu" aria-labelledby="shipping_date">
													<datetimepicker data-ng-model="product.preorder.shipping" data-datetimepicker-config="{ dropdownSelector: '.dropdown-shipping_date', startView: 'month', minView: 'day' }"></datetimepicker>
												</ul>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>

				<br /><br />


				<!--

				Sizechart

				-->

				<div ng-if="use_sizecharts === true" class="sep_dark"></div>
				<div ng-if="use_sizecharts === true" class="row no-gutter">
					<div class="col-xs-12">
						<span class="funiv_bold fs-1 fc-3d"><?= Yii::t("app/deviser", "Sizes") ?></span>
					</div>

					<div class="col-xs-12">
						<div class="row no-gutter">

							<div class="col-xs-6">
								<div class="row no-gutter">
									<div class="row-same-height">
										<div class="col-xs-6 col-height col-middle">
											<span><?= Yii::t("app/deviser", "Choose size chart") ?></span>
										</div>

										<div class="col-xs-6 col-height col-middle">
											<div
												angular-multi-select
												input-model="$parent.sizecharts"
												output-model="$parent.product.sizechart.short_id"
												output-keys="short_id"
												output-type="value"

												id-property="short_id"
												checked-property="check"
												name="ams_sizechart"

												max-checked-leafs="1"
												dropdown-label="<[ '<(name)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select sizechart') ?>']>"
												node-label="<[ name ]>"
												leaf-label="<[ name ]>"

												preselect="short_id, {{ product.sizechart.short_id }}"

												hide-helpers="check_all, check_none, reset"
											></div>
										</div>
									</div>
								</div>
							</div>

							<div ng-cloak class="col-xs-6" ng-show="$parent.sizechart_countries.length > 0">
								<div class="row no-gutter">
									<div class="row-same-height">
										<div class="col-xs-6 col-height col-middle">
											<span><?= Yii::t("app/deviser", "Which size system do you use?") ?></span>
										</div>
										<!--
										Same here, please read comments for the "sizechart" dropdown.
										-->
										<div class="col-xs-6 col-height col-middle">
											<div
												angular-multi-select
												input-model="$parent.sizechart_countries"
												output-model="$parent.product.sizechart.country"
												output-keys="value"
												output-type="value"

												id-property="value"
												checked-property="check"
												name="ams_sizechart_country"

												max-checked-leafs="1"
												dropdown-label="<[ '<(text)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select country') ?>']>"
												node-label="<[ text ]>"
												leaf-label="<[ text ]>"

												preselect="value, {{ product.sizechart.country }}"

												hide-helpers="check_all, check_none, reset"
											></div>
										</div>
									</div>
								</div>
							</div>

							<div ng-if="deviser_sizecharts.length > 0" class="col-xs-6 deviser-sizechart-row">
								<div class="row no-gutter">
									<div class="row-same-height">
										<div class="col-xs-6 col-height col-middle">
											<span><?= Yii::t("app/deviser", "or select a previously saved chart") ?></span>
										</div>
										<div class="col-xs-6 col-height col-middle">
											<div
												angular-multi-select
												input-model="$parent.$parent.deviser_sizecharts"
												output-model="$parent.$parent.selected_deviser_sizechart"

												id-property="value"
												checked-property="check"
												name="deviser_sizechart"

												max-checked-leafs="1"
												dropdown-label="<[ '<(name)>' | outputModelIterator : this : ', ']>"
												node-label="<[ name ]>"
												leaf-label="<[ name ]>"

												preselect="value, {{ product.sizechart.country }}"

												hide-helpers="check_all, check_none, reset"
											></div>
										</div>
									</div>
								</div>
							</div>

						</div>

						<div class="row no-gutter" ng-if="$parent.available_sizes.length > 0">
							<div class="row-same-height">
								<div class="col-xs-1 col-height col-middle">
									<span><?= Yii::t("app/deviser", "Add size to the chart") ?></span>
								</div>
								<div class="col-xs-1 col-height col-middle">
									<div
										angular-multi-select
										input-model="$parent.$parent.available_sizes"
										output-model="$parent.$parent.tmp_selected_sizechart_size"
										output-keys="value"
										output-type="value"

										checked-property="check"

										name="ams_available_sizes"
										max-checked-leafs="1"
										dropdown-label="<[ '<(value)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Available sizes') ?>']>"
										node-label="<[ value ]>"
										leaf-label="<[ value ]>"

										hide-helpers="check_all, check_none, reset"
									></div>
								</div>
								<div class="col-xs-1 col-height col-middle">
									<span class="glyphicon glyphicon-plus pointer fs0-929 fc-f7284b" ng-click="insertSizeInTable()"></span>
								</div>
								<div class="col-xs-9"></div>
							</div>
						</div>

					</div>

					<div ng-cloak class="col-xs-12 sizechart-table-holder" ng-show="use_sizecharts === true">

						<div ng-cloak class="row-same-height no-gutter" ng-show="use_sizecharts === true && (product.sizechart.values.length > 0 || product.sizechart.country.length > 0)">
							<table class="fc-6d funiv fs1-071 fnormal sizechart-table max-width">
								<thead>
									<tr>
										<th class="fpf fs0-857 fnormal text-center sizechart-cell" ng-cloak>{{ countries_lookup[product.sizechart.country] }}</th>
										<th class="fpf fs0-857 fnormal text-center sizechart-cell" ng-cloak ng-repeat="column in product.sizechart.columns track by $index">{{ column[lang] }}</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-cloak ng-repeat="row in product.sizechart.values track by $index" class="sizechart-row" ng-class="{true: 'even', false: 'odd'}[$even]">
										<td ng-repeat="cell in product.sizechart.values[$index] track by $index" class="text-center sizechart-cell">
											<span ng-if="$index == 0">{{ product.sizechart.values[$parent.$parent.$index][$index] }}</span>
											<span ng-if="$index == 0" class="glyphicon glyphicon-remove pointer" aria-hidden="true" ng-click="deleteSizeFromTable($parent.$parent.$index)"></span>
											<input ng-if="$index > 0" ng-model="product.sizechart.values[$parent.$parent.$index][$index]" placeholder="0" />
											<span ng-if="$index > 0">{{ product.sizechart.metric_unit }}</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>

					<div ng-cloak class="col-xs-12" ng-show="use_sizecharts === true && product.sizechart.values.length > 0">
						<div class="funiv_bold fs0-929 fc-f7284b fw-normal pointer save-sizechart" ng-click="save_sizechart()">
							<span class="glyphicon glyphicon-paperclip"></span>
							<span class="" aria-hidden="true"><?= Yii::t("app/deviser", "Save chart to use with other products") ?></span>
						</div>
					</div>
				</div>

				<br /><br />

				<!--

				Price & Stock

				-->

				<div ng-if="use_prints == false" class="sep_dark"></div>
				<div ng-if="use_prints == false" class="row no-gutter">
					<div class="col-xs-12">
						<span class="funiv_bold fs-1 fc-3d"><?= Yii::t("app/deviser", "Price & Stock") ?></span>
					</div>

					<br /><br />

					<div class="col-xs-12">

						<div class="row no-gutter">
							<div class="row-same-height">
								<div class="col-xs-4 col-height col-middle">
									<span><?= Yii::t("app/deviser", "Weight") ?></span>
									<div
										angular-multi-select
										input-model="mus[<?= MetricType::WEIGHT ?>].sub"
										output-model="product.weight_unit"
										output-keys="value"
										output-type="value"

										checked-property="check"

										max-checked-leafs="1"
										dropdown-label="<[ '<(text)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select weight unit') ?>']>"
										node-label="<[ text ]>"
										leaf-label="<[ text ]>"

										preselect="value, {{ product.weight_unit }}"

										hide-helpers="check_all, check_none, reset"
									></div>
								</div>
								<div class="col-xs-4 col-height col-middle">
									<span><?= Yii::t("app/deviser", "Currency") ?></span>
									<div
										angular-multi-select
										input-model="currencies"
										output-model="product.currency"
										output-keys="value"
										output-type="value"

										checked-property="check"

										max-checked-leafs="1"
										dropdown-label="<[ '<(symbol)> (<( value )>)' | outputModelIterator : this : ', ' : '<?= Yii::t('app/deviser', 'Select currency') ?>']>"
										leaf-label="<[ symbol ]> (<[ value ]>)"

										preselect="value, {{ product.currency }}"

										hide-helpers="check_all, check_none, reset"
									></div>
								</div>
								<div class="col-xs-4 col-height col-middle">

								</div>
							</div>
						</div>

					</div>

					<br /><br /><br />

					<div class="col-xs-12">

						<div class="fc-333 funiv fs1-071 fnormal" ng-show="product.price_stock.length === 0"><?= Yii::t("app/deviser", "The price & stock table will be available when all required tags have at least 1 combination.") ?></div>

						<table class="fc-6d funiv fs1-071 fnormal pricestock-table max-width" ng-show="product.price_stock.length > 0">
							<thead>
							<tr>
								<th class="fpf fs0-857 fnormal text-center pricestock-cell" ng-cloak ng-repeat="tag_id in $parent.tags_order_for_price_stock_table track by $index" ng-init="tag = getTag(tag_id)">
									{{ tag.name || '<?= Yii::t("app/deviser", "Size") ?>' }}
									<span ng-if="tag.type == <?= Tag::DROPDOWN ?>"></span>
									<span ng-if="tag.type == <?= Tag::FREETEXT ?>">(
										<span ng-repeat="option in tag.options">
											{{ option.text }}<span ng-show="!$last">,</span>
										</span>
									)</span>
								</th>
								<th class="fpf fs0-857 fnormal text-right pricestock-cell"><?= Yii::t("app/deviser", "Weight") ?></th>
								<th class="fpf fs0-857 fnormal text-center pricestock-buttons-cell"></th>

								<th class="fpf fs0-857 fnormal text-right pricestock-cell"><?= Yii::t("app/deviser", "Stock") ?></th>
								<th class="fpf fs0-857 fnormal text-center pricestock-buttons-cell"></th>

								<th class="fpf fs0-857 fnormal text-right pricestock-cell"><?= Yii::t("app/deviser", "Price") ?></th>
								<th class="fpf fs0-857 fnormal text-center pricestock-buttons-cell"></th>
							</tr>
							</thead>
							<tbody>

							<tr ng-cloak ng-repeat="row in product.price_stock track by $index" class="pricestock-row" ng-class="{true: 'even', false: 'odd'}[$even]">
								<td ng-repeat="tag_id in tags_order_for_price_stock_table" ng-init="tag = getTag(tag_id)" class="text-center pricestock-cell">
									<span ng-if="tag === undefined">{{ row.options['size'] }}</span>

									<span ng-if="tag.type == <?= Tag::DROPDOWN ?>">
										<span ng-repeat="value in row.options[tag_id]">
											{{ getTagOption($parent.tag, value).text }}
											<span ng-show="!$last">,</span>
										</span>
									</span>

									<span ng-if="tag.type == <?= Tag::FREETEXT ?>">
										<span ng-repeat="value in row.options[tag_id]">
											{{ value.value }}
											{{ value.metric_unit }}
											<span ng-show="!$last">x</span>
										</span>
									</span>
								</td>

								<td class="text-right pricestock-cell">
									<input class="text-center" type="number" ng-model="product.price_stock[$index].weight" />
								</td>
								<td>
									<div class=" flex flex-column">
										<span ng-cloak class="funiv fc-6d fs0-643 pointer" ng-click="apply_to_all('weight', product.price_stock[$index].weight)" ng-if="$first">
											<?= Yii::t("app/deviser", "Apply to all") ?>
										</span>
										<span ng-cloak class="funiv fc-6d fs0-643 pointer" ng-click="apply_to_same_size(product.price_stock[$index].options.size, 'weight', product.price_stock[$index].weight)" ng-if="use_sizecharts === true && ($first || product.price_stock[$index].options.size !== product.price_stock[$index - 1].options.size)">
											<?= Yii::t("app/deviser", "Apply to same size") ?>
										</span>
									</div>
								</td>

								<td class="text-right pricestock-cell">
									<input class="text-center" type="number" ng-model="product.price_stock[$index].stock" />
								</td>
								<td>
									<div class=" flex flex-column">
										<span ng-cloak class="funiv fc-6d fs0-643 pointer" ng-click="apply_to_all('stock', product.price_stock[$index].stock)" ng-if="$first">
											<?= Yii::t("app/deviser", "Apply to all") ?>
										</span>
										<span ng-cloak class="funiv fc-6d fs0-643 pointer" ng-click="apply_to_same_size(product.price_stock[$index].options.size, 'stock', product.price_stock[$index].stock)" ng-if="use_sizecharts === true && ($first || product.price_stock[$index].options.size !== product.price_stock[$index - 1].options.size)">
											<?= Yii::t("app/deviser", "Apply to same size") ?>
										</span>
									</div>
								</td>

								<td class="text-right pricestock-cell">
									<input class="text-center" type="number" step="0.01" ng-model="product.price_stock[$index].price" />
								</td>
								<td>
									<div class=" flex flex-column">
										<span ng-cloak class="funiv fc-6d fs0-643 pointer" ng-click="apply_to_all('price', product.price_stock[$index].price)" ng-if="$first">
											<?= Yii::t("app/deviser", "Apply to all") ?>
										</span>
										<span ng-cloak class="funiv fc-6d fs0-643 pointer" ng-click="apply_to_same_size(product.price_stock[$index].options.size, 'price', product.price_stock[$index].price)" ng-if="use_sizecharts === true && ($first || product.price_stock[$index].options.size !== product.price_stock[$index - 1].options.size)">
											<?= Yii::t("app/deviser", "Apply to same size") ?>
										</span>
									</div>
								</td>
							</tr>

							</tbody>
						</table>

					</div>
				</div>

				<!--<pre>{{ dump(product.price_stock) }}</pre>-->

				<!--

				Prints

				-->

				<div ng-if="use_prints == true" class="sep_dark"></div>
				<div ng-if="use_prints == true" class="row no-gutter">
					<div class="col-xs-12">
						<span class="funiv_bold fs-1 fc-3d"><?= Yii::t("app/deviser", "Prints") ?></span>
					</div>

					<div class="col-xs-12">
						price & stock single
					</div>
				</div>

			</div>

			<!--

			Returns & Warranty tab

			-->

			<div ng-cloak ng-show="tab == 3" class="col-xs-12 returns-warranty-content">

				<div class="row no-gutter">

					<div class="col-xs-12" ng-init="product.returns.type = product.returns.type || <?= Returns::NONE ?>">

						<div class="row no-gutter">
							<div class="row-same-height">
								<div class="col-xs-2 col-height col-middle">
									<div class="radio">
										<label class="funiv_bold fs0-857 fc-6d fw-normal">
											<input type="radio" name="returns" id="returns1" value="<?= Returns::DAYS; ?>" ng-model="product.returns.type">
											<?= Yii::t("app/deviser", "Money-back guarantee (days)") ?>
										</label>
									</div>
								</div>
								<div class="col-xs-10 col-height col-middle">
									<div ng-hide="product.returns.type == <?= Returns::NONE; ?>" class="input-group spinner">
										<input type="text" class="form-control" ng-model="product.returns.value" placeholder="0">
										<div class="input-group-btn-vertical">
											<button class="btn btn-default btn-xs" type="button" ng-click="product.returns.value = product.returns.value + 1"><span class="glyphicon glyphicon-triangle-top"></span></button>
											<button class="btn btn-default btn-xs" type="button" ng-click="product.returns.value = product.returns.value - 1"><span class="glyphicon glyphicon-triangle-bottom"></span></button>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row no-gutter">
							<div class="col-xs-2">
								<div class="radio">
									<label class="funiv_bold fs0-857 fc-6d fw-normal">
										<input type="radio" name="returns" id="returns2" value="<?= Returns::NONE; ?>" ng-model="product.returns.type">
										<?= Yii::t("app/deviser", "No returns") ?>
									</label>
								</div>
							</div>
							<div class="col-xs-10">

							</div>
						</div>

					</div>

				</div>

				<div class="row no-gutter">

					<div class="col-xs-12" ng-init="product.warranty.type = product.warranty.type || <?= Warranty::NONE ?>">

						<div class="row no-gutter">
							<div class="row-same-height">
								<div class="col-xs-2 col-height col-middle">
									<label class="warranty-label funiv_bold fs0-857 fc-6d fw-normal">
										<input type="checkbox" ng-model="product.warranty.type" ng-false-value="<?= Warranty::NONE ?>" ng-true-value="<?= Warranty::MONTHS ?>">
										<?= Yii::t("app/deviser", "Warranty (months)") ?>
									</label>
								</div>
								<div class="col-xs-10 col-height col-middle">
									<div ng-hide="product.warranty.type == <?= Warranty::NONE; ?>" class="input-group spinner">
										<input type="text" class="form-control" ng-model="product.warranty.value" placeholder="0">
										<div class="input-group-btn-vertical">
											<button class="btn btn-default btn-xs" type="button" ng-click="product.warranty.value = product.warranty.value + 1"><span class="glyphicon glyphicon-triangle-top"></span></button>
											<button class="btn btn-default btn-xs" type="button" ng-click="product.warranty.value = product.warranty.value - 1"><span class="glyphicon glyphicon-triangle-bottom"></span></button>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>

				</div>

			</div>
		</div>


	</div>
</div>

<?= Crop::widget() ?>
