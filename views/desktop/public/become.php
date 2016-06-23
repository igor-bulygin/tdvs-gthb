<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Json;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\BecomeAsset;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Contact',
	'url' => ['/public/become']
];

BecomeAsset::register($this);

$this->title = 'Todevise / Become a Deviser';
$lang = Yii::$app->language;

?>

<div class="row no-gutter become grey-hard">
<div class="justview">
	<div class="col-xs-12 no-padding flex flex-column become">
		<div class="row-one flex flex-row flex-align-center flex-justify-center">
			<div class="row-one-left"></div>
			<div class="row-one-right flex flex-align-center flex-justify-end">
				<div>
				<?= Html::a(Yii::t("app/public", 'Request invitation'), "", [
					'class' => "btn white funiv_bold fs1 fc-f7284b fs-upper"
				]); ?>
				</div>
			</div>
		</div>
		<div class="row-two">
				<div class="row-two-left"></div>
				<div class="row-two-center"></div>
				<div class="row-two-right"></div>
		</div>
		<div class="row-three flex flex-row flex-justify-center">
			<div class="row-three-left"></div>
			<div class="row-three-right flex flex-column">
				<span class="fs3-643 funiv_thin fs-upper fc-fff">Grown your brand</span>
				<div class="grown-text fc-fff fs1 funiv">
				<div class="par">Reach international customers who understand your philosophy and appreciate your work.</div>
				<div class="par">Get marketing guidance and SEO/SEM optimization by or team of experts.</div>
				<div class="par">Potential onsite and social media promotion of your work</div>
				</div>
			</div>
		</div>
		<div class="row-four flex flex-justify-center">
			<div class="text fpf fs1-286 fc-fff"><span>Having your outlet store on Todevise opens your business to a world of opportunities. Reach new customers, upload products with ease, manage and track you sales efficiently.</span></div>
		</div>

		<div class="row-five flex flex-justify-center flex-align-center flex-column">







				<span class="fs3-643 funiv_thin fs-upper fc-fff">Request invitation</span>
<span class="funiv fs0-857 whyinvitation fc-fff center">We constantly strive for excellence, and for this reason an invitation is needed to register as a deviser</span>


<div class="formcontent">
			<?php $form = ActiveForm::begin(); ?>
			<?php
				//echo $form->field($model, 'name')->input('name', ['placeholder' => "NAME"])->label(false);

			?>
			<div class="flex flex-row">
				<div class="allspace funiv fs0-857">
					<?= $form->field($model, 'name',['inputOptions' => ['placeholder' => Yii::t('app/public','name') . "*"]])->label(false); ?>
					<?= $form->field($model, 'email',['inputOptions' => ['placeholder' => Yii::t('app/public','email') . "*"]])->label(false); ?>
				</div>
				<div class="allspace funiv fs0-857">
					<?= $form->field($model, 'brand',['inputOptions' => ['placeholder' => Yii::t('app/public','Brand name') . "*"]])->label(false); ?>
					<?= $form->field($model, 'phone',['inputOptions' => ['placeholder' => Yii::t('app/public','Phone number') . "*"]])->label(false); ?>
				</div>
			</div>
			<div class="funiv fs0-857">
				<?= $form->field($model, 'create',['inputOptions' => ['placeholder' => Yii::t('app/public','What do you create?') . "*"]])->label(false); ?>
				<?= $form->field($model, 'portfolio',['inputOptions' => ['placeholder' => Yii::t('app/public','Link to portfolio') . "*"]])->label(false); ?>
				<?= $form->field($model, 'video',['inputOptions' => ['placeholder' => Yii::t('app/public','Link to video') . "*"]])->label(false); ?>
				<?= $form->field($model, 'observations',['inputOptions' => ['placeholder' => Yii::t('app/public','observations') . "*"]])->label(false); ?>
			</div>
			<div class="send_button_content">

			<?php
				echo Html::submitButton(
					'<i class="glyphicon glyphicon-send"></i>',
					['class'=>'fc-fff btn send_button']
				);
			?>
			</div>

			<?php ActiveForm::end(); ?>
</div>












		</div>

	</div>
</div>
</div>
