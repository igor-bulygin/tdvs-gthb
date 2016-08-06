<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\IndexAsset;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/public/index']
];

IndexAsset::register($this);

$this->title = 'Todevise / Home';
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding">
		<div class="relative banner_holder">

			<figure id="banner" class="carousel slide relative" data-ride="carousel" data-keyboard="false">

				<div class="carousel-inner absolute" role="listbox">
					<?php foreach ($banners as $i => $banner) { ?>
						<div class="flex item <?= $i == 0 ? 'active' : ''  ?>">
							<img src="<?= $banner['img'] ?>" alt="" />
							<div class="carousel-caption flex flex-column">
								<span class="name funiv_thin fs4-714 fc-1c1919 ls0-01 fs-upper"><?= $banner['caption']['name'] ?></span>
								<span class="category fpf fc-9b fs0-857"><?= $banner['caption']['category'] ?></span>
								<a class="pointer works black funiv_bold fs-upper fc-fff fs1-143"><?= Yii::t('app/public', 'View works') ?></a>
							</div>
						</div>
					<?php } ?>
				</div>

				<!-- Controls -->
				<a class="left carousel-control" href="#banner" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
				</a>
				<a class="right carousel-control" href="#banner" role="button" data-slide="next">
					<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				</a>
			</figure>

			<div class="absolute devisers_holder">
				<div class="white background"></div>
				<div class="container flex flex-column devisers absolute no-horizontal-padding">
					<!-- TODO: enable when needed -->
					<!--
					<div class="row no-gutter">
						<div class="col-xs-12 no-horizontal-padding">
							<div class="flex fpf fc-5b fs0-857 fs-upper links">
								<span class="active pointer"><?= Yii::t('app/public', 'Most popular') ?></span>
								<span class="pointer"><?= Yii::t('app/public', 'Newest') ?></span>
								<span class="pointer"><?= Yii::t('app/public', 'More ecological') ?></span>
								<div class="flex-prop-1"></div>
								<span class="pointer text-right"><?= Yii::t('app/public', 'View all devisers') ?></span>
							</div>
						</div>
					</div>
					-->
					<div class="row no-gutter flex devisers_carousel_wrapper">
						<ul class="devisers_carousel no-vertical-margin flex-prop-1">
							<?php foreach ($devisers as $i => $deviser) { ?>
							<li class="col-xs-3 col-md-2 col-lg-1 relative deviser_holder text-center">
								<div class="absolute deviser_bg white"></div>
								<div class="absolute deviser">
									<a class="dp_wrapper relative" href="<?= Url::to(["public/deviser", "slug" => $deviser["slug"], "deviser_id" => $deviser["short_id"]]) ?>">
										<div class="dp relative">
											<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize(@$deviser['img'])->resize(0, 110) ?>" alt="" class="img-circle" />
										</div>
									</a>

									<div class="text relative">
										<a class="flex flex-column" href="<?= Url::to(["public/deviser", "slug" => $deviser['slug'], "deviser_id" => $deviser["short_id"]]) ?>">
											<span class="name funiv_bold fs1-143 fc-4a"><?= $deviser['name'] ?></span>
											<span class="category fpf fs0-857 fc-9b"><?= $deviser['category'] ?></span>
										</a>
									</div>

									<div class="works relative">
										<div class="row no-gutter works_holder">
											<?php foreach ($deviser['works'] as $j => $work) { ?>
												<div class="col-xs-6 works_row">
													<a href="<?= Url::to(["public/product", "slug" => $work['slug'], "category_id" => @$work['categories'][0], "product_id" => $work['short_id']]) ?>">
														<img class="work" src="<?= Utils::url_scheme() ?><?= Utils::thumborize(@$work['img'])->resize(0, 60) ?>" alt="" />
													</a>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
					<div class="row no-gutter">
						<div class="col-xs-12 no-horizontal-padding">
							<ul class="flex funiv_bold fs0-857 fs-upper tabs no-horizontal-padding no-vertical-margin">
								<?php foreach ($categories as $i => $category) { ?>
									<li class="pointer text-center">
										<a class="fc-5b" data-toggle="tab" href="#<?= $category['short_id'] ?>"><?= $category["name"] ?></a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row no-gutter">
	<div class="col-xs-12 no-padding">
		<div class="tab-content products">
			<?php foreach ($categories as $i => $category) { ?>
				<div role="tabpanel" class="tab-pane fade <?= $i == 0 ? 'in ' : '' ?>" id="<?= $category['short_id'] ?>">
					<?php

					Pjax::begin([
						'id' => $category['short_id'],
						'enablePushState' => false,
						'timeout' => 5000
					]);

					$form = ActiveForm::begin([
						'id' => 'category_filter_' . $category['short_id'],
						'options' => [
							'data-pjax' => true
						]
					]);

					//TODO: Enable when filters are required...
					/*echo*/ $form->field($category['filter_model'], 'selected')->radioList([
						'odd' => Yii::t('app/public', 'Odd products'),
						'even' => Yii::t('app/public', 'Even products')
					], [
						'unselect' => null,
						'item' => function ($index, $label, $name, $checked, $value) use ($form) {
							$active_class = $checked ? 'active' : '';
							return Html::radio($name, $checked, [
								'data-role' => 'filter',
								'label' => Html::tag('span', $label, [
									'class' => "pointer filter-span fpf fc-5b fs-upper fs0-857 $active_class"
								]),
								'labelOptions' => [
									'class' => 'no-margin'
								],
								'value' => $value,
								'onclick' => '$("#' . $form->id . '").yiiActiveForm("submitForm");'
							]);
						},
						'class' => 'filters text-center white'
					])->label(false);

					echo ListView::widget([
						'dataProvider' => $category['products'],
						'itemView' => '_index_product',
						'itemOptions' => [
							'tag' => false
						],
						'options' => [
							'class' => 'products_wrapper'
						],
						'layout' => '<div class="products_holder">{items}</div>{pager}',
					]);

					ActiveForm::end();

					Pjax::end();

					?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
