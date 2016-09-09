<?php
use app\assets\desktop\pub\PostmanAsset;
use app\assets\desktop\pub\Product2Asset;
use app\assets\desktop\pub\PublicCommonAsset;
use app\models\Category;
use app\models\Person;
use app\models\PostmanEmail;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

PostmanAsset::register($this);

/** @var array $emails */
/** @var PostmanEmail $email */

$this->title = 'Ver email - Todevise';

?>

<!--=== Content Part ===-->
<div class="content container">
	<div class="row">
		<div class="col-md-12">
			<h1 style="color: white">Bandeja de salida</h1>
			<table class="table table-striped" style="background-color: white;">
				<thead>
				<tr>
					<th>Id</th>
					<th>De</th>
					<th>Para</th>
					<th>Asunto</th>
					<th>Fecha</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($emails as $email) { ?>
				<tr>
					<td><a href="<?= Url::to(["postman/email-view", "uuid" => $email->uuid], true) ?>" target="_blank"><?= $email->uuid ?></a></td>
					<td><?= $email->from_name ?> < <?= $email->from_email ?> ></td>
					<td><?= $email->to_name ?> < <?= $email->to_email ?> ></td>
					<td><a href="<?= Url::to(["postman/email-view", "uuid" => $email->uuid], true) ?>" target="_blank"><?= $email->subject ?></a></td>
					<td><?= $email->created_at->toDateTime()->format('Y-m-d H:i:s') ?></td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<!--/end row-->
</div>
<!--/end container-->
<!--=== End Content Part ===-->

<ul>




</ul>