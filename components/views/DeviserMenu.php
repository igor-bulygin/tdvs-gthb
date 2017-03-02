<?php
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

// use params to share data between views :(
$categories = array_key_exists('deviser_menu_categories', $this->params) ? $this->params['deviser_menu_categories'] : [];
$activeOption = array_key_exists('deviser_menu_active_option', $this->params) ? $this->params['deviser_menu_active_option'] : '';
$linksTarget = array_key_exists('deviser_links_target', $this->params) ? $this->params['deviser_links_target'] : '';
/** @var Person $person */
$person = $this->params['person'];

?>

<nav class="menu-store" data-spy="affix" data-offset-top="450">
	<ul class="mt-0">
		<li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='store') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/store-edit" : "deviser/store", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">Store</a>
			<?php if (count($categories)>0) { ?>
			<ul class="submenu-store">
				<?php foreach ($categories as $i => $category) { ?>
					<li class="<?= (($i==0) ? 'mt10' : (($i==(count($categories)-1)) ? 'mt20' : '')) ?>">
						<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/store-edit" : "deviser/store", "slug" => $person->slug, 'deviser_id' => $person->short_id, 'category' => $category->short_id])?>"><?= Utils::l($category->name) ?></a>
					</li>
				<?php } ?>
			</ul>
			<?php } ?>
		</li>
        <li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='loved') ? 'active' : '' ?>" href="<?= Url::to(["deviser/loved" , "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">Loved</a>
        </li>
        <li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='boxes') ? 'active' : '' ?>" href="<?= Url::to(["deviser/boxes" , "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">Boxes</a>
        </li>
	</ul>
	<ul class="menu-deviser-bottom">
		<li>
			<a class="<?= ($activeOption=='about') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/about-edit" : "deviser/about", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">About</a>
		</li>
		<li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='press') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/press-edit" : "deviser/press", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">Press</a>
		</li>
		<li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='videos') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/videos-edit" : "deviser/videos", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">Videos</a>
		</li>
		<li>
			<a class="<?= ($person->account_state == Person::ACCOUNT_STATE_DRAFT) ? 'disabled-link' : '' ?> <?= ($activeOption=='faq') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/faq-edit" : "deviser/faq", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">FAQ</a>
		</li>
	</ul>
</nav>
