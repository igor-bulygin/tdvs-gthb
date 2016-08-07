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

<div class="row no-gutter grey-hard">
<div class="justview flex flex-column">
	<div class="col-xs-12 flex flex-column become">
		<div class="row-one flex flex-row flex-justify-end">
			<div class="row-one-right flex flex-align-center flex-justify-center">
				<div class="flex flex-column">
					<div><img src="/imgs/logo_white.png" alt="Todevise" /></div>
					<div class="fs4-714 funiv_thin fs-upper fc-fff flex-justify-center">Express</div>
					<div class="fs4-714 funiv_thin fs-upper fc-fff flex-justify-center">Yourself</div>


					<?= Html::a(Yii::t("app/public", 'Request invitation'), "#invitationform", [
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
		<div class="row-four flex flex-justify-center flex-align-center">
			<div class="row-four-right"><div class="text fpf fs1-286 fc-fff"><span>Having your outlet store on Todevise opens your business to a world of opportunities. Reach new customers, upload products with ease, manage and track you sales efficiently.</span></div></div>
		</div>

	</div>
<div class="row-five flex flex-column flex-justify-center flex-align-center">

	<span class="fs3-643 funiv_thin fs-upper fc-fff">Request invitation</span>
	<span class="funiv fs0-857 whyinvitation fc-fff center">We constantly strive for excellence, and for this reason an invitation is needed to register as a deviser</span>

	<div class="formcontent" ng-controller="becomeCtrl">
		<?php $form = ActiveForm::begin([
			'validateOnChange' => false,
			'validateOnBlur' => false,
			'validateOnType' => false
		]); ?>
		<?php
		//echo $form->field($model, 'name')->input('name', ['placeholder' => "NAME"])->label(false);

		?>
		<div class="form-title fs0-714 fc-fff fs-upper funiv_bold">
			<span class="grey-hard"><?=Yii::t('app/public', 'Personal Info')?></span>
		</div>
		<div class="flex flex-row form-become form-personal flex-justify-between">
			<div class="allspace form-grouped funiv_bold fs0-857">
				<?= $form->field($model, 'name',['inputOptions' => ['placeholder' => Yii::t('app/public','name')]])->label(false); ?>
				<?= $form->field($model, 'email',['inputOptions' => ['placeholder' => Yii::t('app/public','email')]])->label(false); ?>
			</div>
			<div class="allspace form-groupedright funiv_bold fs0-857">
				<div class="form-optional-content funiv fs0-786 fs-upper">
					<div class="form-optional"><?=Yii::t('app/public', 'Optional')?></div>
				</div>
				<?= $form->field($model, 'brand',['inputOptions' => ['placeholder' => Yii::t('app/public','Brand name')]])->label(false); ?>
				<div class="form-optional-content funiv fs0-786 fs-upper">
					<div class="form-optional"><?=Yii::t('app/public', 'Optional')?></div>
				</div>
				<?= $form->field($model, 'phone',['inputOptions' => ['placeholder' => Yii::t('app/public','Phone number')]])->label(false); ?>
			</div>
		</div>
		<div class="form-title fs0-714 fc-fff fs-upper funiv_bold">
			<span class="grey-hard"><?=Yii::t('app/public', 'Your Work')?></span>
		</div>
		<div class="funiv_bold fs0-857">
			<?= $form->field($model, 'create',['inputOptions' => ['placeholder' => Yii::t('app/public','What do you create?')]])->label(false); ?>
			<div ng-repeat="link in portfolio_links">
				<?= $form->field($model, 'portfolio[]',['inputOptions' => ['placeholder' => Yii::t('app/public','Link to portfolio')]])->label(false); ?>
			</div>
			<div class="form-add-new funiv_bold fs1-071"><span class="fc-f7284b" ng-click="newPortFolioLink()"><?=Yii::t('app/public', 'Add new +')?></span></div>
			<div class="form-optional-content funiv fs0-786 fs-upper">
				<div class="form-optional"><?=Yii::t('app/public', 'Optional')?></div>
			</div>
			<div ng-repeat="link in video_links">
				<?= $form->field($model, 'video[]',['inputOptions' => ['placeholder' => Yii::t('app/public','Link to video')]])->label(false); ?>
			</div>
			<div class="form-add-new funiv_bold fs1-071"><span class="fc-f7284b" ng-click="newVideoLink()"><?=Yii::t('app/public', 'Add new +')?></span></div>
			<div class="form-optional-content funiv fs0-786 fs-upper">
				<div class="form-optional"><?=Yii::t('app/public', 'Optional')?></div>
			</div>
			<?= $form->field($model, 'observations',['inputOptions' => ['placeholder' => Yii::t('app/public','observations')]])->label(false); ?>
		</div>
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
</div>
