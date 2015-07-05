<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Json;
use app\helpers\Utils;
use app\components\Crop;
use app\assets\desktop\deviser\EditInfoAsset;

/* @var $this yii\web\View */
/* @var $countries ArrayObject */
/* @var $countries_lookup ArrayObject */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/deviser/edit-info']
];

EditInfoAsset::register($this);

$who = $deviser['personal_info']['name'] . " " . join($deviser['personal_info']['surnames'], " ");
$this->title = "$who / Todevise / Edit info";

$base_path_photos = Utils::join_paths(Yii::getAlias("@deviser"), $deviser["short_id"]);
$header_photo_base64 = isset($deviser["media"]["header"]) ? Utils::fileToBase64(Utils::join_paths($base_path_photos, $deviser["media"]["header"])) : "";
$profile_photo_base64 = isset($deviser["media"]["profile"]) ? Utils::fileToBase64(Utils::join_paths($base_path_photos, $deviser["media"]["profile"])) : "";

?>

<div class="row no-gutter" ng-controller="deviserCtrl" ng-init="init()">
	<div class="col-xs-12 no-horizontal-padding create-profile">

		<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _countries = " . Json::encode($countries) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _countries_lookup = " . Json::encode($countries_lookup) . ";", View::POS_HEAD); ?>

		<?php $this->registerJs("var _header_photo_base64 = '$header_photo_base64';", View::POS_END); ?>
		<?php $this->registerJs("var _profile_photo_base64 = '$profile_photo_base64';", View::POS_END); ?>

		<div class="row no-gutter">
			<div class="col-xs-12 header-photo flex flex-justify-center">
				<div class="header-photo-holder flex-justify-center">
					<img class="header-photo-img" ngf-bg-src="headerphoto[0]" ngf-default-src="<?= $header_photo_base64 ?>">

					<div class="flex flex-justify-center flex-align-center">
						<div class="controls">
							<span class="glyphicon glyphicon-refresh pointer" aria-hidden="true" ngf-select ng-model="headerphoto" ng-cloak ng-show="headerphoto[0] !== undefined"></span>
							<span class="glyphicon glyphicon-resize-small pointer" aria-hidden="true" ng-click="crop_header()" ng-cloak ng-show="headerphoto[0] !== undefined"></span>
						</div>

						<div class="pointer drop-box flex flex-justify-center" ngf-drop ngf-select ng-model="headerphoto" class="drop-box"
							ngf-drag-over-class="dragover" ngf-multiple="false" ngf-allow-dir="false"
							ngf-accept="'image/*'" ngf-drop-available="dropAvailable">
							<div ng-cloak ng-hide="dropAvailable">File Drop not available</div>
							<div ng-cloak ng-show="dropAvailable && (!headerphoto || headerphoto.length === 0)" class="funiv_ultra fs1 fc-3d fs-upper header-photo-txt">
								<span class="glyphicon glyphicons-up-arrow"></span> Upload header photo
							</div>
						</div>
					</div>

					<div class="col-xs-12 profile-photo flex flex-justify-center">
						<div class="profile-photo-holder flex-justify-center">
							<img class="img-circle profile-photo-img" ngf-bg-src="profilephoto[0]" ngf-default-src="<?= $profile_photo_base64 ?>">

							<div class="flex flex-justify-center flex-align-center">
								<div class="controls">
									<span class="glyphicon glyphicon-refresh pointer" aria-hidden="true" ngf-select ng-model="profilephoto" ng-cloak ng-show="profilephoto[0] !== undefined"></span>
									<span class="glyphicon glyphicon-resize-small pointer" aria-hidden="true" ng-click="crop_profile()" ng-cloak ng-show="profilephoto[0] !== undefined"></span>
								</div>

								<div class="pointer drop-box flex flex-justify-center flex-align-center" ngf-drop ngf-select ng-model="profilephoto" class="drop-box"
									ngf-drag-over-class="dragover" ngf-multiple="false" ngf-allow-dir="false"
									ngf-accept="'image/*'" ngf-drop-available="dropAvailable">
									<div ng-cloak ng-hide="dropAvailable">File Drop not available</div>
									<div ng-cloak ng-show="dropAvailable && (!profilephoto || profilephoto.length === 0)" class="funiv_ultra fs1 fc-3d fs-upper">
										<span class="glyphicon glyphicons-up-arrow"></span> <br/> Upload <br/>profile<br/> photo
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<br />

		<div class="row no-gutter deviser">
			<div class="col-xs-4 flex flex-justify-center"></div>
			<div class="col-xs-4 flex flex-justify-center">
				<input type="text" placeholder="<?= Yii::t("app/deviser", "Your name"); ?>" ng-model="deviser.personal_info.name" class="input-name funiv_thin fs2-857 fc-9b fs-upper">
			</div>
			<div class="col-xs-4 flex flex-justify-center"></div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-4 flex flex-justify-center"></div>
			<div class="col-xs-4 flex flex-justify-center">
				<input type="text" placeholder="<?= Yii::t("app/deviser", "City"); ?>" class="input-city funiv_bold fs0-857 fs-upper fc-6d">
			</div>
			<div class="col-xs-4 flex flex-justify-center"></div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-4 flex flex-justify-center"></div>
			<div class="col-xs-4 flex flex-justify-center">
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
			<div class="col-xs-4 flex flex-justify-center"></div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-4 flex flex-justify-center"></div>
			<div class="col-xs-4 flex flex-justify-center">
				<div
					angular-multi-select
					api="api_cat"
					id-property="short_id"
					input-model="categories"
					output-model="selectedCategories"

					group-property="sub"
					tick-property="check"

					item-label="{{ name[lang] }}"
					selection-mode="multi"
					search-property="name[lang]"
					min-search-length="3"
					hidden-property="hidden"
					helper-elements="noall nonone noreset nofilter">
				</div>
			</div>
			<div class="col-xs-4 flex flex-justify-center"></div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-4 flex flex-justify-center"></div>
			<div class="col-xs-4 flex flex-justify-center">
				<textarea placeholder="<?= Yii::t("app/deviser", "Biography"); ?>" ng-model="deviser.personal_info.biography" class="textarea-bio" rows="8"></textarea>
			</div>
			<div class="col-xs-4 flex flex-justify-center"></div>
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
