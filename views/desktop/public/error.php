<?php

use app\assets\desktop\pub\PublicCommonAsset;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<div class="site-error" style="text-align: center">
	
   
    <p class="error-404-title"><?= Yii::t('app/public', 'ERROR_PAGE_TITLE')?></p>
    
    <div class="container">
    	<div class="row">
			<div class="col-md-4 col-md-offset-2">
				<img src="/imgs/error-face.svg" data-pin-nopin="true">
			</div>
			<div class="col-md-4">
				<p class="error-404-text"><?=Yii::t('app/public', 'ERROR_PAGE_MESSAGE_1')?></p>
			</div>
    	</div>
	</div>

	<p class="error-404-pd"><?=Yii::t('app/public', 'ERROR_PAGE_MESSAGE_2')?></p>

	<a href="<?=Url::to('/')?>"><img src="/imgs/logo.png"></a>

</div>
