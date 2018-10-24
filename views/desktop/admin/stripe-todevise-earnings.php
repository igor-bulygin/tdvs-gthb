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
	'label' => 'Stripe Withdrawal',
	'url' => ['/admin/stripe-todevise-earnings']
];

$this->title = 'Todevise / Admin / Stripe - Todevise Earnings by Affiliates';
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-horizontal-padding">

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Todevise Earnings by Affiliates"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">
				</div>
			</div>
		</div>

    <div style=""
    <?php
    echo GridView::widget([
      'id' => 'admins_list',
      'dataProvider' => $todevise_earnings_by_user,
      'options' => [
        'class' => 'funiv fc-fff fs1-071',
      ],
      'pager' => [
        'options' => [
          'class' => 'pagination flex flex-justify-center'
        ]
      ],
      'columns' => [
        [
          'value' => function($item){
            return str_replace("ORDER","",$item['order_id']);
          },
          'header' => Yii::t("app/admin", "Order ID")
        ],
        [
          'value' => function($item){
            return $item['earning'];
          },
          'header' => Yii::t("app/admin", "Earning by order")
        ],
        [
          'value' => function($item){
            return $item['date'];
          },
          'header' => Yii::t("app/admin", "Date")
        ],
      ]
    ]);
		?>

	</div>
</div>
