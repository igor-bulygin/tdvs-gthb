<?php

use app\assets\desktop\pub\DeviserAsset;
use yii\widgets\ListView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Deviser',
	'url' => ['/public/deviser']
];

DeviserAsset::register($this);

$this->title = 'Todevise / Deviser';
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding">
		<div class="relative profile_holder">

			<figure class="profile_pic" style="background-image: url('<?= $deviser['img_header'] ?>')"></figure>

			<div class="absolute data_holder max-width">
				<div class="container flex flex-column data no-horizontal-padding max-width">

					<div class="row no-gutter">
						<div class="col-xs-12 no-horizontal-padding text-center deviser_avatar">
							<img src="<?= $deviser['img'] ?>" class="img-circle">
						</div>
						<div class="col-xs-12 no-horizontal-padding text-center relative">
							<div class="deviser_data_bg absolute background max-width"></div>
							<div class="row no-gutter flex relative">
								<div class="col-xs-2 no-horizontal-padding text-center fc-fff fs1 fpf_bold deviser_categories">
									<?= $deviser['category'] ?>
								</div>
								<div class="col-xs-10 no-horizontal-padding text-center fc-fff fs4 funiv_thin fs-upper deviser_name">
									<?= $deviser['name'] ?>
								</div>
								<div class="col-xs-2 no-horizontal-padding text-center ">
									<!-- Reserved for star button -->
								</div>
							</div>
						</div>
						<div class="col-xs-12 no-horizontal-padding">
							<ul class="flex funiv_bold fs0-857 fs-upper tabs no-horizontal-padding no-vertical-margin">
								<li class="pointer text-center">
									<a class="fc-5b block" data-toggle="tab" href="#works"><?= Yii::t("app/public", "Works") ?></a>
								</li>
								<li class="pointer text-center">
									<a class="fc-5b block" data-toggle="tab" href="#boxes"><?= Yii::t("app/public", "Boxes") ?></a>
								</li>
								<li class="pointer text-center">
									<a class="fc-5b block" data-toggle="tab" href="#stories"><?= Yii::t("app/public", "Stories") ?></a>
								</li>
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

			<div role="tabpanel" class="tab-pane fade in active" id="works">
			<?php

				Pjax::begin([
					'enablePushState' => false,
					'timeout' => 5000
				]);

				echo ListView::widget([
					'dataProvider' => $works,
					'itemView' => '_deviser_product',
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

			<div role="tabpanel" class="tab-pane fade in" id="boxes">
				boxes
			</div>

			<div role="tabpanel" class="tab-pane fade in" id="stories">
				stories
			</div>

		</div>
	</div>
</div>
