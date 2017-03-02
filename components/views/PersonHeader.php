<?php
use app\assets\desktop\deviser\EditHeaderAsset;
use app\components\DeviserMakeProfilePublic;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

EditHeaderAsset::register($this);

// use params to share data between views :(
/** @var Person $deviser */
$deviser = $this->params['deviser'];
$this->registerJs("var deviser = ".Json::encode($deviser), yii\web\View::POS_HEAD, 'deviser-var-script');
?>

<?php if ($deviser->isDraft()) { ?>
	<?= DeviserMakeProfilePublic::widget() ?>
<?php } ?>
<div class="banner-deviser" ng-controller="deviserHeaderCtrl as deviserHeaderCtrl">
	<div class="container pad-about" ng-if="!deviserHeaderCtrl.editingHeader" ng-cloak>
		<img class="cover" ng-src="{{deviserHeaderCtrl.header}}">
		<div class="banner-deviser-content">
			<div class="grey-overlay"></div>
			<div class="container">
				<div class="deviser-profile">
					<div class="avatar-btn-profile">
						<div class="avatar">
							<img class="cover" ng-src="{{deviserHeaderCtrl.profile}}">
						</div>
						<?php if ($deviser->isDeviserEditable()) {?>
							<div class="edit-profile-btn">
								<button class="btn btn-default btn-transparent btn-header" ng-click="deviserHeaderCtrl.editHeader()">Edit header</button>
							</div>
						<?php } ?>
						<div class="deviser-data">
							<div class="name">
								{{deviserHeaderCtrl.deviser.personal_info.brand_name}}
							</div>
							<div class="location">
								{{deviserHeaderCtrl.deviser.personal_info.city}}
							</div>
							<div class="description">
								{{deviserHeaderCtrl.deviser.text_short_description[deviserHeaderCtrl.description_language]}}
							</div>
						</div>
					</div>
				</div>
				<?php if ($deviser->isDeviserEditable()) {?>
					<a class="btn btn-default btn-green btn-add-work" href="<?= Url::to(["product/create", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Add Work</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="container pad-about edit-header-container" ng-if="deviserHeaderCtrl.editingHeader" ng-cloak>
		<span tooltip-placement="top" uib-tooltip="ADD COVER PHOTO" class="button ion-camera edit-cover-icon photo" ngf-select ng-model="deviserHeaderCtrl.new_header" name="header" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
		<span tooltip-placement="top" uib-tooltip="CROP PHOTO" class="button ion-crop edit-cover-icon crop" ng-click="deviserHeaderCtrl.openCropModal(deviserHeaderCtrl.header_original, 'header_cropped')" ng-if="deviserHeaderCtrl.header" ng-cloak></span>
		<span class="req-1" ng-if="deviserHeaderCtrl.headerRequired && !deviserHeaderCtrl.new_header" ng-cloak>REQUIRED</span>
		<img class="cover" ngf-thumbnail="deviserHeaderCtrl.header || '/imgs/default-cover.jpg'" style="height: 411px;">
		<div class="banner-deviser-content banner-deviser-edit-header-content">
			<div class="grey-overlay"></div>
			<div class="container deviser-header-edit-wrapper">
				<div class="deviser-profile">
					<div class="avatar-buttons-wrapper">
						<div class="avatar">
							<img class="cover" ngf-thumbnail="deviserHeaderCtrl.profile || '/imgs/default-avatar.jpg'">
							<span tooltip-placement="top" uib-tooltip="ADD PHOTO" class="button ion-camera edit-avatar-photo-icon ng-class:{'two':deviserHeaderCtrl.profile_original}" ngf-select ng-model="deviserHeaderCtrl.new_profile" name="profile" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
							<span tooltip-placement="top" uib-tooltip="CROP PHOTO" class="button ion-crop crop-avatar-photo-icon" ng-if="deviserHeaderCtrl.profile_original" ng-click="deviserHeaderCtrl.openCropModal(deviserHeaderCtrl.profile_original, 'profile_cropped')" ng-cloak></span>
							<div ng-if="deviserHeaderCtrl.profileRequired && !deviserHeaderCtrl.new_profile" ng-cloak><p class="req-2">REQUIRED</p></div>
						</div>

						<!--BUTTONS-->
						<div class="header-edit-btns">
							<button class="btn btn-default btn-red btn-header" ng-click="deviserHeaderCtrl.saveHeader()">Save & exit</button>
							<button class="btn btn-default btn-grey btn-header" ng-click="deviserHeaderCtrl.cancelEdit()">Cancel</button>
						</div>
					</div>
				</div>
				<div class="deviser-data-edit">
					<form class="grey-form" name="deviserHeaderCtrl.form">
						<!-- brand name -->
						<div>
							<label for="brand_name">Brand name</label>
							<input type="text" class="form-control ng-class:{'error-input': deviserHeaderCtrl.has_error(deviserHeaderCtrl.form, deviserHeaderCtrl.form.brand_name)}" ng-model="deviserHeaderCtrl.deviser.personal_info.brand_name" placeholder="{{deviserHeaderCtrl.deviser.name}}" name="brand_name" required>
						</div>
						<!-- city -->
						<div>
							<label for="city">City</label>
							<input type="text" class="form-control ng-class:{'error-input': deviserHeaderCtrl.has_error(deviserHeaderCtrl.form, deviserHeaderCtrl.form.city)}" ng-model="deviserHeaderCtrl.city" ng-change="deviserHeaderCtrl.searchPlace(deviserHeaderCtrl.city)" placeholder="Your city" name="city" required>
						</div>
						<div ng-if="deviserHeaderCtrl.showCities" ng-cloak>
							<ul class="city-selection">
								<li ng-repeat="city in deviserHeaderCtrl.cities"><span ng-click="deviserHeaderCtrl.selectCity(city)" style="cursor:pointer;">{{city.city}} - {{city.country_name}}</span>
								</li>
								<li>
									<img class="powered-google" src="/imgs/powered_by_google_on_white_hdpi.png">
								</li>
							</ul>
						</div>
						<!-- short biography -->
						<label for="text_short_description">Short description of your brand</label>
						<span class="small-grey">Translate your description by selecting different languages below</span>
							<!-- language selector -->
							<ol class="nya-bs-select about-edit-select header-lang" ng-model="deviserHeaderCtrl.description_language" ng-cloak>
								<li nya-bs-option="language in deviserHeaderCtrl.languages" data-value="language.code" deep-watch="true">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
						<div class="short-description-input-wrapper">
							<textarea name="text_short_description" cols="50" rows="10" class="form-control ng-class:{'error-input': deviserHeaderCtrl.has_error(deviserHeaderCtrl.form, deviserHeaderCtrl.form.text_short_description)}" ng-model="deviserHeaderCtrl.deviser.text_short_description[deviserHeaderCtrl.description_language]" placeholder="Please write a short descrpition here." required></textarea>
						</div>
						<!-- counter for short biography -->
						<span class="text-limitation" ng-cloak>
							<span ng-bind="deviserHeaderCtrl.limit_text_biography - deviserHeaderCtrl.deviser.text_short_description[deviserHeaderCtrl.description_language].length"></span>
						</span>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>