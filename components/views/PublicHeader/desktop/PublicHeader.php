<?php

use app\components\assets\publicHeaderAsset;
use app\components\PublicMyAccount;
use yii\helpers\Html;

publicHeaderAsset::register($this);

?>

<nav class="navbar-inverse navbar" role="navigation">
	<div class="container-fluid container-menutop no-horizontal-padding">
		<div class="navbar-collapse no-horizontal-padding">
			<ul id="w1" class="flex menutop funiv_ultra fs0-857 no-padding no-margin">
				<li class="item-menutop border fs-upper flex-prop-1-0">
					<?= Html::a(Yii::t("app/old", 'Explore boxes'), "site/index", [
						'class' => "hdr_btn funiv_bold fc-4a flex flex-align-center flex-justify-center"
					]); ?>
				</li>
				<li class="item-menutop border fs-upper flex-prop-1-0">
					<?= Html::a(Yii::t("app/old", 'Discover stories'), "site/about", [
						'class' => "hdr_btn funiv_bold fc-4a flex flex-align-center flex-justify-center"
					]); ?>
				</li>
				<li class="item-menutop border fs-upper flex-prop-1-0">
					<?= Html::a(Yii::t("app/old", 'Our devisers'), "site/about", [
						'class' => "hdr_btn funiv_bold fc-4a flex flex-align-center flex-justify-center"
					]); ?>
				</li>
				<li class="item-menutop fs-upper flex-prop-1-0">
					<div class="search_box flex flex-justify-start white">
						<input type="text" class="funiv fs1-286 fc-4e flex-prop-1-0" name="name" value="" placeholder="<?= Yii::t("app/old", "Search") ?>">
						<!--
						<select class="white" name="">
							<option>Foo</option>
							<option>Bar</option>
							<option>Foo Bar</option>
						</select>
						-->
						<span class="helper_btns pointer flex flex-align-center fs1-357 glyphicon glyphicon-search"></span>
					</div>
				</li>
				<li class="item-menutop">
					<div class="hdr_btn funiv_bold flex flex-align-center flex-justify-center">
						<span class="helper_btns pointer flex flex-align-center fs1-357 glyphicon glyphicon-shopping-cart"></span>
					</div>
				</li>
				<li class="item-menutop fs-upper funiv_bold fc-fff pointer">
					<div class="my_account black flex flex-justify-center flex-align-center relative">
						<span class="glyphicon glyphicon-user fs1-214"></span>
						<div class="access"><?= Yii::t("app/old", "My todevise") ?>

						</div>

						<div class="my_account_box absolute cdefault fs-initial">
							<?= PublicMyAccount::widget() ?>
						</div>

					</div>
				</li>
			</ul>
		</div>
	</div>
</nav>
