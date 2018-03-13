<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = Yii::t('app/public', 'ABOUT_US');

?>

<div class="about-us-wrapper">
	<div class="container">
		<div class="about-slide-1">
			<img class="logo" src="/imgs/logo.png">
			<p><?=Yii::t('app/public', 'ABOUT_US_TODEVISE_1')?></p>
		</div>
		<div class="about-slide-2">
			<p class="title"><?=Yii::t('app/public', 'ABOUT_US_PHILOSOPHY')?></p>
			<p><?=Yii::t('app/public', 'ABOUT_US_PHILOSOPHY_TEXT')?></p>
		</div>
		<div class="about-slide-3">
			<div class="side-text">
				<p class="title"><?=Yii::t('app/public', 'ABOUT_US_DEVISERS')?></p>
				<p><?=Yii::t('app/public', 'ABOUT_US_DEVISERS_TEXT_1')?></p>
				<p><?=Yii::t('app/public', 'ABOUT_US_DEVISERS_TEXT_2')?></p>
			</div>
		</div>
		<div class="about-slide-4">
			<p class="title"><?=Yii::t('app/public', 'ABOUT_US_SHOPPING_EXPERIENCE')?></p>
			<p><?=Yii::t('app/public', 'ABOUT_US_SHOPPING_EXPERIENCE_1')?></p>
		</div>
	</div>
</div>
