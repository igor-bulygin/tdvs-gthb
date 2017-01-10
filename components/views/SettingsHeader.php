<?php
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

// use params to share data between views :(
/** @var Person $person */
$person = $this->params['person'];

// use params to share data between views :(
$activeOption = array_key_exists('settings_menu_active_option', $this->params) ? $this->params['settings_menu_active_option'] : '';

?>


<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(50, 50) ?>">

<?=$person->personalInfo->getBrandName()?>

Settings

<ul>
	<li>
		<a class="<?= ($activeOption=='settings') ? 'active' : '' ?>" href="#">Settings</a>
	</li>
	<li>
		<a class="<?= ($activeOption=='orders') ? 'active' : '' ?>" href="#">My Orders</a>
	</li>
	<li>
		<a class="<?= ($activeOption=='stock') ? 'active' : '' ?>" href="#">Stock & Price</a>
	</li>
	<li>
		<a class="<?= ($activeOption=='billing') ? 'active' : '' ?>" href="<?= Url::to(["settings/billing", "slug" => $person->slug, 'person_id' => $person->short_id])?>">Billing & Payments</a>
	</li>
	<li>
		<a class="<?= ($activeOption=='shipping') ? 'active' : '' ?>" href="#">Shipping</a>
	</li>
</ul>