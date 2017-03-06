<?php
use app\helpers\Utils;
use app\models\Person;

// use params to share data between views :(
$categories = array_key_exists('person_menu_store_categories', $this->params) ? $this->params['person_menu_store_categories'] : [];
$activeOption = array_key_exists('person_menu_active_option', $this->params) ? $this->params['person_menu_active_option'] : '';
$linksTarget = array_key_exists('person_links_target', $this->params) ? $this->params['person_links_target'] : '';
/** @var Person $person */
$person = $this->params['person'];

?>

<nav class="menu-store" data-spy="affix" data-offset-top="450">
	<ul class="mt-0">
        <?php if ($person->isDeviser()) { ?>
            <li>
                <a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='store') ? 'active' : '' ?>" href="<?= $linksTarget=="edit_view" ? $person->getStoreEditLink() : $person->getStoreLink()?>">Store</a>
                <?php if (count($categories)>0) { ?>
                <ul class="submenu-store">
                    <?php foreach ($categories as $i => $category) { ?>
                        <li class="<?= (($i==0) ? 'mt10' : (($i==(count($categories)-1)) ? 'mt20' : '')) ?>">
                            <a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?>" href="<?= $linksTarget=="edit_view" ? $person->getStoreEditLink($category->short_id) : $person->getStoreLink($category->short_id)?>"><?= Utils::l($category->name) ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
        <?php } ?>
        <li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='loved') ? 'active' : '' ?>" href="<?= $person->getLovedLink()?>">Loved</a>
        </li>
        <li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='boxes') ? 'active' : '' ?>" href="<?= $person->getBoxesLink()?>">Boxes</a>
        </li>
	</ul>
	<ul class="menu-deviser-bottom">
		<?php if ($person->isDeviser() || $person->isInfluencer()) { ?>
            <li>
                <a class="<?= ($activeOption=='about') ? 'active' : '' ?>" href="<?= $linksTarget == "edit_view" ? $person->getAboutEditLink() : $person->getAboutLink()?>">About</a>
            </li>
		<?php } ?>
		<?php if ($person->isDeviser() || $person->isInfluencer()) { ?>
            <li>
                <a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='press') ? 'active' : '' ?>" href="<?= $linksTarget == "edit_view" ? $person->getPressEditLink() : $person->getPressLink()?>">Press</a>
            </li>
        <?php } ?>
		<?php if ($person->isDeviser() || $person->isInfluencer()) { ?>
            <li>
                <a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='videos') ? 'active' : '' ?>" href="<?=$linksTarget == "edit_view" ? $person->getVideosEditLink() : $person->getVideosLink()?>">Videos</a>
            </li>
        <?php } ?>
        <?php if ($person->isDeviser()) { ?>
            <li>
                <a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='faq') ? 'active' : '' ?>" href="<?= $linksTarget == "edit_view" ? $person->getFaqEditLink() : $person->getFaqLink()?>">FAQ</a>
            </li>
        <?php } ?>
	</ul>
</nav>
