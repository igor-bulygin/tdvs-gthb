<?php

use app\assets\desktop\deviser\EditInfoAsset;
use app\components\Crop;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $deviser ArrayObject */
/* @var $countries ArrayObject */
/* @var $categories ArrayObject */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/deviser/edit-info']
];

EditInfoAsset::register($this);

$who = $deviser['personal_info']['name'];
$this->title = "$who / Todevise / Edit info";

$base_path_photos = Yii::getAlias("@deviser_url") . "/" . $deviser["short_id"] . "/";
$header_photo_url = isset($deviser["media"]["header"]) ? $base_path_photos . $deviser["media"]["header"] : "";
$profile_photo_url = isset($deviser["media"]["profile"]) ? $base_path_photos . $deviser["media"]["profile"] : "";
?>

<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>
<?php $this->registerJs("var _upload_header_photo_url = '" . Url::to(["deviser/upload-header-photo", "slug" => $deviser["slug"]]) . "';", View::POS_HEAD); ?>
<?php $this->registerJs("var _upload_profile_photo_url = '" . Url::to(["deviser/upload-profile-photo", "slug" => $deviser["slug"]]) . "';", View::POS_HEAD); ?>

<div class="row no-gutter" ng-controller="deviserCtrl as deviserCtrl">
	<div class="col-xs-12 no-horizontal-padding create-profile">

		<div class="row no-gutter">
			<div class="col-xs-12 no-horizontal-padding header-photo flex flex-column">
				<div class="header-photo-holder flex flex-column">
					<img class="header-photo-img" ngf-background="deviserCtrl.headerphoto" angular-img-dl angular-img-dl-url="<?= $header_photo_url ?>" angular-img-dl-model="deviserCtrl.headerphoto">

					<div class="flex flex-justify-center flex-align-center flex-prop-1">
						<div class="controls">
							<span class="glyphicon glyphicon-refresh pointer" aria-hidden="true" ngf-select ng-model="deviserCtrl.headerphoto" ng-cloak ng-show="deviserCtrl.headerphoto"></span>
							<span class="glyphicon glyphicon-resize-small pointer" aria-hidden="true" ng-click="deviserCtrl.crop_header()" ng-cloak ng-show="deviserCtrl.headerphoto"></span>
						</div>

						<div class="pointer drop-box flex flex-justify-center" ngf-drop ngf-select ng-model="deviserCtrl.headerphoto" class="drop-box"
							ngf-drag-over-class="dragover" ngf-multiple="false" ngf-allow-dir="false"
							ngf-accept="'image/*'" ngf-drop-available="deviserCtrl.dropAvailable">
							<div ng-cloak ng-hide="deviserCtrl.dropAvailable"><?= Yii::t("app/old", "File drop not available"); ?></div>
							<div ng-cloak ng-show="deviserCtrl.dropAvailable && !deviserCtrl.headerphoto" class="funiv_ultra fs1 fc-3d fs-upper header-photo-txt">
								<span class="glyphicon glyphicons-up-arrow"></span>
								<?= Yii::t("app/old", "UPLOAD_HEADER_PHOTO") ?>
							</div>
						</div>
					</div>


					<div class="profile-photo-holder">
						<img class="img-circle profile-photo-img" ngf-background="deviserCtrl.profilephoto" angular-img-dl angular-img-dl-url="<?= $profile_photo_url ?>" angular-img-dl-model="deviserCtrl.profilephoto">

						<div class="flex flex-justify-center flex-align-center profile-photo-controls-holder">
							<div class="controls flex">
								<span class="glyphicon glyphicon-refresh pointer" aria-hidden="true" ngf-select ng-model="deviserCtrl.profilephoto" ng-cloak ng-show="deviserCtrl.profilephoto"></span>
								<span class="glyphicon glyphicon-resize-small pointer" aria-hidden="true" ng-click="deviserCtrl.crop_profile()" ng-cloak ng-show="deviserCtrl.profilephoto"></span>
							</div>

							<div class="pointer drop-box flex flex-justify-center flex-align-center" ngf-drop ngf-select ng-model="deviserCtrl.profilephoto" class="drop-box"
							     ngf-drag-over-class="dragover" ngf-multiple="false" ngf-allow-dir="false"
							     ngf-accept="'image/*'" ngf-drop-available="deviserCtrl.dropAvailable">
								<div ng-cloak ng-hide="deviserCtrl.dropAvailable"><?= Yii::t("app/old", "File drop not available"); ?></div>
								<div ng-cloak ng-show="deviserCtrl.dropAvailable && !deviserCtrl.profilephoto" class="funiv_ultra fs1 fc-3d fs-upper profile-photo-area">
									<span class="glyphicon glyphicons-up-arrow"></span><br />
									<?= Yii::t("app/old", "UPLOAD_PROFILE_PHOTO") ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xs-12 flex flex-justify-center deviser">
					<input type="text" placeholder="<?= Yii::t("app/old", "Your name"); ?>" ng-model="deviserCtrl.deviser.personal_info.name" class="input-name funiv_thin fs2-857 fc-9b fs-upper">
				</div>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<input type="text" placeholder="<?= Yii::t("app/old", "City"); ?>" class="input-city funiv_bold fs0-857 fs-upper fc-6d" ng-model="deviserCtrl.deviser.personal_info.city">
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<div
					angular-multi-select
					id-property="country_code"
					input-model='<?= Json::encode($countries) ?>'
					output-model="deviserCtrl.deviser.personal_info.country"
					output-keys="country_code"
					output-type="value"

					checked-property="checked"

					dropdown-label="<[ '<( country_name )>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/old', 'SELECT_COUNTRY') ?>' ]>"
					leaf-label="<[ country_name ]>"

					max-checked-leafs="1"

					preselect="country_code, {{ deviserCtrl.deviser.personal_info.country }}"
					hide-helpers="check_all, check_none, reset"
					search-field="country_name"
				></div>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<div
					angular-multi-select
					id-property="short_id"
					input-model='<?= Json::encode($categories) ?>'
					output-model="deviserCtrl.deviser.categories"
					output-keys='short_id'
					output-type="values"

					checked-property="check"

					dropdown-label="<[ '<( name )>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/old', 'SELECT_CATEGORY') ?>' ]>"
					leaf-label="<[ name ]>"

					preselect="{{ deviserCtrl.deviser.categories | arrpatch : 'short_id' }}"
					hide-helpers="check_all, check_none, reset"
					search-field="name"
				></div>
			</div>
		</div>

		<br />

		<!-- LAS BIOS TIENEN QUE PODER TRADUCIRSE
		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<textarea placeholder="<?= Yii::t("app/old", "Biography"); ?>" ng-model="deviser.personal_info.biography" class="textarea-bio" rows="8"></textarea>
			</div>
		</div>
		-->

		<br />

		<div class="row no-gutter">
			<div class="col-xs-12 flex flex-justify-center">
				<div class="btn btn-save-profile fc-fff funiv fs-upper fs0-786" ng-click="deviserCtrl.save()"><?= Yii::t("app/old", 'SAVE') ?></div>
				<div style="width: 50px"></div>
				<div style="background-color: #ccc" class="btn fc-fff funiv fs-upper fs0-786" ng-click="deviserCtrl.new_product()" translate="NEW_PRODUCT"></div>
			</div>
		</div>

	</div>
</div>

<?= Crop::widget() ?>
