<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\assets\publicFooterAsset;

publicFooterAsset::register($this);

?>

<div class="content fc-fff text-center">
	<div class="bg_img"></div>
	<div class="main_holder">

		<div class="row no-gutter main">
			<div class="col-xs-3 fs-upper">
				<div class="center-justify">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/public', 'Explore') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Art'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Fashion'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Industrial design'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Jewelry'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'More'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Discover out devisers'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xs-3 fs-upper">
				<div class="center-justify">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/public', 'Help & Contact') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Art'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Returns & Warranties'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'Contact us'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'FAQs'), Url::to(['public/faq']), [
								'class' => "fc-c7"
							]); ?>
						</li>
						<li class="fs1-786">·</li>
						<li>
							<?= Html::a(Yii::t("app/public", 'About us'), "", [
								'class' => "fc-c7"
							]); ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xs-3 fs-upper">
				<div class="center-justify">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/public', 'Do you want to become a deviser?') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786 dot_deviser no-margin">·</li>
						<li class="become_deviser flex flex-justify-between flex-align-baseline">
							<?= Html::a(Yii::t("app/public", 'Go for it!'), "", [
								'class' => "fc-c7"
							]); ?>
							<?= Html::a(Yii::t("app/public", 'Become a deviser'), "", [
								'class' => "link_btn red funiv_bold fs1 fc-fff"
							]); ?>
						</li>
					</ul>

					<span class="blogger fs0-857 funiv_bold fc-fff"><?= Yii::t('app/public', 'Do you want to become a blogger?') ?></span>
					<ul class="fc-c7 list links no-horizontal-padding no-margin list fc-c7 funiv fs0-786 ls0-02">
						<li class="fs1-786 dot_blogger no-margin">·</li>
						<li class="become_blogger flex flex-justify-between flex-align-baseline">
							<?= Html::a(Yii::t("app/public", 'Contact us'), "", [
								'class' => "fc-c7"
							]); ?>
							<?= Html::a(Yii::t("app/public", 'Become a blogger'), "", [
								'class' => "link_btn funiv_bold fs1 fc-fff"
							]); ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xs-3 fs-upper">
				<div class="center-justify">
					<span class="fs0-857 funiv_bold fc-fff"><?= Yii::t('app/public', 'Subscribe to our newsletter') ?></span>

					<div class="newsletter_holder">
						<input type="text" class="funiv_bold fs1-143 fc-4e" name="email" placeholder="<?= Yii::t('app/public', 'E-mail') ?>">
						<span class="pointer glyphicon glyphicon-circle-arrow-right fc-f7284b"></span>
					</div>
				</div>
			</div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-12 fs-upper funiv fs0-786 ls0-05 links">
				<?= Html::a(Yii::t("app/public", 'Terms & Conditions'), "site/about", [
					'class' => "fc-c7"
				]); ?>
				<span class="dot fc-c7 fs1-500">·</span>
				<?= Html::a(Yii::t("app/public", 'Privacy'), "site/about", [
					'class' => "fc-c7"
				]); ?>
				<span class="dot fc-c7 fs1-500">·</span>
				<?= Html::a(Yii::t("app/public", 'Cookies policy'), "site/about", [
					'class' => "fc-c7"
				]); ?>
			</div>
		</div>

		<div class="row no-gutter copyright">
			<div class="col-xs-12 fc-fff fs0-857 funiv ls0-03">
				<span>©</span>
				<span class="fs-upper funiv_bold"><?= date("Y") ?> Todevise</span>
				<span><?= Yii::t('app/public', 'All rights reserved') ?></span>
			</div>
		</div>

	</div>
</div>
