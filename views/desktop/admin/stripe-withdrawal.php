<?php

use app\assets\desktop\admin\AdminsAsset;
use app\models\Person;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $influencers yii\data\ActiveDataProvider */

AdminsAsset::register($this);


$this->params['breadcrumbs'][] = [
	'label' => 'Stripe Minimum balance',
	'url' => ['/admin/stripe-withdrawal']
];

$this->title = 'Todevise / Admin / Stripe Minimum balance';
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-horizontal-padding">

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Stripe Minimum balance"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">
				</div>
			</div>
		</div>

    <div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<span class="fc-fff">
            <?= Yii::t("app/admin", "Total credit available for clients"); ?>:
          </span>
          <span class="funiv_bold fs-upper fc-fff fs1-071"><?php echo $total.'€'; ?></span>

				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">
				</div>
			</div>
		</div>




	</div>
</div>
