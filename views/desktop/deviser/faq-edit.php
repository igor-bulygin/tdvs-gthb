<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserHeader;
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
use app\assets\desktop\pub\IndexAsset;
use app\assets\desktop\pub\Index2Asset;

PublicCommonAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'faq';

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<div class="faq-wrapper">
                    <div class="section-title">FAQ</div>
                    <div class="edit-faq-wrapper">
                       <div class="delete-options-wrapper">
                           <a class="delete-link pull-right" href="#">Delete language</a>
                           <a class="delete-link pull-right" href="#">Delete question</a>
                           <div class="edit-faq-panel">
                               <div class="faq-language-menu">
                                   <ul class="nav nav-tabs pull-left" role="tablist">
                                       <li class="active">
                                           <a role="language" href="#english" aria-controls="english" role="tab" data-toggle="tab">English</a>
                                       </li>
                                       <li>
                                           <a role="language" href="#french" aria-controls="french" role="tab" data-toggle="tab">French</a>
                                       </li>
                                       <li>
                                           <a role="language" href="#spanish" aria-controls="spanish" role="tab" data-toggle="tab">Spanish</a>
                                       </li>
                                   </ul>
                                    <a class="add-lang" href="#">Add language +</a>
                               </div>
                               <div class="questions">
                                   <div class="tab-content">
                                       <div role="tabpanel" class="tab-pane active" id="english">
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Question</span>
                                               </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Lorem ipsum lorem ipsum??</span>
                                                   </div>
                                           </div>
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Answer</span>
                                                </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Sisi, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</span>
                                                </div>
                                           </div>
                                       </div>
                                       <div role="tabpanel" class="tab-pane" id="french">
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Questiong</span>
                                               </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Lorem ipsum lorem ipsum??</span>
                                                   </div>
                                           </div>
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Respuesté</span>
                                                </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Sisi, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</span>
                                                </div>
                                           </div>
                                       </div>
                                       <div role="tabpanel" class="tab-pane" id="spanish">
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Pregunta</span>
                                               </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Lorem ipsum lorem ipsum??</span>
                                                   </div>
                                           </div>
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Respuesta</span>
                                                </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Sisi, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div> 
                    </div>
                    <div class="edit-faq-wrapper">
                       <div class="delete-options-wrapper">
                           <a class="delete-link pull-right" href="#">Delete language</a>
                           <a class="delete-link pull-right" href="#">Delete question</a>
                           <div class="edit-faq-panel">
                               <div class="faq-language-menu">
                                   <ul class="nav nav-tabs pull-left" role="tablist">
                                       <li class="active">
                                           <a role="language" href="#english" aria-controls="english" role="tab" data-toggle="tab">English</a>
                                       </li>
                                       <li>
                                           <a role="language" href="#french" aria-controls="french" role="tab" data-toggle="tab">French</a>
                                       </li>
                                       <li>
                                           <a role="language" href="#spanish" aria-controls="spanish" role="tab" data-toggle="tab">Spanish</a>
                                       </li>
                                   </ul>
                                    <a class="add-lang" href="#">Add language +</a>
                               </div>
                               <div class="questions">
                                   <div class="tab-content">
                                       <div role="tabpanel" class="tab-pane active" id="english">
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Question</span>
                                               </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Lorem ipsum lorem ipsum??</span>
                                                   </div>
                                           </div>
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Answer</span>
                                                </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Sisi, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</span>
                                                </div>
                                           </div>
                                       </div>
                                       <div role="tabpanel" class="tab-pane" id="french">
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Questiong</span>
                                               </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Lorem ipsum lorem ipsum??</span>
                                                   </div>
                                           </div>
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Respuesté</span>
                                                </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Sisi, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</span>
                                                </div>
                                           </div>
                                       </div>
                                       <div role="tabpanel" class="tab-pane" id="spanish">
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Pregunta</span>
                                               </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Lorem ipsum lorem ipsum??</span>
                                                   </div>
                                           </div>
                                           <div class="faq-row">
                                               <div class="col-sm-1">
                                                   <span class="faq-edit-question">Respuesta</span>
                                                </div>
                                               <div class="col-sm-11">
                                                   <span class="faq-edit-answer">Sisi, lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div> 
                    </div>
                    <a class="edit-faq-btn" href="#">+ ADD QUESTION</a>
                    <button class="btn btn-green btn-done">Done</button>
                    <div class="faq-edit-empty" style="display:none;">
                        <p>You haven’t written any FAQs.<br/> 
Start now by clicking the <b>ADD QUESTION</b> button.</p>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>