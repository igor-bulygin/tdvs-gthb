<?php
use app\components\DeviserHeader;
use app\components\DeviserAdminHeader;
use app\components\DeviserMakeProfilePublic;
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

$this->title = 'About ' . $deviser->personalInfo->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'videos';
$this->params['deviser_links_target'] = 'edit_view';

?>

	<?php if ($deviser->isDraft()) { ?>
		<?= DeviserMakeProfilePublic::widget() ?>
	<?php } ?>
	<?= DeviserAdminHeader::widget() ?>

		<div class="store">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<?= DeviserMenu::widget() ?>
					</div>
					<div class="col-md-10" ng-controller="editVideosCtrl as editVideosCtrl">
						<div class="video-container">
							<form name="editVideosCtrl.form" novalidate>
								<div class="input-group input-group-lg input-video">
									<span class="input-group-addon" id="sizing-addon1">Video</span>
									<input type="text" class="form-control input-add-video" placeholder="http://" ng-model="editVideosCtrl.url" name="url" ng-required="true" ng-pattern="editVideosCtrl.YoutubeRegex">
									<span class="input-group-btn">
										<button class="btn btn-default btn-add-video" type="button" ng-click="editVideosCtrl.addVideo()" ng-disabled="editVideosCtrl.form.$invalid">+</button>
								</span>
								</div>
							</form>
							<div ng-if="editVideosCtrl.deviser.videos.length === 0" class="text-center" ng-cloak>
								<img class="sad-face" src="/imgs/sad-face.svg">
								<p class="no-video-text">You have no videos</p>
							</div>
							<div class="video-edit-list" ng-cloak dnd-list="editVideosCtrl.deviser.videos">
								<div class="row video-row" dnd-draggable="video" dnd-effect-allowed="move" dnd-moved="editVideosCtrl.updateDeviserVideos($index)" ng-repeat="video in editVideosCtrl.deviser.videos">
									<div class="col-md-6">
										<ng-youtube-embed url="video.url" autoplay="false" color="white" disablekb="true" width="100%"></ng-youtube-embed>
									</div>
									<div class="col-md-6 dragg-video-wrapper">
										<span class="title">Which works appear in this video?</span>
										<div class="open-drag-icons">
											<span class="ion-arrow-move move"></span> <span class="ion-android-close x-close" ng-click="editVideosCtrl.deleteVideo($index)"></span>
										</div>
										<form name="editVideosCtrl.searchForm" novalidate>
											<div class="input-group edit-video-wrapper">
												<span class="input-group-addon" id="basic-addon1">
													<span class="ion-search"></span>
												</span>
												<input type="text" placeholder="Type the work name" ng-model="editVideosCtrl.searchTerm[$index]" name="searchTerm" ng-change="editVideosCtrl.findProducts(editVideosCtrl.searchTerm[$index], $index)" ng-model-options="{debounce:{'default': 500}}" class="form-control edit-video-input">
											</div>
											<span ng-if="editVideosCtrl.noProducts">No products found.</span>
											<span ng-if="editVideosCtrl.product_min_length">4 characters required.</span>
											<div ng-cloak class="add-tag-video-wrapper" ng-if="editVideosCtrl.searchTerm[$index].length > 0 && editVideosCtrl.works[$index] && editVideosCtrl.works[$index].length > 0">
												<div ng-repeat="work in editVideosCtrl.works[$parent.$index]" style="cursor:pointer;" ng-click="editVideosCtrl.selectProduct($parent.$index, work)">
													<div class="tag-select-row row">
														<div class="col-md-2">
															<img ng-src="{{work.url_image_preview}}" style="width: 100%; max-height: 50px;">
														</div>
														<div class="col-md-8">
															<p><span ng-bind="work.name"></span> by <span ng-bind="work.deviser.name"></span></p>
														</div>
                                                        <div class="col-md-2">
															<span class="glyphicon glyphicon-plus green-add" style="cursor:pointer;"></span>
														</div>
													</div>
												</div>
											</div>
										</form>
										<div class="tagged-works">
											<span class="title">Tagged works</span>
											<div ng-if="video.products.length === 0">
												<p class="no-tags-title">No tagged works.</p>
												<p class="no-tags-text">Tag works to tell other members what the video is about.</p>
											</div>
											<div class="produts-tags-wrapper">
												<div ng-repeat="product in video.products" ng-if="video.products.length > 0">
													<div class="tag-row">
														<div class="col-md-2">
															<img ng-src="{{product.url_image_preview}}" style="width: 100%; max-height: 50px;">
														</div>
														<div class="col-md-8">
															<div ng-bind="product.name"></div>
															<div>
																<span>by </span><span ng-bind="product.deviser.name"></span>
															</div>
														</div>
														<div class="col-md-2">
															<span class="glyphicon glyphicon-remove red-close" style="cursor:pointer;" ng-click="editVideosCtrl.deleteTag($parent.$parent.$index, $index)"></span>
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
			</div>
		</div>