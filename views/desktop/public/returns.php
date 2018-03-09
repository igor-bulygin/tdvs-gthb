<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = Yii::t('app/returns', 'TITLE');

?>

<div class="returns-warranties-wrapper">
	<div class="container">
		<div class="row">
			<h1><?=Yii::t('app/returns', 'TITLE')?></h1>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 img_div">
				<img src="/imgs/returns_1.png">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 number_div">
				<div class="row">
					<div class="col-xs-2 number">
						<p>1.</p>
					</div>
					<div class="col-xs-10 title">
						<p><?=Yii::t('app/returns', 'BLOCK_1_TITLE')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-2">
						<p><?=Yii::t('app/returns', 'BLOCK_1_TEXT_1')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-2">
						<p><?=Yii::t('app/returns', 'BLOCK_1_TEXT_2')?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 img_div">
				<img src="/imgs/returns_2.png">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 number_div">
				<div class="row">
					<div class="col-xs-2 number">
						<p>2.</p>
					</div>
					<div class="col-xs-10 title">
						<p><?=Yii::t('app/returns', 'BLOCK_2_TITLE')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-2">
						<p><?=Yii::t('app/returns', 'BLOCK_2_TEXT_1')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-2">
						<p><?=Yii::t('app/returns', 'BLOCK_2_TEXT_2')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-2 bold">
						<p><?=Yii::t('app/returns', 'BLOCK_2_TEXT_3')?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 img_div">
				<img src="/imgs/returns_3.png">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 number_div">
				<div class="row">
					<div class="col-xs-2 number">
						<p>3.</p>
					</div>
					<div class="col-xs-10 title">
						<p><?=Yii::t('app/returns', 'BLOCK_3_TITLE')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-2">
						<p><?=Yii::t('app/returns', 'BLOCK_3_TEXT_1', ['contact_link' => \yii\helpers\Url::to('/public/contact')])?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
