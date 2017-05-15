<?php
use app\assets\desktop\deviser\EditHeaderAsset;
use app\components\MakeProfilePublic;
use app\models\Person;
use yii\helpers\Json;

EditHeaderAsset::register($this);

// use params to share data between views :(
/** @var Person $person */
$person = $this->params['person'];
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
?>

<?php if ($person->isDraft()) { ?>
	<?= MakeProfilePublic::widget() ?>
<?php } ?>
<div class="banner-deviser" ng-controller="personHeaderCtrl as personHeaderCtrl">
	<div class="container pad-about" ng-if="!personHeaderCtrl.editingHeader" ng-cloak>
		<img class="cover" ng-src="{{personHeaderCtrl.header}}">
		<div class="banner-deviser-content">
			<div class="grey-overlay"></div>
			<div class="container">
				<div class="deviser-profile">
					<div class="avatar-btn-profile">
						<div class="avatar">
							<img class="cover" ng-src="{{personHeaderCtrl.profile || '/imgs/default-avatar.png'}}">
						</div>
						<?php if ($person->isPersonEditable()) {?>
							<div class="edit-profile-btn">
								<button class="btn btn-default btn-transparent btn-header ng-class:{'button-error': personHeaderCtrl.required['header_info']}" ng-click="personHeaderCtrl.editHeader()">Edit header</button>
							</div>
						<?php } ?>
						<div class="deviser-data">
							<div class="name">
								{{personHeaderCtrl.person.name}}
							</div>
							<div class="location">
								{{personHeaderCtrl.person.city}}
							</div>
							<div class="description">
								{{personHeaderCtrl.person.text_short_description[personHeaderCtrl.description_language]}}
							</div>
						</div>
					</div>
				</div>
				<?php if ($person->isDeviserEditable()) {?>
					<a class="btn btn-default btn-add-work" ng-class="personHeaderCtrl.required['store'] ? 'button-error' : 'btn-green'" href="<?= $person->getCreateWorkLink()?>">Add Work</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="container pad-about edit-header-container" ng-if="personHeaderCtrl.editingHeader" ng-cloak>
		<span tooltip-placement="top" uib-tooltip="ADD COVER PHOTO" class="button ion-camera edit-cover-icon photo" ngf-select ng-model="personHeaderCtrl.new_header" name="header" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
		<span tooltip-placement="top" uib-tooltip="CROP PHOTO" class="button ion-crop edit-cover-icon crop" ng-click="personHeaderCtrl.openCropModal(personHeaderCtrl.header_original, 'header_cropped')" ng-if="personHeaderCtrl.header" ng-cloak></span>
		<span class="req-1" ng-if="personHeaderCtrl.required['header'] && !personHeaderCtrl.new_header" ng-cloak>REQUIRED</span>
		<img class="cover" ngf-thumbnail="personHeaderCtrl.header || '/imgs/default-cover.jpg'" style="height: 411px;">
		<div class="banner-deviser-content banner-deviser-edit-header-content">
			<div class="grey-overlay"></div>
			<div class="container deviser-header-edit-wrapper">
				<div class="deviser-profile">
					<div class="avatar-buttons-wrapper">
						<div class="avatar">
							<img class="cover" ngf-thumbnail="personHeaderCtrl.profile || '/imgs/default-avatar.png'">
							<span tooltip-placement="top" uib-tooltip="ADD PHOTO" class="button ion-camera edit-avatar-photo-icon ng-class:{'two':personHeaderCtrl.profile_original}" ngf-select ng-model="personHeaderCtrl.new_profile" name="profile" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
							<span tooltip-placement="top" uib-tooltip="CROP PHOTO" class="button ion-crop crop-avatar-photo-icon" ng-if="personHeaderCtrl.profile_original" ng-click="personHeaderCtrl.openCropModal(personHeaderCtrl.profile_original, 'profile_cropped')" ng-cloak></span>
							<div ng-if="personHeaderCtrl.required['profile'] && !personHeaderCtrl.new_profile" ng-cloak><p class="req-2">REQUIRED</p></div>
						</div>

						<!--BUTTONS-->
						<div class="header-edit-btns">
							<button class="btn btn-default btn-red btn-header" ng-click="personHeaderCtrl.saveHeader()">Save & exit</button>
							<button class="btn btn-default btn-grey btn-header" ng-click="personHeaderCtrl.cancelEdit()">Cancel</button>
						</div>
					</div>
				</div>
				<div class="deviser-data-edit">
					<form class="grey-form" name="personHeaderCtrl.form">
						<!-- names -->
						<div ng-if="personHeaderCtrl.isDeviser(personHeaderCtrl.person)">
							<label for="brand_name">Brand name</label>
							<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.brand_name)}" ng-model="personHeaderCtrl.person.personal_info.brand_name" placeholder="{{personHeaderCtrl.person.name}}" name="brand_name" required>
						</div>
						<div ng-if="personHeaderCtrl.isInfluencer(personHeaderCtrl.person) || personHeaderCtrl.isClient(personHeaderCtrl.person)">
							<div class="row">
								<div class="col-md-6">
								<label for="name">First name</label>
								<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.name)}" ng-model="personHeaderCtrl.person.personal_info.name" placeholder="{{personHeaderCtrl.person.name}}" name="name" required>
								</div>
								<div class="col-md-6">
									<label for="last_name">Last name</label>
									<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.last_name)}" ng-model="personHeaderCtrl.person.personal_info.last_name" placeholder="{{personHeaderCtrl.person.last_name}}" name="last_name" required>						
								</div>
							</div>
						</div>
						<!-- city -->
						<div>
							<label for="city">City</label>
							<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.city)}" ng-model="personHeaderCtrl.city" ng-model-options='{ debounce: 500 }' ng-change="personHeaderCtrl.searchPlace(personHeaderCtrl.city)" placeholder="Your city" name="city" required>
						</div>
						<div ng-if="personHeaderCtrl.showCities" ng-cloak>
							<ul class="city-selection">
								<li ng-repeat="city in personHeaderCtrl.cities"><span ng-click="personHeaderCtrl.selectCity(city)" style="cursor:pointer;">{{city.city}} - {{city.country_name}}</span>
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
							<ol class="nya-bs-select about-edit-select header-lang" ng-model="personHeaderCtrl.description_language" ng-cloak>
								<li nya-bs-option="language in personHeaderCtrl.languages" data-value="language.code" deep-watch="true">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
						<div class="short-description-input-wrapper">
							<textarea name="text_short_description" cols="50" rows="10" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.text_short_description)}" ng-model="personHeaderCtrl.person.text_short_description[personHeaderCtrl.description_language]" placeholder="Please write a short descrpition here." required></textarea>
						</div>
						<!-- counter for short biography -->
						<span class="text-limitation" ng-cloak>
							<span ng-bind="personHeaderCtrl.limit_text_biography - personHeaderCtrl.person.text_short_description[personHeaderCtrl.description_language].length"></span>
						</span>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>