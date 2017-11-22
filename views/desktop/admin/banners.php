<?php

use app\assets\desktop\admin\BannersAsset;

/* @var $this yii\web\View */
/* @var $countries ArrayObject */
/* @var $devisers yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Banners',
	'url' => ['/admin/banners']
];

BannersAsset::register($this);

$this->title = 'Todevise / Admin / Banners';
?>

<div class="store" ng-controller="bannerCtrl as bannerCtrl">
	<div class="col-md-12">
		<div class="col-md-9 col-md-offset-1">
			<div class="col-md-2">
				<div class="items-row">
					<span class="category-select">
						<ol class="col-md-12 about-edit-select title-lang-btn nya-bs-select" ng-model="bannerCtrl.selectedBannerOption" ng-change="bannerCtrl.selectBannerOption()">
							<li nya-bs-option="option in bannerCtrl.bannerOptions" data-value="option.value" deep-watch="true">
								<a href=""><span ng-bind="option.name"></span></a>
							</li>
						</ol>
					</span>
				</div>
			</div>
			<div class="col-md-2" ng-if="bannerCtrl.showHomeSelection" ng-cloak>
				<div class="items-row">
					<span class="category-select">
						<ol class="col-md-12 about-edit-select title-lang-btn nya-bs-select" ng-model="bannerCtrl.selectedType" ng-change="bannerCtrl.getBanners()">
							<li nya-bs-option="option in bannerCtrl.bannerTypes" data-value="option.value" deep-watch="true">
								<a href=""><span ng-bind="option.name"></span></a>
							</li>
						</ol>
					</span>
				</div>
			</div>
			<div class="col-md-8" ng-repeat="item in bannerCtrl.categories_helper track by $index" ng-if="bannerCtrl.showCategorySelection" ng-cloak>
				<div class="items-row">
					<span class="category-select" ng-repeat="category in bannerCtrl.categories_helper[$index].categories_selected">
						<ol name="{{'categories_' + $parent.$index + '_' + $index}}" class="col-sm-2 col-md-2 nya-bs-select btn-group bootstrap-select form-control product-select" ng-model="bannerCtrl.categories_helper[$parent.$index].categories_selected[$index]" ng-change="bannerCtrl.categorySelected(bannerCtrl.categories_helper[$parent.$index].categories_selected[$index], $parent.$index, $index)">
							<li nya-bs-option="subcategory in bannerCtrl.categories_helper[$parent.$index].categories[$index]" data-value="subcategory.id" deep-watch="true">
								<a href="">
									<span ng-bind="subcategory.name"></span>
								</a>
							</li>
						</ol>
					</span>
					<span class="ion-android-close close-row" ng-if="bannerCtrl.product.categories.length > 1" ng-click="bannerCtrl.deleteCategory($index)"></span>
				</div>
			</div>
		</div>
		<div class="col-md-2 text-right mt-10">
			<a ng-if="!bannerCtrl.viewNewBanner" class="btn btn-red edit-faq-btn" href="#" ng-click="bannerCtrl.showNewBanner()"><span>add banner</span></a>
		</div>
	</div>
	<div class="container">
		<div class="text-center" ng-if="bannerCtrl.loading" ng-cloak>
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		</div>
		<div ng-if="!bannerCtrl.loading" ng-cloak>
			<div class="row" >
				<div class="col-md-10 col-md-offset-1">
					<form class="row" name="bannerCtrl.newBannerForm" novalidate ng-if="bannerCtrl.viewNewBanner"  ng-cloak>
						<ol class="col-md-2 about-edit-select title-lang-btn nya-bs-select" ng-model="bannerCtrl.name_language">
							<li nya-bs-option="language in bannerCtrl.languages" data-value="language.code" deep-watch="true">
								<a href=""><span ng-bind="language.name"></span> <span class="glyphicon glyphicon-ok check-mark"></span></a>
							</li>
						</ol>
						<div class="col-md-10">
							<label class="col-md-2 mb-20" for="name">Alt Text</label>
							<input class="col-md-10 mb-20 title-input form-control ng-class:{'error-input': bannerCtrl.textRequired}" type="text" name="alt_tet" ng-model="bannerCtrl.newBanner.alt_text[bannerCtrl.name_language]">
							<div class="col-md-10 error-text" ng-if="bannerCtrl.textRequired" ng-cloak>
								<span translate="product.basic_info.TITLE_MANDATORY" translate-values='{ languageList: bannerCtrl.mandatory_langs_names}'></span>
							</div>
						</div>
						<div class="col-md-10">
							<label class="col-md-2 mb-20" for="link">Link (opt)</label>
							<input class="col-md-10 mb-20 title-input form-control" type="text" name="link" ng-model="bannerCtrl.newBanner.link[bannerCtrl.name_language]">
						</div>
						<div class="col-md-10">
							<label class="col-md-12" for="file"><span>photo</span></label>
							<div class="col-md-2">
								<div class="col-md-4 button no-pad" name="file" ngf-select="bannerCtrl.uploadPhoto($files, $invalidFiles)"  ngf-accept="'image/*'"  ngf-drop-available="bannerCtrl.isDropAvailable" ngf-multiple="false"><a style="cursor:pointer;" class="btn btn-small btn-red">upload</a></div>
							</div>
							<div class="col-md-10" ng-if="bannerCtrl.newBanner.image[bannerCtrl.name_language]" ng-cloak>
								<img class="grid-image" ng-src="{{bannerCtrl.baseUrl}}{{bannerCtrl.newImage[bannerCtrl.name_language]}}">
							</div>
						</div>
						<div class="col-md-10 error-text" ng-if="bannerCtrl.imageRequired" ng-cloak>
							<span translate="product.basic_info.TITLE_MANDATORY" translate-values='{ languageList: bannerCtrl.mandatory_langs_names}'></span>
						</div>
						<div class="col-md-6 mt-20">
							<button class="btn btn-red btn-small" ng-click="bannerCtrl.saveBanner()"><span>Save</span></button>
						</div>
						<div class="col-md-6 mt-20">
							<button class="btn btn-red btn-small" ng-click="bannerCtrl.cancelEdition()"><span>Cancel</span></button>
						</div>
					</form>
					<div class="panel faq-panel col-md-12" ng-if="bannerCtrl.banners.length > 0 && !bannerCtrl.viewNewBanner" ng-cloak>
						<ul class="list-group" ui-sortable="bannerCtrl.sortableOptions" ng-model="bannerCtrl.banners">
							<li class="list-group-item row"  ng-repeat="banner in bannerCtrl.banners" >
								<div class="panel-heading panel-heading-faq col-md-12" >
									<h4 class="panel-title col-md-12 mt-10">
										<span class="col-md-4 mt-10" ng-bind="banner.alt_text[bannerCtrl.lang]"></span>
										<div ng-if="banner.link[bannerCtrl.lang]" ng-cloak>
											<label class="col-md-12">Link</label>
											<span class="col-md-12" ng-bind="banner.link[bannerCtrl.lang]"></span>
										</div>
										<div class="col-md-1 mt-10">
											<img class="img-responsive" ng-src="{{bannerCtrl.baseUrl}}{{banner.image_link[bannerCtrl.lang]}}">
										</div>
										<div class="col-md-7 text-right mt-10">
											<a href="" ng-click="bannerCtrl.editBanner(banner)"><i class="glyphicon glyphicon-pencil black-icon"></i></a>
											<a href="" ng-click="bannerCtrl.deleteBanner(banner)"><i class="glyphicon glyphicon-trash black-icon"></i></a>
										</div>
									</h4>
								</div>
							</li>
						</ul>
					</div>
					
					<div class="faq-edit-empty" ng-if="bannerCtrl.banners.length === 0 && !bannerCtrl.viewNewBanner" ng-cloak>
						<img class="sad-face" src="/imgs/sad-face.svg">
						<p><span>
							No banners founded
						</span>
						<br/></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>