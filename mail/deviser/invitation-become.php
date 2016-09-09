<?php
use app\models\PostmanEmailAction;
use app\modules\api\pub\v1\forms\BecomeDeviserForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var PostmanEmailAction $actionAccept */
?>

<div>
	<h1>Invitación a darse de alta como Deviser</h1>
	<p><?= $message ?></p>
	<a href="<?= Url::to(["/public/create-deviser-account", "uuid" => $actionAccept->uuid], true) ?>">Pincha aquí para aceptar la invitación</a>
</div>


