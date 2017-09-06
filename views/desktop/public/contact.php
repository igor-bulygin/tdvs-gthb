<?php

use app\assets\desktop\pub\ContactAsset;
use yii\helpers\Html;
use yii\helpers\Json;
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
					<div class="contact-right flex flex-align-center flex-column flex-justify-center">
						<?php if ($email == 'ok') { ?>
							<div class="alert alert-success" translate="todevise.contact.EMAIL_OK"></div>
						<?php } elseif ($email == 'error') { ?>
							<div class="alert alert-danger" translate="todevise.contact.EMAIL_ERROR"></div>
						<?php } ?>
						<div class="fs3-857 funiv_thin fs-upper fc-6d">
							<?= Yii::t('app/public', 'CONTACT_HEADER') ?>
						</div>
						<div class="funiv_bold fs0-857">
							<?php $form = ActiveForm::begin([
								'validateOnChange' => false,
								'validateOnBlur' => false,
								'validateOnType' => false
							]); ?>
								<?php
						echo $form->field($model, 'name')->input('name', [])->label(Yii::t('app/public', 'CONTACT_NAME'));
						echo $form->field($model, 'email')->input('email', [])->label(Yii::t('app/public', 'CONTACT_EMAIL'));
//						echo $form->field($model, 'about')->dropDownList($dropdown_members, ['prompt'=>'What is your question about?', 'ng-model' => 'contactCtrl.selected', 'ng-change' => 'contactCtrl.changed()'])->label(false);
//						echo $form->field($model, 'ordernum')->input('subject', ['placeholder' => "ORDER Nº", 'ng-if' =>'contactCtrl.orderShow'])->label(false);
//						echo $form->field($model, 'subject')->input('subject', ['placeholder' => "SUBJECT"])->label(false);

						echo $form->field($model, 'body')->textArea(['rows' => '6', 'placeholder' => Yii::t('app/public', 'CONTACT_MESSAGE_PLACEHOLDER')])->label(false);
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
							<?= Yii::t('app/public', 'CONTACT_WHATSAPP_HEADER') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-fff">
							<?= Yii::t('app/public', 'CONTACT_WHATSAPP_TEXT') ?>
						</div>
						<div class="funiv_bold fs1 fs1-587 fc-fff">
							<?= Yii::t('app/public', 'CONTACT_WHATSAPP_NUMBER') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-fff">
							<?= Yii::t('app/public', 'CONTACT_WHATSAPP_INFO') ?>
						</div>
					</div>
					<div class="contact-down-center grey-soft flex flex-align-center flex-column flex-justify-center">
						<div class="fs3-000 funiv_thin fs-upper fc-1c1919">
							<?= Yii::t('app/public', 'CONTACT_FACEBOOK_HEADER') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-1c1919">
							<?= Yii::t('app/public', 'CONTACT_FACEBOOK_TEXT_1') ?>
						</div>
						<div class="fpf_bold fs0-857 fc-1c1919">
							<?= Yii::t('app/public', 'CONTACT_FACEBOOK_TEXT_2') ?>
						</div>
						<a href="https://www.facebook.com/todevise" class="link_btn_white funiv_bold fs1-143 red fc-fff" target="_blank"><?=Yii::t('app/public', 'CONTACT_FACEBOOK_BUTTON')?></a>
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