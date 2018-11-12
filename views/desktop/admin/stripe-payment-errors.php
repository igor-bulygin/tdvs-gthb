<?php

use app\assets\desktop\admin\PaymentErrorsAsset;
use app\models\Person;
use app\models\PaymentErrors;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $influencers yii\data\ActiveDataProvider */

PaymentErrorsAsset::register($this);


$this->params['breadcrumbs'][] = [
	'label' => 'Stripe Payment Errors',
	'url' => ['/admin/stripe-payment-errors']
];

$this->title = 'Todevise / Admin / Stripe - Stripe Payment Errors';
?>

<div class="row no-gutter" ng-controller="paymentErrorsCtrl as paymentErrorsCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Payment Errors"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">
				</div>
			</div>
		</div>

    <div style=""
    <?php
    echo GridView::widget([
      'id' => 'payment_errors_list',
      'dataProvider' => $payment_errors,
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
					'class' => 'yii\grid\ActionColumn',
					'template' => " {transfer} ",
					'buttons' => [
						'transfer' => function($url, $model, $key) {
							return Html::tag("span", "", [
								"class" => "pointer glyphicon glyphicon-transfer fc-fff fs1",
								"title" => "Transfer Money",
								"ng-click" => "paymentErrorsCtrl.transfer('$model->short_id')"
							]);
						},
					],
					'header' => Html::tag("div", Yii::t("app/admin", "Actions")),

					'headerOptions' => [
						'class' => 'text-center'
					],
					'contentOptions' => [
						'class' => 'text-center'
					]
				],
        [
          'value' => function($item){
            return $item['order_id'];
          },
          'header' => Yii::t("app/admin", "Order ID")
        ],
				[
          'value' => function($item){
            return $item['pack_id'];
          },
          'header' => Yii::t("app/admin", "Pack ID")
        ],
				[
          'value' => function($item){
						$item_person = Person::find()->where(['short_id' => $item['person_id']])->one();
            return $item_person->getName();
          },
          'header' => Yii::t("app/admin", "Person")
        ],
        [
          'value' => function($item){
            return $item['amount_earned'];
          },
          'header' => Yii::t("app/admin", "Amount")
        ],
				[
          'value' => function($item){
            return $item['error_type_id'];
          },
          'header' => Yii::t("app/admin", "Error ID")
        ],
				[
          'value' => function($item){
            return $item['error_type_description'];
          },
          'header' => Yii::t("app/admin", "Error Description")
        ],
        [
          'value' => function($item){
            return $item['created_at']->toDateTime()->format('Y-m-d H:i:s');
          },
          'header' => Yii::t("app/admin", "Date")
        ],
      ]
    ]);
		?>

	</div>
</div>
