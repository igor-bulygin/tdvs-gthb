
<?php
/** @var $this \yii\web\View view component instance */
/** @var \app\modules\api\pub\v1\forms\BecomeInfluencerForm $form */
?>

<div><p>Una persona ha solicitado convertirse en influencer. Estos son los datos que ha enviado:</p></div>

<div><p>Nombre: <?= $form->representative_name ?></p></div>
<div><p>Brand: <?= $form->brand_name ?></p></div>
<div><p>Email: <?= $form->email ?></p></div>
<div><p>Telefono: <?= $form->phone_number ?></p></div>
<div><p>¿Que fabrica?: <?= $form->creations_description ?></p></div>
<div><p>Portfolio:</p>
	<ul>
		<?php foreach($form->urls_portfolio as $url){ ?>
			<li><?=$url?></li>
		<?php } ?>
	</ul>
</div>
<div><p>Video:</p>
	<ul>
		<?php foreach($form->urls_video as $url){ ?>
			<li><?=$url?></li>
		<?php } ?>
	</ul>
</div>
<div><p>Observaciones: <?= $form->observations?></p></div>