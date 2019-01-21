<?php

use app\assets\desktop\deviser\IndexStoryAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;
use app\helpers\Utils;

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
			<div class="col-md-10 mt-30">
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
				<div ng-if="socialManagerCtrl.posts.length > 0 && !socialManagerCtrl.showCreatePost" ng-cloak>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 mb-20 text-center" ng-if="socialManagerCtrl.viewingConnectedUser()" ng-cloak>
						<button class="btn btn-red btn-add-box" ng-click="socialManagerCtrl.showNewPost()"><span translate="person.posts.ADD_POST"></span></button>
					</div>
					<div id="content-posts" class="col-xs-4" ng-repeat="post in socialManagerCtrl.posts" ng-cloak>
						<figure class="showcase" ng-click="socialManagerCtrl.openPostDetailsModal(post)">
							<div class="images-box">
								<img class="col-xs-12 grid-image" style="width: 100%; height: 100%; padding: 0; object-fit: cover;" src="<?= Utils::url_scheme() ?><?= Utils::thumborize('{{post.photo_url}}')->resize(300, 0) ?>">
							</div>
						</figure>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/ng-template" id="newPostModal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="socialManagerCtrl.dismiss()">
				<span class="ion-android-close" aria-hidden="true"></span>
			</button>
			<h3>
				<span ng-if="!socialManagerCtrl.resolve.isEdition" translate="person.posts.NEW_POST"></span>
				<span ng-if="socialManagerCtrl.resolve.isEdition" translate="person.posts.EDIT_POST"></span>
			</h3>
		</div>
		<div class="modal-body">
			<div class="stories-wrapper">
				<div class="story-component-wrapper">
					<div class="col-xs-12">
						<form novalidate>
							<div class="row no-margin">
								<div class="text-center" ng-if="!socialManagerCtrl.isEdition">
									<div class="photo-loader loader-post" ng-if="!socialManagerCtrl.newPost.photo">
										<div class="new-add-wrapper">
										</div>
									</div>
									<div class="col-xs-12 mb-20" ng-if="!socialManagerCtrl.newPost.photo">
											<button id="btn_create_box_popup" class="btn btn-red" name="file" ngf-select="socialManagerCtrl.uploadPhoto($files, $invalidFiles)"  ngf-accept="'image/*'"  ngf-drop-available="socialManagerCtrl.isDropAvailable" ngf-multiple="false">
											<span>upload <span class="ion-camera"></span></span>
										</button>
									</div>
									<div class="col-xs-12 mb-20 text-center" ng-if="socialManagerCtrl.newPost.photo" ng-cloak>
										<img class="grid-image img-responsive" ng-src="{{socialManagerCtrl.newImage}}">
									</div>
								</div>
							</div>
							<div class="col-xs-4 col-xs-push-8 no-margin">
								<ol class="nya-bs-select form-control" ng-model="socialManagerCtrl.newPost.selected_language" ng-change="socialManagerCtrl.parseText(socialManagerCtrl.newPost)" ng-init="socialManagerCtrl.newPost.selected_language = socialManagerCtrl.selected_language">
									<li nya-bs-option="language in socialManagerCtrl.languages" class="ng-class:{'lang-selected': socialManagerCtrl.isLanguageOk(language.code, socialManagerCtrl.newPost)}" data-value="language.code" deep-watch="true">
										<a href="" tabindex="0">
											<span ng-bind="language.name"></span>
											<span class="glyphicon glyphicon-ok ok-white-icon pull-right" ng-if="socialManagerCtrl.isLanguageOk(language.code,post)"></span>
										</a>
									</li>
								</ol>
							</div>
							<div class="col-xs-8 no-margin no-padding">
									<div text-angular ng-model="socialManagerCtrl.newPost.text[socialManagerCtrl.newPost.selected_language]" ta-toolbar="[]" translate-attr="{placeholder: 'person.posts.DESCRIPTION'}" ta-paste="socialManagerCtrl.stripHTMLTags($html)" placeholder="Description"></div>
							</div>
						</form>
					</div>
				</div>
				<div class="text-center" style="display: block; width: 100%; float: left; margin:20px 0 15px;">
					<div class="col-md-10 text-right error-text" ng-if="socialManagerCtrl.newPost.required_text">
						<span translate="person.faq.FIELD_LANGS_MANDATORY" translate-values='{ languageList: socialManagerCtrl.mandatory_langs_names}'></span>
					</div>
					<button class="btn btn-default btn-auto btn-red" ng-click="socialManagerCtrl.createPost()"><span translate="person.posts.PUBLISH"></span></button>
				</div>
			</div>
		</div>
	</div>
</script>
</div>
