<?php
use app\components\Crop;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditInfoAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/deviser/edit-info']
];

EditInfoAsset::register($this);

$who = $deviser['personal_info']['name'] . " " . join($deviser['personal_info']['surnames'], " ");
$this->title = "$who / Todevise / Edit info";
?>

<div class="row no-gutter" ng-controller="deviserCtrl" ng-init="init()">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _profile_photo_url = '" . Url::to(["deviser/upload-profile-photo", "slug" => $deviser['slug']]) . "';", View::POS_HEAD); ?>

		<div class="row no-gutter">
			<div class="col-xs-12 header-photo">
				upload header photo
			</div>

			<div class="col-xs-12 profile-photo flex flex-justify-center">

					<div class="profile-photo-holder">
						<img class="img-circle profile-photo-img" ngf-bg-src="profilephoto[0]">

						<div class="flex flex-justify-center flex-align-center">
							<div class="controls">
								<span class="glyphicon glyphicon-refresh pointer" aria-hidden="true" ngf-select ng-model="profilephoto" ng-cloak ng-show="profilephoto.length === 1"></span>
								<span class="glyphicon glyphicon-resize-small pointer" aria-hidden="true" ng-click="crop_profile()" ng-cloak ng-show="profilephoto.length === 1"></span>
							</div>

							<div class="pointer drop-box flex flex-justify-center flex-align-center" ngf-drop ngf-select ng-model="profilephoto" class="drop-box"
							     ngf-drag-over-class="dragover" ngf-multiple="false" ngf-allow-dir="false"
							     ngf-accept="'image/*'" ngf-drop-available="dropAvailable">
								<div ng-cloak ng-hide="dropAvailable">File Drop not available</div>
								<div ng-cloak ng-show="dropAvailable && (!profilephoto || profilephoto.length === 0)">Upload profile photo</div>
							</div>
						</div>

					</div>

			</div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-3"></div>
			<div class="col-xs-6">
				<input type="text" placeholder="your name" ng-model="deviser.personal_info.name">
			</div>
			<div class="col-xs-3"></div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-3"></div>
			<div class="col-xs-6">
				<input type="text" placeholder="city">
			</div>
			<div class="col-xs-3"></div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-3"></div>
			<div class="col-xs-6">
				<input type="text" placeholder="country">
			</div>
			<div class="col-xs-3"></div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-3"></div>
			<div class="col-xs-6">
				<input type="text" placeholder="field">
			</div>
			<div class="col-xs-3"></div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-3"></div>
			<div class="col-xs-6">
				biography <textarea></textarea>
			</div>
			<div class="col-xs-3"></div>
		</div>

		<div class="row no-gutter">
			<div class="col-xs-12">
				<div class="btn btn-default pointer" ng-click="save">Guardar -></div>
			</div>
		</div>
	</div>
</div>

<?= Crop::widget() ?>