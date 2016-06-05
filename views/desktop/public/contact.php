<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\ContactAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Contact',
	'url' => ['/public/contact']
];

ContactAsset::register($this);

$this->title = 'Todevise / Contact';
$lang = Yii::$app->language;

?>

<div class="row no-gutter" ng-controller="contactCtrl" ng-init="init()">
	<div class="col-xs-12 no-padding flex flex-column contact">
		<div class="contact-up flex flex-row">
			<div class="contact-left flex-column">
				<div class="contact-left-top">


					<div class="search-header-content fc-fff">
						<div class="cols-xs12 fs-upper flex flex-align-center flex-column flex-justify-center search-header">
							<div class="search_holder fs0-857">
								<input type="text" class="funiv_bold fs1-143 fc-4e" name="search" placeholder="WHAT IS YOUR QUESTION ABOUT?">
								<span class="pointer glyphicon-content bc-c7 fc-fff">
									<span class="glyphicon glyphicon-search"></span>
								</span>
							</div>
						</div>
					</div>


				</div>
				<div class="contact-left-bottom">
					<div class="flex flex-align-center flex-column flex-justify-center">
						<span class="question fc-9b fs0-857 funiv_bold fs-upper">common cuestion</span>
						<span class="answer fpf fs1">test1</span>
						<span class="question fc-9b fs0-857 funiv_bold fs-upper">common cuestion</span>
						<span class="answer fpf fs1">test2</span>
						<span class="question fc-9b fs0-857 funiv_bold fs-upper">common cuestion</span>
						<span class="answer fpf fs1">test3</span>
						<span class="question fc-9b fs0-857 funiv_bold fs-upper">common cuestion</span>
						<span class="answer fpf fs1">test4</span>
						<?= Html::a(Yii::t("app/public", 'See our full FAQ'), Url::to(['public/faq']), [
							'class' => "link_btn funiv_bold fs1 fc-6b fs-upper"
						]); ?>
					</div>
				</div>
			</div>
			<div class="contact-right flex flex-align-center flex-column flex-justify-center">
				<div class="fs3-857 funiv_thin fs-upper fc-6d"><?= Yii::t('app/public', 'Contact via Message') ?></div>

				<form name="contactForm" class="contactForm funiv_bold fs-upper">
					<div><input ng-model="formData.name" type="text" placeholder="NAME"/></div>
					<div><input ng-model="formData.email" type="text" placeholder="EMAIL"/></div>
					<div class="dropdown">
						<button class="dropbtn fs-upper fc-9" ng-init="showedDropdown=false"  ng-click="showedDropdown = ! showedDropdown">{{formData.selectedText}}</button>
						<div class="dropdown-content" ng-if="showedDropdown">
							<div ng-repeat="(key, option) in formData.dropDownOptions">
								<a href="#" ng-click="selectOption(key)">{{option}}</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="contact-down flex flex-row">
			<div class="contact-down-left red flex flex-align-center flex-column flex-justify-center">
				<div class="fs3-000 funiv_thin fs-upper fc-fff"><?= Yii::t('app/public', 'Contact via Whatsapp') ?></div>
				<div class="fpf_bold fs0-857 fc-fff">
					<?= Yii::t('app/public', 'Just add this number to your phone´s contact list and start a conversation with us!') ?>
				</div>
				<div class="funiv_bold fs1 fs1-587 fc-fff">
					<?= Yii::t('app/public', '+0034 645 234 234') ?>
				</div>
				<div class="fpf_bold fs0-857 fc-fff">
					<?= Yii::t('app/public', 'Because of time zones difference an agent can take up to 24 hours to reply to you message.') ?>
				</div>
			</div>
			<div class="contact-down-center grey-soft flex flex-align-center flex-column flex-justify-center">
				<div class="fs3-000 funiv_thin fs-upper fc-1c1919"><?= Yii::t('app/public', 'Contact via Facebook') ?></div>
				<div class="fpf_bold fs0-857 fc-1c1919">
					<?= Yii::t('app/public', 'You can message us via Facebook. Our social media team will reply in less than 24 hours.') ?>
				</div>
				<div class="fpf_bold fs0-857 fc-1c1919">
					<?= Yii::t('app/public', 'Click the button below to go to our profile') ?>
				</div>
				<?= Html::a(Yii::t("app/public", 'Contact via Facebook'), Url::to(['']), [
					'class' => "link_btn_white funiv_bold fs1-143 red fc-fff"
				]); ?>
			</div>
			<div class="contact-down-right grey-hard flex flex-align-center flex-column flex-justify-center">
				<div class="fs3-000 funiv_thin fs-upper fc-fff"><?= Yii::t('app/public', 'Contact via Phone') ?></div>
				<div class="fpf_bold fs0-857 fc-fff">
					<?= Yii::t('app/public', 'Contact us via phone, our customer service representative are here to help you.') ?>
				</div>
				<div class="funiv_bold fs1 fs1-587 fc-fff">
					<?= Yii::t('app/public', '+0034 645 234 234') ?>
				</div>
			</div>
		</div>

		<?php

			//Pjax::begin([
			//	'enablePushState' => false
			//]);
			// echo ListView::widget([
			// 	'dataProvider' => $products,
			// 	'itemView' => '_category_product',
			// 	'itemOptions' => [
			// 		'tag' => false
			// 	],
			// 	'options' => [
			// 		'class' => 'products_wrapper'
			// 	],
			// 	'layout' => '<div class="funiv fs1-143 fc-6d">{summary}</div><div class="products_holder">{items}</div>{pager}',
			// ]);
      //echo $test;

			//Pjax::end();

		?>

	</div>
</div>
