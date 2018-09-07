<?php

use app\assets\desktop\pub\PublicCommonAsset;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$title = Yii::t('app/public', 'ERROR_PAGE_TITLE');
$this->title = $title;
Yii::$app->opengraph->title = $this->title;
?>

<div class="site-error" style="text-align: center">
	
   
    <p class="error-404-title"><?= $title?></p>
    
    <div class="container">
    	<div class="row">
			<div class="col-xs-8 col-sm-8 col-md-4 col-xs-offset-2 col-sm-offset-2 col-md-offset-2">
				<img src="/imgs/error-face.svg" class="img-responsive" data-pin-nopin="true">
			</div>
			<div class="col-xs-10 col-sm-10 col-md-4 col-xs-offset-1 col-sm-offset-1">
				<p class="error-404-text"><?=Yii::t('app/public', 'ERROR_PAGE_MESSAGE_1')?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-10 col-sm-10 col-md-8 col-xs-offset-1 col-sm-offset-1 col-md-offset-2"><p class="error-404-pd"><?=Yii::t('app/public', 'ERROR_PAGE_MESSAGE_2')?></p>
		</div>
		<div class="row mb-40">
			<div class="col-xs-offset-3 col-sm-offset-3 col-md-offset-4 col-xs-6 col-sm-6 col-md-4"><a href="<?=Url::to('/')?>"><img src="/imgs/logo-red.svg"></a></div>
    	</div>
	</div>


</div>
