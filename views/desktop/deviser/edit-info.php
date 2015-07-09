<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Url;
use yii\helpers\Json;
use app\helpers\Utils;
use app\components\Crop;
use app\assets\desktop\deviser\EditInfoAsset;

/* @var $this yii\web\View */
/* @var $deviser ArrayObject */
/* @var $countries ArrayObject */
/* @var $categories ArrayObject */
/* @var $countries_lookup ArrayObject */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/deviser/edit-info']
];

EditInfoAsset::register($this);

$who = $deviser['personal_info']['name'] . " " . join($deviser['personal_info']['surnames'], " ");
$this->title = "$who / Todevise / Edit info";

$base_path_photos = Yii::getAlias("@deviser_url") . "/" . $deviser["short_id"] . "/";
$header_photo_url = isset($deviser["media"]["header"]) ? $base_path_photos . $deviser["media"]["header"] : "";
$profile_photo_url = isset($deviser["media"]["profile"]) ? $base_path_photos . $deviser["media"]["profile"] : "";
?>

<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>
<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD); ?>
<?php $this->registerJs("var _countries = " . Json::encode($countries) . ";", View::POS_HEAD); ?>
<?php $this->registerJs("var _countries_lookup = " . Json::encode($countries_lookup) . ";", View::POS_HEAD); ?>
<?php $this->registerJs("var _upload_header_photo_url = '" . Url::to(["deviser/upload-header-photo", "slug" => $deviser["slug"]]) . "';", View::POS_HEAD); ?>
<?php $this->registerJs("var _upload_profile_photo_url = '" . Url::to(["deviser/upload-profile-photo", "slug" => $deviser["slug"]]) . "';", View::POS_HEAD); ?>

<div class="row no-gutter" ng-controller="deviserCtrl" ng-init="init()">
	<div class="col-xs-12 no-horizontal-padding create-profile">

		<div class="row no-gutter">
			<div class="col-xs-12 no-horizontal-padding header-photo flex flex-column">
				<div class="header-photo-holder flex flex-column">
					<img class="header-photo-img" ngf-bg-src="headerphoto[0]" angular-img-dl angular-img-dl-url="<?= $header_photo_url ?>" angular-img-dl-model="headerphoto[0]">

					<div class="flex flex-justify-center flex-align-center flex-prop-1">
						<div class="controls">
							<span class="glyphicon glyphicon-refresh pointer" aria-hidden="true" ngf-select ng-model="headerphoto" ng-cloak ng-show="headerphoto[0] !== undefined"></span>
							<span class="glyphicon glyphicon-resize-small pointer" aria-hidden="true" ng-click="crop_header()" ng-cloak ng-show="headerphoto[0] !== undefined"></span>
						</div>

						<div class="pointer drop-box flex flex-justify-center" ngf-drop ngf-select ng-model="headerphoto" class="drop-box"
							ngf-drag-over-class="dragover" ngf-multiple="false" ngf-allow-dir="false"
							ngf-accept="'image/*'" ngf-drop-available="dropAvailable">
							<div ng-cloak ng-hide="dropAvailable">File Drop not available</div>
							<div ng-cloak ng-show="dropAvailable && (!headerphoto || headerphoto.length === 0)" class="funiv_ultra fs1 fc-3d fs-upper header-photo-txt">
								<span class="glyphicon glyphicons-up-arrow"></span>
								<?= Yii::t("app/deviser", "Upload header photo") ?>
							</div>
						</div>
					</div>


					<div class="profile-photo-holder">
						<img class="img-circle profile-photo-img" ngf-bg-src="profilephoto[0]" angular-img-dl angular-img-dl-url="<?= $profile_photo_url ?>" angular-img-dl-model="profilephoto[0]">

						<div class="flex flex-justify-center flex-align-center profile-photo-controls-holder">
							<div class="controls">
								<span class="glyphicon glyphicon-refresh pointer" aria-hidden="true" ngf-select ng-model="profilephoto" ng-cloak ng-show="profilephoto[0] !== undefined"></span>
								<span class="glyphicon glyphicon-resize-small pointer" aria-hidden="true" ng-click="crop_profile()" ng-cloak ng-show="profilephoto[0] !== undefined"></span>
							</div>

							<div class="pointer drop-box flex flex-justify-center flex-align-center" ngf-drop ngf-select ng-model="profilephoto" class="drop-box"
							     ngf-drag-over-class="dragover" ngf-multiple="false" ngf-allow-dir="false"
							     ngf-accept="'image/*'" ngf-drop-available="dropAvailable">
								<div ng-cloak ng-hide="dropAvailable">File Drop not available</div>
								<div ng-cloak ng-show="dropAvailable && (!profilephoto || profilephoto.length === 0)" class="funiv_ultra fs1 fc-3d fs-upper profile-photo-area">
									<span class="glyphicon glyphicons-up-arrow"></span><br />
									<?= Yii::t("app/deviser", "Upload profile photo") ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xs-12 flex flex-justify-center deviser">
					<input type="text" placeholder="<?= Yii::t("app/deviser", "Your name"); ?>" ng-model="deviser.personal_info.name" class="input-name funiv_thin fs2-857 fc-9b fs-upper">
				</div>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<input type="text" placeholder="<?= Yii::t("app/deviser", "City"); ?>" class="input-city funiv_bold fs0-857 fs-upper fc-6d">
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<div
					angular-multi-select
					api="api"
					id-property="country_code"
					input-model="countries"
					output-model="selected_country"
					tick-property="checked"
					item-label="{{ countries_lookup[country_code] }}"
					selection-mode="single"
					button-template="angular-multi-select-btn-data.htm"
					button-label="{{ countries_lookup[country_code] }}"
					search-property="country_name['<?php echo array_keys(Lang::EN_US)[0]; ?>']"
					helper-elements="noall nonone noreset filter">
				</div>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<div
					angular-multi-select
					api="api_cat"
					id-property="short_id"
					input-model="categories"
					output-model="selectedCategories"
					tick-property="check"
					item-label="{{ name[lang] }}"
					selection-mode="multi"
					search-property="name[lang]"
					min-search-length="3"
					hidden-property="hidden"
					helper-elements="noall nonone noreset nofilter">
				</div>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<textarea placeholder="<?= Yii::t("app/deviser", "Biography"); ?>" ng-model="deviser.personal_info.biography" class="textarea-bio" rows="8"></textarea>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<div class="btn btn-save-profile fc-fff funiv fs-upper fs0-786" ng-click="save()">Save</div>
			</div>
		</div>
	</div>
</div>

<?= Crop::widget() ?>
