<?php

use app\components\assets\PersonMenuAsset;
use app\helpers\Utils;
use app\models\Person;

PersonMenuAsset::register($this);

// use params to share data between views :(
$categories = array_key_exists('person_menu_store_categories', $this->params) ? $this->params['person_menu_store_categories'] : [];
$activeOption = array_key_exists('person_menu_active_option', $this->params) ? $this->params['person_menu_active_option'] : '';
$linksTarget = array_key_exists('person_links_target', $this->params) ? $this->params['person_links_target'] : '';
/** @var Person $person */
$person = $this->params['person'];

?>

<nav class="menu-store" ng-controller="personMenuCtrl as personMenuCtrl">
	<ul class="mt-0">
		<?php if ($person->isDeviser()) { ?>
			<li class="<?= ($activeOption=='store') ? 'active' : '' ?>">
				<a class=" <?= ($activeOption=='store') ? 'active' : '' ?> ng-class:{'purple-text': personMenuCtrl.required['store']}" href="<?= $linksTarget=="edit_view" ? $person->getStoreEditLink() : $person->getStoreLink()?>">Store</a>
				<?php if (count($categories)>0) { ?>
				<ul class="submenu-store hidden-xs">
					<?php foreach ($categories as $i => $category) { ?>
						<li class="<?= (($i==0) ? 'mt10' : (($i==(count($categories)-1)) ? 'mt20' : '')) ?>">
							<a class="" href="<?= $linksTarget=="edit_view" ? $person->getStoreEditLink(['category' => $category->short_id]) : $person->getStoreLink(['category' => $category->short_id])?>"><?= Utils::l($category->name) ?></a>
						</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
		<?php } ?>
		<?php /*
		<?php if ($person->isInfluencer() || $person->isDeviser()) { ?>
			<li>
				<a class=" <?= ($activeOption=='social') ? 'active' : '' ?>" href="<?= $person->getSocialLink()?>">Social feed</a>
			</li>
		<?php } ?>
		*/ ?>
		<li class=" <?= ($activeOption=='loved') ? 'active' : '' ?>">
			<a class=" <?= ($activeOption=='loved') ? 'active' : '' ?>" href="<?= $person->getLovedLink()?>">Loved</a>
		</li>
		<li class=" <?= ($activeOption=='boxes') ? 'active' : '' ?>">
			<a class=" <?= ($activeOption=='boxes') ? 'active' : '' ?>" href="<?= $person->getBoxesLink()?>">Boxes</a>
		</li>
		<?php /*
		<?php if ($person->isDeviser() || $person->isInfluencer()) { ?>
			<li>
				<a class=" <?= ($activeOption=='stories') ? 'active' : '' ?>" href="<?= $person->getStoriesLink()?>">Stories</a>
			</li>
		<?php } ?>
		*/ ?>
	</ul>
	<ul class="menu-deviser-bottom">
		<?php if ($person->isDeviser() || $person->isInfluencer()) { ?>
			<li class="<?= ($activeOption=='about') ? 'active' : '' ?>">
				<a class="<?= ($activeOption=='about') ? 'active' : '' ?> ng-class:{'purple-text': personMenuCtrl.required['about']}" href="<?= $linksTarget == "edit_view" ? $person->getAboutEditLink() : $person->getAboutLink()?>">About</a>
			</li>
		<?php } ?>
		<?php if ($person->isDeviser() || $person->isInfluencer()) { ?>
			<li class=" <?= ($activeOption=='press') ? 'active' : '' ?>">
				<a class=" <?= ($activeOption=='press') ? 'active' : '' ?>" href="<?= $linksTarget == "edit_view" ? $person->getPressEditLink() : $person->getPressLink()?>">Press</a>
			</li>
		<?php } ?>
		<?php if ($person->isDeviser() || $person->isInfluencer()) { ?>
			<li class=" <?= ($activeOption=='videos') ? 'active' : '' ?>">
				<a class=" <?= ($activeOption=='videos') ? 'active' : '' ?>" href="<?=$linksTarget == "edit_view" ? $person->getVideosEditLink() : $person->getVideosLink()?>">Videos</a>
			</li>
		<?php } ?>
		<?php if ($person->isDeviser()) { ?>
			<li class=" <?= ($activeOption=='faq') ? 'active' : '' ?>">
				<a class=" <?= ($activeOption=='faq') ? 'active' : '' ?>" href="<?= $linksTarget == "edit_view" ? $person->getFaqEditLink() : $person->getFaqLink()?>">FAQ</a>
			</li>
		<?php } ?>
	</ul>
	<?php 
	if ($person->isDeviser()) {
	if (count($categories)>0) { ?>
		<ul class="submenu-store hidden-sm hidden-md hidden-lg">
			<?php foreach ($categories as $i => $category) { ?>
				<li class="<?= (($i==0) ? 'mt10' : (($i==(count($categories)-1)) ? 'mt20' : '')) ?>">
					<a class="" href="<?= $linksTarget=="edit_view" ? $person->getStoreEditLink(['category' => $category->short_id]) : $person->getStoreLink(['category' => $category->short_id])?>"><?= Utils::l($category->name) ?></a>
				</li>
			<?php } ?>
		</ul>
	<?php 
	}
	} ?>

</nav>
