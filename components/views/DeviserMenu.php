<?php
use app\components\assets\cropAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

// use params to share data between views :(
$categories = array_key_exists('deviser_menu_categories', $this->params) ? $this->params['deviser_menu_categories'] : [];
$activeOption = array_key_exists('deviser_menu_active_option', $this->params) ? $this->params['deviser_menu_active_option'] : '';
$linksTarget = array_key_exists('deviser_links_target', $this->params) ? $this->params['deviser_links_target'] : '';
$deviser = $this->params['deviser'];

?>

<nav class="menu-store" data-spy="affix" data-offset-top="450">
	<ul class="mt-0">
		<li>
			<a class="<?= ($activeOption=='store') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/store-edit" : "deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Store</a>
			<?php if (count($categories)>0) { ?>
			<ul class="submenu-store">
				<?php foreach ($categories as $i => $category) { ?>
					<li class="<?= (($i==0) ? 'mt10' : (($i==(count($categories)-1)) ? 'mt20' : '')) ?>">
						<a href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/store-edit" : "deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'category' => $category->short_id])?>"><?= Utils::l($category->name) ?></a>
					</li>
				<?php } ?>
			</ul>
			<?php } ?>
		</li>
	</ul>
	<ul class="menu-deviser-bottom">
		<li>
			<a class="<?= ($activeOption=='about') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/about-edit" : "deviser/about", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">About</a>
		</li>
		<li>
			<a class="<?= ($activeOption=='press') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/press-edit" : "deviser/press", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Press</a>
		</li>
		<li>
			<a class="<?= ($activeOption=='videos') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/videos-edit" : "deviser/videos", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Videos</a>
		</li>
		<li>
			<a class="<?= ($activeOption=='faq') ? 'active' : '' ?>" href="<?= Url::to([($linksTarget=="edit_view") ? "deviser/faq-edit" : "deviser/faq", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">FAQ</a>
		</li>
	</ul>
</nav>
