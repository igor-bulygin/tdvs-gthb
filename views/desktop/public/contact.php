<?php

use app\assets\desktop\pub\ContactAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Contact',
	'url' => ['/public/contact']
];

ContactAsset::register($this);

$this->title = Yii::t('app/public', 'CONTACT_TITLE');
$lang = Yii::$app->language;

?>

	<div class="row no-gutter" ng-controller="contactCtrl as contactCtrl">
		<?php $this->registerJs("var _faqs = " . Json::encode($faqs) . ";", View::POS_END); ?>

			<div class="col-xs-12 no-padding flex flex-column contact">
				<div class="contact-up flex flex-row">
					<div class="contact-left flex-column">
						<div class="contact-left-top">


							<div class="search-header-content fc-fff">
								<div class="cols-xs12 fs-upper flexr flex-align-center flex-column flex-justify-center search-header">
									<div class="search_holder fs0-857">
										<input type="text" class="funiv_bold fs1-143 fc-4e" name="search" placeholder="{{ 'todevise.contact.QUESTION_ABOUT' | translate }}">
										<span class="pointer glyphicon-content bc-c7 fc-fff">
									        <span class="glyphicon glyphicon-search"></span>
										</span>
									</div>
								</div>
							</div>


						</div>
						<div class="contact-left-bottom">
							<div class="flex flex-align-center flex-column flex-justify-center faqs">
								<div ng-repeat="faq in contactCtrl.faqs">
									<div class="question-content fs0-857 funiv_bold fs-upper" ng-click="faq.showAnswer = !faq.showAnswer">
										<span class="question fc-9b fs0-857 funiv_bold fs-upper" ng-bind="faq.question"></span>
									</div>
									<div class="answer fpf fs1" ng-if="faq.showAnswer"><span ng-bind="faq.answer"></span></div>
								</div>
								<?= Html::a(Yii::t("app/old", 'See our full FAQ'), Url::to(['public/faq']), [
							'class' => "link_btn funiv_bold fs1 fc-6b fs-upper"
						]); ?>
							</div>
						</div>
					</div>
					<div class="contact-right flex flex-align-center flex-column flex-justify-center">
						<?php if ($email == 'ok') { ?>
							<div class="alert alert-success" translate="todevise.contact.EMAIL_OK"></div>
						<?php } elseif ($email == 'error') { ?>
							<div class="alert alert-danger" translate="todevise.contact.EMAIL_ERROR"></div>
						<?php } ?>
						<div class="fs3-857 funiv_thin fs-upper fc-6d">
							<?= Yii::t('app/old', 'Contact via Message') ?>
						</div>
						<div class="funiv_bold fs0-857">
							<?php $form = ActiveForm::begin([
								'validateOnChange' => false,
								'validateOnBlur' => false,
								'validateOnType' => false
							]); ?>
								<?php
						echo $form->field($model, 'name')->input('name', ['placeholder' => "NAME"])->label(false);
						echo $form->field($model, 'email')->input('email', ['placeholder' => "EMAIL"])->label(false);
//						echo $form->field($model, 'about')->dropDownList($dropdown_members, ['prompt'=>'What is your question about?', 'ng-model' => 'contactCtrl.selected', 'ng-change' => 'contactCtrl.changed()'])->label(false);
//						echo $form->field($model, 'ordernum')->input('subject', ['placeholder' => "ORDER Nº", 'ng-if' =>'contactCtrl.orderShow'])->label(false);
//						echo $form->field($model, 'subject')->input('subject', ['placeholder' => "SUBJECT"])->label(false);

						echo $form->field($model, 'body')->textArea(['rows' => '6', 'placeholder' => "MESSAGE"])->label(false);
					?>
									<div class="send_button_content">
										<?php
						echo Html::submitButton(
							'<img src="/imgs/shape.png" />',
							['class'=>'fc-fff btn send_button']
						);
					?>
									</div>

									<?php ActiveForm::end(); ?>
						</div>
					</div>
				</div>
				<div class="contact-down flex flex-row">
					<div class="contact-down-left red flex flex-align-center flex-column flex-justify-center">
						<div class="fs3-000 funiv_thin fs-upper fc-fff">
							<?= Yii::t('app/old', 'Contact via Whatsapp') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-fff">
							<?= Yii::t('app/old', 'Just add this number to your phone´s contact list and start a conversation with us!') ?>
						</div>
						<div class="funiv_bold fs1 fs1-587 fc-fff">
							<?= Yii::t('app/old', '+0034 645 234 234') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-fff">
							<?= Yii::t('app/old', 'Because of time zones difference an agent can take up to 24 hours to reply to you message.') ?>
						</div>
					</div>
					<div class="contact-down-center grey-soft flex flex-align-center flex-column flex-justify-center">
						<div class="fs3-000 funiv_thin fs-upper fc-1c1919">
							<?= Yii::t('app/old', 'Contact via Facebook') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-1c1919">
							<?= Yii::t('app/old', 'You can message us via Facebook. Our social media team will reply in less than 24 hours.') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-1c1919">
							<?= Yii::t('app/old', 'Click the button below to go to our profile') ?>
						</div>
						<?= Html::a(Yii::t("app/old", 'Contact via Facebook'), Url::to('https://www.facebook.com/todevise'), [
					'class' => "link_btn_white funiv_bold fs1-143 red fc-fff"
				]); ?>
					</div>
					<div class="contact-down-right grey-hard flex flex-align-center flex-column flex-justify-center">
						<div class="fs3-000 funiv_thin fs-upper fc-fff">
							<?= Yii::t('app/old', 'Contact via Phone') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-fff">
							<?= Yii::t('app/old', 'Contact us via phone, our customer service representative are here to help you.') ?>
						</div>
						<div class="funiv_bold fs1 fs1-587 fc-fff">
							<?= Yii::t('app/old', '+0034 645 234 234') ?>
						</div>
					</div>
				</div>

				<?php

			//Pjax::begin([
			//	'enablePushState' => false
			//]);
			// echo ListView::widget([
			// 	'dataProvider' => $products,
			// 	'itemView' => '_category_product',
			// 	'itemOptions' => [
			// 		'tag' => false
			// 	],
			// 	'options' => [
			// 		'class' => 'products_wrapper'
			// 	],
			// 	'layout' => '<div class="funiv fs1-143 fc-6d">{summary}</div><div class="products_holder">{items}</div>{pager}',
			// ]);
			//echo $test;

			//Pjax::end();

		?>

			</div>
	</div>