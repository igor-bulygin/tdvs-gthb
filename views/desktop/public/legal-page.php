<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = $title;
Yii::$app->opengraph->title = $this->title;

?>

<div class="about-us-wrapper">
	<div class="container">
		<h1><?=$title?></h1>
		<?=$text?>
		<br />
		<br />
		<br />
	</div>
</div>
