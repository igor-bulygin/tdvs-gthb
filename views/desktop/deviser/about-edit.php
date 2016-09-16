<?php
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\Index2Asset;
use app\assets\desktop\deviser\EditAboutAsset;

EditAboutAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser_menu_active_option'] = 'about';
$this->params['deviser'] = $deviser;

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10 about-bg" ng-controller="editAboutCtrl as editAboutCtrl">
				<div class="col-md-5 pad-about">
					<div class="about-wrapper">
						<div class="about-container">
							<!--<div class="title">Abo<br>ut</div>-->
							<div class="name-location-wrapper">
								<div class="name" ng-bind="editAboutCtrl.deviser.name">
								</div>
								<div class="location">
									<?= $deviser->getLocationLabel() ?>
								</div>
							</div>
							<div class="subtitle">
								<ol class="nya-bs-select" ng-model="editAboutCtrl.deviser.categories" multiple ng-cloak>
									<li ng-repeat="category in editAboutCtrl.categories" class="nya-bs-option" value="{{category.id}}">
										<a href=""><span ng-bind="category.name"></span> <span class="glyphicon glyphicon-ok check-mark" ng-if="editAboutCtrl.deviser.categories.indexOf(category.id) > -1"></span></a>
									</li>
								</ol>
							</div>
							<?php if ($deviser->hasResumeFile()) { ?>
							<div class="resume-header"><a href="<?= $deviser->getUrlResumeFile() ?>">See resume</a></div>
							<?php } ?>
							<ol class="nya-bs-select" ng-model="editAboutCtrl.biography_language" ng-cloak>
								<li class="nya-bs-option" value=""></li>
								<li ng-repeat="language in editAboutCtrl.languages" class="nya-bs-option" value="{{language.code}}">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
							<div text-angular ta-text-editor-class="header" ng-model="editAboutCtrl.deviser.text_biography[editAboutCtrl.biography_language]" ng-cloak ta-toolbar="[]"></div>
							<span class="glyphicon glyphicon-pencil" style="color:white;"></span>
						</div>
					</div>
				</div>
				<div class="col-md-7 pad-about about-grid">
					<div class="col-xs-12 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-1.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-2.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-3.jpg">
					</div>
					<div class="col-xs-12 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-4.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-5.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-6.jpg">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
