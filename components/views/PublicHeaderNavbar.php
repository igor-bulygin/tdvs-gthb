<?php
use yii\helpers\Html;
use app\components\assets\publicHeaderNavbarAsset;

publicHeaderNavbarAsset::register($this);

?>

<nav class="navbar-inverse navbar" role="navigation">
	<div class="container-fluid container-menutop">
		<div class="navbar-collapse">
			<ul id="w1" class="navbar-nav flex menutop funiv_ultra fs1 nav">
				<li class="item-menutop fs-upper flex-prop-1-0">
					<?= Html::a(Yii::t("app/public", 'Find the perfect gift'), "site/index"); ?>
				</li>
				<li class="item-menutop fs-upper flex-prop-1-0">
					<?= Html::a(Yii::t("app/public", 'Discover trends'), "site/about"); ?>
				</li>
				<li class="item-menutop fs-upper flex-prop-1-0 flex-grow-3">
					<?= Html::a(Yii::t("app/public", 'Caja de busqueda'), "site/about"); ?>
				</li>
				<li class="item-menutop flex-prop-1-0"><a href="#">IMG</a></li>
				<li class="item-menutop fs-upper flex-prop-1-0 fpf_bold fs0-786">
					<a href="#">TODEVISE</a>
				</li>
				<li class="item-menutop flex-prop-1-0">
					<?= Html::a(Yii::t("app/public", 'Login'), "site/login"); ?>
				</li>
			</ul>
		</div>
	</div>
</nav>