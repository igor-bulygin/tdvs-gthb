<?php
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\helpers\Utils;
use app\models\Person;
use yii\web\View;
use app\models\Lang;
use app\assets\desktop\pub\Index2Asset;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditVideosAsset;

EditVideosAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'videos';

?>

	<?= DeviserHeader::widget() ?>

		<div class="store">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<?= DeviserMenu::widget() ?>
					</div>
					<div class="col-md-10 about-bg" ng-controller="editVideosCtrl as editVideosCtrl">
						<div class="text-center" style="padding-bottom:20px;">
							<form name="editVideosCtrl.form" novalidate class="form-inline">
								<div class="form-group">
									<span>Video</span>
									<input type="text" placeholder="https://" ng-model="editVideosCtrl.url" name="url" ng-required="true" class="form-control">
									<button class="btn btn-default" ng-click="editVideosCtrl.addVideo()" ng-disabled="editVideosCtrl.form.$invalid">+</button>
								</div>
							</form>
						</div>
						<div ng-if="editVideosCtrl.deviser.videos.length === 0" class="text-center" ng-cloak>
							<p>You have no videos :(</p>
						</div>
						<div ng-repeat="video in editVideosCtrl.deviser.videos" ng-cloak dnd-list="editVideosCtrl.deviser.videos">
							<div class="row" dnd-draggable="video" dnd-effect-allowed="move" dnd-moved="editVideosCtrl.updateDeviserVideos($index)">
								<div class="col-md-6">
									<ng-youtube-embed url="video.url" autoplay="false" color="white" disablekb="true" width="100%"></ng-youtube-embed>
								</div>
								<div class="col-md-6">
									<h4>Which works appear in this video?</h4>
									<form name="editVideosCtrl.searchForm" novalidate>
										<div class="form-group">
											<input type="text" placeholder="Type the work name" ng-model="editVideosCtrl.searchTerm[$index]" name="searchTerm" ng-change="editVideosCtrl.findProducts(editVideosCtrl.searchTerm[$index], $index)" ng-minlength="4" ng-model-options="{debounce:{'default': 500}}" class="form-control">
										</div>
										<div ng-cloak style="height:200px; overflow-y:scroll; background:white;" ng-if="editVideosCtrl.searchTerm[$index].length > 0 && editVideosCtrl.works[$index] && editVideosCtrl.works[$index].length > 0">
											<div ng-repeat="work in editVideosCtrl.works[$parent.$index]" style="cursor:pointer;" ng-click="editVideosCtrl.selectProduct($parent.$index, work)">
												<div class="row">
													<div class="col-md-2">
														<img ng-src="{{work.image}}" style="width: 100%; max-height: 50px;">
													</div>
													<div class="col-md-8">
														<p><span ng-bind="work.name"></span> by <span ng-bind="work.deviser.name"></span></p>
													</div>
												</div>
											</div>
										</div>
									</form>
									<div>
										<h4>Tagged works</h4>
										<div ng-if="video.products.length === 0">
											<p>No tagged works.</p>
											<p>Tag works to tell other members what the video is about.</p>
										</div>
										<div ng-repeat="product in video.products" ng-if="video.products.length > 0">
											<div class="row">
												<div class="col-md-2">
													<img ng-src="{{product.url_image_preview}}" style="width: 100%; max-height: 50px;">
												</div>
												<div class="col-md-8">
													<p><span ng-bind="product.name"></span> by <span ng-bind="product.deviser.name"></span></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>