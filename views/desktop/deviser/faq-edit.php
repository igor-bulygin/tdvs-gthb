<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserHeader;
use app\components\DeviserAdminHeader;
use app\components\DeviserMakeProfilePublic;
use app\components\DeviserMenu;
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\Index2Asset;
use app\assets\desktop\deviser\EditFaqAsset;

EditFaqAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->personalInfo->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'faq';
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
								<div class="col-md-10" ng-controller="editFaqCtrl as editFaqCtrl">
									<div class="faq-wrapper faq-edit-list">
										<!-- <div class="section-title">FAQ</div> -->
										<div ng-if="editFaqCtrl.deviser.faq.length>0">
											<a class="red-link-btn" href="#" ng-click="editFaqCtrl.done()">I'm done editing</a></div>
										<div class="edit-faq-wrapper" ng-cloak ng-if="editFaqCtrl.deviser.faq.length > 0">
											<div class="plus-add-wrapper" ng-if="editFaqCtrl.deviser.faq.length > 0" ng-click="editFaqCtrl.addQuestion()">
												<div class="plus-add">
													<span>+</span>
												</div>
												<div class="text">ADD QUESTION</div>
											</div>
											<div dnd-list="editFaqCtrl.deviser.faq" dnd-dragover="editFaqCtrl.dragOver(index)">
												<div class="delete-options-wrapper" ng-repeat="question in editFaqCtrl.deviser.faq track by $index" style="cursor:move;" dnd-draggable="question" dnd-effect-allowed="move" dnd-dragstart="editFaqCtrl.dragStart($index)" dnd-canceled="editFaqCtrl.canceled()" dnd-moved="editFaqCtrl.moved($index)">
													<a class="delete-link pull-right" href="#" ng-click="editFaqCtrl.deleteQuestion($index)">Delete question</a>
													<div class="edit-faq-panel">
														<div class="faq-language-menu">
															<ol class="nya-bs-select form-control" ng-model="question.languageSelected" ng-change="editFaqCtrl.parseQuestion(question)" ng-init="question.languageSelected='en-US'">
																<li nya-bs-option="language in editFaqCtrl.languages" class="ng-class:{'lang-selected': editFaqCtrl.isLanguageOk(language.code, question)}" data-value="language.code" deep-watch="true">
																	<a href="">
																		<span ng-bind="language.name"></span>
																		<span class="glyphicon glyphicon-ok ok-white-icon pull-right" ng-if="editFaqCtrl.isLanguageOk(language.code,question)"></span>
																	</a>
																</li>
															</ol>
															<div class="faq-row">
																<div class="col-sm-1">
																	<span class="faq-edit-question">Question</span>
																</div>
																<div class="col-sm-11">
																	<input type="text" class="faq-edit-answer" placeholder="Question" ng-model="question.question[question.languageSelected]">
																</div>
															</div>
															<div class="faq-row">
																<div class="col-sm-1">
																	<span class="faq-edit-question">Answer</span>
																</div>
																<div class="col-sm-11">
																	<div class="faq-edit-answer" text-angular ng-model="question.answer[question.languageSelected]" ta-toolbar="[]" placeholder="Answer"></div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="faq-edit-empty" ng-if="editFaqCtrl.deviser.faq.length === 0" ng-cloak>
											<img class="sad-face" src="/imgs/sad-face.svg">
											<p>You haven’t written any FAQs.
												<br/> Start now by clicking the <b>ADD QUESTION</b> button.</p>
												<a class="btn btn-green edit-faq-btn" href="#" ng-click="editFaqCtrl.addQuestion()">ADD QUESTION</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>