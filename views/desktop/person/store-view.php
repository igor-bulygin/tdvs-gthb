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

// use params to share data between views :(
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'store';
$this->params['person_links_target'] = 'public_view';
$this->params['person_menu_store_categories'] = $categories;

?>

<?= PersonHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<div class="content-store">
					
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
						<div id="boxes-container" class="macy-container" data-columns="5" ng-controller="viewStoreCtrl as viewStoreCtrl">
							<?php foreach ($products as $i => $product) { ?>
								<div class="menu-category list-group">
									<div class="grid">
										<figure class="effect-zoe">
											<?php if ($person->isPersonEditable()) { ?>
											<span class="close-product-icon-left" ng-click="viewStoreCtrl.open_modal_delete('<?=$product->short_id?>')"></span>
											<a class="edit-product-icon" href="<?= $product->getEditLink()?>" translate-attr="{title: 'person.EDIT_WORK'}">
												<i class="ion-edit"></i>
											</a>
											<?php } ?>
											<?php if(!$person->isPersonEditable()) { ?>
												<image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}"> 
											<?php } ?>
												<a href="<?= $product->getViewLink() ?>">
													<img class="grid-image"
														 src="<?= $product->getImagePreview(400, 0) ?>">
													<span class="img-bgveil"></span>
												</a>
											<?php if(!$person->isPersonEditable()) { ?>
												</image-hover-buttons>
											<?php } ?>
											<a href="<?= $product->getViewLink()?>">
												<figcaption>
													<p class="instauser"><?= \yii\helpers\StringHelper::truncate($product->getName(), 18, '…') ?></p>
													<p class="price">€ <?= $product->getMinimumPrice() ?></p>
													<?php if ($person->isPersonEditable()) { ?>
														<!-- /* old position of a.edit-product-icon */ -->
													<?php } ?>
												</figcaption>
											</a>
										</figure>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/ng-template" id="modalDeleteProduct.html">
	<div class="modal-delete">
		<div class="modal-header">
			<h3 class="modal-title"></h3>
		</div>
		<div class="modal-body">
			<p><span translate="person.DELETE_WORK_QUESTION"></span></p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-default btn-red pull-left" ng-click="modalDeleteProductCtrl.close()"><span translate="global.CANCEL"></span></button>
			<button class="btn btn-default pull-right" ng-click="modalDeleteProductCtrl.ok()"><span translate="global.DELETE"></span></button>
		</div>
	</div>
</script>