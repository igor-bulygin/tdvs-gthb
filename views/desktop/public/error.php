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

<div class="site-error" style="color: white; text-align: center">

    <p><?= Yii::t('app/public', 'ERROR_PAGE_TITLE')?></p>

	<p><?=Yii::t('app/public', 'ERROR_PAGE_MESSAGE_1')?></p>

	<p><?=Yii::t('app/public', 'ERROR_PAGE_MESSAGE_2')?></p>

	<a href="<?=Url::to('/')?>"><img src="/imgs/logo.png"></a>

</div>
