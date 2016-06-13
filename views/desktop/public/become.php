<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Json;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\ContactAsset;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Contact',
	'url' => ['/public/become']
];

ContactAsset::register($this);

$this->title = 'Todevise / Become a Deviser';
$lang = Yii::$app->language;

?>

<div class="row no-gutter" ng-controller="becomeCtrl" ng-init="init()">

	<div class="col-xs-12 no-padding flex flex-column become">
		<div class="row-one">
			<div class="row-one-left"></div>
			<div class="row-one-right">
				<?= Html::a(Yii::t("app/public", 'Request invitation'), "", [
					'class' => "link_btn red funiv_bold fs1 fc-fff"
				]); ?>
			</div>
		</div>
		<div class="row-two">
				<div class="row-two-left"></div>
				<div class="row-two-center"></div>
				<div class="row-two-right"></div>
		</div>
		<div class="row-three"></div>
		<div class="row-four"></div>


		<?php $form = ActiveForm::begin(); ?>
		<?php
			//echo $form->field($model, 'name')->input('name', ['placeholder' => "NAME"])->label(false);

		?>
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
