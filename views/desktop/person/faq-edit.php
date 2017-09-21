<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public',
	'EDIT_PERSON_NAME_FAQS',
	['person_name' => $person->getName()]
);

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'faq';
$this->params['person_links_target'] = 'edit_view';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?= PersonHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10" ng-controller="editFaqCtrl as editFaqCtrl">
				<div class="faq-wrapper faq-edit-list">
					<!-- <div class="section-title">FAQ</div> -->
					<div class="edit-faq-wrapper" ng-cloak ng-if="editFaqCtrl.person.faq.length > 0">

						<div dnd-list="editFaqCtrl.person.faq" dnd-dragover="editFaqCtrl.dragOver(index)">
							<div class="delete-options-wrapper" ng-repeat="question in editFaqCtrl.person.faq track by $index" style="cursor:move;" dnd-draggable="question" dnd-effect-allowed="move" dnd-dragstart="editFaqCtrl.dragStart($index)" dnd-canceled="editFaqCtrl.canceled()" dnd-moved="editFaqCtrl.moved($index)">
								<div class="delete-links-wrapper">
									<!--a class="delete-link pull-right" href="#" ng-click="editFaqCtrl.deleteQuestion($index)"><span translate="person.faq.DELETE_QUESTION"></span></a-->
									<a class="delete-link pull-right" href="#" ng-click="editFaqCtrl.deleteQuestion($index)"><span translate="person.faq.DELETE_QUESTION"></span></a>
								</div>
								<div class="edit-faq-panel-wrapper">
									<div class="edit-faq-panel">
										<div class="faq-language-menu">
											<div class="faq-row">
												<div class="col-sm-2">
													<span class="faq-edit-question"><span translate="person.faq.QUESTION"></span></span>
												</div>
												<div class="col-sm-10">
													<input type="text" class="faq-edit-answer" translate-attr="{placeholder: 'person.faq.QUESTION'}" ng-model="question.question[question.languageSelected]">
												</div>
											</div>
											<div class="faq-row">
												<div class="col-sm-2">
													<span class="faq-edit-question"><span translate="person.faq.ANSWER"></span></span>
												</div>
												<div class="col-sm-10">
													<div class="faq-edit-answer" text-angular ng-model="question.answer[question.languageSelected]" ta-toolbar="[]" translate-attr="{placeholder: 'person.faq.ANSWER'}" ta-paste="editFaqCtrl.stripHTMLTags($html)"></div>
												</div>
											</div>
										</div>
									</div>
									<ol class="faq-lang nya-bs-select form-control" ng-model="question.languageSelected" ng-change="editFaqCtrl.parseQuestion(question)" ng-init="question.languageSelected='en-US'">
										<li nya-bs-option="language in editFaqCtrl.languages" class="ng-class:{'lang-selected': editFaqCtrl.isLanguageOk(language.code, question)}" data-value="language.code" deep-watch="true">
											<a href="">
												<span ng-bind="language.name"></span>
												<span class="glyphicon glyphicon-ok ok-white-icon pull-right" ng-if="editFaqCtrl.isLanguageOk(language.code,question)"></span>
											</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
					<div id="link-faq-done" ng-if="editFaqCtrl.person.faq.length>0">
						<a class="red-link-btn" href="#" ng-click="editFaqCtrl.done()">
							<span translate="person.faq.DONE_FAQ"></span>
						</a>
					</div>
					<div id="btn-faq-add-question">
						<button class="btn btn-red btn-default auto-center mb-40" ng-if="editFaqCtrl.person.faq.length > 0" ng-click="editFaqCtrl.addQuestion()"><span translate="person.faq.ADD_QUESTION"></span></button>
					</div>
					<div class="faq-edit-empty" ng-if="editFaqCtrl.person.faq.length === 0" ng-cloak>
						<img class="sad-face" src="/imgs/sad-face.svg">
							<p><span translate="person.faq.NO_FAQS"></span>
							<br/> <span translate="person.faq.START_WRITE_FAQ"></span></p>
							<a class="btn btn-red edit-faq-btn" href="#" ng-click="editFaqCtrl.addQuestion()"><span translate="person.faq.ADD_QUESTION"></span></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>