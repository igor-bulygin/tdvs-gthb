<?php

use app\assets\desktop\deviser\IndexStoryAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

IndexStoryAsset::register($this);


/** @var Person $person */
/** @var array $photos */
$this->title = Yii::t('app/public',
	'SOCIAL_FEED_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
Yii::$app->opengraph->title = $this->title;

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'social';
$this->params['person_links_target'] = 'public_view';

?>

<?= PersonHeader::widget() ?>

<div class="store" ng-controller="socialManagerCtrl as socialManagerCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<div class="empty-wrapper" ng-if="socialManagerCtrl.posts.length < 1 && !socialManagerCtrl.showCreatePost" ng-cloak>
					<div ng-if="socialManagerCtrl.viewingConnectedUser()">
						<img class="sad-face" src="/imgs/sad-face.svg">
						<p class="no-video-text"><span translate="person.posts.NO_POSTS"></span></p>
						<button  class="btn btn-red btn-add-box" ng-click="socialManagerCtrl.showNewPost()"><span translate="person.posts.ADD_POST"></span></button>
					</div>
					<div ng-if="!socialManagerCtrl.viewingConnectedUser()">
						<p class="no-video-text"><?=$person->getName()?> <span translate="person.posts.USER_NO_POSTS"></span></p>
					</div>
				</div>
				<div ng-if="socialManagerCtrl.showCreatePost && socialManagerCtrl.viewingConnectedUser()" ng-cloak>
					<div class="stories-wrapper">
						<div>
							<div class="story-component-wrapper">
								<div class="col-xs-12 edit-faq-panel-wrapper">
									<div class="edit-faq-panel">
										<h5 class="stories-title"><span translate="person.posts.NEW_POST"></span></h5>
										<div class="col-xs-4">
											<div class="col-md-12" ng-if="!socialManagerCtrl.newPost.photo">
												<div class="button no-pad" name="file" ngf-select="socialManagerCtrl.uploadPhoto($files, $invalidFiles)"  ngf-accept="'image/*'"  ngf-drop-available="socialManagerCtrl.isDropAvailable" ngf-multiple="false"><a style="cursor:pointer;" class="btn btn-small btn-red">upload</a></div>
											</div>
											<div class="col-md-12" ng-if="socialManagerCtrl.newPost.photo" ng-cloak>
												<img class="grid-image" ng-src="{{socialManagerCtrl.newImage}}">
											</div>											
										</div>
										<div class="col-xs-8 faq-language-menu">
											<div class="faq-row">
												<div class="col-sm-2">
													<span class="faq-edit-question"><span translate="person.posts.DESCRIPTION"></span></span>
												</div>
												<div class="col-sm-10">
													<div class="faq-edit-answer" text-angular ng-model="socialManagerCtrl.newPost.text[socialManagerCtrl.newPost.selected_language]" ta-toolbar="[]" translate-attr="{placeholder: 'person.posts.DESCRIPTION'}" ta-paste="socialManagerCtrl.stripHTMLTags($html)"></div>
												</div>
											</div>
										</div>
									</div>
									<ol class="faq-lang nya-bs-select form-control" ng-model="socialManagerCtrl.newPost.selected_language" ng-change="socialManagerCtrl.parseText(socialManagerCtrl.newPost)" ng-init="socialManagerCtrl.newPost.selected_language = socialManagerCtrl.selected_language">
										<li nya-bs-option="language in socialManagerCtrl.languages" class="ng-class:{'lang-selected': socialManagerCtrl.isLanguageOk(language.code, socialManagerCtrl.newPost)}" data-value="language.code" deep-watch="true">
											<a href="">
												<span ng-bind="language.name"></span>
												<span class="glyphicon glyphicon-ok ok-white-icon pull-right" ng-if="socialManagerCtrl.isLanguageOk(language.code,post)"></span>
											</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
						<div class="text-center" style="display: block; width: 100%; float: left; margin:20px 0 100px;">
							<button class="btn btn-default btn-red" ng-click="socialManagerCtrl.createPost()"><span translate="person.posts.PUBLISH"></span></button>
						</div>
					</div>
				</div>
				<div ng-if="socialManagerCtrl.posts.length > 0 && !socialManagerCtrl.showCreatePost" ng-cloak>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" ng-if="socialManagerCtrl.viewingConnectedUser()" ng-cloak>
						<button class="btn btn-red btn-add-box" ng-click="socialManagerCtrl.showNewPost()"><span translate="person.posts.ADD_POST"></span></button>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" ng-repeat="post in socialManagerCtrl.posts" ng-cloak>
						<div class="menu-category list-group" >
							<div class="grid">
									<img class="col-xs-12 grid-image" ng-src="{{post.photo_url}}">
									<div class="col-xs-12 text-right">
										<span ng-bind="post.loveds"></span>
										<span ng-if="post.isLoved" class="icons-hover heart-icon heart-red-icon" ng-click="socialManagerCtrl.unLovePost(post)" ng-cloak></span>
										<span ng-if="!post.isLoved" class="icons-hover heart-icon heart-black-icon" ng-click="socialManagerCtrl.lovePost(post)" ng-cloak></span>
									</div>
									<span class="col-xs-12" ng-bind-html="socialManagerCtrl.truncateString(post.text, socialManagerCtrl.maxCharacters, '...')"></span>
									<a ng-if="post.text.length > socialManagerCtrl.maxCharacters && !socialManagerCtrl.viewingConnectedUser()" class="col-xs-12" ng-click="socialManagerCtrl.openPostDetailsModal(post)"><span translate="person.posts.SEE_MORE"></span></a>
									<a ng-if="socialManagerCtrl.viewingConnectedUser()" class="col-xs-12" ng-click="socialManagerCtrl.editPost(post)"><span class="icons-hover pencil-edit"></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>