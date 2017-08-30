<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = Yii::t('app/public', 'About us');

?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding about">
		<h1><?=Yii::t('app/public', 'About us')?></h1>
	</div>
</div>
