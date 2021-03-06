<?php

use app\components\assets\publicFooterAsset;
use yii\helpers\Html;
use yii\helpers\Url;

publicFooterAsset::register($this);

?>

<div class="content fc-fff text-center">
	<div class="bg_img"></div>
	<div class="main_holder">

		<div class="row no-gutter main">
			<div class="col-xs-3 fs-upper">
				<div class="center-justify">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/old', 'Explore') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'Art'), Url::to(['public/category', 'category_id' => '1a23b', 'slug' => 'art']), [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'Fashion'), Url::to(['public/category', 'category_id' => '4a2b4', 'slug' => 'fashion']), [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'Industrial design'), Url::to(['public/category', 'category_id' => '2p45q', 'slug' => 'industrial-design']), [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'Jewelry'), Url::to(['public/category', 'category_id' => '3f78g', 'slug' => 'Jewelry']), [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'More'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'Discover our devisers'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xs-3 fs-upper">
				<div class="center-justify">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/old', 'Help & Contact') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'Returns & Warranties'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'Contact us'), Url::to(['public/contact']), [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'FAQs'), Url::to(['public/faq']), [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/old", 'ABOUT_US'), Url::to(['public/terms']), [
								'class' => "fc-c7"
							]); ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xs-3 fs-upper">
				<div class="center-justify">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/old', 'Do you want to become a deviser?') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786 dot_deviser no-margin">·</li>
						<li class="become_deviser flex flex-justify-between flex-align-baseline">
							<?= Yii::t("app/old", 'Go for it!')?>
							<?= Html::a(Yii::t("app/old", 'Become a deviser'), Url::to(['public/become']), [
								'class' => "link_btn red funiv_bold fs1 fc-fff"
							]); ?>
						</li>
					</ul>

					<span class="blogger fs0-857 funiv_bold fc-fff"><?= Yii::t('app/old', 'Do you want to become a blogger?') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786 dot_blogger no-margin">·</li>
						<li class="become_blogger flex flex-justify-between flex-align-baseline">
								<?=Yii::t("app/old", 'Contact us')?>

							<?= Html::a(Yii::t("app/old", 'Become a blogger'), "", [
								'class' => "link_btn funiv_bold fs1 fc-fff"
							]); ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xs-3 fs-upper">
				<div class="center-justify feedback-box">
					<span class="fs0-857 funiv_bold fc-fff "><?= Yii::t('app/old', 'Subscribe to our newsletter') ?></span>

					<div class="newsletter_holder">
						<input type="text" class="funiv_bold fs1-143 fc-4e" name="email" placeholder="<?= Yii::t('app/old', 'E-mail') ?>">
						<span class="pointer glyphicon glyphicon-circle-arrow-right fc-f7284b"></span>
					</div>

					<div class="connectd_holder">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/old', 'Stay connected') ?></span>
					<div class="social-icons flex flex-row flex-justify-end">
						<div class="icon-content"><a href="https://www.facebook.com/todevise"><i class="icon-2x icon-facebook"></i></a></div>
						<div class="icon-content"><a href="https://twitter.com/todevise"><i class="icon-2x icon-twitter"></i></a></div>
						<div class="icon-content"><a href="https://plus.google.com/+Todevise"><i class="icon-2x icon-googleplus"></i></a></div>
						<div class="icon-content"><a href="https://es.pinterest.com/todevise/"><i class="icon-2x icon-pinterest"></i></a></div>
					</div>
				</div>


				</div>
			</div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-12 fs-upper funiv fs0-786 ls0-05 links">
				<?= Html::a(Yii::t("app/old", 'Terms & Conditions'), Url::to(['public/terms']), [
					'class' => "fc-c7"
				]); ?>
				<span class="dot fc-c7 fs1-500">·</span>
				<?= Html::a(Yii::t("app/old", 'Privacy'), "site/about", [
					'class' => "fc-c7"
				]); ?>
				<span class="dot fc-c7 fs1-500">·</span>
				<?= Html::a(Yii::t("app/old", 'Cookies policy'), "site/about", [
					'class' => "fc-c7"
				]); ?>
			</div>
		</div>

		<div class="row no-gutter copyright">
			<div class="col-xs-12 fc-fff fs0-857 funiv ls0-03">
				<span>©</span>
				<span class="fs-upper funiv_bold"><?= date("Y") ?> Todevise</span>
				<span><?= Yii::t('app/old', 'ALL_RIGHTS_RESERVED') ?></span>
			</div>
		</div>

	</div>
</div>
