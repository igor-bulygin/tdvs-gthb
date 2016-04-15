<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\ProductAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Product',
	'url' => ['/public/product']
];

ProductAsset::register($this);

$this->title = 'Todevise / Product';
?>

<div class="row no-gutter product relative flex flex-column">

	<div class="bg absolute" style="background-image: url('https://unsplash.it/1920/1080');">

	</div>

	<div class="col-xs-12 deviser_wrapper flex-prop-0-0">

		<div class="row no-gutter relative">
			<div class="col-xs-12 name funiv_thin fs3-857 fc-4f fs-upper">
				<?= Utils::l($product->name) ?>
			</div>

			<div class="info_bg absolute"></div>
			<div class="info absolute">
				<div class="row no-gutter max-height lwhite">
					<div class="col-xs-2 max-height avatar_wrapper flex flex-align-center">
						<div class="avatar img-circle max-height" style="background-image: url('https://unsplash.it/240/120');"></div>
					</div>

					<div class="col-xs-8 max-height flex flex-column flex-justify-center">
						<span class="category funiv fs0-786 fc-9b fs-upper">art</span>
						<span class="name funiv_ultra fs-upper fs1-286 fc-48"><?= $deviser->personal_info['name'] . " " . implode(" ", $deviser->personal_info['surnames']) ?></span>
					</div>

					<div class="col-xs-2 max-height stock_price_wrapper flex flex-column flex-justify-center text-right">
						<span class="stock funiv fs0-786 fc-7aaa4a">6 stock</span>
						<span class="price funiv_ultra fs1-571 fc-f7284b">4700€</span>
					</div>

					<div class="col-xs-12 lwhite">

					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="col-xs-12 gallery_wrapper flex flex-prop-1">

		<div class="row no-gutter relative flex flex-prop-1">

			<div class="col-xs-12 gallery flex flex-prop-1">
				<div class="carosel flex flex-prop-1">
					<div class="carosel-control carosel-control-left"></div>
					<div class="carosel-inner flex-prop-1">
						<img class="carosel-item" src="http://www.discovertheforest.org/images/hero/home/6.jpg" />
						<img class="carosel-item" src="https://upload.wikimedia.org/wikipedia/commons/7/7b/Height_'n'_Width.jpg" />
						<img class="carosel-item" src="http://www.orderofinterbeing.org/wp-content/uploads/2014/04/light-forest.jpg" />
						<img class="carosel-item" src="https://unsplash.it/1200/800?random=4" />
						<img class="carosel-item" src="https://unsplash.it/1200/800?random=5" />
						<img class="carosel-item" src="https://unsplash.it/1200/800?random=6" />
					</div>
					<div class="carosel-control carosel-control-right"></div>
				</div>
			</div>

			<div class="info_bg absolute">

			</div>
		</div>

	</div>

	<div class="col-xs-12 no-horizontal-padding tabs-wrapper flex flex-prop-0-0">
		<ul class="flex funiv_bold fs0-857 fs-upper tabs no-horizontal-padding no-vertical-margin">
			<li class="pointer text-center">
				<a class="fc-5b" data-toggle="tab" href="#deviser_works"><?= Yii::t('app/public', 'Works by {name} {surnames}', [
					'name' => $deviser->personal_info['name'],
					'surnames' => implode(" ", $deviser->personal_info['surnames'])
				]) ?></a>
			</li>
			<li class="pointer text-center">
				<a class="fc-5b" data-toggle="tab" href="#related_works"><?= Yii::t('app/public', 'Related works') ?></a>
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

			<div role="tabpanel" class="tab-pane fade in" id="deviser_works">
				<?php
/*
				Pjax::begin([
					'id' => $category['short_id'],
					'enablePushState' => false,
					'linkSelector' => '.pagination a'
				]);

				$form = ActiveForm::begin([
					'id' => 'category_filter_' . $category['short_id'],
					'options' => [
						'data-pjax' => true
					]
				]);

				echo $form->field($category['filter_model'], 'selected')->radioList([
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
*/
				?>
			</div>

		</div>
	</div>
</div>
