<?php
use app\components\assets\cropAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

// use params to share data between views :(
/** @var Person $deviser */
$deviser = $this->params['deviser'];

?>
<div>
	<h1 style="color: whitesmoke;">Este perfil es un borrador.</h1>
	<button>Make profile public</button>
</div>
