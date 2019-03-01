<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Category;
use app\models\Person;
use app\models\Product;

GlobalAsset::register($this);

/** @var Person $person */
/** @var Product[] $products */
/** @var Category $category */
/** @var Category $selectedCategory */

if ($selectedSubcategory && $selectedSubcategory->short_id) { // check if is a real category
	$this->title = Yii::t('app/public',
		'CATEGORY_WORKS_BY_PERSON_NAME',
		['category_name' => $selectedSubcategory->getName(), 'person_name' => $person->getName()]
	);
} elseif ($selectedCategory && $selectedCategory->short_id) {
	$this->title = Yii::t('app/public',
		'CATEGORY_WORKS_BY_PERSON_NAME',
		['category_name' => $selectedCategory->getName(), 'person_name' => $person->getName()]
	);
} else {
	$this->title = Yii::t('app/public',
		'WORKS_BY_PERSON_NAME',
		['person_name' => $person->getName()]
	);
}
Yii::$app->opengraph->title = $this->title;

// use params to share data between views :(
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'store';
$this->params['person_links_target'] = 'public_view';
$this->params['person_menu_store_categories'] = $categories;

?>

<?= PersonHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row mb-40">
			<div class="col-xs-12 col-sm-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-xs-12 col-sm-10">
				<div class="content-store">
					<?php if ($person->isPersonEditable()) { ?>
						<div class="mt-20"><a href="<?= $person->getStoreEditLink()?>" class="red-link-btn"><span translate="person.store.EDIT_DELETE_WORKS"></span></a></div>
                        <div class="mt-20"><a href="<?= $person->getStoreImportLink()?>" class="red-link-btn"><span translate="person.store.IMPORT_WORKS"></span></a></div>
					<?php } ?>
					<div class="store-grid">
						<nav class="products-menu">
							<ul>
								<?php foreach ($selectedCategory->getDeviserSubcategories() as $i => $subcategory) { ?>
									<li>
										<a href="<?= $person->getStoreLink(['category' => $selectedCategory->short_id, 'subcategory' => $subcategory->short_id])?>" class="<?= ($selectedSubcategory->short_id==$subcategory->short_id) ? 'active' : '' ?>"><?= Utils::l($subcategory["name"]) ?></a>
									</li>
								<?php } ?>
							</ul>
						</nav>
						<div id="works-container" class="grid-margin">
							<?=\app\components\ProductsGrid::widget(['products' => $products, 'css_class' => 'col-xs-6 col-sm-4 col-md-3']) ?>
						</div>
						<?php /*
						<div id="boxes-container" class="macy-container" data-columns="5" ng-controller="viewStoreCtrl as viewStoreCtrl">
							<?php foreach ($products as $i => $product) { ?>
								<div class="menu-category list-group">
									<div class="grid">
										<figure class="effect-zoe">
											<image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}"> 
											<a href="<?= $product->getViewLink() ?>">
												<img class="grid-image"
													 src="<?= $product->getImagePreview(400, 0) ?>">
												<span class="img-bgveil"></span>
											</a>
											</image-hover-buttons>
											<a href="<?= $product->getViewLink()?>">
												<figcaption>
													<p class="instauser"><?= \yii\helpers\StringHelper::truncate($product->getName(), 18, '…') ?></p>
													<p class="price">€ <?= $product->getMinimumPrice() ?></p>
												</figcaption>
											</a>
										</figure>
									</div>
								</div>
							<?php } ?>
						</div>
 						*/ ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>