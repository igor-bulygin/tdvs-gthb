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
		<?php $this->registerJs("var _deviser_sizecharts = " . Json::encode($deviser_sizecharts) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _mus = " . Json::encode([
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::NONE]),
				"sub" => []
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE]),
				"sub" => array_map(function($x) { return ["text" => $x, "value" => $x]; }, MetricType::UNITS[MetricType::SIZE])
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT]),
				"sub" => array_map(function($x) { return ["text" => $x, "value" => $x]; }, MetricType::UNITS[MetricType::WEIGHT])
			]
		]) . ";", View::POS_END); ?>
		<?php $this->registerJs("var _base_product_photo_url = '" . Yii::getAlias("@product_url") . "/" . $product["short_id"] . "/" . "';", View::POS_END); ?>
		<?php $this->registerJs("var _upload_product_photo_url = '" . Url::to(["deviser/upload-product-photo", "slug" => $deviser["slug"], "short_id" => $product["short_id"]]) . "';", View::POS_END); ?>
		<?php $this->registerJs("var _delete_product_photo_url = '" . Url::to(["deviser/delete-product-photo", "slug" => $deviser["slug"], "short_id" => $product["short_id"]]) . "';", View::POS_END); ?>

		<!--

		Header (deviser profile picture, save & publish buttons)

		-->

		<div class="row no-gutter flex flex-align-center internal-header">
			<div class="col-xs-4">
				<img class="deviser_photo" ngf-bg-src="profilephoto" angular-img-dl angular-img-dl-url="<?= $profile_photo_url ?>" angular-img-dl-model="profilephoto" />
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

			<div class="row-same-height">

				<div class="col-xs-9 col-height col-top main-left-holder">

					<div class="background-holder"></div>

					<div class="flex-inline flex-column flex-align-stretch main-left">

						<div class="product-name-holder">
							<input type="text" placeholder="<?= Yii::t("app/deviser", "Product name"); ?>" ng-model="product.name[tmp_selected_lang_name]" class="funiv_thin fs2-857 fc-9b fs-upper product_name">

							<div
								angular-multi-select
								input-model="langs"
								single-output-model="tmp_selected_lang_name"
								single-output-prop="key"

								preselect-prop="key"
								preselect-value='["{{ lang }}"]'

								group-property="sub"
								tick-property="check"

								button-template="angular-multi-select-btn-data.htm"
								button-label="{{ value }}"

								item-label="{{ value }}"
								selection-mode="single"
								helper-elements="noall nonone noreset nofilter">
							</div>

						</div>

						<!--<pre>{{ dump(product.options) }}</pre>-->

						<div class="flex flex-prop-1-0 drop-box-holder">
							<div ngf-drop ngf-select ngf-keep="true" ngf-keep-distinct="true" ng-model="shadow_photos" class="flex flex-wrap-wrap drop-box" ngf-drag-over-class="dragover" ngf-multiple="true" ngf-allow-dir="true" ngf-accept="'image/*'">

								<div ng-cloak ng-repeat="photo in product.media.photos track by $index" class="photo-holder">
									<div class="photo-white-area" ng-click="$event.stopPropagation();">
										<span class="glyphicon glyphicon-remove fs0-786 btn_delete_photo pointer" ng-click="delete_photo($index, $event)"></span>

										<div ng-if="photo.name !== ''" ngf-bg-src="photo.blob" angular-img-dl angular-img-dl-url="{{ _base_product_photo_url + photo.name }}" angular-img-dl-model="photo.blob" class="photo"></div>
										<div ng-if="photo.name === ''" ngf-bg-src="photo.blob" angular-img-dl angular-img-dl-url="" angular-img-dl-model="photo.blob" class="photo"></div>
									</div>
									<div class="photo-controls flex flex-align-center flex-justify-center">
										<div class="set-as-main text-center pointer pull-left" ng-click="set_main_photo()">
											<span class="fc-fff glyphicon glyphicon-star-empty"></span>
											<br />
											<span class="funiv fs0-786 fc-3d"><?= Yii::t("app/devser", "Set as product's main photo") ?></span>
										</div>
										<div class="connect-with-tags text-center pointer">
											<span class="fc-fff glyphicon glyphicon-link"></span>
											<br />
											<span class="funiv fs0-786 fc-3d"><?= Yii::t("app/devser", "Set as product's main photo") ?></span>
										</div>
									</div>
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

						<div class="row no-gutter tabs" ng-init="tab = 2">
							<div class="col-xs-4 text-center tab fc-6d funiv_bold fs0-786 fw-bold fs-upper pointer" ng-click="tab = 1" ng-class="{'active' : tab === 1}">description</div>
							<div class="col-xs-4 text-center tab fc-6d funiv_bold fs0-786 fw-bold fs-upper pointer" ng-click="tab = 2" ng-class="{'active' : tab === 2}">price & stock</div>
							<div class="col-xs-4 text-center tab fc-6d funiv_bold fs0-786 fw-bold fs-upper pointer" ng-click="tab = 3" ng-class="{'active' : tab === 3}">returns & warranty</div>
						</div>

					</div>

				</div>
				<div class="col-xs-3 col-height col-top main-right-holder">
					<div class="row no-gutter main-right">

						<div class="col-xs-12 dropdown-main">
							<div
								angular-multi-select
								api="api_cat"
								id-property="short_id"
								input-model="categories"
								output-model="product.categories"

								output-model-props='["short_id"]'
								output-model-type="array"

								preselect-prop="short_id"
								preselect-value="{{ product.categories }}"

								group-property="sub"
								tick-property="check"

								item-label="{{ name[lang] }}"
								selection-mode="multi"
								search-property="name['{{lang}}']"
								min-search-length="3"
								hidden-property="hidden"
								helper-elements="noall nonone noreset filter">
							</div>
						</div>

						<!--
						<div class="col-xs-12">
							Dropdown with collections...
						</div>
						-->

						<span class="sep"></span>

						<div ng-cloak ng-form="formOptions" class="col-xs-12" ng-repeat="tag in tags_from_categories">

							<div ng-cloak ng-if="tag.type === 0">
								<span class="fs0-857 funiv_bold fc-6d tag-title-dropdown">
									<span class="fs0-714 fc-f7284b glyphicon glyphicon-asterisk" ng-if="required_tags_ids.indexOf(tag.short_id) !== -1"></span>
									{{ ::tag.name[lang] }} - {{ ::tag.description[lang] }}
								</span>

								<div class="combination-row" ng-repeat="values in product.options[tag.short_id] track by $index">
									<div class="row no-gutter flex flex-align-center">
										<div class="col-xs-0-5 fs0-857 funiv_bold fc-6d">
											{{ ::$index + 1 }}:
										</div>

										<div class="col-xs-11">
											<div
												angular-multi-select
												input-model="tag.options"
												output-model="product.options[tag.short_id][$index]"
												output-model-props='["value"]'
												output-model-type="array"

												preselect-prop="value"
												preselect-value="{{ product.options[tag.short_id][$index] }}"

												tick-property="check"
												button-template="angular-multi-select-btn-data.htm"
												button-label="{{ text[lang] }}"
												item-label="{{ text[lang] }}"
												selection-mode="{{ tag.n_options }}"
												helper-elements="noall nonone noreset nofilter">
											</div>
										</div>

										<div class="col-xs-0-5 text-center">
											<span class=" fs0-857 funiv_bold pointer" ng-click="remove_product_option(tag.short_id, $index)">
												<span class="glyphicon glyphicon-remove fc-7aaa4a pointer" aria-hidden="true"></span>
											</span>
										</div>
									</div>

								</div>

								<br />
								<span class="fc-7aaa4a fs0-857 funiv_bold pointer new-combination" ng-click="create_product_option(tag.short_id)">
									<?= Yii::t("app/deviser", "Create new combination +") ?>
								</span>

							</div>

							<div ng-cloak ng-if="tag.type === 1">

								<span class="fs0-857 funiv_bold fc-6d tag-title-dropdown">
									<span class="fs0-714 fc-f7284b glyphicon glyphicon-asterisk" ng-if="required_tags_ids.indexOf(tag.short_id) !== -1"></span>
									{{ ::tag.name[lang] }} - {{ ::tag.description[lang] }}
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
														{{ option.text[lang] }}
													</div>

													<!-- If the option doesn't have any metric unit, we want to take 100% of the width -->
													<div ng-class="option.metric_type > 0 ? 'col-xs-5' : 'col-xs-9'" >

														<!-- Numeric type -->
														<div ng-cloak ng-if="option.type === 0">
															<input angular-unit-converter convert-from="{{ mus[option.metric_type].sub[0].value }}" convert-to="{{ product.options[tag.short_id][$parent.$parent.$index][$index].metric_unit }}"
															       ng-model="product.options[tag.short_id][$parent.$parent.$index][$index].value" ng-pattern="/^\-?\d+((\.|\,)\d+)?$/"
															       class="form-control" placeholder="{{ option.text[lang] }}" name="tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}">
															<div role="alert">
																<span class="error" ng-show="!formOptions['tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}'].$valid"><?= Yii::t("app/deviser", "Numbers only") ?></span>
															</div>
														</div>

														<!-- Alphanumeric type -->
														<div ng-cloak ng-if="option.type === 1">
															<input ng-model="product.options[tag.short_id][$parent.$parent.$index][$index].value" class="form-control"
															       placeholder="{{ option.text[lang] }}" name="tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}">
															<div role="alert">
																<span class="error" ng-show="!formOptions['tag{{ tag.short_id }}index{{ $parent.$parent.$index }}option{{ $index }}'].$valid"><?= Yii::t("app/deviser", "Invalid input") ?></span>
															</div>
														</div>

													</div>

													<div class="col-xs-3 col-xs-offset-1" ng-if="option.metric_type > 0">
														<div
															angular-multi-select
															input-model="mus[option.metric_type].sub"
															output-model="[]"
															tick-property="check"
															preselect-prop="value"
															preselect-value="{{ product.options[tag.short_id][$parent.$parent.$index][$index].metric_unit }}"
															single-output-prop="value"
															single-output-model="product.options[tag.short_id][$parent.$parent.$index][$index].metric_unit"
															button-template="angular-multi-select-btn-data.htm"
															button-label="{{ text }}"
															item-label="{{ text }}"
															selection-mode="single"
															helper-elements="noall nonone noreset nofilter">
														</div>
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

								<br />
								<span class="fc-7aaa4a fs0-857 funiv_bold pointer new-combination" ng-click="create_product_option(tag.short_id)">
									<?= Yii::t("app/deviser", "Create new combination +") ?>
								</span>

							</div>

							<span class="sep"></span>

						</div>

					</div>
				</div>

			</div>

		</div>

		<br />

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
						<div class="product-desc-holder">
							<textarea type="text" placeholder="<?= Yii::t("app/deviser", "Product description"); ?>" ng-model="product.description[tmp_selected_lang_desc]" class="funiv fs1-071 fc-3d product_desc"></textarea>
						</div>
					</div>

					<div class="col-xs-12">

						<div
							angular-multi-select
							input-model="langs"
							single-output-model="tmp_selected_lang_desc"
							single-output-prop="key"

							preselect-prop="key"
							preselect-value='["{{ lang }}"]'

							group-property="sub"
							tick-property="check"

							button-template="angular-multi-select-btn-data.htm"
							button-label="{{ value }}"

							item-label="{{ value }}"
							selection-mode="single"
							helper-elements="noall nonone noreset nofilter">
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

										<!--
										 We need a ng-if for pristine because that is how we decide if we must
										 preselect the dropdown.
										 If pristine === true, it means that the values in the sizechart are an exact
										 copy of the values from the original sizechart, which means we should preselect
										 the dropdown. Else, it means that the deviser modified some values, so we
										 shouldn't preselect anything.
										 -->
										<div ng-if="$parent.product.sizechart.pristine === true" class="col-xs-6 col-height col-middle">
											<div
												angular-multi-select
												api="$parent.$parent.api_sizechart"
												id-property="short_id"
												input-model="$parent.$parent.sizecharts"
												output-model="$parent.$parent.tmp_selected_sizechart"

												tick-property="check"

												preselect-prop="short_id"
												preselect-value='["{{ product.sizechart.short_id }}"]'

												button-template="angular-multi-select-btn-data.htm"
												button-label="{{ name[lang] }}"

												item-label="{{ name[lang] }}"
												selection-mode="single"
												helper-elements="noall nonone noreset nofilter">
											</div>
										</div>
										<div ng-if="$parent.product.sizechart.pristine !== true" class="col-xs-6 col-height col-middle">
											<div
												angular-multi-select
												api="$parent.$parent.api_sizechart"
												id-property="short_id"
												input-model="$parent.$parent.sizecharts"
												output-model="$parent.$parent.tmp_selected_sizechart"

												tick-property="check"

												button-template="angular-multi-select-btn-data.htm"
												button-label="{{ name[lang] }}"

												item-label="{{ name[lang] }}"
												selection-mode="single"
												helper-elements="noall nonone noreset nofilter">
											</div>
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
										<div ng-if="$parent.product.sizechart.pristine === true" class="col-xs-6 col-height col-middle">
											<div
												angular-multi-select
												api="$parent.$parent.api_sizechart_country"
												id-property="value"
												input-model="$parent.$parent.sizechart_countries"
												single-output-model="$parent.$parent.tmp_selected_sizechart_country"
												single-output-prop="value"

												tick-property="check"

												preselect-prop="value"
												preselect-value='["{{ product.sizechart.country }}"]'

												button-template="angular-multi-select-btn-data.htm"
												button-label="{{ text }}"

												item-label="{{ text }}"
												selection-mode="single"
												helper-elements="noall nonone noreset nofilter">
											</div>
										</div>
										<div ng-if="$parent.product.sizechart.pristine !== true" class="col-xs-6 col-height col-middle">
											<div
												angular-multi-select
												api="$parent.$parent.api_sizechart_country"
												id-property="value"
												input-model="$parent.$parent.sizechart_countries"
												single-output-model="$parent.$parent.tmp_selected_sizechart_country"
												single-output-prop="value"

												tick-property="check"

												button-template="angular-multi-select-btn-data.htm"
												button-label="{{ text }}"

												item-label="{{ text }}"
												selection-mode="single"
												helper-elements="noall nonone noreset nofilter">
											</div>
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
												api="$parent.$parent.api_deviser_sizechart"
												id-property="value"
												input-model="$parent.$parent.deviser_sizecharts"
												output-model="$parent.$parent.selected_deviser_sizechart"

												tick-property="check"

												button-template="angular-multi-select-btn-data.htm"
												button-label="{{ name[lang] }}"

												item-label="{{ name[lang] }}"
												selection-mode="single"
												helper-elements="noall nonone noreset nofilter">
											</div>
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
										api="$parent.$parent.api_available_sizes"
										id-property="value"
										input-model="$parent.$parent.available_sizes"
										single-output-model="$parent.$parent.tmp_selected_size"
										single-output-prop="value"

										tick-property="check"

										button-template="angular-multi-select-btn-data.htm"
										button-label="{{ text }}"

										item-label="{{ text }}"
										selection-mode="single"
										helper-elements="noall nonone noreset nofilter">
									</div>
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
							<table class="fc-6d funiv fs1-071 fnormal sizechart-table">
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
											<input ng-if="$index > 0" ng-model="product.sizechart.values[$parent.$parent.$index][$index]" placeholder="0" angular-unit-converter convert-from="mm" convert-to="{{ product.sizechart.metric_unit }}" />
											<span ng-if="$index > 0">{{ product.sizechart.metric_unit }}</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>

					<!--<pre>{{ dump(product.sizechart) }}</pre>-->

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
										single-output-model="product.weight_unit"
										single-output-prop="value"

										preselect-prop="value"
										preselect-value='["{{ product.weight_unit }}"]'

										tick-property="check"

										button-template="angular-multi-select-btn-data.htm"
										button-label="{{ text }}"

										item-label="{{ text }}"
										selection-mode="single"
										helper-elements="noall nonone noreset nofilter">
									</div>
								</div>
								<div class="col-xs-4 col-height col-middle">
									<span><?= Yii::t("app/deviser", "Currency") ?></span>
									<div
										angular-multi-select
										input-model="currencies"
										single-output-model="product.currency"
										single-output-prop="value"

										preselect-prop="value"
										preselect-value='["{{ product.currency }}"]'

										tick-property="check"

										button-template="angular-multi-select-btn-data.htm"
										button-label="{{ symbol }} ({{ value }})"

										item-label="{{ symbol }} ({{ value }})"
										selection-mode="single"
										helper-elements="noall nonone noreset nofilter">
									</div>
								</div>
								<div class="col-xs-4 col-height col-middle">

								</div>
							</div>
						</div>

					</div>

					<br /><br /><br />

					<div class="col-xs-12">

						<div class="fc-333 funiv fs1-071 fnormal" ng-hide="show_pricestock"><?= Yii::t("app/deviser", "The price & stock table will be available when all required tags have at least 1 combination.") ?></div>

						<table class="fc-6d funiv fs1-071 fnormal pricestock-table" ng-show="show_pricestock">
							<thead>
							<tr>
								<th class="fpf fs0-857 fnormal text-center pricestock-cell" ng-cloak ng-if="use_sizecharts === true"><?= Yii::t("app/deviser", "Size") ?></th>
								<th class="fpf fs0-857 fnormal text-center pricestock-cell" ng-cloak ng-repeat="tag_id in $parent._ps_header" ng-init="tag = getTag(tag_id)">
									{{ tag.name[lang] }}
									<span ng-if="tag.type == <?= Tag::DROPDOWN ?>"></span>
									<span ng-if="tag.type == <?= Tag::FREETEXT ?>">(
										<span ng-repeat="option in tag.options">
											{{ option.text[lang] }}<span ng-show="!$last">,</span>
										</span>
									)</span>
								</th>
								<th class="fpf fs0-857 fnormal text-center pricestock-cell"><?= Yii::t("app/deviser", "Weight") ?></th>
								<th class="fpf fs0-857 fnormal text-center pricestock-cell"><?= Yii::t("app/deviser", "Stock") ?></th>
								<th class="fpf fs0-857 fnormal text-center pricestock-cell"><?= Yii::t("app/deviser", "Price") ?></th>
							</tr>
							</thead>
							<tbody>

							<tr ng-cloak ng-repeat="row in product.price_stock" class="pricestock-row" ng-class="{true: 'even', false: 'odd'}[$even]">
								<td ng-cloak ng-if="use_sizecharts === true" class="text-center pricestock-cell">{{ product.price_stock[$index].size }}</td>
								<td ng-repeat="(tag_id, values) in product.price_stock[$index].options" ng-init="tag = getTag(tag_id)" class="text-center pricestock-cell">
									<span ng-if="tag.type == <?= Tag::DROPDOWN ?>">
										<span ng-repeat="value in values">
											{{ getTagOption($parent.tag, value).text[lang] }}
											<span ng-show="!$last">,</span>
										</span>
									</span>

									<span ng-if="tag.type == <?= Tag::FREETEXT ?>">
										<span ng-repeat="value in values">
											{{ value.value }}
											{{ value.metric_unit }}
											<span ng-show="!$last">x</span>
										</span>
									</span>
								</td>
								<td class="text-center pricestock-cell">
									<input class="text-center" type="number" ng-model="product.price_stock[$index].weight" />
								</td>
								<td class="text-center pricestock-cell">
									<input class="text-center" type="number" ng-model="product.price_stock[$index].stock" />
								</td>
								<td class="text-center pricestock-cell">
									<input class="text-center" type="number" ng-model="product.price_stock[$index].price" />
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
											<?= Yii::t("app/deviser", "Money-back guarantee") ?>
										</label>
									</div>
								</div>
								<div class="col-xs-10 col-height col-middle">
									<div ng-hide="product.returns.type == <?= Returns::NONE; ?>" class="input-group spinner">
										<input type="text" class="form-control" ng-model="product.returns.value">
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
										<input type="checkbox" ng-model="product.warranty.type" ng-false-value="<?= Warranty::NONE ?>" ng-true-value="<?= Warranty::DAYS ?>">
										<?= Yii::t("app/deviser", "Warranty") ?>
									</label>
								</div>
								<div class="col-xs-10 col-height col-middle">
									<div ng-hide="product.warranty.type == <?= Warranty::NONE; ?>" class="input-group spinner">
										<input type="text" class="form-control" ng-model="product.warranty.value">
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
