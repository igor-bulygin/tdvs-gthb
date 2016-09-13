<?php
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\IndexAsset;
use app\assets\desktop\pub\Index2Asset;

Index2Asset::register($this);

$this->title = 'Todevise / About us';

?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding about">
		<h1>About us</h1>
	</div>
</div>
