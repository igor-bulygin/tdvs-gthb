<?php
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

app\components\assets\SettingsHeaderAsset::register($this);


// use params to share data between views :(
/** @var Person $person */
$person = $this->params['person'];

// use params to share data between views :(
$activeOption = array_key_exists('settings_menu_active_option', $this->params) ? $this->params['settings_menu_active_option'] : '';

?>

<div ng-controller="settingsHeaderCtrl as settingsHeaderCtrl">
	<div class="upper-setting-header">
		<div class="container">
			<img class="avatar" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(50, 50) ?>">
			<span class="deviser-name"><?=$person->personalInfoMapping->getBrandName()?></span>
			<?php /*<button class="btn btn-green pull-right" ng-click="settingsHeaderCtrl.saveChanges()">Save changes</button>*/ ?>
		</div>
	</div>
	<ul class="nav nav-tabs header-settings-tabs">
		<li role="presentation">
			<a class="<?= ($activeOption=='settings') ? 'active' : '' ?>" href="#">Settings</a>
		</li>
		<li role="presentation">
			<a class="<?= ($activeOption=='orders') ? 'active' : '' ?>" href="#">My Orders</a>
		</li>
		<li role="presentation">
			<a class="<?= ($activeOption=='stock') ? 'active' : '' ?>" href="#">Stock & Price</a>
		</li>
		<li role="presentation">
			<a class="<?= ($activeOption=='billing') ? 'active' : '' ?>" href="<?= Url::to(["settings/billing", "slug" => $person->slug, 'person_id' => $person->short_id])?>">Billing & Payments</a>
		</li>
		<li role="presentation">
			<a class="<?= ($activeOption=='shipping') ? 'active' : '' ?>" href="#">Shipping</a>
		</li>
	</ul>

</div>

<script type="text/ng-template" id="changesSaved.html">
	<div class="modal-body">
		<span>Changes saved successfully</span>
		<span class="pull-right" ng-click="$dismiss()">&times;</span>
	</div>
</script>
<script type="text/ng-template" id="invalidForm.html">
	<div class="modal-body">
		<span class="purple-text">Please correct the errors below and try saving again</span>
		<span class="pull-right" ng-click="$dismiss()">&times;</span>
	</div>
</script>
