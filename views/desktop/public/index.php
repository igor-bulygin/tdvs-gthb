<?php
use yii\web\View;
use yii\widgets\Pjax;
use app\assets\desktop\pub\IndexAsset;
use yii\widgets\ListView;

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
		<!--<?= Yii::t("app", "This is a test from {0} controller!", $this->context->id); ?>-->
		<div class="relative banner_holder">

			<div id="banner" class="carousel slide" data-ride="carousel" data-keyboard="false">

				<div class="carousel-inner" role="listbox">
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
			</div>

			<div class="absolute devisers_holder">
				<div class="white background"></div>
				<div class="container flex flex-column devisers absolute no-horizontal-padding">
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
					<div class="row no-gutter flex devisers_carousel_wrapper">
						<ul class="devisers_carousel no-vertical-margin flex-prop-1">
							<?php for ($i = 0; $i < 15; $i++) { ?>
							<li class="col-xs-3 col-md-2 col-lg-1 relative deviser_holder text-center">
								<div class="absolute deviser_bg white"></div>
								<div class="absolute deviser">
									<div class="dp relative">
										<img src="https://pbs.twimg.com/profile_images/646281863355604992/oA4qAMpY.jpg" alt="" class="img-circle" />
									</div>

									<div class="text flex flex-column relative">
										<span class="name funiv_bold fs1-143 fc-4a">Foo bar <?= $i ?></span>
										<span class="category fpf fs0-857 fc-9b">Foo bar</span>
									</div>

									<div class="works relative">
										<div class="row no-gutter works_holder">
											<div class="col-xs-6 works_row">
												<img class="work" src="https://pbs.twimg.com/profile_images/378800000802851388/b23fb1b059cc588f115a824c2975ec6f.png" alt="" />
											</div>
											<div class="col-xs-6 works_row">
												<img class="work" src="https://pbs.twimg.com/profile_images/378800000078376536/d3d98226c211f7bd0568453daf540bb4.jpeg" alt="" />
											</div>
										</div>
										<div class="row no-gutter works_holder">
											<div class="col-xs-6 works_row">
												<img class="work" src="http://www.miathletic.com/media/galeria/6/8/4/6/5/t_athletic_club_de_bilbao_moda_y_famoseo-7545648.jpeg" alt="" />
											</div>
											<div class="col-xs-6 works_row">
												<img class="work" src="https://pbs.twimg.com/profile_images/378800000039765887/a02c83cbaa2abdf472148be5b40d7b3c.jpeg" alt="" />
											</div>
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
									<li class="pointer text-center <?= $i == 0 ? '' : '' ?>">
										<a class="fc-5b" data-toggle="tab" href="#<?= $category['short_id'] ?>"><?= $category['name'] ?></a>
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
					<?php Pjax::begin([
						'id' => $category['short_id'],
						'enablePushState' => false
					]); ?>

					<?= ListView::widget([
						'dataProvider' => $data,
						'itemView' => '_index_product',
						'itemOptions' => [
							'tag' => false
						],
						'options' => [
							'class' => 'products_wrapper'
						],
						'layout' => '<div class="products_holder">{items}</div>{pager}',
					]); ?>

					<?php Pjax::end(); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
