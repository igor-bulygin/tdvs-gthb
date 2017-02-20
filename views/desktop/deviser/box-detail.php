<?php
use app\assets\desktop\pub\LovedViewAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use yii\helpers\Json;

LovedViewAsset::register($this);

/** @var Person $deviser */
/** @var \app\models\Box $box */

$this->title = 'Box '.$box->name.' by ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'boxes';
$this->params['deviser_links_target'] = 'public_view';

/** array $faq */

// <a class="edit-faq-btn" href="<***?= Url::to(["deviser/faq-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?****>">+ ADD / EDIT QUESTIONS</a>
$this->registerJs("var box = ".Json::encode($box), yii\web\View::POS_HEAD, 'box-script');

?>

<div class="store" ng-controller="boxDetailCtrl as boxDetailCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10" style="background-color: #2e2e2e; height: 73px; opacity: 0.8;">
				<div class="col-md-8" style="padding-top: 10px;">
					<img src="<?=$deviser->getAvatarImage128()?>" style="max-width: 50px;">
					<?php if ($deviser->isConnectedUser()) { ?>
						<span style="color: white;">&lt; My profile</span>
					<?php } else  { ?>
						<span style="color: white;">&lt; <?=$deviser->getBrandName()?></span>
					<?php } ?>
				</div>
				<?php if ($deviser->isConnectedUser()) { ?>
					<div class="col-md-2" style="padding-top: 17px;">
						<button class="btn btn-default btn-green" ng-click="boxDetailCtrl.openEditBoxModal()">Edit box</button>
					</div>
					<div class="col-md-2" style="padding-top: 17px;">
						<button class="btn btn-default" ng-click="boxDetailCtrl.openDeleteBoxModal()">Delete box</button>
					</div>
				<?php } ?>
			</div>
			<div class="col-md-3" style="background-color: #636363">
				<h3 style="color: white;" ng-bind="boxDetailCtrl.box.name"></h3>
				<p style="color:white;" ng-bind="boxDetailCtrl.box.description"></p>
			</div>
			<div class="col-md-7" style="background-color: black; opacity: 0.3;">
				<!-- repeat -->
			</div>
		</div>
	</div>
</div>

