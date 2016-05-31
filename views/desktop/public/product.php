<?php
use yii\web\View;
use app\models\Tag;
use yii\helpers\Url;
use app\models\Lang;
use yii\widgets\Pjax;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Returns;
use app\models\Warranty;
use app\models\TagOption;
use yii\widgets\ListView;
use app\helpers\Currency;
use app\assets\desktop\pub\ProductAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Product',
	'url' => ['/public/product']
];

ProductAsset::register($this);

$this->title = 'Todevise / Product';
?>

<div class="row no-gutter product relative flex flex-column" ng-controller="productCtrl" ng-init="init()">

	<?php $this->registerJs("var _tags = " . Json::encode($tags) . ";", View::POS_END); ?>
	<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_END); ?>
	<?php $this->registerJs("var _product = " . Json::encode($product) . ";", View::POS_END); ?>
	<?php $this->registerJs("var _colors = " . Json::encode(TagOption::COLORS) . ";", View::POS_HEAD); ?>

	<?php foreach ($product['media']['photos'] as $photo) {
		if (isset($photo['main_product_photo']) && $photo['main_product_photo'] === true) {
	?>
			<div class="bg absolute" style="background-image: url('<?= Yii::getAlias('@product_url') ?>/<?= $product['short_id'] ?>/<?= $photo['name'] ?>');"></div>
	<?php
		}
	} ?>

	<div class="col-xs-12 deviser_wrapper flex-prop-0-0">

		<div class="row no-gutter relative">
			<div class="col-xs-12 name funiv_thin fs3-857 fc-4f fs-upper">
				<?= $product['name'] ?>
			</div>

			<div class="info_bg absolute"></div>
			<div class="info absolute">
				<div class="row no-gutter max-height lwhite">
					<div class="col-xs-2 max-height avatar_wrapper flex flex-align-center">
						<div class="avatar img-circle max-height" style="background-image: url('<?= $deviser['img'] ?>');"></div>
					</div>

					<div class="col-xs-7 max-height flex flex-column flex-justify-center">
						<span class="category funiv fs0-857 fc-9b fs-upper"><?= $product['category'] ?></span>
						<span class="name funiv_bold fs1-286 fc-48"><?= $deviser['fullname'] ?></span>
					</div>

					<div class="col-xs-3 max-height stock_price_wrapper flex flex-column flex-justify-center text-right">
						<span ng-cloak class="stock funiv fs0-857 fc-7aaa4a">{{ selected_options_match.stock }} <?= Yii::t('app/public', 'stock') ?></span>
						<span ng-cloak class="price funiv_bold fs1-571 fc-7ab83a">{{ selected_options_match.price }} <?= Currency::getSymbol($product['currency']) ?></span>
					</div>

					<div class="col-xs-12 lwhite"></div>
				</div>
			</div>
		</div>

	</div>

	<div class="col-xs-12 gallery_wrapper flex flex-prop-1">

		<div class="row no-gutter relative flex flex-prop-1 max-width">

			<div class="col-xs-12 gallery flex flex-prop-1">
				<div class="carosel flex flex-prop-1">
					<div class="carosel-control carosel-control-left"></div>
					<div class="carosel-inner flex flex-prop-1">
						<?php foreach ($product['media']['photos'] as $photo) { ?>
							<div class="carosel-item">
								<img src="<?= Yii::getAlias('@product_url') ?>/<?= $product['short_id'] ?>/<?= $photo['name'] ?>" />
							</div>
						<?php } ?>
					</div>
					<div class="carosel-control carosel-control-right"></div>
				</div>
			</div>

			<div class="info_bg absolute"></div>
			<div class="info absolute">
				<div class="row no-gutter max-height flex flex-column flex-justify-around">
					<div class="attributes flex flex-column flex-prop-1-0 lwhite">

						<div class="" ng-cloak ng-if="product.sizechart.short_id != undefined">
							<div class="row no-gutter option-row">
								<div class="col-lg-2 col-xs-3">
									<span class="cdefault funiv_bold fc-6d fs0-929 fs-upper"><?= Yii::t('app/public', 'Size') ?></span>
								</div>
								<div class="col-lg-10 col-xs-9">
									<span class="option pointer flex-inline funiv fs0-857 fc-4a bc-4a"
										ng-class="{'selected': selected_options_index['size'] == $index }" ng-cloak
										ng-repeat="row in product.sizechart.values"
										ng-click="selected_options['size'] = row[0] ; selected_options_index['size'] = $index">
										{{ row[0] }}
									</span>
								</div>
							</div>
						</div>

						<div class="" ng-cloak ng-repeat="(tag_id, values) in product.options">

							<div class="" ng-cloak ng-init="tag = getTag(tag_id)" >

								<div ng-cloak ng-if="tag.stock_and_price == true">

									<div class="row no-gutter option-row">
										<div class="col-lg-2 col-xs-3">
											<span class="cdefault funiv_bold fc-6d fs0-929 fs-upper">{{ tag.name[lang] }}</span>
										</div>
										<div class="col-lg-10 col-xs-9">
											<span class="flex" ng-if="tag.type == <?= Tag::FREETEXT ?>">
												<span class="option pointer funiv fs0-857 fc-4a bc-4a"
													ng-repeat="values in product.options[tag.short_id]"
													ng-click="selected_options[tag.short_id] = values ; selected_options_index[tag.short_id] = $index"
													ng-class="{'selected': selected_options_index[tag.short_id] == $index }">
													<span ng-repeat="value in values">
														{{ value.value }}{{ value.metric_unit }}
													</span>
												</span>
											</span>

											<span class="flex" ng-if="tag.type == <?= Tag::DROPDOWN ?>">
												<span class="option flex-inline pointer funiv fs0-857 fc-4a bc-4a"
													ng-repeat="values in product.options[tag.short_id]"
													ng-click="selected_options[tag.short_id] = values ; selected_options_index[tag.short_id] = $index"
													ng-class="{'selected': selected_options_index[tag.short_id] == $index }">
													<span ng-repeat="value in values">
														<div ng-init="option = getTagOption($parent.tag, value)">
															<span ng-if="option.is_color != 1">{{ option.text[lang] }}</span>
															<div ng-if="option.is_color == 1" class="color-cell {{ get_color_from_value(option.value).class }} pull-left" title="{{ option.text[lang] }}"></div>
														</div>
													</span>
												</span>
											</span>
										</div>
									</div>
								</div>

							</div>

						</div>

					</div>

					<div class="buttons flex flex-justify-center flex-prop-1-0 flex-prop funiv_ultra fs-upper fc-3d fs1 lwhite">
						<span class="savebox pointer">
							<span class="glyphicon glyphicon-arrow-down"></span>
							<span class="funiv_bold fs1 fs-upper"><?= Yii::t('app/public', 'Save in a box') ?></span>
						</span>
						<span class="addcart pointer">
							<span class="glyphicon glyphicon-shopping-cart"></span>
							<span class="funiv_bold fs1 fs-upper" ng-click="addToCart()"><?= Yii::t('app/public', 'Add to cart') ?></span>
						</span>
					</div>

					<div class="panel-group flex flex-column no-margin" id="accordion" role="tablist" aria-multiselectable="true">

						<div class="panel panel-default panel-description open flex flex-column">
							<div class="panel-heading flex flex-prop-0-0" role="tab" id="headingOne">
								<div class="panel-title description_title funiv_bold fs0-929 fc-6d fs-upper relative max-width">
									<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										<?= Yii::t('app/public', 'Description') ?>
									</a>
									<span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span>
								</div>
							</div>
							<div id="collapseOne" class="panel-collapse collapse in description_wrapper overflow" role="tabpanel" aria-labelledby="headingOne">
								<span class="panel-body description no-padding block fpf fs1 fc-1c1919"><?= $product['description'] ?></span>
							</div>
						</div>

						<div class="panel panel-default panel-characteristics closed flex flex-column">
							<div class="panel-heading flex flex-prop-0-0" role="tab" id="headingTwo">
								<div class="panel-title characteristics_title funiv_bold fs0-929 fc-6d fs-upper relative max-width">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										<?= Yii::t('app/public', 'Characteristics') ?>
									</a>
									<span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span>
								</div>
							</div>
							<div id="collapseTwo" class="panel-collapse collapse characteristics_wrapper overflow" role="tabpanel" aria-labelledby="headingTwo">
								<div class="panel-body block no-padding fpf fs1 fc-1c1919">
									<div class="characteristic" ng-cloak ng-repeat="tag in tags | filter : {stock_and_price: '!true'}">
										{{ tag.name[lang] }}:

										<span ng-if="tag.type == <?= Tag::FREETEXT ?>">
											<span ng-repeat="values in product.options[tag.short_id]">
												<span ng-repeat="value in values">
													{{ value.value }}{{ value.metric_unit }}
												</span><span ng-show="!$last">, </span>
											</span>
										</span>

										<span ng-if="tag.type == <?= Tag::DROPDOWN ?>">
											<span ng-repeat="values in product.options[tag.short_id]">
												<span ng-repeat="value in values">
													{{ getTagOption($parent.tag, value).text[lang] }}<span ng-show="!$last">, </span>
												</span>
											</span>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-default panel-work-policies closed flex flex-column">
							<div class="panel-heading flex flex-prop-0-0" role="tab" id="headingThree">
								<div class="panel-title policies_title funiv_bold fs0-929 fc-6d fs-upper relative max-width">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										<?= Yii::t('app/public', 'Work policies') ?>
									</a>
									<span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span>
								</div>
							</div>
							<div id="collapseThree" class="panel-collapse collapse policies_wrapper overflow" role="tabpanel" aria-labelledby="headingThree">
								<div class="panel-body block policies no-padding fpf fs1 fc-1c1919 flex flex-column">
									<span>
										<?php
										if ($product["returns"]["type"] == Returns::DAYS) {
											echo Yii::t('app/public', '{days,plural,=0{No returns} =1{Money-back guarantee of # day} other{Money-back guarantee of # days}}.', ['days' => $product["returns"]["value"]]);
										}
										?>
									</span>
									<span>
										<?php
										if ($product["warranty"]["type"] == Warranty::MONTHS) {
											echo Yii::t('app/public', '{months,plural,=0{# No warranty} =1{# month warranty} other{# months warranty}}.', ['months' => $product["warranty"]["value"]]);
										}
										?>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="flex flex-justify-end flex-prop-1-0 social_wrapper">
						<span class="social_title funiv fc-64 fs1"><?= Yii::t('app/public', 'Share on') ?></span>
						<div class="ssk-round ssk-grayscale ssk-group ssk-xs">
							<a href="" class="ssk ssk-facebook"></a>
							<a href="" class="ssk ssk-twitter"></a>
							<a href="" class="ssk ssk-google-plus"></a>
							<a href="" class="ssk ssk-pinterest"></a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="col-xs-12 no-horizontal-padding tabs-wrapper flex flex-prop-0-0">
		<ul class="flex funiv_bold fs0-857 fs-upper tabs no-horizontal-padding no-vertical-margin">
			<li class="pointer text-center active">
				<a class="fc-5b" data-toggle="tab" href="#deviser_works"><?= Yii::t('app/public', 'Works by {name} {surnames}', [
					'name' => $deviser['personal_info']['name'],
					'surnames' => implode(" ", $deviser['personal_info']['surnames'])
				]) ?></a>
			</li>
			<li class="pointer text-center">
				<a class="fc-5b" data-toggle="tab" href="#boxes"><?= Yii::t('app/public', 'Boxes') ?></a>
			</li>
			<li class="pointer text-center">
				<a class="fc-5b" data-toggle="tab" href="#comments"><?= Yii::t('app/public', 'Comments') ?></a>
			</li>
		</ul>
	</div>
</div>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding">
		<div class="tab-content products">

			<div role="tabpanel" class="tab-pane fade in active" id="deviser_works">
				<?php

					Pjax::begin([
						'enablePushState' => false
					]);

					echo ListView::widget([
						'dataProvider' => $other_works,
						'itemView' => '_product_product',
						'itemOptions' => [
							'tag' => false
						],
						'options' => [
							'class' => 'products_wrapper'
						],
						'layout' => '<div class="products_holder">{items}</div>{pager}',
					]);

					Pjax::end();

				?>
			</div>

			<div role="tabpanel" class="tab-pane fade" id="boxes">
				boxes
			</div>

			<div role="tabpanel" class="tab-pane fade" id="comments">
				comments
			</div>

		</div>
	</div>
</div>
